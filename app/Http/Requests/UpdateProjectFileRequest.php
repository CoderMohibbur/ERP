<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProjectFileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'title' => ['required', 'string', 'max:255'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xlsx,csv,zip,jpg,png', 'max:20480'],
        ];
    }
}
