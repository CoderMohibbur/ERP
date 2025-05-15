<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id'   => ['required', 'exists:projects,id'],
            'title'        => ['required', 'string', 'max:150'],
            'priority'     => ['required', 'in:low,normal,high'],
            'assigned_to'  => ['nullable', 'exists:employees,id'],
            'progress'     => ['required', 'integer', 'min:0', 'max:100'],
            'due_date'     => ['nullable', 'date'],
        ];
    }
}
