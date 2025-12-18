<?php

namespace App\Http\Requests;

use App\Models\FollowUpRuleCondition;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFollowUpRuleRequest extends FormRequest
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
        $availableFields = array_keys(FollowUpRuleCondition::AVAILABLE_FIELDS);
        $availableOperators = array_keys(FollowUpRuleCondition::OPERATORS);

        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'priority' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['boolean'],
            'logic_type' => ['required', Rule::in(['AND', 'OR'])],
            'conditions' => ['required', 'array', 'min:1'],
            'conditions.*.field' => ['required', 'string', Rule::in($availableFields)],
            'conditions.*.operator' => ['required', 'string', Rule::in($availableOperators)],
            'conditions.*.value' => ['nullable'],
        ];
    }

    /**
     * Get custom messages for validation errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Rule name is required.',
            'conditions.required' => 'At least one condition is required.',
            'conditions.min' => 'At least one condition is required.',
            'conditions.*.field.required' => 'Condition field is required.',
            'conditions.*.field.in' => 'Invalid condition field selected.',
            'conditions.*.operator.required' => 'Condition operator is required.',
            'conditions.*.operator.in' => 'Invalid operator selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (! $this->has('is_active')) {
            $this->merge(['is_active' => true]);
        }

        if (! $this->has('priority')) {
            $this->merge(['priority' => 10]);
        }
    }
}
