<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'          => ['required', 'exists:employees,id'],
            'date'                 => ['required', 'date'],
            'status'               => ['required', 'in:present,absent,leave,late'],
            'note'                 => ['nullable', 'string', 'max:255'],
            'in_time'              => ['nullable', 'date_format:H:i'],
            'out_time'             => ['nullable', 'date_format:H:i', 'after_or_equal:in_time'],
            'worked_hours'         => ['nullable', 'numeric', 'between:0,24'],
            'late_by_minutes'      => ['nullable', 'integer', 'min:0'],
            'early_leave_minutes'  => ['nullable', 'integer', 'min:0'],
            'location'             => ['nullable', 'string', 'max:255'],
            'device_type'          => ['nullable', 'in:web,mobile,kiosk'],
        ];
    }
}
