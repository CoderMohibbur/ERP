<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectNote;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreProjectNoteRequest;
use App\Http\Requests\UpdateProjectNoteRequest;

class ProjectNoteController extends Controller
{
    /**
     * Display a listing of the project notes.
     */
    public function index(): View
    {
        $notes = ProjectNote::with(['project', 'creator'])->latest()->paginate(10);
        return view('project-notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new project note.
     */
    public function create(): View
    {
        $projects = Project::pluck('name', 'id');
        return view('project-notes.create', compact('projects'));
    }

    /**
     * Store a newly created project note in storage.
     */
    public function store(StoreProjectNoteRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        ProjectNote::create($data);

        return redirect()->route('project-notes.index')
                         ->with('success', 'Project note created successfully.');
    }

    /**
     * Show the form for editing the specified project note.
     */
    public function edit(ProjectNote $projectNote): View
    {
        $projects = Project::pluck('name', 'id');
        return view('project-notes.edit', compact('projectNote', 'projects'));
    }

    /**
     * Update the specified project note in storage.
     */
    public function update(UpdateProjectNoteRequest $request, ProjectNote $projectNote): RedirectResponse
    {
        $projectNote->update($request->validated());

        return redirect()->route('project-notes.index')
                         ->with('success', 'Project note updated successfully.');
    }

    /**
     * Remove the specified project note from storage.
     */
    public function destroy(ProjectNote $projectNote): RedirectResponse
    {
        $projectNote->delete();

        return redirect()->route('project-notes.index')
                         ->with('success', 'Project note deleted successfully.');
    }
}
