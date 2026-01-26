<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use App\Models\TimeLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $query = Task::with(['project', 'assignee'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tasks = $query->paginate(10)->withQueryString();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * ✅ Task show page (Spec): Start/Stop timer + Today total
     */
    public function show(Task $task): View
    {
        // Eager load to avoid N+1 on show
        $task->loadMissing(['project', 'assignee']);

        $userId = auth()->id();

        $todayStart = now()->startOfDay();
        $todayEnd   = now()->endOfDay();

        // Today logs that overlap today (cross-midnight safe)
        $logs = TimeLog::query()
            ->where('task_id', $task->id)
            ->where('user_id', $userId)
            ->where('started_at', '<=', $todayEnd)
            ->where(function ($q) use ($todayStart) {
                $q->whereNull('ended_at')
                    ->orWhere('ended_at', '>=', $todayStart);
            })
            ->orderByDesc('started_at')
            ->get();

        // Calculate today's total seconds precisely by overlap with today's window
        $todayTotalSeconds = 0;
        foreach ($logs as $log) {
            $start = Carbon::parse($log->started_at);
            $end   = $log->ended_at ? Carbon::parse($log->ended_at) : now();

            $effectiveStart = $start->greaterThan($todayStart) ? $start : $todayStart;
            $effectiveEnd   = $end->lessThan($todayEnd) ? $end : $todayEnd;

            if ($effectiveEnd->greaterThan($effectiveStart)) {
                $todayTotalSeconds += $effectiveEnd->diffInSeconds($effectiveStart);
            }
        }

        // Running timer for this user (any task) - for UI warning/disable start
        $runningAny = TimeLog::query()
            ->where('user_id', $userId)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->first();

        $runningForThis = ($runningAny && (int) $runningAny->task_id === (int) $task->id) ? $runningAny : null;
        $runningOther   = ($runningAny && (int) $runningAny->task_id !== (int) $task->id) ? $runningAny : null;

        return view('tasks.show', [
            'task' => $task,
            'todayLogs' => $logs,
            'todayTotalSeconds' => (int) $todayTotalSeconds,
            'runningForThis' => $runningForThis,
            'runningOther' => $runningOther,
        ]);
    }

    public function create(): View
    {
        $projects   = Project::pluck('title', 'id');
        $employees  = User::pluck('name', 'id'); // ✅ rename to match view
        $tasks      = Task::pluck('title', 'id');

        return view('tasks.create', compact('projects', 'employees', 'tasks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'priority'           => 'required|in:low,normal,high,urgent',
            'status'             => 'required|in:pending,in_progress,completed,blocked',
            'progress'           => 'nullable|integer|min:0|max:100',
            'start_date'         => 'nullable|date',
            'due_date'           => 'nullable|date|after_or_equal:start_date',
            'end_date'           => 'nullable|date',
            'estimated_hours'    => 'nullable|numeric|min:0',
            'actual_hours'       => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
            'project_id'         => 'required|exists:projects,id',
            'assigned_to'        => 'nullable|exists:users,id',
            'parent_task_id'     => 'nullable|exists:tasks,id',
            'dependency_task_id' => 'nullable|exists:tasks,id',
        ]);

        $validated['created_by'] = Auth::id();

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', '✅ Task created successfully.');
    }

    public function edit(Task $task): View
    {
        $projects   = Project::pluck('title', 'id');
        $employees  = User::pluck('name', 'id'); // ✅ rename to match view
        $tasks      = Task::where('id', '!=', $task->id)->pluck('title', 'id');

        return view('tasks.edit', compact('task', 'projects', 'employees', 'tasks'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'priority'           => 'required|in:low,normal,high,urgent',
            'status'             => 'required|in:pending,in_progress,completed,blocked',
            'progress'           => 'nullable|integer|min:0|max:100',
            'start_date'         => 'nullable|date',
            'due_date'           => 'nullable|date|after_or_equal:start_date',
            'end_date'           => 'nullable|date',
            'estimated_hours'    => 'nullable|numeric|min:0',
            'actual_hours'       => 'nullable|numeric|min:0',
            'note'               => 'nullable|string',
            'project_id'         => 'required|exists:projects,id',
            'assigned_to'        => 'nullable|exists:users,id',
            'parent_task_id'     => 'nullable|exists:tasks,id',
            'dependency_task_id' => 'nullable|exists:tasks,id',
        ]);

        $validated['updated_by'] = Auth::id();

        $task->update($validated);

        return redirect()->route('tasks.index')->with('success', '✅ Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', '🗑️ Task deleted successfully.');
    }
}
