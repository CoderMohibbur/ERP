<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->can('activity.update') || $user->can('activity.*'));
    }

    public function rules(): array
    {
        $typeChoices = ['call', 'whatsapp', 'email', 'meeting', 'note'];
        $statusChoices = ['open', 'done'];

        return [
            'subject' => ['sometimes', 'required', 'string', 'max:190'],
            'type' => ['sometimes', 'required', 'string', Rule::in($typeChoices)],
            'body' => ['nullable', 'string'],

            'activity_at' => ['sometimes', 'nullable', 'date'],
            'next_follow_up_at' => ['sometimes', 'nullable', 'date'],

            'status' => ['sometimes', 'required', 'string', Rule::in($statusChoices)],
        ];
    }
}
