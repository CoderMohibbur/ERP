<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;

        // Spatie permission: task.update বা task.* থাকলে allow
        return $user->can('task.update') || $user->can('task.*');
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                'string',
                Rule::in(['backlog', 'doing', 'review', 'done', 'blocked']),
            ],
            'blocked_reason' => ['nullable', 'string', 'max:500'],
        ];
    }
}
