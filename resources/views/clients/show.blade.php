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
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gray-50 border-b">
                        <h3 class="text-lg font-semibold text-gray-900">Additional Information</h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($dynamicFields as $field)
                            @php
                                $fieldValue = $client->fieldValues->firstWhere('field_definition_id', $field->id);
                                $value = $fieldValue?->value;
                            @endphp
                            <div class="{{ $field->type === 'image' ? 'md:col-span-2' : '' }}">
                                <p class="text-sm text-gray-500">{{ $field->label }}</p>
                                @if ($field->type === 'image' && $value)
                                    <img src="{{ asset('storage/' . $value) }}" alt="{{ $field->label }}"
                                        class="mt-2 max-w-xs rounded-lg shadow">
                                @elseif($field->type === 'link' && $value)
                                    <a href="{{ $value }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 underline">{{ $value }}</a>
                                @elseif($field->type === 'document' && $value)
                                    <a href="{{ asset('storage/' . $value) }}" target="_blank"
                                        class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        View Document
                                    </a>
                                @else
                                    <p class="text-gray-900 font-medium">{{ $value ?? '-' }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
