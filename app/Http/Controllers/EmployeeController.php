<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\View\View;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the employees.
     */
    // public function index(): View
    // {
    //     $employees = Employee::with(['department', 'designation'])->latest()->paginate(10);
    //     return view('employees.index', compact('employees'));
    // }


    public function index(Request $request): View
    {
        $query = Employee::with(['department', 'designation']);

        // 🔍 Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // 👤 Filter by employee_id
        if ($request->filled('employee_id')) {
            $query->where('id', $request->employee_id);
        }

        // 🗓️ Filter by join_date month
        if ($request->filled('month')) {
            $query->whereMonth('join_date', $request->month);
        }

        // 📅 Filter by exact join_date
        if ($request->filled('join_date')) {
            $query->whereDate('join_date', $request->join_date);
        }

        $employees = $query->latest()->paginate(10)->withQueryString(); // pagination with filters
        $allEmployees = Employee::select('id', 'name')->orderBy('name')->get(); // for dropdown

        return view('employees.index', compact('employees', 'allEmployees'));
    }




    /**
     * Show the form for creating a new employee.
     */
    public function create(): View
    {
        $departments = Department::pluck('name', 'id');
        $designations = Designation::pluck('name', 'id');
        return view('employees.create', compact('departments', 'designations'));
    }

    /**
     * Store a newly created employee in storage.
     */
    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('uploads/employees', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }



    /**
     * Show the form for editing the specified employee.
     */
    public function edit(Employee $employee): View
    {
        $departments = Department::pluck('name', 'id');
        $designations = Designation::pluck('name', 'id');
        return view('employees.edit', compact('employee', 'departments', 'designations'));
    }

    /**
     * Update the specified employee in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('uploads/employees', 'public');
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }


    /**
     * Remove the specified employee from storage.
     */
    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
