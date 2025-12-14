<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'lead_date' => ['required', 'date', 'before_or_equal:today'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['required', Rule::in(['WhatsApp', 'Messenger', 'Website'])],
            'service_interested' => ['required', Rule::in(['Website', 'Software', 'CRM', 'Marketing'])],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'assigned_to' => ['nullable', 'exists:users,id'],
            'initial_remarks' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'lead_date.before_or_equal' => 'Lead date cannot be in the future.',
            'phone_number.required' => 'Phone number is required.',
            'source.required' => 'Please select a lead source.',
            'source.in' => 'Invalid lead source selected.',
            'service_interested.required' => 'Please select a service.',
            'service_interested.in' => 'Invalid service selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('priority')) {
            $this->merge(['priority' => 'Medium']);
        }
    }
}
