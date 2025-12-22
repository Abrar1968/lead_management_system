<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Client
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $client->conversion->lead->client_name ?? $client->conversion->lead->phone_number }}</p>
            </div>
            <a href="{{ route('clients.show', $client) }}"
                class="px-4 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-semibold transition-all">
                Cancel
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('clients.update', $client) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
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
                            <label for="billing_info" class="block text-sm font-medium text-gray-700 mb-2">Billing
                                Information</label>
                            <textarea name="billing_info" id="billing_info" rows="3"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('billing_info', $client->billing_info) }}</textarea>
                            @error('billing_info')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Support Contact Person -->
                        <div>
                            <label for="support_contact_person"
                                class="block text-sm font-medium text-gray-700 mb-2">Support Contact Person</label>
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
                                <input type="checkbox" name="whatsapp_group_created" id="whatsapp_group_created"
                                    value="1"
                                    {{ old('whatsapp_group_created', $client->whatsapp_group_created) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <label for="whatsapp_group_created" class="ml-2 text-sm font-medium text-gray-700">
                                    WhatsApp Group Created
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" name="remarketing_eligible" id="remarketing_eligible"
                                    value="1"
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
                @if ($dynamicFields->isNotEmpty())
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                        <div class="px-6 py-4 bg-gray-50 border-b">
                            <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
                            <p class="text-sm text-gray-500">Custom fields for client</p>
                        </div>
                        <div class="p-6 space-y-6">
                            @foreach ($dynamicFields as $field)
                                @php
                                    $fieldKey = 'dynamic_' . $field->name;
                                    $fieldValue = $client->fieldValues->firstWhere('field_definition_id', $field->id);
                                    $currentValue = old($fieldKey, $fieldValue?->value);
                                @endphp
                                <div>
                                    <label for="{{ $fieldKey }}"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        {{ $field->label }}
                                        @if ($field->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if ($field->type === 'image')
                                        @if ($currentValue)
                                            <div class="mb-3 flex items-center gap-4">
                                                <img src="{{ asset('storage/' . $currentValue) }}"
                                                    alt="{{ $field->label }}"
                                                    class="h-20 w-20 object-cover rounded-lg shadow">
                                                <form action="{{ route('clients.remove-image', $client) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                        onclick="return confirm('Remove this image?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        <input type="file" name="{{ $fieldKey }}" id="{{ $fieldKey }}"
                                            accept="image/*"
                                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <p class="mt-1 text-xs text-gray-500">Max 2MB. Supported formats: JPG, PNG, GIF,
                                            WebP</p>
                                    @elseif($field->type === 'document')
                                        @if ($currentValue)
                                            <div class="mb-3 flex items-center gap-4">
                                                <a href="{{ asset('storage/' . $currentValue) }}" target="_blank"
                                                    class="flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium p-3 bg-blue-50 rounded-xl border border-blue-100">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                    View Current Document
                                                </a>
                                                <form action="{{ route('clients.remove-image', $client) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="field_id"
                                                        value="{{ $field->id }}">
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-800 text-sm font-medium"
                                                        onclick="return confirm('Remove this document?')">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        <input type="file" name="{{ $fieldKey }}" id="{{ $fieldKey }}"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.txt"
                                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <p class="mt-1 text-xs text-gray-500">Max 5MB. Supported formats: PDF, DOC,
                                            DOCX, XLS, XLSX, TXT</p>
                                    @elseif($field->type === 'link')
                                        <input type="url" name="{{ $fieldKey }}" id="{{ $fieldKey }}"
                                            value="{{ $currentValue }}" placeholder="https://example.com"
                                            {{ $field->required ? 'required' : '' }}
                                            class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @else
                                        <input type="text" name="{{ $fieldKey }}" id="{{ $fieldKey }}"
                                            value="{{ $currentValue }}" {{ $field->required ? 'required' : '' }}
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

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add file upload feedback for all file inputs
                const imageInputs = document.querySelectorAll('input[type="file"][accept*="image"]');
                const documentInputs = document.querySelectorAll('input[type="file"][accept*=".pdf"]');

                // Handle image inputs
                imageInputs.forEach(input => {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            // Create feedback element if it doesn't exist
                            let feedback = this.nextElementSibling;
                            while (feedback && !feedback.classList.contains('file-feedback')) {
                                feedback = feedback.nextElementSibling;
                            }

                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className =
                                    'file-feedback mt-2 p-3 bg-green-50 border border-green-200 rounded-lg';
                                this.parentElement.insertBefore(feedback, this.nextElementSibling);
                            }

                            // Show image preview
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                feedback.innerHTML = `
                                <div class="flex items-center gap-3">
                                    <img src="${e.target.result}" class="h-16 w-16 object-cover rounded-lg shadow" alt="Preview">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-green-700"><svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Ready to upload</p>
                                        <p class="text-xs text-gray-600">${file.name} (${(file.size / 1024).toFixed(1)} KB)</p>
                                    </div>
                                </div>
                            `;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                });

                // Handle document inputs
                documentInputs.forEach(input => {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            // Create feedback element if it doesn't exist
                            let feedback = this.nextElementSibling;
                            while (feedback && !feedback.classList.contains('file-feedback')) {
                                feedback = feedback.nextElementSibling;
                            }

                            if (!feedback) {
                                feedback = document.createElement('div');
                                feedback.className =
                                    'file-feedback mt-2 p-3 bg-green-50 border border-green-200 rounded-lg';
                                this.parentElement.insertBefore(feedback, this.nextElementSibling);
                            }

                            // Show document info
                            const fileExt = file.name.split('.').pop().toUpperCase();
                            feedback.innerHTML = `
                            <div class="flex items-center gap-3">
                                <div class="h-16 w-16 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-green-700"><svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg> Ready to upload</p>
                                    <p class="text-xs text-gray-600">${file.name} (${(file.size / 1024).toFixed(1)} KB) • ${fileExt}</p>
                                </div>
                            </div>
                        `;
                        }
                    });
                });


                // Add submit animation
                const form = document.querySelector('form[action*="/clients/"]');
                if (form) {
                    const saveButton = Array.from(form.querySelectorAll('button[type="submit"]')).find(btn =>
                        btn.textContent.includes('Save Changes')
                    );

                    if (saveButton) {
                        form.addEventListener('submit', function(e) {
                            // Show loading state
                            saveButton.disabled = true;
                            saveButton.innerHTML =
                                '\u003csvg class="animate-spin h-5 w-5 inline mr-2" viewBox="0 0 24 24">\u003ccircle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"/>\u003cpath class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>\u003c/svg> Saving...';
                        });
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
