<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string'],
            'assigned_to' => ['nullable', 'exists:employees,id'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
        ];
    }
}
