<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Demos
                </h2>
                <p class="mt-1 text-sm text-gray-500">Manage product demonstrations and presentations</p>
            </div>
            <a href="{{ route('demos.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg shadow-blue-500/30 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Schedule Demo
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">



            <!-- Filters -->
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by title, client name..."
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="w-40">
                    <input type="date" name="date" value="{{ request('date') }}"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="w-40">
                    <select name="status"
                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="Scheduled" {{ request('status') === 'Scheduled' ? 'selected' : '' }}>Scheduled
                        </option>
                        <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed
                        </option>
                        <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled
                        </option>
                        <option value="Rescheduled" {{ request('status') === 'Rescheduled' ? 'selected' : '' }}>
                            Rescheduled</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg transition-all">
                    Filter
                </button>
                @if (request()->hasAny(['search', 'date', 'status']))
                    <a href="{{ route('demos.index') }}"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                        Clear
                    </a>
                @endif
            </form>

            <!-- Demos Table -->
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Demo</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Lead</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Schedule</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Type</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Status</th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Created By</th>
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
                            @forelse($demos as $demo)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-semibold text-gray-900">{{ $demo->title }}</div>
                                        @if ($demo->description)
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                {{ Str::limit($demo->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($demo->lead)
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ strtoupper(substr($demo->lead->client_name ?? $demo->lead->phone_number, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $demo->lead->client_name ?? 'N/A' }}</div>
                                                    <div class="text-xs text-gray-500">{{ $demo->lead->phone_number }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-400">No lead linked</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $demo->demo_date->format('M d, Y') }}</div>
                                        @if ($demo->demo_time)
                                            <div class="text-xs text-gray-500">{{ $demo->demo_time->format('h:i A') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($demo->type === 'Online')
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-cyan-100 text-cyan-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Online
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Physical
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'Scheduled' => 'bg-blue-100 text-blue-800',
                                                'Completed' => 'bg-emerald-100 text-emerald-800',
                                                'Cancelled' => 'bg-red-100 text-red-800',
                                                'Rescheduled' => 'bg-yellow-100 text-yellow-800',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$demo->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $demo->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $demo->createdBy->name ?? 'N/A' }}</div>
                                    </td>
                                    @foreach ($dynamicFields as $field)
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $fieldValue = $demo->fieldValues->firstWhere(
                                                    'field_definition_id',
                                                    $field->id,
                                                );
                                                $value = $fieldValue?->value;
                                            @endphp

                                            @if ($field->type === 'image' && $value)
                                                <img src="{{ asset('storage/' . $value) }}" alt="{{ $field->label }}"
                                                    class="h-10 w-10 rounded-lg object-cover border border-gray-200">
                                            @elseif($field->type === 'document' && $value)
                                                <a href="{{ route('demos.preview-document', ['demo' => $demo, 'fieldId' => $field->id]) }}" target="_blank"
                                                    class="text-blue-600 hover:text-blue-800 text-xs font-medium inline-flex items-center gap-1">
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
                                                    class="text-blue-600 hover:text-blue-800 text-xs underline">Link</a>
                                            @else
                                                <div class="text-sm text-gray-900">{{ Str::limit($value ?? '-', 20) }}
                                                </div>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('demos.show', $demo) }}"
                                                class="text-blue-600 hover:text-blue-900 font-semibold">View</a>
                                            <a href="{{ route('demos.edit', $demo) }}"
                                                class="text-indigo-600 hover:text-indigo-900 font-semibold">Edit</a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ 7 + $dynamicFields->where('type', '!=', 'image')->count() }}"
                                        class="px-6 py-12 text-center text-gray-500">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <p class="text-lg font-medium">No demos found</p>
                                            <p class="text-sm">Schedule your first demo to see it here!</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($demos->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                        {{ $demos->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
