<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientContactDestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (auth()->user()->can('client_contact.delete') || auth()->user()->can('client_contact.*'));
    }

    public function rules(): array
    {
        return [];
    }
}
