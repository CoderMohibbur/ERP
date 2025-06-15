<?php

namespace App\Http\Controllers;

use App\Models\EmployeeHistory;
use App\Models\Employee;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeHistoryController extends Controller
{
    public function index(): View
    {
        $histories = EmployeeHistory::with(['employee', 'designation', 'department', 'changer'])
            ->latest('effective_from')
            ->paginate(15);

        return view('employee-histories.index', compact('histories'));
    }

    public function create(): View
    {
        $employees    = Employee::pluck('name', 'id');
        $designations = Designation::pluck('name', 'id'); // ✅ fixed
        $departments  = Department::pluck('name', 'id');

        return view('employee-histories.create', compact('employees', 'designations', 'departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'designation_id' => 'required|exists:designations,id',
            'department_id'  => 'nullable|exists:departments,id',
            'effective_from' => 'required|date',
            'effective_to'   => 'nullable|date|after_or_equal:effective_from',
            'change_type'    => 'required|in:promotion,transfer,reinstatement,demotion,joining',
            'remarks'        => 'nullable|string',
        ]);

        $validated['changed_by'] = auth()->id();

        EmployeeHistory::create($validated);

        return redirect()->route('employee-histories.index')
            ->with('success', 'Employee history added successfully.');
    }

    public function edit(EmployeeHistory $employeeHistory): View
    {
        $employees    = Employee::pluck('name', 'id');
        $designations = Designation::pluck('name', 'id'); // ✅ fixed
        $departments  = Department::pluck('name', 'id');

        return view('employee-histories.edit', compact('employeeHistory', 'employees', 'designations', 'departments'));
    }

    public function update(Request $request, EmployeeHistory $employeeHistory): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'    => 'required|exists:employees,id',
            'designation_id' => 'required|exists:designations,id',
            'department_id'  => 'nullable|exists:departments,id',
            'effective_from' => 'required|date',
            'effective_to'   => 'nullable|date|after_or_equal:effective_from',
            'change_type'    => 'required|in:promotion,transfer,reinstatement,demotion,joining',
            'remarks'        => 'nullable|string',
        ]);

        $validated['changed_by'] = auth()->id();

        $employeeHistory->update($validated);

        return redirect()->route('employee-histories.index')
            ->with('success', 'Employee history updated successfully.');
    }

    public function destroy(EmployeeHistory $employeeHistory): RedirectResponse
    {
        $employeeHistory->delete();

        return redirect()->route('employee-histories.index')
            ->with('success', 'Employee history deleted.');
    }
}
