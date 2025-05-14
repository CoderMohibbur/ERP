<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // যদি Auth ব্যবহার করেন তাহলে এখানে শর্ত দিতে পারেন
    }

    
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:departments,name'],
        ];
    }
}
