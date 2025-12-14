<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Daily Leads
            </h2>
            <a href="{{ route('leads.create', ['date' => $dateNav['current']]) }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Date Navigation --}}
            <div class="mb-6 flex items-center justify-between rounded-lg bg-white p-4 shadow">
                <a href="{{ route('leads.daily', ['date' => $dateNav['previous']]) }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <svg class="mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Previous
                </a>

                <div class="text-center">
                    <div class="text-lg font-semibold text-gray-900">{{ $dateNav['display'] }}</div>
                    <div class="text-sm text-gray-500">{{ $dateNav['day_name'] }}</div>
                    @if($dateNav['is_today'])
                        <span class="mt-1 inline-flex items-center rounded-full bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800">
                            Today
                        </span>
                    @endif
                </div>

                <a href="{{ route('leads.daily', ['date' => $dateNav['next']]) }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 {{ $dateNav['is_future'] ? 'opacity-50 pointer-events-none' : '' }}">
                    Next
                    <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            {{-- Quick Navigation --}}
            <div class="mb-6 flex flex-wrap gap-2">
                <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}"
                   class="rounded-md {{ $dateNav['is_today'] ? 'bg-indigo-600 text-white' : 'bg-white text-gray-700' }} px-3 py-1.5 text-sm font-medium shadow hover:bg-indigo-500 hover:text-white">
                    Today
                </a>
                <a href="{{ route('leads.daily', ['date' => now()->subDay()->format('Y-m-d')]) }}"
                   class="rounded-md bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow hover:bg-gray-100">
                    Yesterday
                </a>
                <a href="{{ route('leads.daily', ['date' => now()->subDays(2)->format('Y-m-d')]) }}"
                   class="rounded-md bg-white px-3 py-1.5 text-sm font-medium text-gray-700 shadow hover:bg-gray-100">
                    2 Days Ago
                </a>
                <input type="date"
                       value="{{ $dateNav['current'] }}"
                       max="{{ now()->format('Y-m-d') }}"
                       onchange="window.location.href='{{ route('leads.daily') }}?date=' + this.value"
                       class="rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>

            {{-- Summary Stats --}}
            <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-gray-900">{{ $summary['total_leads'] }}</div>
                    <div class="text-sm text-gray-500">Total Leads</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-blue-600">{{ $summary['calls_made'] }}</div>
                    <div class="text-sm text-gray-500">Calls Made</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-green-600">{{ $summary['conversions'] }}</div>
                    <div class="text-sm text-gray-500">Conversions</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-orange-600">{{ $summary['pending_followups'] }}</div>
                    <div class="text-sm text-gray-500">Pending Follow-ups</div>
                </div>
            </div>

            {{-- Filters --}}
            <div x-data="{ showFilters: false }" class="mb-6">
                <button @click="showFilters = !showFilters"
                        class="mb-3 inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow hover:bg-gray-50">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filters
                    <span x-show="showFilters" class="ml-1">▲</span>
                    <span x-show="!showFilters" class="ml-1">▼</span>
                </button>

                <form x-show="showFilters" x-transition method="GET" action="{{ route('leads.daily') }}"
                      class="grid grid-cols-2 gap-4 rounded-lg bg-white p-4 shadow sm:grid-cols-5">
                    <input type="hidden" name="date" value="{{ $dateNav['current'] }}">

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Source</label>
                        <select name="source" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">All Sources</option>
                            <option value="WhatsApp" {{ $filters['source'] === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                            <option value="Messenger" {{ $filters['source'] === 'Messenger' ? 'selected' : '' }}>Messenger</option>
                            <option value="Website" {{ $filters['source'] === 'Website' ? 'selected' : '' }}>Website</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Service</label>
                        <select name="service" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">All Services</option>
                            <option value="Website" {{ $filters['service'] === 'Website' ? 'selected' : '' }}>Website</option>
                            <option value="Software" {{ $filters['service'] === 'Software' ? 'selected' : '' }}>Software</option>
                            <option value="CRM" {{ $filters['service'] === 'CRM' ? 'selected' : '' }}>CRM</option>
                            <option value="Marketing" {{ $filters['service'] === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">All Statuses</option>
                            <option value="New" {{ $filters['status'] === 'New' ? 'selected' : '' }}>New</option>
                            <option value="Contacted" {{ $filters['status'] === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                            <option value="Qualified" {{ $filters['status'] === 'Qualified' ? 'selected' : '' }}>Qualified</option>
                            <option value="Negotiation" {{ $filters['status'] === 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                            <option value="Converted" {{ $filters['status'] === 'Converted' ? 'selected' : '' }}>Converted</option>
                            <option value="Lost" {{ $filters['status'] === 'Lost' ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority" class="mt-1 block w-full rounded-md border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="all">All Priorities</option>
                            <option value="High" {{ $filters['priority'] === 'High' ? 'selected' : '' }}>High</option>
                            <option value="Medium" {{ $filters['priority'] === 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="Low" {{ $filters['priority'] === 'Low' ? 'selected' : '' }}>Low</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2">
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-medium text-white hover:bg-indigo-500">
                            Apply
                        </button>
                        <a href="{{ route('leads.daily', ['date' => $dateNav['current']]) }}"
                           class="rounded-md bg-gray-200 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-300">
                            Reset
                        </a>
                    </div>
                </form>
            </div>

            {{-- Leads List --}}
            @if($leads->isEmpty())
                <div class="rounded-lg bg-white p-12 text-center shadow">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No leads found</h3>
                    <p class="mt-1 text-sm text-gray-500">No leads recorded for {{ $dateNav['display'] }}</p>
                    <div class="mt-6">
                        <a href="{{ route('leads.create', ['date' => $dateNav['current']]) }}"
                           class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                            </svg>
                            Add Lead
                        </a>
                    </div>
                </div>
            @else
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($leads as $lead)
                        <div class="overflow-hidden rounded-lg bg-white shadow hover:shadow-md transition-shadow">
                            <div class="p-4">
                                {{-- Header --}}
                                <div class="flex items-start justify-between">
                                    <div>
                                        <a href="{{ route('leads.show', $lead) }}"
                                           class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                            {{ $lead->lead_number }}
                                        </a>
                                        <h3 class="mt-1 text-lg font-semibold text-gray-900">
                                            {{ $lead->customer_name ?? 'Unknown' }}
                                        </h3>
                                    </div>
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                        @switch($lead->priority)
                                            @case('High') bg-red-100 text-red-800 @break
                                            @case('Medium') bg-yellow-100 text-yellow-800 @break
                                            @case('Low') bg-green-100 text-green-800 @break
                                        @endswitch">
                                        {{ $lead->priority }}
                                    </span>
                                </div>

                                {{-- Contact Info --}}
                                <div class="mt-3 space-y-1">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        {{ $lead->phone_number }}
                                    </div>
                                    @if($lead->email)
                                        <div class="flex items-center text-sm text-gray-600">
                                            <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            {{ $lead->email }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Tags --}}
                                <div class="mt-3 flex flex-wrap gap-1">
                                    <span class="inline-flex items-center rounded bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                        {{ $lead->source }}
                                    </span>
                                    <span class="inline-flex items-center rounded bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">
                                        {{ $lead->service_interested }}
                                    </span>
                                    <span class="inline-flex items-center rounded px-2 py-0.5 text-xs font-medium
                                        @switch($lead->status)
                                            @case('New') bg-gray-100 text-gray-800 @break
                                            @case('Contacted') bg-blue-100 text-blue-800 @break
                                            @case('Qualified') bg-indigo-100 text-indigo-800 @break
                                            @case('Negotiation') bg-yellow-100 text-yellow-800 @break
                                            @case('Converted') bg-green-100 text-green-800 @break
                                            @case('Lost') bg-red-100 text-red-800 @break
                                        @endswitch">
                                        {{ $lead->status }}
                                    </span>
                                </div>

                                {{-- Follow-up & Meeting Stages --}}
                                @php
                                    $latestFollowUp = $lead->followUps->first();
                                    $latestMeeting = $lead->meetings->first();
                                @endphp
                                @if($latestFollowUp || $latestMeeting)
                                <div class="mt-2 flex flex-wrap gap-2 border-t pt-2">
                                    @if($latestFollowUp)
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-500">Follow-up:</span>
                                            @if($latestFollowUp->interest)
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                                    {{ \App\Http\Controllers\FollowUpController::INTEREST_STATUSES[$latestFollowUp->interest]['bg'] ?? 'bg-gray-100' }}
                                                    {{ \App\Http\Controllers\FollowUpController::INTEREST_STATUSES[$latestFollowUp->interest]['text'] ?? 'text-gray-800' }}">
                                                    {{ $latestFollowUp->interest }}
                                                </span>
                                            @else
                                                <span class="text-xs px-2 py-0.5 rounded-full {{ $latestFollowUp->status === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                                    {{ $latestFollowUp->status }}
                                                </span>
                                            @endif
                                            @if($latestFollowUp->price)
                                                <span class="text-xs font-semibold text-green-600">৳{{ number_format($latestFollowUp->price, 0) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($latestMeeting)
                                        <div class="flex items-center gap-1">
                                            <span class="text-xs text-gray-500">Meeting:</span>
                                            @if($latestMeeting->meeting_status)
                                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                                    {{ \App\Http\Controllers\MeetingController::MEETING_STATUSES[$latestMeeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                                    {{ \App\Http\Controllers\MeetingController::MEETING_STATUSES[$latestMeeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                                    {{ $latestMeeting->meeting_status }}
                                                </span>
                                            @else
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-800">
                                                    {{ $latestMeeting->outcome }}
                                                </span>
                                            @endif
                                            @if($latestMeeting->price)
                                                <span class="text-xs font-semibold text-green-600">৳{{ number_format($latestMeeting->price, 0) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @endif

                                {{-- Assigned To --}}
                                <div class="mt-3 flex items-center justify-between border-t pt-3">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <svg class="mr-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $lead->assignedTo?->name ?? 'Unassigned' }}
                                    </div>
                                    <div class="flex items-center gap-1 text-sm text-gray-400">
                                        @if($lead->contacts->count() > 0)
                                            <span class="flex items-center" title="Calls made">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $lead->contacts->count() }}
                                            </span>
                                        @endif
                                        @if($lead->followUps->where('status', 'Pending')->count() > 0)
                                            <span class="flex items-center text-orange-500" title="Pending follow-ups">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                {{ $lead->followUps->where('status', 'Pending')->count() }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Action Footer --}}
                            <div class="flex border-t bg-gray-50">
                                <a href="{{ route('leads.show', $lead) }}"
                                   class="flex-1 py-2 text-center text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                                    View
                                </a>
                                <a href="{{ route('leads.edit', $lead) }}"
                                   class="flex-1 border-l py-2 text-center text-sm font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900">
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
