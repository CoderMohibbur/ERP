<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index(Request $request): View
    {
        $query = Project::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                  ->orWhere('project_code', 'like', "%$search%");
            });
        }

        $projects = $query->latest()->paginate(10)->withQueryString();

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
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'deadline'      => 'nullable|date',
            'started_at'    => 'nullable|date',
            'completed_at'  => 'nullable|date',
            'budget'        => 'nullable|numeric',
            'actual_cost'   => 'nullable|numeric',
            'project_code'  => 'required|string|unique:projects,project_code',
            'priority'      => 'required|in:low,medium,high,urgent',
            'status'        => 'required|in:pending,in_progress,completed,cancelled',
            'client_id'     => 'required|exists:clients,id',
        ]);

        $data['created_by'] = Auth::id();

        Project::create($data);

        return redirect()->route('projects.index')->with('success', '✅ Project created successfully.');
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
    public function update(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string',
            'deadline'      => 'nullable|date',
            'started_at'    => 'nullable|date',
            'completed_at'  => 'nullable|date',
            'budget'        => 'nullable|numeric',
            'actual_cost'   => 'nullable|numeric',
            'project_code'  => 'required|string|unique:projects,project_code,' . $project->id,
            'priority'      => 'required|in:low,medium,high,urgent',
            'status'        => 'required|in:pending,in_progress,completed,cancelled',
            'client_id'     => 'required|exists:clients,id',
        ]);

        $data['updated_by'] = Auth::id();

        $project->update($data);

        return redirect()->route('projects.index')->with('success', '✅ Project updated successfully.');
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', '🗑️ Project deleted successfully.');
    }
}
