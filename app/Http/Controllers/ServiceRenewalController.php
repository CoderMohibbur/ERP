<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use App\Models\ServiceRenewal;
use App\Services\InvoiceStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ServiceRenewalController extends Controller
{
    /**
     * List renewals (UI)
     */
    public function index(Request $request)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.view') || auth()->user()->can('renewal.*'), 403);

        $validated = $request->validate([
            'status' => 'nullable|in:pending,invoiced,paid,skipped',
            'from'   => 'nullable|date',
            'to'     => 'nullable|date',
        ]);

        $renewals = ServiceRenewal::query()
            ->with(['service.client'])
            ->when($validated['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
            ->when($validated['from'] ?? null, fn ($q, $v) => $q->whereDate('renewal_date', '>=', $v))
            ->when($validated['to'] ?? null, fn ($q, $v) => $q->whereDate('renewal_date', '<=', $v))
            ->orderByDesc('renewal_date')
            ->paginate(20)
            ->withQueryString();

        return view('service-renewals.index', compact('renewals'));
    }

    /**
     * Show renewal
     */
    public function show(ServiceRenewal $serviceRenewal)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.view') || auth()->user()->can('renewal.*'), 403);

        $serviceRenewal->load(['service.client']);

        return view('service-renewals.show', compact('serviceRenewal'));
    }

    /**
     * Generate renewal invoice (Service -> Invoice + Item + ServiceRenewal row)
     */
    public function generateInvoice(Service $service)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.create') || auth()->user()->can('renewal.*'), 403);

        if (empty($service->client_id)) {
            return back()->with('error', 'Service has no client assigned.');
        }

        $renewalDate = $service->next_renewal_at
            ? (method_exists($service->next_renewal_at, 'toDateString')
                ? $service->next_renewal_at->toDateString()
                : (string) $service->next_renewal_at)
            : now()->toDateString();

        // 🔒 Prevent duplicate invoice for same service + date
        $existing = ServiceRenewal::query()
            ->where('service_id', $service->id)
            ->whereDate('renewal_date', $renewalDate)
            ->whereNotNull('invoice_id')
            ->whereIn('status', [ServiceRenewal::STATUS_INVOICED, ServiceRenewal::STATUS_PAID])
            ->first();

        if ($existing) {
            return back()
                ->with('success', 'Renewal invoice already generated.')
                ->with('renewal_id', $existing->id);
        }

        DB::transaction(function () use ($service, $renewalDate) {
            $amount = round((float) ($service->amount ?? 0), 2);

            /** @var Invoice $invoice */
            $invoice = new Invoice();

            // invoice fields (schema-safe: support old/new columns)
            $invoiceData = [
                'client_id'   => $service->client_id,
                'due_date'    => now()->addDays(7)->toDateString(),
                'issue_date'  => now()->toDateString(),

                // Prefer your InvoiceStatusService logic which writes to `status`:contentReference[oaicite:2]{index=2}
                'status'      => 'unpaid',

                // If some schema uses erp_status, set it too (won’t error due to fillIfColumnsExist)
                'erp_status'  => 'unpaid',

                'currency'    => $service->currency ?? 'BDT',

                // totals (both schema styles)
                'total'        => $amount,
                'total_amount' => $amount,

                'paid_total'   => 0,
                'paid_amount'  => 0,

                'balance'      => $amount,
                'due_amount'   => $amount,

                // optional legacy fields
                'invoice_number' => 'INV-' . now()->format('Ymd-His') . '-' . Str::upper(Str::random(4)),
                'invoice_type'   => 'final',
            ];

            $this->fillIfColumnsExist($invoice, $invoiceData);
            $invoice->save();

            // Invoice Item (optional table/columns safe)
            if (class_exists(InvoiceItem::class)) {
                /** @var InvoiceItem $item */
                $item = new InvoiceItem();

                $itemData = [
                    'invoice_id'   => $invoice->id,
                    'title'        => 'Service Renewal',
                    'description'  => ($service->name ?? 'Service') . ' renewal',
                    'quantity'     => 1,
                    'unit_price'   => $amount,
                    'amount'       => $amount,
                    'line_total'   => $amount,
                ];

                $this->fillIfColumnsExist($item, $itemData);
                $item->save();
            }

            /** @var ServiceRenewal $renewal */
            $renewal = new ServiceRenewal();

            $renewalData = [
                'service_id'   => $service->id,
                'renewal_date' => $renewalDate,
                'amount'       => $amount,
                'invoice_id'   => $invoice->id,
                'status'       => ServiceRenewal::STATUS_INVOICED,
                'created_by'   => auth()->id(),
            ];

            $this->fillIfColumnsExist($renewal, $renewalData);
            $renewal->save();

            // 🔁 Final safety sync: recalculates totals/status based on your existing service logic
            app(InvoiceStatusService::class)->syncInvoice((int) $invoice->id);
        });

        return back()->with('success', 'Renewal invoice generated successfully.');
    }

    /**
     * Fill model only if column exists (schema-safe)
     */
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
