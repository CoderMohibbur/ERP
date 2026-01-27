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

class ServiceRenewalController extends Controller
{
    /**
     * List renewals (UI)
     */
    public function index(Request $request)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.view') || auth()->user()->can('renewal.*'), 403);

        // Validate filters (safe, no FormRequest needed for read)
        $validated = $request->validate([
            'status' => 'nullable|in:pending,invoiced,paid,skipped',
            'from'   => 'nullable|date',
            'to'     => 'nullable|date',
        ]);

        $renewals = ServiceRenewal::query()
            ->with(['service.client'])
            ->when(!empty($validated['status']), fn ($q) => $q->where('status', $validated['status']))
            ->when(!empty($validated['from']), fn ($q) => $q->whereDate('renewal_date', '>=', $validated['from']))
            ->when(!empty($validated['to']), fn ($q) => $q->whereDate('renewal_date', '<=', $validated['to']))
            ->orderByDesc('renewal_date')
            ->paginate(20)
            ->withQueryString();

        return view('service-renewals.index', compact('renewals'));
    }

    /**
     * Show renewal (UI)
     */
    public function show(ServiceRenewal $serviceRenewal)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.view') || auth()->user()->can('renewal.*'), 403);

        $serviceRenewal->load(['service.client']);

        return view('service-renewals.show', compact('serviceRenewal'));
    }

    /**
     * Generate invoice from a Service (Mandatory flow)
     */
    public function generateInvoice(Service $service)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.create') || auth()->user()->can('renewal.*'), 403);

        if (empty($service->client_id)) {
            return back()->with('error', 'This service has no client. Please set client first.');
        }

        // Prevent accidental duplicates for the same service + same renewal_date
        $renewalDate = $service->next_renewal_at ? (string) $service->next_renewal_at : now()->toDateString();

        $existingQuery = ServiceRenewal::query()
            ->where('service_id', $service->id)
            ->whereDate('renewal_date', $renewalDate)
            ->whereNotNull('invoice_id')
            ->whereIn('status', ['invoiced', 'paid']);

        // softDeletes safe check
        if (Schema::hasColumn((new ServiceRenewal())->getTable(), 'deleted_at')) {
            $existingQuery->whereNull('deleted_at');
        }

        $existing = $existingQuery->first();
        if ($existing) {
            return back()->with('success', 'Renewal invoice already generated.')->with('renewal_id', $existing->id);
        }

        DB::transaction(function () use ($service, $renewalDate) {
            $invoice = new Invoice();

            $amount = (float) ($service->amount ?? 0);

            $invoiceData = [
                'client_id'   => $service->client_id,
                'due_date'    => now()->addDays(7)->toDateString(),
                'status'      => 'unpaid',
                'currency'    => $service->currency ?? 'BDT',
                'total'       => $amount,
                'paid_total'  => 0,
                'balance'     => $amount,
            ];

            $this->fillIfColumnsExist($invoice, $invoiceData);
            $invoice->save();

            if (class_exists(InvoiceItem::class)) {
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

            $renewal = new ServiceRenewal();

            $renewalData = [
                'service_id'   => $service->id,
                'renewal_date' => $renewalDate,
                'amount'       => $amount,
                'invoice_id'   => $invoice->id,
                'status'       => 'invoiced',
                'created_by'   => auth()->id(),
            ];

            $this->fillIfColumnsExist($renewal, $renewalData);
            $renewal->save();

            app(InvoiceStatusService::class)->syncInvoice((int) $invoice->id);
        });

        return back()->with('success', 'Renewal invoice generated.');
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
