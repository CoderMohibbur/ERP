<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'working_days' => ['required', 'integer', 'min:1', 'max:31'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'grace_period' => ['nullable', 'integer', 'min:0', 'max:60'],
            'note' => ['nullable', 'string', 'max:255'],
        ];
    }
}
