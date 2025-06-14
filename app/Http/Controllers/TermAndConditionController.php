<?php

namespace App\Http\Controllers;

use App\Models\TermAndCondition;
use Illuminate\Http\Request;

class TermAndConditionController extends Controller
{
    public function index()
    {
        $terms = TermAndCondition::latest()->paginate(10);
        return view('terms.index', compact('terms'));
    }

    public function create()
    {
        return view('terms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        TermAndCondition::create($request->only(['title', 'description']));

        return redirect()->route('terms.index')->with('success', 'Term created successfully.');
    }

    public function edit(TermAndCondition $term)
    {
        return view('terms.edit', compact('term'));
    }

    public function update(Request $request, TermAndCondition $term)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $term->update($request->only(['title', 'description']));

        return redirect()->route('terms.index')->with('success', 'Term updated successfully.');
    }

    public function destroy(TermAndCondition $term)
    {
        $term->delete();
        return back()->with('success', 'Term deleted successfully.');
    }
}
