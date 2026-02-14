<?php

namespace App\Http\Requests;

use App\Models\Activity;
use App\Models\Client;
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

        return $user && (
            $user->can('activity.create')
            || $user->can('activity.store')
            || $user->can('activity.*')
        );
    }

    public function rules(): array
    {
        return [
            // ✅ keep previous rules (TYPES enum validation)
            'subject' => ['required', 'string', 'max:190'],
            'type'    => ['required', 'string', Rule::in(Activity::TYPES)],
            'body'    => ['nullable', 'string'],

            'activity_at'       => ['nullable', 'date'],
            // ✅ new improvement (safe): next followup can't be before activity_at
            'next_follow_up_at' => ['nullable', 'date', 'after_or_equal:activity_at'],

            // ✅ keep polymorphic + morphMap support + add Client
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

                    // ✅ MVP: Lead/Deal/Client
                    if (!in_array($class, [Lead::class, Deal::class, Client::class], true)) {
                        $fail('Invalid actionable type.');
                    }
                },
            ],

            'actionable_id' => ['required', 'integer', 'min:1'],

            /**
             * ✅ optional: only if your Activity model/table actually has status
             * Keeping it nullable so existing feature won't break.
             * (If you confirm status exists, you can make it required + Rule::in([...]) later.)
             */
            'status' => ['nullable', 'string', 'max:30'],
        ];
    }
}
