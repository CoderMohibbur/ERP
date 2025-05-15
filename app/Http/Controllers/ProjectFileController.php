<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Http\Requests\StoreProjectFileRequest;
use App\Http\Requests\UpdateProjectFileRequest;

class ProjectFileController extends Controller
{
    /**
     * Display a listing of the project files.
     */
    public function index(): View
    {
        $files = ProjectFile::with('project')->latest()->paginate(10);
        return view('project-files.index', compact('files'));
    }

    /**
     * Show the form for uploading a new file.
     */
    public function create(): View
    {
        $projects = Project::pluck('title', 'id');

        return view('project-files.create', compact('projects'));
    }

    /**
     * Store a newly uploaded file.
     */
    public function store(StoreProjectFileRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('project_files');
        }

        ProjectFile::create($data);

        return redirect()->route('project-files.index')
                         ->with('success', 'Project file uploaded successfully.');
    }

    /**
     * Show the form for editing the specified file.
     */
    public function edit(ProjectFile $projectFile): View
    {
        $projects = Project::pluck('title', 'id');

        return view('project-files.edit', compact('projectFile', 'projects'));
    }

    /**
     * Update the specified file in storage.
     */
    public function update(UpdateProjectFileRequest $request, ProjectFile $projectFile): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('file')) {
            if ($projectFile->file_path && Storage::exists($projectFile->file_path)) {
                Storage::delete($projectFile->file_path);
            }
            $data['file_path'] = $request->file('file')->store('project_files');
        }

        $projectFile->update($data);

        return redirect()->route('project-files.index')
                         ->with('success', 'Project file updated successfully.');
    }

    /**
     * Remove the specified file.
     */
    public function destroy(ProjectFile $projectFile): RedirectResponse
    {
        if ($projectFile->file_path && Storage::exists($projectFile->file_path)) {
            Storage::delete($projectFile->file_path);
        }

        $projectFile->delete();

        return redirect()->route('project-files.index')
                         ->with('success', 'Project file deleted successfully.');
    }
}
