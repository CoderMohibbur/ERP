<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use Illuminate\Support\Facades\Schema;

class InvoiceStatusService
{
    public function syncInvoice(int $invoiceId): void
    {
        $invoice = Invoice::query()->find($invoiceId);
        if (!$invoice) {
            return;
        }

        // If invoice is void, never touch it
        if (isset($invoice->status) && (string) $invoice->status === 'void') {
            return;
        }

        $total = $this->getInvoiceTotal($invoiceId, $invoice);
        $paid  = $this->getInvoicePaidTotal($invoiceId);

        $balance = max(0, round($total - $paid, 2));

        $status = 'unpaid';
        if ($paid > 0 && $paid < $total) {
            $status = 'partial';
        } elseif ($total > 0 && $paid >= $total) {
            $status = 'paid';
        }

        // Write only if columns exist
        $this->fillIfColumnsExist($invoice, [
            'total'      => $total,
            'paid_total' => $paid,
            'balance'    => $balance,
            'status'     => $status,
        ]);

        // Avoid unnecessary updated events (safe)
        $invoice->saveQuietly();
    }

    private function getInvoicePaidTotal(int $invoiceId): float
    {
        $amountCol = Schema::hasColumn('payments', 'amount') ? 'amount' : null;
        if (!$amountCol) {
            return 0.0;
        }

        $paid = (float) Payment::query()
            ->where('invoice_id', $invoiceId)
            ->sum($amountCol);

        return round($paid, 2);
    }

    private function getInvoiceTotal(int $invoiceId, Invoice $invoice): float
    {
        // Prefer invoice.total if exists
        if (Schema::hasColumn('invoices', 'total') && $invoice->total !== null) {
            return round((float) $invoice->total, 2);
        }
        if (Schema::hasColumn('invoices', 'total_amount') && isset($invoice->total_amount)) {
            return round((float) $invoice->total_amount, 2);
        }

        // Fallback: compute from invoice_items
        $items = InvoiceItem::query()->where('invoice_id', $invoiceId)->get();

        $sum = 0.0;

        $hasAmount    = Schema::hasColumn('invoice_items', 'amount');
        $hasLineTotal = Schema::hasColumn('invoice_items', 'line_total');
        $hasQty       = Schema::hasColumn('invoice_items', 'quantity');
        $hasPrice     = Schema::hasColumn('invoice_items', 'unit_price');

        foreach ($items as $item) {
            if ($hasAmount && $item->amount !== null) {
                $sum += (float) $item->amount;
                continue;
            }
            if ($hasLineTotal && $item->line_total !== null) {
                $sum += (float) $item->line_total;
                continue;
            }
            if ($hasQty && $hasPrice) {
                $sum += ((float) $item->quantity) * ((float) $item->unit_price);
                continue;
            }
        }

        return round($sum, 2);
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
