<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadConvertRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('lead.convert') ?? false;
    }

    public function rules(): array
    {
        return [
            'mode' => ['required', Rule::in(['create', 'link'])],

            // Link mode
            'existing_client_id' => [
                Rule::requiredIf(fn () => $this->input('mode') === 'link'),
                'nullable',
                'integer',
                'exists:clients,id',
            ],

            // Create mode (client fields)
            'name' => [
                Rule::requiredIf(fn () => $this->input('mode') === 'create'),
                'nullable',
                'string',
                'max:255',
            ],
            'email' => ['nullable', 'email', 'max:255', 'unique:clients,email'],
            'phone' => ['nullable', 'string', 'max:50'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'country' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
