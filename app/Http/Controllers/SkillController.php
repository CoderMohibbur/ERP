<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class SkillController extends Controller
{
    /**
     * Display a listing of the skills.
     */
    public function index(): View
    {
        $skills = Skill::latest()->paginate(15);

        return view('skills.index', compact('skills'));
    }

    /**
     * Show the form for creating a new skill.
     */
    public function create(): View
    {
        return view('skills.create');
    }

    /**
     * Store a newly created skill in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:skills,name',
            'slug'        => 'nullable|string|max:100|unique:skills,slug',
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['created_by'] = auth()->id();

        Skill::create($validated);

        return redirect()->route('skills.index')
            ->with('success', 'Skill created successfully.');
    }

    /**
     * Show the form for editing the specified skill.
     */
    public function edit(Skill $skill): View
    {
        return view('skills.edit', compact('skill'));
    }

    /**
     * Update the specified skill in storage.
     */
    public function update(Request $request, Skill $skill): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:skills,name,' . $skill->id,
            'slug'        => 'nullable|string|max:100|unique:skills,slug,' . $skill->id,
            'category'    => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $skill->update($validated);

        return redirect()->route('skills.index')
            ->with('success', 'Skill updated successfully.');
    }

    /**
     * Remove the specified skill from storage.
     */
    public function destroy(Skill $skill): RedirectResponse
    {
        $skill->delete();

        return redirect()->route('skills.index')
            ->with('success', 'Skill deleted successfully.');
    }
}
