<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'project_id' => ['nullable', 'exists:projects,id'],
            'invoice_number' => ['required', 'string', 'max:50', 'unique:invoices,invoice_number'],
            'issue_date' => ['required', 'date'],
            'due_date' => ['nullable', 'date', 'after_or_equal:issue_date'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:unpaid,paid,partial,cancelled'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
