<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of attendances with filters.
     */
    public function index(Request $request): View
    {
        $query = Attendance::with(['employee', 'verifiedBy']);

        // 🔍 Apply filters
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('date')) {
            $query->onDate($request->date);
        }

        if ($request->filled('month')) {
            $query->whereMonth('date', $request->month);
        }

        if ($request->filled('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        if ($request->filled('employee_id')) {
            $query->where('employee_id', $request->employee_id);
        }

        $attendances = $query->latest()->paginate(10)->withQueryString();
        $employees = Employee::orderBy('name')->pluck('name', 'id');

        return view('attendances.index', compact('attendances', 'employees'));
    }

    /**
     * Show the form for creating a new attendance.
     */
    public function create(): View
    {
        $employees = Employee::orderBy('name')->pluck('name', 'id');
        return view('attendances.create', compact('employees'));
    }

    /**
     * Store a newly created attendance record.
     */
    public function store(StoreAttendanceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['verified_by'] = Auth::id();

        Attendance::create($data);

        return redirect()->route('attendances.index')
            ->with('success', '✅ Attendance recorded successfully.');
    }

    /**
     * Show the form for editing the specified attendance.
     */
    public function edit(Attendance $attendance): View
    {
        $employees = Employee::orderBy('name')->pluck('name', 'id');
        return view('attendances.edit', compact('attendance', 'employees'));
    }

    /**
     * Update the specified attendance record.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance): RedirectResponse
    {
        $data = $request->validated();
        $data['verified_by'] = Auth::id();

        $attendance->update($data);

        return redirect()->route('attendances.index')
            ->with('success', '✅ Attendance updated successfully.');
    }

    /**
     * Remove the specified attendance record.
     */
    public function destroy(Attendance $attendance): RedirectResponse
    {
        $attendance->delete();

        return redirect()->route('attendances.index')
            ->with('success', '🗑️ Attendance deleted successfully.');
    }
}
