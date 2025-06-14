<?php

namespace App\Http\Controllers;

use App\Models\AttendanceSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateAttendanceSettingRequest;

class AttendanceSettingController extends Controller
{
    /**
     * Show the edit form for the single attendance setting.
     */
    public function edit(): View
    {
        $attendanceSetting = AttendanceSetting::latest()->firstOrCreate(
            [], // No specific condition
            [
                'office_start'             => '09:00',
                'start_time'               => '08:30',
                'end_time'                 => '18:00',
                'grace_minutes'            => 10,
                'half_day_after'           => 60,
                'working_days'            => 26,
                'weekend_days'            => ['Friday'],
                'timezone'                 => 'Asia/Dhaka',
                'allow_remote_attendance' => false,
                'note'                     => 'Default attendance rule',
                'created_by'              => Auth::id(),
            ]
        );

        return view('attendance-settings.edit', compact('attendanceSetting'));
    }

    /**
     * Update the singleton attendance setting.
     */
    public function update(UpdateAttendanceSettingRequest $request, AttendanceSetting $attendanceSetting): RedirectResponse
    {
        $attendanceSetting->update([
            'office_start'             => $request->office_start,
            'start_time'               => $request->start_time,
            'end_time'                 => $request->end_time,
            'grace_minutes'            => $request->grace_minutes,
            'half_day_after'           => $request->half_day_after,
            'working_days'            => $request->working_days,
            'weekend_days'            => $request->weekend_days,
            'timezone'                 => $request->timezone,
            'allow_remote_attendance' => $request->has('allow_remote_attendance'),
            'note'                     => $request->note,
            'updated_by'              => Auth::id(),
        ]);

        return redirect()->route('attendance-settings.edit')
            ->with('success', '✅ Attendance settings updated successfully.');
    }
}
