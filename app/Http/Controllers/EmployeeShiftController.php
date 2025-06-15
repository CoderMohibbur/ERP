<?php

namespace App\Http\Controllers;

use App\Models\EmployeeShift;
use App\Models\Employee;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeShiftController extends Controller
{
    /**
     * Show list of all employee shifts.
     */
    public function index(): View
    {
        $shifts = EmployeeShift::with(['employee', 'shift', 'assignedBy', 'verifiedBy'])
            ->latest()
            ->paginate(20);

        return view('employee-shifts.index', compact('shifts'));
    }

    /**
     * Show form to assign a shift.
     */
    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');
        $shifts    = Shift::pluck('name', 'id');

        return view('employee-shifts.create', compact('employees', 'shifts'));
    }

    /**
     * Store new shift assignment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'          => 'required|exists:employees,id',
            'shift_id'             => 'required|exists:shifts,id',
            'shift_date'           => 'required|date',
            'start_time_override'  => 'nullable|date_format:H:i',
            'end_time_override'    => 'nullable|date_format:H:i',
            'is_manual_override'   => 'boolean',
            'status'               => 'required|in:assigned,completed,cancelled',
            'remarks'              => 'nullable|string',
            'verified_by'          => 'nullable|exists:users,id',
        ]);

        // Prevent duplicate assignment on same day
        $exists = EmployeeShift::where('employee_id', $validated['employee_id'])
            ->where('shift_date', $validated['shift_date'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['This employee already has a shift on that date.'])->withInput();
        }

        $validated['assigned_by'] = auth()->id();
        $validated['shift_type_cache'] = Shift::find($validated['shift_id'])?->type;

        EmployeeShift::create($validated);

        return redirect()->route('employee-shifts.index')
            ->with('success', 'Shift assigned successfully.');
    }

    /**
     * Edit an assigned shift.
     */
    public function edit(EmployeeShift $employeeShift): View
    {
        $employees = Employee::pluck('name', 'id');
        $shifts    = Shift::pluck('name', 'id');

        return view('employee-shifts.edit', compact('employeeShift', 'employees', 'shifts'));
    }

    /**
     * Update shift assignment.
     */
    public function update(Request $request, EmployeeShift $employeeShift): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'          => 'required|exists:employees,id',
            'shift_id'             => 'required|exists:shifts,id',
            'shift_date'           => 'required|date',
            'start_time_override'  => 'nullable|date_format:H:i',
            'end_time_override'    => 'nullable|date_format:H:i',
            'is_manual_override'   => 'boolean',
            'status'               => 'required|in:assigned,completed,cancelled',
            'remarks'              => 'nullable|string',
            'verified_by'          => 'nullable|exists:users,id',
        ]);

        // Prevent duplicate shift (excluding current row)
        $duplicate = EmployeeShift::where('employee_id', $validated['employee_id'])
            ->where('shift_date', $validated['shift_date'])
            ->where('id', '!=', $employeeShift->id)
            ->exists();

        if ($duplicate) {
            return back()->withErrors(['Another shift exists for this employee on this date.'])->withInput();
        }

        $validated['assigned_by'] = auth()->id();
        $validated['shift_type_cache'] = Shift::find($validated['shift_id'])?->type;

        $employeeShift->update($validated);

        return redirect()->route('employee-shifts.index')
            ->with('success', 'Employee shift updated successfully.');
    }

    /**
     * Delete shift assignment.
     */
    public function destroy(EmployeeShift $employeeShift): RedirectResponse
    {
        $employeeShift->delete();

        return redirect()->route('employee-shifts.index')
            ->with('success', 'Employee shift deleted.');
    }
}
