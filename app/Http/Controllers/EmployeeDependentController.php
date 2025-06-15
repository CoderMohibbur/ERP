<?php

namespace App\Http\Controllers;

use App\Models\EmployeeDependent;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class EmployeeDependentController extends Controller
{
    /**
     * Display a listing of the employee dependents.
     */
    public function index(): View
    {
        $dependents = EmployeeDependent::with('employee')
            ->latest()
            ->paginate(20);

        return view('employee-dependents.index', compact('dependents'));
    }

    /**
     * Show the form for creating a new dependent.
     */
    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('employee-dependents.create', compact('employees'));
    }

    /**
     * Store a newly created dependent in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'name'                => 'required|string|max:255',
            'relation'            => 'required|in:spouse,child,father,mother,sibling,other',
            'dob'                 => 'nullable|date',
            'phone'               => 'nullable|string|max:20',
            'nid_number'          => 'nullable|string|max:30|unique:employee_dependents,nid_number',
            'is_emergency_contact'=> 'boolean',
            'notes'               => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        EmployeeDependent::create($validated);

        return redirect()->route('employee-dependents.index')
            ->with('success', 'Dependent added successfully.');
    }

    /**
     * Show the form for editing the specified dependent.
     */
    public function edit(EmployeeDependent $employeeDependent): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('employee-dependents.edit', compact('employeeDependent', 'employees'));
    }

    /**
     * Update the specified dependent in storage.
     */
    public function update(Request $request, EmployeeDependent $employeeDependent): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id'         => 'required|exists:employees,id',
            'name'                => 'required|string|max:255',
            'relation'            => 'required|in:spouse,child,father,mother,sibling,other',
            'dob'                 => 'nullable|date',
            'phone'               => 'nullable|string|max:20',
            'nid_number'          => 'nullable|string|max:30|unique:employee_dependents,nid_number,' . $employeeDependent->id,
            'is_emergency_contact'=> 'boolean',
            'notes'               => 'nullable|string',
        ]);

        $validated['updated_by'] = auth()->id();

        $employeeDependent->update($validated);

        return redirect()->route('employee-dependents.index')
            ->with('success', 'Dependent updated successfully.');
    }

    /**
     * Remove the specified dependent from storage.
     */
    public function destroy(EmployeeDependent $employeeDependent): RedirectResponse
    {
        $employeeDependent->delete();

        return redirect()->route('employee-dependents.index')
            ->with('success', 'Dependent deleted successfully.');
    }
}
