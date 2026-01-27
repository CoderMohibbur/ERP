<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ActivityPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // 🔒 P0: DO NOT leave this commented.
        // Allow if user has any of these permissions (Spatie):
        // - activity.* (spec minimum)
        // - activity.view / activity.index (optional granular)
        $this->middleware('permission:activity.*|activity.view|activity.index')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $types = Activity::TYPES;
        $statuses = Activity::STATUSES;

        $query = Activity::query()
            ->with([
                'actor:id,name',
                'actionable',
            ])
            ->orderByDesc('activity_at')
            ->orderByDesc('id');

        // 🔒 If user doesn't have "view all", limit to own activities only
        if (!$this->canViewAllActivities($user)) {
            $query->where('actor_id', $user->id);
        }

        $q = trim((string) $request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('subject', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', (string) $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        if ($request->boolean('followup_due')) {
            $query->followUpDue();
        }

        if ($request->filled('from')) {
            $from = Carbon::parse($request->input('from'))->startOfDay();
            $query->where('activity_at', '>=', $from);
        }

        if ($request->filled('to')) {
            $to = Carbon::parse($request->input('to'))->endOfDay();
            $query->where('activity_at', '<=', $to);
        }

        $activities = $query->paginate(20)->withQueryString();

        return view('activities.index', [
            'activities' => $activities,
            'types' => $types,
            'statuses' => $statuses,
            'filters' => [
                'q' => $q,
                'type' => (string) $request->input('type', ''),
                'status' => (string) $request->input('status', ''),
                'followup_due' => $request->boolean('followup_due'),
                'from' => (string) $request->input('from', ''),
                'to' => (string) $request->input('to', ''),
            ],
        ]);
    }

    public function show(Request $request, Activity $activity)
    {
        $user = $request->user();

        // 🔒 Block direct URL access to others' activities if user can't view all
        if (!$this->canViewAllActivities($user) && (int) $activity->actor_id !== (int) $user->id) {
            abort(403);
        }

        $activity->load([
            'actor:id,name',
            'actionable',
        ]);

        return view('activities.show', [
            'activity' => $activity,
            'types' => Activity::TYPES,
            'statuses' => Activity::STATUSES,
        ]);
    }

    private function canViewAllActivities($user): bool
    {
        if (!$user) return false;

        // Spec minimum: activity.* means full access
        return $user->can('activity.*')
            || $user->can('activity.viewAll')
            || $user->can('activity.view_any')
            || $user->can('activity.viewAny')
            || $user->can('activity.admin');
    }
}
