<?php

namespace App\Http\Requests;

use App\Models\Deal;
use App\Models\Lead;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        return $user && ($user->can('activity.create') || $user->can('activity.*'));
    }

    public function rules(): array
    {
        $typeChoices = ['call', 'whatsapp', 'email', 'meeting', 'note'];

        return [
            'subject' => ['required', 'string', 'max:190'],
            'type' => ['required', 'string', Rule::in($typeChoices)],
            'body' => ['nullable', 'string'],

            'activity_at' => ['nullable', 'date'],
            'next_follow_up_at' => ['nullable', 'date'],

            'actionable_type' => [
                'required',
                'string',
                'max:190',
                function ($attribute, $value, $fail) {
                    $class = Relation::getMorphedModel($value) ?? $value;

                    if (!class_exists($class)) {
                        $fail('Invalid actionable type.');
                        return;
                    }

                    if (!in_array($class, [Lead::class, Deal::class], true)) {
                        $fail('Invalid actionable type.');
                    }
                },
            ],
            'actionable_id' => ['required', 'integer', 'min:1'],
        ];
    }
}
