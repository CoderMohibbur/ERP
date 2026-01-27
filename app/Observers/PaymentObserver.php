<?php

namespace App\Observers;

use App\Models\Payment;
use App\Services\InvoiceStatusService;

class PaymentObserver
{
    public function created(Payment $payment): void
    {
        $this->sync($payment);
    }

    public function updated(Payment $payment): void
    {
        $this->sync($payment);
    }

    public function deleted(Payment $payment): void
    {
        $this->sync($payment);
    }

    public function restored(Payment $payment): void
    {
        $this->sync($payment);
    }

    public function forceDeleted(Payment $payment): void
    {
        $this->sync($payment);
    }

    private function sync(Payment $payment): void
    {
        if (empty($payment->invoice_id)) {
            return;
        }

        app(InvoiceStatusService::class)->syncInvoice((int) $payment->invoice_id);
    }
}
