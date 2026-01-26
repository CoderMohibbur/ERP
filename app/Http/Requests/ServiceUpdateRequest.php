<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('service.edit');
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'integer', 'exists:clients,id'],

            'type' => [
                'required',
                'string',
                Rule::in(['shared_hosting', 'dedicated', 'domain', 'ssl', 'maintenance']),
            ],

            'billing_cycle' => [
                'required',
                'string',
                Rule::in(['monthly', 'quarterly', 'half_yearly', 'yearly', 'custom']),
            ],

            'amount' => ['required', 'numeric', 'min:0'],

            'started_at' => ['required', 'date'],
            'expires_at' => ['nullable', 'date'],
            'next_renewal_at' => ['required', 'date'],

            'status' => [
                'required',
                'string',
                Rule::in(['active', 'suspended', 'cancelled', 'expired']),
            ],

            'auto_invoice' => ['nullable', 'boolean'],

            'name' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
