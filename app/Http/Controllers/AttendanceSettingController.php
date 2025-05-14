<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Http\Requests\StoreAttendanceSettingRequest;
use App\Http\Requests\UpdateAttendanceSettingRequest;

class AttendanceSettingController extends Controller
{
    /**
     * Display a listing of the attendance settings.
     */
    public function index(): View
    {
        $settings = AttendanceSetting::latest()->paginate(10);
        return view('attendance-settings.index', compact('settings'));
    }

    /**
     * Show the form for creating a new attendance setting.
     */
    public function create(): View
    {
        return view('attendance-settings.create');
    }

    /**
     * Store a newly created attendance setting in storage.
     */
    public function store(StoreAttendanceSettingRequest $request): RedirectResponse
    {
        AttendanceSetting::create([
            'office_start'     => $request->office_start,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'grace_minutes'    => $request->grace_minutes,
            'half_day_after'   => $request->half_day_after,
            'working_days'     => $request->working_days,
            'weekend_days'     => $request->weekend_days,
            'note'             => $request->note,
        ]);

        return redirect()->route('attendance-settings.index')
            ->with('success', 'Attendance setting created successfully.');
    }


    /**
     * Show the form for editing the specified attendance setting.
     */
    public function edit(AttendanceSetting $attendanceSetting): View
    {
        return view('attendance-settings.edit', compact('attendanceSetting'));
    }

    /**
     * Update the specified attendance setting in storage.
     */
    public function update(UpdateAttendanceSettingRequest $request, AttendanceSetting $attendanceSetting): RedirectResponse
    {
        $attendanceSetting->update([
            'office_start'     => $request->office_start,
            'start_time'       => $request->start_time,
            'end_time'         => $request->end_time,
            'grace_minutes'    => $request->grace_minutes,
            'half_day_after'   => $request->half_day_after,
            'working_days'     => $request->working_days,
            'weekend_days'     => $request->weekend_days,
            'note'             => $request->note,
        ]);

        return redirect()->route('attendance-settings.index')
            ->with('success', 'Attendance setting updated successfully.');
    }


    /**
     * Remove the specified attendance setting from storage.
     */
    public function destroy(AttendanceSetting $attendanceSetting): RedirectResponse
    {
        $attendanceSetting->delete();

        return redirect()->route('attendance-settings.index')
            ->with('success', 'Attendance setting deleted successfully.');
    }
}
