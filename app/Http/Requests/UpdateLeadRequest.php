<?php

namespace App\Http\Requests;

use App\Models\Lead;
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
            'service_interested' => ['nullable', 'string'],
            'service_id' => ['sometimes', 'exists:services,id'],
            'status' => ['sometimes', Rule::in(Lead::getStatusValues())],
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
            'lead_date.date' => 'Please provide a valid date.',
            'lead_date.before_or_equal' => 'Lead date cannot be in the future.',
            'client_name.max' => 'Client name cannot exceed 255 characters.',
            'phone_number.max' => 'Phone number cannot exceed 20 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'source.in' => 'Invalid lead source selected. Please choose WhatsApp, Messenger, or Website.',
            'service_id.exists' => 'The selected service does not exist.',
            'status.in' => 'Invalid status selected.',
            'priority.in' => 'Invalid priority level. Please choose High, Medium, or Low.',
            'assigned_to.exists' => 'The selected user does not exist.',
            'initial_remarks.max' => 'Initial remarks cannot exceed 1000 characters.',
        ];
    }
}
