<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Follow-up Rule</h2>
                <p class="mt-1 text-sm text-gray-500">Update conditions for "{{ $followUpRule->name }}"</p>
            </div>
            <a href="{{ route('follow-up-rules.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Rules
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="ml-3">
                            <h3 class="text-sm font-semibold text-red-800">Please fix the following errors:</h3>
                            <ul class="mt-2 list-disc list-inside text-sm text-red-700 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('follow-up-rules.update', $followUpRule) }}" method="POST"
                  x-data="ruleForm({{ json_encode($followUpRule->conditions->map(fn($c) => ['field' => $c->field, 'operator' => $c->operator, 'value' => $c->value])->toArray()) }})"
                  class="space-y-6 bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-6">
                @csrf
                @method('PUT')

                <!-- Rule Details -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Rule Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Rule Name <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name', $followUpRule->name) }}" required
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="e.g., Hot Leads - High Priority">
                            @error('name')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="2"
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                      placeholder="Describe when this rule should match...">{{ old('description', $followUpRule->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-semibold text-gray-700 mb-1">Priority <span class="text-red-500">*</span></label>
                            <input type="number" id="priority" name="priority" value="{{ old('priority', $followUpRule->priority) }}" min="0" max="100" required
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Lower number = higher priority (0 is highest)</p>
                            @error('priority')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="logic_type" class="block text-sm font-semibold text-gray-700 mb-1">Logic Type <span class="text-red-500">*</span></label>
                            <select id="logic_type" name="logic_type" required
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="AND" {{ old('logic_type', $followUpRule->logic_type) === 'AND' ? 'selected' : '' }}>AND - All conditions must match</option>
                                <option value="OR" {{ old('logic_type', $followUpRule->logic_type) === 'OR' ? 'selected' : '' }}>OR - Any condition can match</option>
                            </select>
                            @error('logic_type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-3">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $followUpRule->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            <label for="is_active" class="text-sm font-semibold text-gray-700">Active</label>
                        </div>
                    </div>
                </div>

                <!-- Conditions -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between border-b border-gray-200 pb-2">
                        <h3 class="text-lg font-semibold text-gray-900">Conditions</h3>
                        <button type="button" @click="addCondition()"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Condition
                        </button>
                    </div>

                    <template x-for="(condition, index) in conditions" :key="index">
                        <div class="flex flex-wrap items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex-1 min-w-[180px]">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Field</label>
                                <select :name="'conditions[' + index + '][field]'" x-model="condition.field"
                                        @change="updateOperators(index)"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select field...</option>
                                    <optgroup label="Lead Fields">
                                        <option value="lead.status">Status</option>
                                        <option value="lead.priority">Priority</option>
                                        <option value="lead.source">Source</option>
                                        <option value="lead.is_repeat_lead">Is Repeat Lead</option>
                                        <option value="lead.days_since_lead">Days Since Lead Created</option>
                                    </optgroup>
                                    <optgroup label="Contact Fields">
                                        <option value="contact.response_status">Last Response Status</option>
                                        <option value="contact.total_calls">Total Calls Made</option>
                                        <option value="contact.days_since_last_call">Days Since Last Call</option>
                                    </optgroup>
                                    <optgroup label="Follow-up Fields">
                                        <option value="followup.interest">Last Interest Level</option>
                                        <option value="followup.pending_count">Pending Follow-ups Count</option>
                                        <option value="followup.days_since_last">Days Since Last Follow-up</option>
                                    </optgroup>
                                    <optgroup label="Meeting Fields">
                                        <option value="meeting.has_any">Has Any Meeting</option>
                                        <option value="meeting.last_outcome">Last Meeting Outcome</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div class="w-40">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Operator</label>
                                <select :name="'conditions[' + index + '][operator]'" x-model="condition.operator"
                                        class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Select...</option>
                                    <option value="equals">Equals</option>
                                    <option value="not_equals">Not Equals</option>
                                    <option value="greater_than">Greater Than</option>
                                    <option value="less_than">Less Than</option>
                                    <option value="greater_or_equal">Greater or Equal</option>
                                    <option value="less_or_equal">Less or Equal</option>
                                    <option value="in">Is One Of</option>
                                    <option value="not_in">Is Not One Of</option>
                                    <option value="is_null">Is Empty</option>
                                    <option value="is_not_null">Is Not Empty</option>
                                </select>
                            </div>

                            <div class="flex-1 min-w-[180px]" x-show="!['is_null', 'is_not_null'].includes(condition.operator)">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Value</label>
                                <!-- Dynamic value input based on field type -->
                                <template x-if="['lead.status'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="New">New</option>
                                        <option value="Contacted">Contacted</option>
                                        <option value="Qualified">Qualified</option>
                                        <option value="Negotiation">Negotiation</option>
                                        <option value="Converted">Converted</option>
                                        <option value="Lost">Lost</option>
                                    </select>
                                </template>
                                <template x-if="['lead.priority'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="High">High</option>
                                        <option value="Medium">Medium</option>
                                        <option value="Low">Low</option>
                                    </select>
                                </template>
                                <template x-if="['lead.source'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="WhatsApp">WhatsApp</option>
                                        <option value="Messenger">Messenger</option>
                                        <option value="Website">Website</option>
                                    </select>
                                </template>
                                <template x-if="['lead.is_repeat_lead', 'meeting.has_any'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </template>
                                <template x-if="['contact.response_status'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="No Response">No Response</option>
                                        <option value="50%">50% Interest</option>
                                        <option value="Call Later">Call Later</option>
                                        <option value="Phone off">Phone Off</option>
                                        <option value="Interested">Interested</option>
                                    </select>
                                </template>
                                <template x-if="['followup.interest'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                        <option value="No Response">No Response</option>
                                        <option value="Call Later">Call Later</option>
                                        <option value="50%">50%</option>
                                    </select>
                                </template>
                                <template x-if="['meeting.last_outcome'].includes(condition.field)">
                                    <select :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                            class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select...</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                        <option value="Rescheduled">Rescheduled</option>
                                    </select>
                                </template>
                                <template x-if="['lead.days_since_lead', 'contact.total_calls', 'contact.days_since_last_call', 'followup.pending_count', 'followup.days_since_last'].includes(condition.field)">
                                    <input type="number" :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Enter number..." min="0">
                                </template>
                                <template x-if="!['lead.status', 'lead.priority', 'lead.source', 'lead.is_repeat_lead', 'meeting.has_any', 'contact.response_status', 'followup.interest', 'meeting.last_outcome', 'lead.days_since_lead', 'contact.total_calls', 'contact.days_since_last_call', 'followup.pending_count', 'followup.days_since_last'].includes(condition.field)">
                                    <input type="text" :name="'conditions[' + index + '][value]'" x-model="condition.value"
                                           class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500"
                                           placeholder="Enter value...">
                                </template>
                            </div>

                            <div class="flex items-end pb-1">
                                <button type="button" @click="removeCondition(index)"
                                        x-show="conditions.length > 1"
                                        class="p-2 text-gray-400 hover:text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    @error('conditions')
                        <p class="text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <a href="{{ route('follow-up-rules.preview', $followUpRule) }}"
                       class="inline-flex items-center gap-2 px-4 py-2 text-purple-600 bg-purple-50 rounded-xl hover:bg-purple-100 font-semibold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        Preview Matches
                    </a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg shadow-blue-500/30 transition-all">
                        Update Rule
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function ruleForm(existingConditions = []) {
            return {
                conditions: existingConditions.length > 0 ? existingConditions : [{ field: '', operator: '', value: '' }],
                addCondition() {
                    this.conditions.push({ field: '', operator: '', value: '' });
                },
                removeCondition(index) {
                    if (this.conditions.length > 1) {
                        this.conditions.splice(index, 1);
                    }
                },
                updateOperators(index) {
                    // Reset operator and value when field changes
                    this.conditions[index].operator = '';
                    this.conditions[index].value = '';
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
