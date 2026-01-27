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

        // ✅ Optional (আপনার Spatie permission seed করা থাকলে অন করুন)
        // $this->middleware('permission:activity.view|activity.*')->only(['index', 'show']);
    }

    public function index(Request $request)
    {
        $types = ['call', 'whatsapp', 'email', 'meeting', 'note'];
        $statuses = ['open', 'done'];

        $query = Activity::query()
            ->with(['actor', 'actionable'])
            ->orderByDesc('activity_at')
            ->orderByDesc('id');

        $q = trim((string) $request->input('q', ''));
        if ($q !== '') {
            $query->where(function ($w) use ($q) {
                $w->where('subject', 'like', "%{$q}%")
                    ->orWhere('body', 'like', "%{$q}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->boolean('followup_due')) {
            $query->where('status', 'open')
                ->whereNotNull('next_follow_up_at')
                ->where('next_follow_up_at', '<=', now());
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

    public function show(Activity $activity)
    {
        $activity->load(['actor', 'actionable']);

        return view('activities.show', [
            'activity' => $activity,
            'types' => ['call', 'whatsapp', 'email', 'meeting', 'note'],
            'statuses' => ['open', 'done'],
        ]);
    }
}
