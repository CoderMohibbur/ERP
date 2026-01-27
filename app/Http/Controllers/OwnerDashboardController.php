<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $today = Carbon::today();
        $inDays = (int) $request->input('in_days', 7);
        $inDays = max(1, min(60, $inDays));
        $until = $today->copy()->addDays($inDays);

        $followUpsDue = Lead::query()
            // ✅ N+1 safe if dashboard shows lead owner
            ->with(['owner:id,name'])
            ->whereNotNull('next_follow_up_at')
            ->whereDate('next_follow_up_at', '<=', $until->toDateString())
            ->orderBy('next_follow_up_at')
            ->limit(25)
            ->get();

        $renewalsDue = Service::query()
            ->with(['client'])
            ->whereDate('next_renewal_at', '<=', $until->toDateString())
            ->whereIn('status', ['active', 'suspended'])
            ->orderBy('next_renewal_at')
            ->limit(25)
            ->get();

        $dueInvoices = collect();
        if (Schema::hasTable('invoices') && Schema::hasColumn('invoices', 'status')) {
            $query = DB::table('invoices')->whereIn('status', ['unpaid', 'partial']);

            if (Schema::hasColumn('invoices', 'due_date')) {
                $query->whereDate('due_date', '<=', $until->toDateString())->orderBy('due_date');
            } elseif (Schema::hasColumn('invoices', 'invoice_date')) {
                $query->orderBy('invoice_date');
            } else {
                $query->orderByDesc('id');
            }

            $dueInvoices = $query->limit(25)->get();
        }

        $teamLoad = User::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->limit(50)
            ->get();

        return view('owner.dashboard', compact(
            'today',
            'inDays',
            'until',
            'followUpsDue',
            'renewalsDue',
            'dueInvoices',
            'teamLoad'
        ));
    }

    // backward compatible (আগে যদি invokable দিয়ে call করা হয়ে থাকে)
    public function __invoke(Request $request): View
    {
        return $this->index($request);
    }
}
