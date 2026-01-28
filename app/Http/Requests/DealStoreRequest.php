<?php

namespace App\Http\Requests;

use App\Models\Deal;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DealStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Keep your existing permission logic if already present
        return $this->user()?->can('deal.create') ?? false;
    }

    protected function prepareForValidation(): void
    {
        // Normalize empty strings to null
        $this->merge([
            'lead_id' => $this->input('lead_id') ?: null,
            'client_id' => $this->input('client_id') ?: null,
            'link_type' => $this->input('link_type') ?: null,
        ]);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'stage' => ['required', Rule::in(Deal::STAGES)],
            'link_type' => ['required', Rule::in(['lead', 'client'])],

            'lead_id' => ['nullable', 'integer', 'exists:leads,id'],
            'client_id' => ['nullable', 'integer', 'exists:clients,id'],

            'estimated_value' => ['nullable', 'numeric', 'min:0'],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'expected_close_date' => ['nullable', 'date'],
            'lost_reason' => ['nullable', 'string', 'max:255'],
            'owner_id' => ['prohibited'],

        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($v) {
            $leadId = $this->input('lead_id');
            $clientId = $this->input('client_id');
            $type = $this->input('link_type');

            // Must have exactly one
            if (!$leadId && !$clientId) {
                $v->errors()->add('lead_id', 'Please select a Lead or a Client.');
                $v->errors()->add('client_id', 'Please select a Lead or a Client.');
                return;
            }

            if ($leadId && $clientId) {
                $v->errors()->add('lead_id', 'Select either Lead or Client (not both).');
                $v->errors()->add('client_id', 'Select either Lead or Client (not both).');
                return;
            }

            // Ensure UI type matches provided id (extra safety)
            if ($type === 'lead' && !$leadId) {
                $v->errors()->add('lead_id', 'Lead is required when Attach deal to = Lead.');
            }
            if ($type === 'client' && !$clientId) {
                $v->errors()->add('client_id', 'Client is required when Attach deal to = Client.');
            }
        });
    }
}
