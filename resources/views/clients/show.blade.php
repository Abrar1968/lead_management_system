<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Client Details
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    {{ $client->conversion->lead->client_name ?? $client->conversion->lead->phone_number }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('clients.edit', $client) }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 shadow-lg shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('clients.index') }}"
                    class="px-4 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 font-semibold transition-all">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Conversion Info Card -->
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl shadow-xl p-6 text-white">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-emerald-100 text-sm">Deal Value</p>
                        <p class="text-2xl font-bold">৳{{ number_format($client->conversion->deal_value) }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-100 text-sm">Package Plan</p>
                        <p class="text-lg font-semibold">{{ $client->conversion->package_plan ?? 'Standard' }}</p>
                    </div>
                    <div>
                        <p class="text-emerald-100 text-sm">Converted On</p>
                        <p class="text-lg font-semibold">{{ $client->conversion->conversion_date->format('M d, Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-emerald-100 text-sm">Converted By</p>
                        <p class="text-lg font-semibold">{{ $client->conversion->convertedBy->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Lead Info -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Lead Information</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Client Name</p>
                        <p class="text-gray-900 font-medium">{{ $client->conversion->lead->client_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Phone Number</p>
                        <p class="text-gray-900 font-medium">{{ $client->conversion->lead->phone_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Source</p>
                        <p class="text-gray-900 font-medium">{{ $client->conversion->lead->source }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Service</p>
                        <p class="text-gray-900 font-medium">{{ $client->conversion->lead->service?->name ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Client Details -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Client Details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Address</p>
                        <p class="text-gray-900 font-medium">{{ $client->address ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Billing Info</p>
                        <p class="text-gray-900 font-medium">{{ $client->billing_info ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Support Contact Person</p>
                        <p class="text-gray-900 font-medium">{{ $client->support_contact_person ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">WhatsApp Group</p>
                        @if ($client->whatsapp_group_created)
                            <span class="inline-flex items-center gap-1 text-emerald-600 font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Created
                            </span>
                        @else
                            <span class="text-gray-400">Not created</span>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-500">Feedback</p>
                        <p class="text-gray-900 font-medium">{{ $client->feedback ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Remarketing Eligible</p>
                        @if ($client->remarketing_eligible)
                            <span class="inline-flex items-center gap-1 text-blue-600 font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                Yes
                            </span>
                        @else
                            <span class="text-gray-400">No</span>
                        @endif
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
                            <p class="text-xs text-gray-500 mt-1">Supplementary data and custom attachments</p>
                        </div>
                        <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach ($dynamicFields as $field)
                                @php
                                    $fieldValue = $client->fieldValues->firstWhere('field_definition_id', $field->id);
                                    $value = $fieldValue?->value;
                                @endphp
                                <div class="{{ $field->type === 'image' ? 'md:col-span-2' : '' }} group">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        @if ($field->type === 'image')
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @elseif($field->type === 'document')
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                        @elseif($field->type === 'link')
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.826L11.414 7.914a4 4 0 115.656 5.656L15.97 14.7" />
                                            </svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5" />
                                            </svg>
                                        @endif
                                        <p class="text-sm font-semibold text-gray-500">{{ $field->label }}</p>
                                    </div>

                                    @if ($field->type === 'image' && $value)
                                        <div class="inline-block relative group/zoom">
                                            <img src="{{ asset('storage/' . $value) }}" alt="{{ $field->label }}"
                                                class="max-w-md w-full rounded-2xl shadow-lg border border-gray-100 transition-transform group-hover/zoom:scale-[1.01]">
                                            <a href="{{ asset('storage/' . $value) }}" target="_blank"
                                                class="absolute bottom-4 right-4 bg-white/90 backdrop-blur px-3 py-1.5 rounded-lg text-xs font-bold text-gray-900 shadow-xl opacity-0 group-hover/zoom:opacity-100 transition-opacity">
                                                View Full Size
                                            </a>
                                        </div>
                                    @elseif($field->type === 'link' && $value)
                                        <a href="{{ $value }}" target="_blank"
                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-bold bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 transition-all">
                                            <span>{{ $value }}</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @elseif($field->type === 'document' && $value)
                                        <a href="{{ route('clients.preview-document', ['client' => $client, 'fieldId' => $field->id]) }}" target="_blank"
                                            class="flex items-center justify-between gap-4 p-4 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl border border-blue-100 group/item hover:shadow-md transition-all max-w-sm">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="h-10 w-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-600">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-bold text-gray-900 leading-tight">View
                                                        Attachment</p>
                                                    <p class="text-xs text-blue-600/70 font-medium italic">Click to
                                                        open in new tab</p>
                                                </div>
                                            </div>
                                            <svg class="w-5 h-5 text-blue-400 group-hover/item:translate-x-1 transition-transform"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    @else
                                        <p
                                            class="text-gray-900 font-bold bg-gray-50 px-3 py-2 rounded-xl border border-gray-100 inline-block min-w-[120px]">
                                            {{ $value ?? 'Not provided' }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
