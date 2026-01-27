<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Lead;
use App\Models\Service;
use App\Models\TimeLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class OwnerDashboardController extends Controller
{
    // public function __construct()
    // {
    //     // dashboard is sensitive (owner-only / management)
    //     $this->middleware('auth');

    //     // safest permission check (supports either exact or wildcard)
    //     // - spec uses: dashboard.owner / dashboard.team (wildcards possible)
    //     // - keep backward compatibility: owner_dashboard.view / owner_dashboard.*
    //     $this->middleware(function ($request, $next) {
    //         $u = auth()->user();

    //         abort_unless($u, 401);

    //         $allowed =
    //             $u->can('dashboard.owner') || $u->can('dashboard.*') ||
    //             $u->can('owner_dashboard.view') || $u->can('owner_dashboard.*');

    //         abort_unless($allowed, 403);

    //         return $next($request);
    //     });
    // }

    public function index(Request $request): View
    {
        $today = Carbon::today();

        // window (1..60 days)
        $inDays = (int) $request->input('in_days', 7);
        $inDays = max(1, min(60, $inDays));
        $until = $today->copy()->addDays($inDays);

        /*
        |--------------------------------------------------------------------------
        | Follow-ups due (Leads)
        |--------------------------------------------------------------------------
        */
        $followUpsDue = collect();
        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'next_follow_up_at')) {
            $followUpsDue = Lead::query()
                ->with(['owner:id,name'])
                ->when(Schema::hasColumn('leads', 'deleted_at'), fn ($q) => $q->whereNull('deleted_at'))
                ->whereNotNull('next_follow_up_at')
                ->whereDate('next_follow_up_at', '<=', $until->toDateString())
                ->orderBy('next_follow_up_at')
                ->limit(25)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Renewals due (Services)
        |--------------------------------------------------------------------------
        */
        $renewalsDue = collect();
        if (Schema::hasTable('services') && Schema::hasColumn('services', 'next_renewal_at')) {
            $renewalsDue = Service::query()
                ->with(['client'])
                ->when(Schema::hasColumn('services', 'deleted_at'), fn ($q) => $q->whereNull('deleted_at'))
                ->whereNotNull('next_renewal_at')
                ->whereDate('next_renewal_at', '<=', $until->toDateString())
                ->when(Schema::hasColumn('services', 'status'), function ($q) {
                    // current code used active/suspended — keep same safe filter
                    $q->whereIn('status', ['active', 'suspended']);
                })
                ->orderBy('next_renewal_at')
                ->limit(25)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Due invoices (unpaid/partial up to window)
        |--------------------------------------------------------------------------
        */
        $dueInvoices = collect();
        $overdueCount = 0;

        if (Schema::hasTable('invoices')) {
            // Prefer Eloquent when possible, fallback to DB when Invoice model/table columns differ
            $hasStatus = Schema::hasColumn('invoices', 'status');
            $hasDueDate = Schema::hasColumn('invoices', 'due_date');

            if ($hasStatus) {
                // Eloquent path (best: with client eager load)
                try {
                    $invoiceQuery = Invoice::query()
                        ->with(['client'])
                        ->when(Schema::hasColumn('invoices', 'deleted_at'), fn ($q) => $q->whereNull('deleted_at'))
                        ->whereIn('status', ['unpaid', 'partial']);

                    if ($hasDueDate) {
                        $dueInvoices = (clone $invoiceQuery)
                            ->whereNotNull('due_date')
                            ->whereDate('due_date', '<=', $until->toDateString())
                            ->orderBy('due_date')
                            ->limit(25)
                            ->get();

                        $overdueCount = (clone $invoiceQuery)
                            ->whereNotNull('due_date')
                            ->whereDate('due_date', '<', $today->toDateString())
                            ->count();
                    } else {
                        // no due_date column -> just latest unpaid/partial
                        $dueInvoices = (clone $invoiceQuery)
                            ->orderByDesc('id')
                            ->limit(25)
                            ->get();
                        $overdueCount = 0;
                    }
                } catch (\Throwable $e) {
                    // fallback: DB query (never crash dashboard)
                    $query = DB::table('invoices')->whereIn('status', ['unpaid', 'partial']);

                    if ($hasDueDate) {
                        $query->whereDate('due_date', '<=', $until->toDateString())->orderBy('due_date');
                        $overdueCount = (clone $query)->whereDate('due_date', '<', $today->toDateString())->count();
                    } elseif (Schema::hasColumn('invoices', 'invoice_date')) {
                        $query->orderBy('invoice_date');
                    } else {
                        $query->orderByDesc('id');
                    }

                    $dueInvoices = $query->limit(25)->get();
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Team load (weekly seconds aggregated)
        |--------------------------------------------------------------------------
        */
        $teamLoad = collect();

        if (Schema::hasTable('users')) {
            $users = User::query()
                ->select(['id', 'name', 'email'])
                ->orderBy('name')
                ->limit(50)
                ->get();

            $weekStart = $today->copy()->startOfWeek();
            $weekEnd = $today->copy()->endOfWeek();

            $secondsByUser = collect();

            if (Schema::hasTable('time_logs') && Schema::hasColumn('time_logs', 'user_id')) {
                // Prefer sum(seconds) if seconds exists
                if (Schema::hasColumn('time_logs', 'seconds')) {
                    $secondsByUser = TimeLog::query()
                        ->when(Schema::hasColumn('time_logs', 'deleted_at'), fn ($q) => $q->whereNull('deleted_at'))
                        ->when(Schema::hasColumn('time_logs', 'started_at'), function ($q) use ($weekStart, $weekEnd) {
                            $q->whereBetween('started_at', [
                                $weekStart->toDateTimeString(),
                                $weekEnd->toDateTimeString(),
                            ]);
                        })
                        ->selectRaw('user_id, COALESCE(SUM(seconds),0) as total_seconds')
                        ->groupBy('user_id')
                        ->pluck('total_seconds', 'user_id');
                } else {
                    // seconds missing -> compute from ended_at-started_at where possible (DB-level best effort)
                    if (Schema::hasColumn('time_logs', 'started_at') && Schema::hasColumn('time_logs', 'ended_at')) {
                        $secondsByUser = TimeLog::query()
                            ->when(Schema::hasColumn('time_logs', 'deleted_at'), fn ($q) => $q->whereNull('deleted_at'))
                            ->whereNotNull('ended_at')
                            ->whereBetween('started_at', [
                                $weekStart->toDateTimeString(),
                                $weekEnd->toDateTimeString(),
                            ])
                            ->selectRaw('user_id, COALESCE(SUM(TIMESTAMPDIFF(SECOND, started_at, ended_at)),0) as total_seconds')
                            ->groupBy('user_id')
                            ->pluck('total_seconds', 'user_id');
                    }
                }
            }

            $teamLoad = $users->map(function ($u) use ($secondsByUser) {
                $seconds = (int) ($secondsByUser[$u->id] ?? 0);
                $u->week_seconds = $seconds;
                $u->week_hours = round($seconds / 3600, 2);
                return $u;
            });
        }

        return view('owner.dashboard', compact(
            'today',
            'inDays',
            'until',
            'followUpsDue',
            'renewalsDue',
            'dueInvoices',
            'overdueCount',
            'teamLoad'
        ));
    }

    // backward compatible (আগে যদি invokable দিয়ে call করা হয়ে থাকে)
    public function __invoke(Request $request): View
    {
        return $this->index($request);
    }
}
