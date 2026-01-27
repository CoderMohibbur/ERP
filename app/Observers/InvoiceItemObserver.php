<?php

namespace App\Observers;

use App\Models\InvoiceItem;
use App\Services\InvoiceStatusService;

class InvoiceItemObserver
{
    public function created(InvoiceItem $item): void
    {
        $this->syncInvoice($item);
    }

    public function updated(InvoiceItem $item): void
    {
        $this->syncInvoice($item);
    }

    public function deleted(InvoiceItem $item): void
    {
        $this->syncInvoice($item);
    }

    public function restored(InvoiceItem $item): void
    {
        $this->syncInvoice($item);
    }

    public function forceDeleted(InvoiceItem $item): void
    {
        $this->syncInvoice($item);
    }

    /**
     * Sync parent invoice totals & status safely
     */
    private function syncInvoice(InvoiceItem $item): void
    {
        // Safety guard: no invoice linked
        if (empty($item->invoice_id)) {
            return;
        }

        // Call service (single source of truth)
        app(InvoiceStatusService::class)
            ->syncInvoice((int) $item->invoice_id);
    }
}
