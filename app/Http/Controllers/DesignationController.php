<?php

namespace App\Http\Controllers;

use App\Models\Designation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDesignationRequest;
use App\Http\Requests\UpdateDesignationRequest;
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
    public function store(StoreDesignationRequest $request): RedirectResponse
    {
        Designation::create($request->validated());

        return redirect()->route('designations.index')
                         ->with('success', 'Designation created successfully.');
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
    public function update(UpdateDesignationRequest $request, Designation $designation): RedirectResponse
    {
        $designation->update($request->validated());

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