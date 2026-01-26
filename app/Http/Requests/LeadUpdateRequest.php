<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeadUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('lead.edit');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:255'],

            'source' => [
                'nullable',
                'string',
                'max:50',
                Rule::in(['whatsapp', 'facebook', 'website', 'referral']),
            ],

            'status' => [
                'required',
                'string',
                Rule::in(['new', 'contacted', 'qualified', 'unqualified']),
            ],

            'owner_id' => ['required', 'integer', 'exists:users,id'],

            'next_follow_up_at' => ['nullable', 'date'],
        ];
    }
}
