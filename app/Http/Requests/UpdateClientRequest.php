<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && (auth()->user()->can('client.edit') || auth()->user()->can('client.*'));
    }

    protected function prepareForValidation(): void
    {
        $data = $this->all();

        // Backward-compat: old field `company` -> new `company_name`
        if (empty($data['company_name']) && !empty($data['company'])) {
            $data['company_name'] = $data['company'];
        }

        // Default status if not provided
        if (empty($data['status'])) {
            $data['status'] = 'active';
        }

        $this->replace($data);
    }

    public function rules(): array
    {
        $routeClient = $this->route('client');
        $clientId = is_object($routeClient) ? ($routeClient->id ?? null) : $routeClient;

        return [
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('clients', 'email')->ignore($clientId),
            ],

            'phone' => ['nullable', 'string', 'max:20'],

            // Support both (old + new)
            'company' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],

            'address' => ['nullable', 'string', 'max:255'],

            // Extra (optional)
            'industry_type' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
            'tax_id' => ['nullable', 'string', 'max:100'],

            'status' => ['required', Rule::in(['active', 'inactive'])],

            // ✅ Accept textarea string JSON OR array; normalize in validated()
            'custom_fields' => [
                'nullable',
                function (string $attribute, $value, \Closure $fail) {
                    if (is_array($value)) {
                        return;
                    }

                    if ($value === null) {
                        return;
                    }

                    if (!is_string($value)) {
                        $fail('Custom fields must be a valid JSON object or array.');
                        return;
                    }

                    $trim = trim($value);

                    if ($trim === '' || strtolower($trim) === 'null') {
                        return;
                    }

                    $decoded = json_decode($trim, true);

                    if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                        $fail('Custom fields must be valid JSON (object/array). Example: {"LinkedIn":"...","Notes":"..."}');
                    }
                },
            ],
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();

        if (array_key_exists('custom_fields', $data)) {
            $v = $data['custom_fields'];

            if (is_string($v)) {
                $trim = trim($v);

                if ($trim === '' || strtolower($trim) === 'null') {
                    $data['custom_fields'] = null;
                } else {
                    $decoded = json_decode($trim, true);
                    $data['custom_fields'] = is_array($decoded) ? $decoded : null;
                }
            }
        }

        return data_get($data, $key, $default);
    }
}
