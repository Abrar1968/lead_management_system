<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
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
            'lead_date' => ['sometimes', 'date', 'before_or_equal:today'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'lead_time' => ['sometimes', 'date_format:H:i'],
            'phone_number' => ['sometimes', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['sometimes', Rule::in(['WhatsApp', 'Messenger', 'Website'])],
            'service_interested' => ['sometimes', Rule::in(['Website', 'Software', 'CRM', 'Marketing'])],
            'status' => ['sometimes', Rule::in(['New', 'Contacted', 'Qualified', 'Negotiation', 'Converted', 'Lost'])],
            'priority' => ['sometimes', Rule::in(['High', 'Medium', 'Low'])],
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
            'source.in' => 'Invalid lead source selected.',
            'service_interested.in' => 'Invalid service selected.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}
