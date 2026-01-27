<?php

namespace App\Http\Requests;

use App\Models\Deal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DealStageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        // P0: stage change permission strict + compatible with existing seed permissions
        return $user->can('deal.stage.update')
            || $user->can('deal.updateStage')
            || $user->can('deal.stage')
            || $user->can('deal.*')
            || $user->can('deal.update')
            || $user->can('deal.edit');
    }

    public function rules(): array
    {
        return [
            'stage' => ['required', 'string', Rule::in(Deal::STAGES)],
            'lost_reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
