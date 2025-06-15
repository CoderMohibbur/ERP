<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class ShiftController extends Controller
{
    /**
     * Display a listing of the shifts.
     */
    public function index(): View
    {
        $shifts = Shift::latest()->paginate(15);

        return view('shifts.index', compact('shifts'));
    }

    /**
     * Show the form for creating a new shift.
     */
    public function create(): View
    {
        return view('shifts.create');
    }

    /**
     * Store a newly created shift in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:100|unique:shifts,name',
            'slug'              => 'nullable|string|max:100|unique:shifts,slug',
            'code'              => 'required|string|max:20|unique:shifts,code',
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i',
            'crosses_midnight'  => 'boolean',
            'type'              => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:20',
            'notes'             => 'nullable|string',
            'week_days'         => 'nullable|array',
            'week_days.*'       => 'in:sun,mon,tue,wed,thu,fri,sat',
            'is_active'         => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['created_by'] = auth()->id();

        Shift::create($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift created successfully.');
    }

    /**
     * Show the form for editing the specified shift.
     */
    public function edit(Shift $shift): View
    {
        return view('shifts.edit', compact('shift'));
    }

    /**
     * Update the specified shift in storage.
     */
    public function update(Request $request, Shift $shift): RedirectResponse
    {
        $validated = $request->validate([
            'name'              => 'required|string|max:100|unique:shifts,name,' . $shift->id,
            'slug'              => 'nullable|string|max:100|unique:shifts,slug,' . $shift->id,
            'code'              => 'required|string|max:20|unique:shifts,code,' . $shift->id,
            'start_time'        => 'required|date_format:H:i',
            'end_time'          => 'required|date_format:H:i',
            'crosses_midnight'  => 'boolean',
            'type'              => 'nullable|string|max:50',
            'color'             => 'nullable|string|max:20',
            'notes'             => 'nullable|string',
            'week_days'         => 'nullable|array',
            'week_days.*'       => 'in:sun,mon,tue,wed,thu,fri,sat',
            'is_active'         => 'boolean',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['updated_by'] = auth()->id();

        $shift->update($validated);

        return redirect()->route('shifts.index')
            ->with('success', 'Shift updated successfully.');
    }

    /**
     * Remove the specified shift from storage.
     */
    public function destroy(Shift $shift): RedirectResponse
    {
        $shift->delete();

        return redirect()->route('shifts.index')
            ->with('success', 'Shift deleted successfully.');
    }
}
