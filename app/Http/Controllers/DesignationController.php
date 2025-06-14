<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DesignationController extends Controller
{
    /**
     * Display a listing of the designations.
     */
    public function index(): View
    {
        $designations = Designation::latest()->paginate(10);
        return view('designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new designation.
     */
    public function create(): View
    {
        return view('designations.create');
    }

    /**
     * Store a newly created designation in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:designations,name',
            'code'        => 'required|string|max:10|unique:designations,code',
            'description' => 'nullable|string',
            'level'       => 'nullable|integer|min:1|max:10',
        ]);

        $designation = Designation::create([
            'name'        => $request->name,
            'code'        => $request->code,
            'description' => $request->description,
            'level'       => $request->level,
            'created_by'  => Auth::id(),
        ]);

        // 🎯 If used from a form modal (e.g., Employee form)
        if ($request->has('from_employee_form')) {
            session()->flash('new_designation_id', $designation->id);
            return redirect()->back()->with('success', '✅ New Designation added successfully.');
        }

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
    }

    /**
     * Show the form for editing the specified designation.
     */
    public function edit(Designation $designation): View
    {
        return view('designations.edit', compact('designation'));
    }

    /**
     * Update the specified designation in storage.
     */
    public function update(Request $request, Designation $designation): RedirectResponse
    {
        $request->validate([
            'name'        => 'required|string|max:255|unique:designations,name,' . $designation->id,
            'code'        => 'required|string|max:10|unique:designations,code,' . $designation->id,
            'description' => 'nullable|string',
            'level'       => 'nullable|integer|min:1|max:10',
        ]);

        $designation->update([
            'name'        => $request->name,
            'code'        => $request->code,
            'description' => $request->description,
            'level'       => $request->level,
            'updated_by'  => Auth::id(),
        ]);

        return redirect()->route('designations.index')
                         ->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified designation from storage.
     */
    public function destroy(Designation $designation): RedirectResponse
    {
        $designation->delete();

        return redirect()->route('designations.index')
                         ->with('success', 'Designation deleted successfully.');
    }
}
