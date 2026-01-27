<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Deal;
use App\Models\Payment;
use App\Models\TimeLog;
use App\Observers\DealObserver;
use App\Observers\PaymentObserver;
use App\Observers\TimeLogObserver;
use App\Models\InvoiceItem;
use App\Observers\InvoiceItemObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Deal::observe(DealObserver::class);
        Payment::observe(PaymentObserver::class);
        TimeLog::observe(TimeLogObserver::class);
        InvoiceItem::observe(InvoiceItemObserver::class);
    }
}
