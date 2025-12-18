<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Client
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $client->conversion->lead->client_name ?? $client->conversion->lead->phone_number }}</p>
            </div>
            <a href="{{ route('clients.show', $client) }}"
               class="px-4 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-semibold transition-all">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('clients.update', $client) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Client Details -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Client Details</h3>
                    </div>
                    <div class="p-6 space-y-6">
                        <!-- Address -->
                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                            <textarea name="address" id="address" rows="2"
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('address', $client->address) }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Billing Info -->
                        <div>
                            <label for="billing_info" class="block text-sm font-medium text-gray-700 mb-2">Billing Information</label>
                            <textarea name="billing_info" id="billing_info" rows="3"
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('billing_info', $client->billing_info) }}</textarea>
                            @error('billing_info')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Support Contact Person -->
                        <div>
                            <label for="support_contact_person" class="block text-sm font-medium text-gray-700 mb-2">Support Contact Person</label>
                            <input type="text" name="support_contact_person" id="support_contact_person"
                                   value="{{ old('support_contact_person', $client->support_contact_person) }}"
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('support_contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Feedback -->
                        <div>
                            <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">Feedback</label>
                            <textarea name="feedback" id="feedback" rows="3"
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('feedback', $client->feedback) }}</textarea>
                            @error('feedback')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Checkboxes -->
                        <div class="flex flex-wrap gap-6">
                            <div class="flex items-center">
                                <input type="checkbox" name="whatsapp_group_created" id="whatsapp_group_created" value="1"
                                       {{ old('whatsapp_group_created', $client->whatsapp_group_created) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="whatsapp_group_created" class="ml-2 text-sm font-medium text-gray-700">
                                    WhatsApp Group Created
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="remarketing_eligible" id="remarketing_eligible" value="1"
                                       {{ old('remarketing_eligible', $client->remarketing_eligible) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="remarketing_eligible" class="ml-2 text-sm font-medium text-gray-700">
                                    Remarketing Eligible
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields -->
                @if($dynamicFields->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
                        <p class="text-sm text-gray-500">Custom fields for client</p>
                    </div>
                    <div class="p-6 space-y-6">
                        @foreach($dynamicFields as $field)
                            @php
                                $fieldKey = 'dynamic_' . $field->name;
                                $fieldValue = $client->fieldValues->firstWhere('field_definition_id', $field->id);
                                $currentValue = old($fieldKey, $fieldValue?->value);
                            @endphp
                            <div>
                                <label for="{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $field->label }}
                                    @if($field->required)
                                        <span class="text-red-500">*</span>
                                    @endif
                                </label>

                                @if($field->type === 'image')
                                    @if($currentValue)
                                        <div class="mb-3 flex items-center gap-4">
                                            <img src="{{ asset('storage/' . $currentValue) }}" alt="{{ $field->label }}" class="h-20 w-20 object-cover rounded-lg shadow">
                                            <form action="{{ route('clients.remove-image', $client) }}" method="POST" class="inline">
                                                @csrf
                                                <input type="hidden" name="field_id" value="{{ $field->id }}">
                                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                        onclick="return confirm('Remove this image?')">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                    <input type="file" name="{{ $fieldKey }}" id="{{ $fieldKey }}" accept="image/*"
                                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1 text-xs text-gray-500">Max 2MB. Supported formats: JPG, PNG, GIF, WebP</p>

                                @elseif($field->type === 'link')
                                    <input type="url" name="{{ $fieldKey }}" id="{{ $fieldKey }}"
                                           value="{{ $currentValue }}"
                                           placeholder="https://example.com"
                                           {{ $field->required ? 'required' : '' }}
                                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                                @else
                                    <input type="text" name="{{ $fieldKey }}" id="{{ $fieldKey }}"
                                           value="{{ $currentValue }}"
                                           {{ $field->required ? 'required' : '' }}
                                           class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @endif

                                @error($fieldKey)
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('clients.show', $client) }}"
                       class="px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg transition-all">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
