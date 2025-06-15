<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

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
