<?php

namespace App\Observers;

use App\Models\Deal;
use App\Services\DealWonService;

class DealObserver
{
    public function updated(Deal $deal): void
    {
        // Trigger only when stage changes to won
        if ($deal->wasChanged('stage') && (string) $deal->stage === 'won') {
            app(DealWonService::class)->handle($deal);
        }
    }
}
