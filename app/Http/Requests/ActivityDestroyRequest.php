<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;

class ActivityDestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) return false;

        /** @var Activity|null $activity */
        $activity = $this->route('activity');

        if ($user->can('activity.*') || $user->can('activity.deleteAny') || $user->can('activity.admin')) {
            return true;
        }

        if (!($user->can('activity.delete') || $user->can('activity.destroy'))) {
            return false;
        }

        // Only own activity if not full-access
        if ($activity instanceof Activity) {
            return (int) $activity->actor_id === (int) $user->id;
        }

        return false;
    }

    public function rules(): array
    {
        return [];
    }
}
    