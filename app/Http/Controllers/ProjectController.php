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
        $clients = Client::pluck('name', 'id');
        return view('projects.create', compact('clients'));
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();
        Project::create($data);

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
        $project->update($request->validated());

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
