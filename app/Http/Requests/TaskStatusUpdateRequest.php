<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskStatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        // Spatie Permission compatible checks
        if (method_exists($user, 'can')) {
            if ($user->can('task.update') || $user->can('task.edit') || $user->can('task.*')) {
                return true;
            }
            if ($user->can('project.update') || $user->can('project.*')) {
                return true;
            }
        }

        if (method_exists($user, 'hasRole') && $user->hasRole('Owner')) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(['backlog', 'doing', 'review', 'done', 'blocked'])],
            'blocked_reason' => [
                'nullable',
                'string',
                'max:1000',
                Rule::requiredIf(fn () => $this->input('status') === 'blocked'),
            ],
        ];
    }
}
