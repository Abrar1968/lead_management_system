<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Clients
                </h2>
                <p class="mt-1 text-sm text-gray-500">Converted leads with client details</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Search -->
            <form method="GET" class="flex gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by name, phone, address..."
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg transition-all">
                    Search
                </button>
                @if (request('search'))
                    <a href="{{ route('clients.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                        Clear
                    </a>
                @endif
            </form>

            <!-- Clients Table -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Client</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Deal Value</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Converted On</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Package</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Support Contact</th>
                                @foreach ($dynamicFields as $field)
                                    <th
                                        class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                        {{ $field->label }}</th>
                                @endforeach
                                <th
                                    class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($clients as $client)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="h-10 w-10 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-500/30">
                                                {{ strtoupper(substr($client->conversion->lead->client_name ?? $client->conversion->lead->phone_number, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $client->conversion->lead->client_name ?? 'N/A' }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $client->conversion->lead->phone_number }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-bold text-emerald-600">
                                            ৳{{ number_format($client->conversion->deal_value) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $client->conversion->conversion_date->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                            {{ $client->conversion->package_plan ?? 'Standard' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600">{{ $client->support_contact_person ?? '-' }}
                                        </div>
                                    </td>
                                    @foreach ($dynamicFields as $field)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $fieldValue = $client->fieldValues->firstWhere(
                                                    'field_definition_id',
                                                    $field->id,
                                                );
                                                $value = $fieldValue?->value;
                                            @endphp

                                            @if ($field->type === 'image' && $value)
                                                <div class="relative group/thumb inline-block">
                                                    <img src="{{ asset('storage/' . $value) }}"
                                                        alt="{{ $field->label }}"
                                                        class="h-10 w-10 rounded-xl object-cover border-2 border-white shadow-sm ring-1 ring-gray-100 group-hover/thumb:scale-110 transition-transform">
                                                </div>
                                            @elseif($field->type === 'document' && $value)
                                                <a href="{{ route('clients.preview-document', ['client' => $client, 'fieldId' => $field->id]) }}" target="_blank"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all text-xs font-bold border border-blue-100">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                    </svg>
                                                    View
                                                </a>
                                            @elseif($field->type === 'link' && $value)
                                                <a href="{{ $value }}" target="_blank"
                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-100 transition-all text-[11px] font-bold border border-indigo-100">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                    Link
                                                </a>
                                            @else
                                                <div class="text-sm text-gray-600 font-medium italic">
                                                    {{ Str::limit($value ?? '-', 15) }}
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('clients.show', $client) }}"
                                                class="text-blue-600 hover:text-blue-900 font-semibold">View</a>
                                            <a href="{{ route('clients.edit', $client) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 6 + $dynamicFields->where('type', '!=', 'image')->count() }}"
                                        class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <p class="text-lg font-medium">No clients found</p>
                                            <p class="text-sm">Convert some leads to see them here!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($clients->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $clients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
