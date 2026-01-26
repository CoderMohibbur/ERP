<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DealStageUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('deal.edit');
    }

    public function rules(): array
    {
        return [
            'stage' => ['required', 'string', Rule::in(['new', 'contacted', 'quoted', 'negotiating', 'won', 'lost'])],
            'lost_reason' => ['nullable', 'string', 'max:255'],
        ];
    }
}
