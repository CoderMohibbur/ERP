<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (auth()->user()->can('client_contact.create') || auth()->user()->can('client_contact.*'));
    }

    public function rules(): array
    {
        return [
            // Keep backward-compat (if controller still sends client_id)
            'client_id' => ['nullable', 'integer', Rule::exists('clients', 'id')],

            // Keep allowed types from current code (enum-like)
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
