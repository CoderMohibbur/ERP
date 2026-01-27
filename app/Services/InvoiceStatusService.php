<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\ServiceRenewal;
use Illuminate\Support\Facades\Schema;

class InvoiceStatusService
{
    public const ERP_STATUS_DRAFT   = 'draft';
    public const ERP_STATUS_UNPAID  = 'unpaid';
    public const ERP_STATUS_PARTIAL = 'partial';
    public const ERP_STATUS_PAID    = 'paid';
    public const ERP_STATUS_VOID    = 'void';

    /**
     * Sync invoice totals + erp_status from invoice items + payments.
     * Also keeps ServiceRenewal.status consistent when invoice is paid.
     */
    public function syncInvoice(int $invoiceId): void
    {
        $invoice = Invoice::query()->find($invoiceId);
        if (!$invoice) {
            return;
        }

        $currentErpStatus = $this->getErpStatus($invoice);

        // If invoice is void, never touch it
        if ($currentErpStatus === self::ERP_STATUS_VOID) {
            return;
        }

        $total = $this->getInvoiceTotal($invoiceId, $invoice);
        $paid  = $this->getInvoicePaidTotal($invoiceId);

        // DB has 14,4 totals; keep 4-decimal safe
        $total = round($total, 4);
        $paid  = round($paid, 4);

        $balance = max(0, round($total - $paid, 4));

        $newErpStatus = $this->computeErpStatus($total, $paid, $currentErpStatus);

        // ✅ Sync BOTH ERP + Legacy totals safely (only if columns exist)
        $this->fillIfColumnsExist($invoice, [
            // ERP columns
            'total'       => $total,
            'paid_total'  => $paid,
            'balance'     => $balance,
            'erp_status'  => $newErpStatus,

            // Legacy columns (kept in schema)
            'total_amount' => $total,
            'paid_amount'  => $paid,
            'due_amount'   => $balance,
        ]);

        /**
         * ✅ Legacy enum-safe status sync:
         * invoices.status enum is draft/sent/paid/overdue (so never write unpaid/partial there).
         * Only safe writes:
         *  - paid  -> paid
         *  - draft -> draft
         */
        if (Schema::hasColumn('invoices', 'status')) {
            if ($newErpStatus === self::ERP_STATUS_PAID) {
                $invoice->status = 'paid';
            } elseif ($newErpStatus === self::ERP_STATUS_DRAFT) {
                $invoice->status = 'draft';
            }
        }

        $invoice->saveQuietly();

        // ✅ Renewal link consistency
        $this->syncServiceRenewalsForInvoice($invoice->id, $newErpStatus);
    }

    private function computeErpStatus(float $total, float $paid, string $currentErpStatus): string
    {
        // Keep draft ONLY if no payment yet
        if ($currentErpStatus === self::ERP_STATUS_DRAFT && $paid <= 0) {
            return self::ERP_STATUS_DRAFT;
        }

        // If total is zero and no payment -> draft
        if ($total <= 0 && $paid <= 0) {
            return self::ERP_STATUS_DRAFT;
        }

        if ($paid <= 0) {
            return self::ERP_STATUS_UNPAID;
        }

        if ($total > 0 && $paid < $total) {
            return self::ERP_STATUS_PARTIAL;
        }

        // paid >= total (or total == 0 but payment exists)
        return self::ERP_STATUS_PAID;
    }

    private function getErpStatus(Invoice $invoice): string
    {
        if (Schema::hasColumn('invoices', 'erp_status') && isset($invoice->erp_status)) {
            return (string) $invoice->erp_status;
        }

        // Fallback (legacy-only db). If legacy has 'void' string somewhere, respect it.
        if (Schema::hasColumn('invoices', 'status') && isset($invoice->status)) {
            return (string) $invoice->status;
        }

        return self::ERP_STATUS_DRAFT;
    }

    private function getInvoicePaidTotal(int $invoiceId): float
    {
        if (!Schema::hasTable('payments') || !Schema::hasColumn('payments', 'amount')) {
            return 0.0;
        }

        $q = Payment::query()->where('invoice_id', $invoiceId);

        // ✅ Only approved payments count (if column exists)
        if (Schema::hasColumn('payments', 'payment_status')) {
            $q->where('payment_status', 'approved');
        }

        return round((float) $q->sum('amount'), 4);
    }

    private function getInvoiceTotal(int $invoiceId, Invoice $invoice): float
    {
        // Prefer ERP total if exists
        if (Schema::hasColumn('invoices', 'total') && $invoice->total !== null) {
            return (float) $invoice->total;
        }

        // fallback legacy total_amount
        if (Schema::hasColumn('invoices', 'total_amount') && isset($invoice->total_amount)) {
            return (float) $invoice->total_amount;
        }

        // Fallback: compute from invoice_items (support multiple schemas)
        if (!class_exists(InvoiceItem::class) || !Schema::hasTable('invoice_items')) {
            return 0.0;
        }

        $items = InvoiceItem::query()->where('invoice_id', $invoiceId)->get();

        $sum = 0.0;

        $hasAmount    = Schema::hasColumn('invoice_items', 'amount');
        $hasLineTotal = Schema::hasColumn('invoice_items', 'line_total');
        $hasQty       = Schema::hasColumn('invoice_items', 'quantity');
        $hasPrice     = Schema::hasColumn('invoice_items', 'unit_price');
        $hasTotalCol  = Schema::hasColumn('invoice_items', 'total'); // some schemas use 'total'

        foreach ($items as $item) {
            if ($hasAmount && $item->amount !== null) {
                $sum += (float) $item->amount;
                continue;
            }
            if ($hasLineTotal && $item->line_total !== null) {
                $sum += (float) $item->line_total;
                continue;
            }
            if ($hasTotalCol && $item->total !== null) {
                $sum += (float) $item->total;
                continue;
            }
            if ($hasQty && $hasPrice) {
                $sum += ((float) $item->quantity) * ((float) $item->unit_price);
                continue;
            }
        }

        return round($sum, 4);
    }

    private function syncServiceRenewalsForInvoice(int $invoiceId, string $erpStatus): void
    {
        if (!class_exists(ServiceRenewal::class) || !Schema::hasTable('service_renewals') || !Schema::hasColumn('service_renewals', 'invoice_id')) {
            return;
        }

        $q = ServiceRenewal::query()->where('invoice_id', $invoiceId);

        // softDeletes safe
        if (Schema::hasColumn('service_renewals', 'deleted_at')) {
            $q->whereNull('deleted_at');
        }

        // never touch skipped
        if (Schema::hasColumn('service_renewals', 'status')) {
            $q->where('status', '!=', ServiceRenewal::STATUS_SKIPPED);
        }

        if ($erpStatus === self::ERP_STATUS_PAID) {
            // paid => paid (never downgrade)
            $q->where('status', '!=', ServiceRenewal::STATUS_PAID)
              ->update(['status' => ServiceRenewal::STATUS_PAID]);

            return;
        }

        // not paid yet: pending -> invoiced (but never downgrade paid)
        $q->where('status', ServiceRenewal::STATUS_PENDING)
          ->update(['status' => ServiceRenewal::STATUS_INVOICED]);
    }

    private function fillIfColumnsExist($model, array $data): void
    {
        $table = $model->getTable();

        foreach ($data as $key => $value) {
            if (Schema::hasColumn($table, $key)) {
                $model->{$key} = $value;
            }
        }
    }
}
