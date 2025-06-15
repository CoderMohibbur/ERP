<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeResignation;
use App\Http\Requests\StoreEmployeeResignationRequest;
use App\Http\Requests\UpdateEmployeeResignationRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EmployeeResignationController extends Controller
{
    /**
     * Display a listing of the resignations.
     */
    public function index(Request $request): View
    {
        $resignations = EmployeeResignation::with(['employee', 'approvedBy'])
            ->latest('resignation_date')
            ->paginate(10);

        return view('employee-resignations.index', compact('resignations'));
    }

    /**
     * Show the form for creating a new resignation.
     */
    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('employee-resignations.create', compact('employees'));
    }

    /**
     * Store a newly created resignation.
     */
    public function store(StoreEmployeeResignationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        EmployeeResignation::create($data);

        return redirect()->route('employee-resignations.index')
                         ->with('success', 'Resignation submitted successfully.');
    }

    /**
     * Show the form for editing the specified resignation.
     */
    public function edit(EmployeeResignation $employeeResignation): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('employee-resignations.edit', compact('employeeResignation', 'employees'));
    }

    /**
     * Update the specified resignation.
     */
    public function update(UpdateEmployeeResignationRequest $request, EmployeeResignation $employeeResignation): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        $employeeResignation->update($data);

        return redirect()->route('employee-resignations.index')
                         ->with('success', 'Resignation updated successfully.');
    }

    /**
     * Remove the specified resignation.
     */
    public function destroy(EmployeeResignation $employeeResignation): RedirectResponse
    {
        $employeeResignation->delete();

        return redirect()->route('employee-resignations.index')
                         ->with('success', 'Resignation deleted successfully.');
    }
}
