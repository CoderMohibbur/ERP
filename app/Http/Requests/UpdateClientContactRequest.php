<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (auth()->user()->can('client_contact.edit') || auth()->user()->can('client_contact.*'));
    }

    public function rules(): array
    {
        return [
            // Backward-compat: if controller still sends client_id, validate it; if route-model-binding used, it can be omitted
            'client_id' => ['nullable', 'integer', Rule::exists('clients', 'id')],

            // Keep enum-like allowed types + add string/max validation
            'type' => [
                'required',
                'string',
                'max:50',
                Rule::in(['name', 'email', 'phone', 'designation']),
            ],

            'value' => ['required', 'string', 'max:255'],
        ];
    }
}
