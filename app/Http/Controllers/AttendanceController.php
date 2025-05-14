<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the attendances.
     */
    public function index(): View
    {
        $attendances = Attendance::with('employee')->latest()->paginate(10);
        return view('attendances.index', compact('attendances'));
    }

    /**
     * Show the form for creating a new attendance.
     */
    public function create(): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created attendance in storage.
     */
    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        Attendance::create($request->validated());

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance record created successfully.');
    }

    /**
     * Show the form for editing the specified attendance.
     */
    public function edit(Attendance $attendance): View
    {
        $employees = Employee::pluck('name', 'id');
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified attendance in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $attendance->update($request->validated());

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance record updated successfully.');
    }

    /**
     * Remove the specified attendance from storage.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
                         ->with('success', 'Attendance record deleted successfully.');
    }
}
