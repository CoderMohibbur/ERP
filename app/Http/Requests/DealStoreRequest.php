<?php

namespace App\Http\Requests;

use App\Models\Deal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class DealStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('deal.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'stage' => ['required', 'string', Rule::in(Deal::STAGES)],

            'lead_id' => ['nullable', 'integer', 'exists:leads,id'],
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],

            'value_estimated' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $hasLead = $this->filled('lead_id');
            $hasClient = $this->filled('client_id');

            if (! $hasLead && ! $hasClient) {
                $message = 'Either lead_id or client_id is required. At least one must be provided.';
                $validator->errors()->add('lead_id', $message);
                $validator->errors()->add('client_id', $message);
            }
        });
    }
}
