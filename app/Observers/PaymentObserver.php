<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\InvoiceStatusService;

class PaymentObserver
{
    public function saved(Payment $payment): void
    {
        $service = app(InvoiceStatusService::class);

        $currentInvoiceId = $payment->invoice_id ?? null;
        $originalInvoiceId = $payment->getOriginal('invoice_id');

        if ($originalInvoiceId && $originalInvoiceId !== $currentInvoiceId) {
            $service->syncInvoice((int) $originalInvoiceId);
        }

        if ($currentInvoiceId) {
            $service->syncInvoice((int) $currentInvoiceId);
        }
    }

    public function deleted(Payment $payment): void
    {
        if ($payment->invoice_id) {
            app(InvoiceStatusService::class)->syncInvoice((int) $payment->invoice_id);
        }
    }

    public function restored(Payment $payment): void
    {
        if ($payment->invoice_id) {
            app(InvoiceStatusService::class)->syncInvoice((int) $payment->invoice_id);
        }
    }
}
