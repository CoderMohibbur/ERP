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
            'office_start'     => ['required', 'date_format:H:i'],
            'start_time'       => ['required', 'date_format:H:i'],
            'end_time'         => ['required', 'date_format:H:i', 'after:start_time'],
            'grace_minutes'    => ['required', 'integer', 'min:0', 'max:60'],
            'half_day_after'   => ['nullable', 'integer', 'min:0'],
            'working_days'     => ['required', 'integer', 'min:1', 'max:31'],
            'weekend_days'     => ['nullable', 'array'],
            'weekend_days.*'   => ['string'],
            'note'             => ['nullable', 'string', 'max:255'],
        ];
    }
}
