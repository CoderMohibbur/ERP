<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'     => ['required', 'exists:clients,id'],
            'project_id'    => ['nullable', 'exists:projects,id'],
            'total_amount'  => ['required', 'numeric', 'min:0'],
            'due_amount'    => ['required', 'numeric', 'min:0'],
            'status'        => ['required', 'in:unpaid,paid'],
        ];
    }
}
