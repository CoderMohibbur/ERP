<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Client;
use App\Models\Project;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the tasks.
     */
    public function index(): View
    {
        $tasks = Task::with(['project', 'assignedEmployee'])->latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new task.
     */
    public function create(): View
    {
        $projects = Project::orderBy('id', 'desc')->pluck('title', 'id');
        $employees = Employee::orderBy('id', 'desc')->pluck('name', 'id');
        $clients = Client::orderBy('id', 'desc')->pluck('name', 'id');

        $departments = Department::orderBy('id', 'desc')->pluck('name', 'id'); // ✅ add this
        $designations = Designation::orderBy('id', 'desc')->pluck('name', 'id'); // ✅ add this

        $newProjectId = session('new_project_id');
        $newEmployeeId = session('new_employee_id');

        return view('tasks.create', compact(
            'projects',
            'employees',
            'clients',
            'departments', // ✅
            'designations', // ✅
            'newProjectId',
            'newEmployeeId'
        ));
    }


    /**
     * Store a newly created task in storage.
     */
    public function store(StoreTaskRequest $request): RedirectResponse
    {

        Task::create($request->validated()); // note field will be validated and stored here
        return redirect()->route('tasks.index')
            ->with('success', 'Task created successfully.');
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(Task $task): View
    {
        $projects = Project::pluck('title', 'id');
        $employees = Employee::pluck('name', 'id');
        return view('tasks.edit', compact('task', 'projects', 'employees'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $task->update($request->validated()); // note updated here too
        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'Task deleted successfully.');
    }
}
