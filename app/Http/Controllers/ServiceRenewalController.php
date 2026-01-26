<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Service;
use App\Models\ServiceRenewal;
use App\Services\InvoiceStatusService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ServiceRenewalController extends Controller
{
    public function generateInvoice(Service $service)
    {
        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->can('renewal.create') || auth()->user()->can('renewal.*'), 403);

        DB::transaction(function () use ($service) {
            $invoice = new Invoice();

            $amount = (float) ($service->amount ?? 0);

            $invoiceData = [
                'client_id' => $service->client_id,
                'due_date'  => now()->addDays(7)->toDateString(),
                'status'    => 'unpaid',
                'currency'  => $service->currency ?? 'BDT',
                'total'     => $amount,
                'paid_total'=> 0,
                'balance'   => $amount,
            ];

            $this->fillIfColumnsExist($invoice, $invoiceData);
            $invoice->save();

            if (class_exists(InvoiceItem::class)) {
                $item = new InvoiceItem();

                $itemData = [
                    'invoice_id'  => $invoice->id,
                    'title'       => 'Service Renewal',
                    'description' => ($service->name ?? 'Service') . ' renewal',
                    'quantity'    => 1,
                    'unit_price'  => $amount,
                    'amount'      => $amount,
                    'line_total'  => $amount,
                ];

                $this->fillIfColumnsExist($item, $itemData);
                $item->save();
            }

            $renewal = new ServiceRenewal();

            $renewalData = [
                'service_id'   => $service->id,
                'renewal_date' => $service->next_renewal_at ?? now()->toDateString(),
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
