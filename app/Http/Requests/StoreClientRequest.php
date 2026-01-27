<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (auth()->user()->can('client.create') || auth()->user()->can('client.*'));
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Backward-compat: old field `company` -> new `company_name`
        if (empty($data['company_name']) && !empty($data['company'])) {
            $data['company_name'] = $data['company'];
        }

        // Default status if not provided
        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        $this->replace($data);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email'),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            // Support both (old + new)
            'company' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],

            'address' => ['nullable', 'string', 'max:255'],

            // Extra (optional)
            'industry_type' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],

            'status' => ['required', Rule::in(['active', 'inactive'])],
            'custom_fields' => ['nullable', 'array'],
        ];
    }
}
