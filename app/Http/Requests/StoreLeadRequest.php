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
            'client_name' => ['nullable', 'string', 'max:255'],
            'lead_time' => ['nullable', 'date_format:H:i'],
            'phone_number' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'source' => ['required', Rule::in(['WhatsApp', 'Messenger', 'Website'])],
            'service_interested' => ['nullable', 'string'],
            'service_id' => ['required', 'exists:services,id'],
            'priority' => ['nullable', Rule::in(['High', 'Medium', 'Low'])],
            'status' => ['nullable', Rule::in(\App\Models\Lead::getStatusValues())],
            'initial_response' => ['nullable', Rule::in(array_keys(\App\Http\Controllers\LeadContactController::RESPONSE_STATUSES))],
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
            'lead_date.required' => 'Lead date is required. Please select a date.',
            'lead_date.date' => 'Please provide a valid date.',
            'lead_date.before_or_equal' => 'Lead date cannot be in the future.',
            'phone_number.required' => 'Phone number is required. Please enter the client\'s phone number.',
            'phone_number.max' => 'Phone number cannot exceed 20 characters.',
            'email.email' => 'Please provide a valid email address.',
            'email.max' => 'Email cannot exceed 255 characters.',
            'source.required' => 'Lead source is required. Please select where this lead came from.',
            'source.in' => 'Invalid lead source selected. Please choose WhatsApp, Messenger, or Website.',
            'service_id.required' => 'Service is required. Please select the service the client is interested in.',
            'service_id.exists' => 'The selected service does not exist.',
            'priority.in' => 'Invalid priority level. Please choose High, Medium, or Low.',
            'status.in' => 'Invalid status selected.',
            'initial_response.in' => 'Invalid response status selected.',
            'assigned_to.exists' => 'The selected user does not exist.',
            'initial_remarks.max' => 'Initial remarks cannot exceed 1000 characters.',
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
