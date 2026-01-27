<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClientDestroyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (auth()->user()->can('client.delete') || auth()->user()->can('client.*'));
    }

    public function rules(): array
    {
        return [];
    }
}
