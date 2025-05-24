<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(): View
    {
        $projects = Project::with(['client', 'creator'])->latest()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new project.
     */
    public function create(): View
    {
        $clients = Client::orderBy('id', 'desc')->pluck('name', 'id'); // DESC order
        $newClientId = session('new_client_id'); // সেশন থেকে client id নিচ্ছি

        return view('projects.create', compact('clients', 'newClientId'));
    }


    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        $project = Project::create($data);

        if ($request->has('from_task_form')) {
            return redirect()->route('tasks.create')
        ->with('new_project_id', $project->id)
        ->withInput() // ✅ এটা যোগ করুন
        ->with('success', 'Project created successfully.');
        }

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }


    /**
     * Show the form for editing the specified project.
     */
    public function edit(Project $project): View
    {
        $clients = Client::pluck('name', 'id');
        return view('projects.edit', compact('project', 'clients'));
    }

    /**
     * Update the specified project in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();

        // যদি deadline ফিল্ড string আকারে আসে, তবে সেটাকে date টাইপে কনভার্ট করা যেতে পারে (যদি দরকার হয়)
        if (isset($data['deadline'])) {
            $data['deadline'] = \Carbon\Carbon::parse($data['deadline']);
        }

        $project->update($data);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }


    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
