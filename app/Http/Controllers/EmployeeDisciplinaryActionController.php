<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeDisciplinaryAction;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreEmployeeDisciplinaryActionRequest;
use App\Http\Requests\UpdateEmployeeDisciplinaryActionRequest;

class EmployeeDisciplinaryActionController extends Controller
{
    /**
     * Display a listing of the disciplinary actions.
     */
    public function index(): View
    {
        $actions = EmployeeDisciplinaryAction::with(['employee', 'approvedBy'])
                    ->latest('incident_date')
                    ->paginate(10);

        return view('employee-disciplinary-actions.index', compact('actions'));
    }

    /**
     * Show the form for creating a new disciplinary action.
     */
    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('employee-disciplinary-actions.create', compact('employees'));
    }

    /**
     * Store a newly created disciplinary action.
     */
    public function store(StoreEmployeeDisciplinaryActionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['created_by'] = auth()->id();

        if ($request->hasFile('attachment_path')) {
            $data['attachment_path'] = $request->file('attachment_path')->store('disciplinary-attachments', 'public');
        }

        EmployeeDisciplinaryAction::create($data);

        return redirect()->route('employee-disciplinary-actions.index')
                         ->with('success', 'Disciplinary action recorded successfully.');
    }

    /**
     * Show the form for editing the specified disciplinary action.
     */
    public function edit(EmployeeDisciplinaryAction $employeeDisciplinaryAction): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('employee-disciplinary-actions.edit', compact('employeeDisciplinaryAction', 'employees'));
    }

    /**
     * Update the specified disciplinary action.
     */
    public function update(UpdateEmployeeDisciplinaryActionRequest $request, EmployeeDisciplinaryAction $employeeDisciplinaryAction): RedirectResponse
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->id();

        if ($request->hasFile('attachment_path')) {
            if ($employeeDisciplinaryAction->attachment_path) {
                Storage::disk('public')->delete($employeeDisciplinaryAction->attachment_path);
            }

            $data['attachment_path'] = $request->file('attachment_path')->store('disciplinary-attachments', 'public');
        }

        $employeeDisciplinaryAction->update($data);

        return redirect()->route('employee-disciplinary-actions.index')
                         ->with('success', 'Disciplinary action updated successfully.');
    }

    /**
     * Remove the specified disciplinary action.
     */
    public function destroy(EmployeeDisciplinaryAction $employeeDisciplinaryAction): RedirectResponse
    {
        if ($employeeDisciplinaryAction->attachment_path) {
            Storage::disk('public')->delete($employeeDisciplinaryAction->attachment_path);
        }

        $employeeDisciplinaryAction->delete();

        return redirect()->route('employee-disciplinary-actions.index')
                         ->with('success', 'Disciplinary action deleted successfully.');
    }
}
