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
                    <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                        <div
                            class="px-6 py-4 bg-gradient-to-r from-gray-50 to-white border-b flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 leading-none">Additional Information</h3>
                                <p class="text-xs text-gray-500 mt-1">Custom fields for detailed client profiling</p>
                            </div>
                            <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach ($dynamicFields as $field)
                                    @php
                                        $fieldKey = 'dynamic_' . $field->name;
                                        $fieldValue = $client->fieldValues->firstWhere(
                                            'field_definition_id',
                                            $field->id,
                                        );
                                        $currentValue = old($fieldKey, $fieldValue?->value);
                                    @endphp
                                    <div
                                        class="{{ in_array($field->type, ['image', 'document']) ? 'md:col-span-2' : '' }} group">
                                        <label for="{{ $fieldKey }}"
                                            class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center gap-1.5 transition-colors group-focus-within:text-blue-600">
                                            @if ($field->type === 'image')
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @elseif($field->type === 'document')
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @elseif($field->type === 'link')
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826L11.414 7.914a4 4 0 115.656 5.656L15.97 14.7" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 text-gray-400" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                                </svg>
                                            @endif
                                            {{ $field->label }}
                                            @if ($field->required)
                                                <span class="text-red-500 font-bold">*</span>
                                            @endif
                                        </label>

                                        @if ($field->type === 'image')
                                            <div class="space-y-3">
                                                @if ($currentValue)
                                                    <div class="relative group/img inline-block">
                                                        <img src="{{ asset('storage/' . $currentValue) }}"
                                                            alt="{{ $field->label }}"
                                                            class="h-24 w-24 object-cover rounded-2xl shadow-md border-2 border-white ring-1 ring-gray-200">
                                                        <button type="button"
                                                            onclick="document.getElementById('remove-form-{{ $field->id }}').submit()"
                                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600 transition-all opacity-0 group-hover/img:opacity-100"
                                                            title="Remove image">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                                <div class="relative">
                                                    <input type="file" name="{{ $fieldKey }}"
                                                        id="{{ $fieldKey }}" accept="image/*"
                                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all border border-gray-200 rounded-xl">
                                                </div>
                                                <p class="text-[11px] text-gray-400 px-1 italic">Max 2MB. Optimized for
                                                    screen viewing.</p>
                                            </div>
                                        @elseif($field->type === 'document')
                                            <div class="space-y-3">
                                                @if ($currentValue)
                                                    <div class="flex items-center gap-3">
                                                        <a href="{{ route('clients.preview-document', ['client' => $client, 'fieldId' => $field->id]) }}"
                                                            target="_blank"
                                                            class="flex-1 flex items-center justify-between gap-4 p-3.5 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl border border-blue-100 group/doc hover:shadow-md transition-all">
                                                            <div class="flex items-center gap-3">
                                                                <div
                                                                    class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600">
                                                                    <svg class="w-6 h-6" fill="none"
                                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round"
                                                                            stroke-linejoin="round" stroke-width="2"
                                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                                    </svg>
                                                                </div>
                                                                <div>
                                                                    <p
                                                                        class="text-sm font-bold text-gray-900 leading-tight">
                                                                        Current Document</p>
                                                                    <p class="text-xs text-blue-600/70 font-medium">
                                                                        Click to preview</p>
                                                                </div>
                                                            </div>
                                                            <svg class="w-5 h-5 text-blue-400 group-hover/doc:translate-x-1 transition-transform"
                                                                fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                            </svg>
                                                        </a>
                                                        <button type="button"
                                                            onclick="if(confirm('Remove this document?')) document.getElementById('remove-form-{{ $field->id }}').submit()"
                                                            class="flex-shrink-0 h-12 w-12 bg-white rounded-2xl border border-red-100 flex items-center justify-center text-red-500 hover:bg-red-50 hover:border-red-200 transition-all shadow-sm"
                                                            title="Remove document">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                                <input type="file" name="{{ $fieldKey }}"
                                                    id="{{ $fieldKey }}" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt"
                                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition-all border border-gray-200 rounded-xl">
                                                <p class="text-[11px] text-gray-400 px-1 italic">Max 5MB. PDF, Word,
                                                    Excel, or Text files.</p>
                                            </div>
                                        @elseif($field->type === 'link')
                                            <input type="url" name="{{ $fieldKey }}"
                                                id="{{ $fieldKey }}" value="{{ $currentValue }}"
                                                placeholder="https://example.com"
                                                {{ $field->required ? 'required' : '' }}
                                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 placeholder:text-gray-300">
                                        @else
                                            <input type="text" name="{{ $fieldKey }}"
                                                id="{{ $fieldKey }}" value="{{ $currentValue }}"
                                                {{ $field->required ? 'required' : '' }}
                                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        @endif

                                        @error($fieldKey)
                                            <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4 pt-4">
                    <a href="{{ route('clients.show', $client) }}"
                        class="px-6 py-2.5 text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 font-bold transition-all shadow-sm">
                        Keep Changes
                    </a>
                    <button type="submit"
                        class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-xl hover:from-blue-700 hover:to-indigo-800 font-bold shadow-lg shadow-blue-500/25 transition-all">
                        Save Updates
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Hidden Forms for Dynamic Field Removal (To fix nested form issue) -->
    @foreach ($dynamicFields as $field)
        @if ($client->fieldValues->firstWhere('field_definition_id', $field->id)?->value)
            <form id="remove-form-{{ $field->id }}" action="{{ route('clients.remove-image', $client) }}"
                method="POST" class="hidden">
                @csrf
                <input type="hidden" name="field_id" value="{{ $field->id }}">
            </form>
        @endif
    @endforeach

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
