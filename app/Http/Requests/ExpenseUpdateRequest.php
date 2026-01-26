<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('expense.edit');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],

            'category' => [
                'required',
                'string',
                Rule::in(['server', 'tools', 'salary', 'office', 'marketing', 'other']),
            ],

            'amount' => ['required', 'numeric', 'min:0'],
            'expense_date' => ['required', 'date'],

            'vendor' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
