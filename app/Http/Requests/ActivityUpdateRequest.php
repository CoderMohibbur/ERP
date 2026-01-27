<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;

        /** @var Activity|null $activity */
        $activity = $this->route('activity');

        // Full access
        if ($user->can('activity.*') || $user->can('activity.updateAny') || $user->can('activity.admin')) {
            return true;
        }

        // Basic update permission
        if (!($user->can('activity.update') || $user->can('activity.edit'))) {
            return false;
        }

        // If not full-access, can only update own activity
        if ($activity instanceof Activity) {
            return (int) $activity->actor_id === (int) $user->id;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'subject' => ['sometimes', 'required', 'string', 'max:190'],
            'type' => ['sometimes', 'required', 'string', Rule::in(Activity::TYPES)],
            'body' => ['nullable', 'string'],

            'activity_at' => ['sometimes', 'nullable', 'date'],
            'next_follow_up_at' => ['sometimes', 'nullable', 'date'],

            'status' => ['sometimes', 'required', 'string', Rule::in(Activity::STATUSES)],
        ];
    }
}
