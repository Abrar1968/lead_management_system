<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Daily Leads
                </h2>
                <p class="mt-1 text-sm text-gray-500">Track and manage leads for any specific day</p>
            </div>
            <a href="{{ route('leads.create', ['date' => $dateNav['current']]) }}"
               class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20 transition-transform duration-300 group-hover:rotate-90">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                </span>
                Add Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Date Navigation --}}
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-transparent to-indigo-500/5"></div>
                <div class="relative flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('leads.daily', ['date' => $dateNav['previous']]) }}"
                       class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:shadow-md hover:-translate-x-1">
                        <svg class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Previous Day
                    </a>

                    <div class="text-center">
                        <div class="flex items-center justify-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-900">{{ $dateNav['display'] }}</div>
                                <div class="text-sm font-medium text-gray-500">{{ $dateNav['day_name'] }}</div>
                            </div>
                        </div>
                        @if($dateNav['is_today'])
                            <span class="mt-3 inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-emerald-500 to-green-600 px-4 py-1 text-xs font-semibold text-white shadow-lg shadow-emerald-500/30">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-white"></span>
                                Today
                            </span>
                        @endif
                    </div>

                    <a href="{{ route('leads.daily', ['date' => $dateNav['next']]) }}"
                       class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:shadow-md hover:translate-x-1 {{ $dateNav['is_future'] ? 'opacity-50 pointer-events-none' : '' }}">
                        Next Day
                        <svg class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Quick Navigation --}}
            <div class="flex flex-wrap items-center gap-3">
                <span class="text-sm font-medium text-gray-500">Quick Jump:</span>
                <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}"
                   class="rounded-xl px-4 py-2 text-sm font-semibold transition-all duration-300 {{ $dateNav['is_today'] ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30' : 'bg-white text-gray-700 shadow-md hover:shadow-lg hover:-translate-y-0.5' }}">
                    Today
                </a>
                <a href="{{ route('leads.daily', ['date' => now()->subDay()->format('Y-m-d')]) }}"
                   class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    Yesterday
                </a>
                <a href="{{ route('leads.daily', ['date' => now()->subDays(2)->format('Y-m-d')]) }}"
                   class="rounded-xl bg-white px-4 py-2 text-sm font-semibold text-gray-700 shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    2 Days Ago
                </a>
                <div class="relative">
                    <input type="date"
                           value="{{ $dateNav['current'] }}"
                           max="{{ now()->format('Y-m-d') }}"
                           onchange="window.location.href='{{ route('leads.daily') }}?date=' + this.value"
                           class="rounded-xl border-0 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-md transition-all duration-300 hover:shadow-lg focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                </div>
            </div>

            {{-- Summary Stats --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-gray-500/10 to-gray-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-700 shadow-lg shadow-gray-500/30">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900">{{ $summary['total_leads'] }}</div>
                                <div class="text-sm font-medium text-gray-500">Total Leads</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-blue-500/10 to-blue-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg shadow-blue-500/30">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-blue-600">{{ $summary['calls_made'] }}</div>
                                <div class="text-sm font-medium text-gray-500">Calls Made</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-500/30">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-emerald-600">{{ $summary['conversions'] }}</div>
                                <div class="text-sm font-medium text-gray-500">Conversions</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-amber-500/10 to-orange-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-amber-600">{{ $summary['pending_followups'] }}</div>
                                <div class="text-sm font-medium text-gray-500">Pending Follow-ups</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filters --}}
            <div x-data="{ showFilters: false }" class="relative">
                <button @click="showFilters = !showFilters"
                        class="inline-flex items-center gap-2 rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5">
                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 text-white">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                    </div>
                    Filters
                    <svg class="h-5 w-5 transition-transform duration-300" :class="showFilters ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <form x-show="showFilters"
                      x-transition:enter="transition ease-out duration-300"
                      x-transition:enter-start="opacity-0 -translate-y-4"
                      x-transition:enter-end="opacity-100 translate-y-0"
                      x-transition:leave="transition ease-in duration-200"
                      x-transition:leave-start="opacity-100 translate-y-0"
                      x-transition:leave-end="opacity-0 -translate-y-4"
                      method="GET"
                      action="{{ route('leads.daily') }}"
                      class="mt-4 overflow-hidden rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                    <input type="hidden" name="date" value="{{ $dateNav['current'] }}">

                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-5">
                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Source</label>
                            <select name="source" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                <option value="all">All Sources</option>
                                <option value="WhatsApp" {{ $filters['source'] === 'WhatsApp' ? 'selected' : '' }}>WhatsApp</option>
                                <option value="Messenger" {{ $filters['source'] === 'Messenger' ? 'selected' : '' }}>Messenger</option>
                                <option value="Website" {{ $filters['source'] === 'Website' ? 'selected' : '' }}>Website</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Service</label>
                            <select name="service" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                <option value="all">All Services</option>
                                <option value="Website" {{ $filters['service'] === 'Website' ? 'selected' : '' }}>Website</option>
                                <option value="Software" {{ $filters['service'] === 'Software' ? 'selected' : '' }}>Software</option>
                                <option value="CRM" {{ $filters['service'] === 'CRM' ? 'selected' : '' }}>CRM</option>
                                <option value="Marketing" {{ $filters['service'] === 'Marketing' ? 'selected' : '' }}>Marketing</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Status</label>
                            <select name="status" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                <option value="all">All Statuses</option>
                                <option value="New" {{ $filters['status'] === 'New' ? 'selected' : '' }}>New</option>
                                <option value="Contacted" {{ $filters['status'] === 'Contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="Qualified" {{ $filters['status'] === 'Qualified' ? 'selected' : '' }}>Qualified</option>
                                <option value="Negotiation" {{ $filters['status'] === 'Negotiation' ? 'selected' : '' }}>Negotiation</option>
                                <option value="Converted" {{ $filters['status'] === 'Converted' ? 'selected' : '' }}>Converted</option>
                                <option value="Lost" {{ $filters['status'] === 'Lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-semibold text-gray-700">Priority</label>
                            <select name="priority" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                                <option value="all">All Priorities</option>
                                <option value="High" {{ $filters['priority'] === 'High' ? 'selected' : '' }}>High</option>
                                <option value="Medium" {{ $filters['priority'] === 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option value="Low" {{ $filters['priority'] === 'Low' ? 'selected' : '' }}>Low</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button type="submit"
                                    class="flex-1 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40">
                                Apply
                            </button>
                            <a href="{{ route('leads.daily', ['date' => $dateNav['current']]) }}"
                               class="rounded-xl bg-gray-200 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-300">
                                Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Leads List --}}
            @if($leads->isEmpty())
                <div class="overflow-hidden rounded-2xl bg-white p-12 text-center shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200">
                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900">No leads found</h3>
                    <p class="mt-1 text-sm text-gray-500">No leads recorded for {{ $dateNav['display'] }}</p>
                    <div class="mt-6">
                        <a href="{{ route('leads.create', ['date' => $dateNav['current']]) }}"
                           class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                            <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20 transition-transform duration-300 group-hover:rotate-90">
                                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                </svg>
                            </span>
                            Add Lead
                        </a>
                    </div>
                </div>
            @else
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($leads as $lead)
                        <div class="group relative overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            {{-- Priority Indicator --}}
                            <div class="absolute right-0 top-0 h-20 w-20 overflow-hidden">
                                <div class="absolute right-[-35px] top-[12px] w-[100px] rotate-45 py-1 text-center text-[10px] font-bold uppercase tracking-wider
                                    @switch($lead->priority)
                                        @case('High') bg-gradient-to-r from-red-500 to-rose-600 text-white @break
                                        @case('Medium') bg-gradient-to-r from-amber-400 to-orange-500 text-white @break
                                        @case('Low') bg-gradient-to-r from-green-400 to-emerald-500 text-white @break
                                    @endswitch">
                                    {{ $lead->priority }}
                                </div>
                            </div>

                            <div class="p-5">
                                {{-- Header --}}
                                <div class="flex items-start justify-between pr-10">
                                    <div>
                                        <a href="{{ route('leads.show', $lead) }}"
                                           class="inline-flex items-center gap-1.5 text-sm font-semibold text-blue-600 transition-colors duration-200 hover:text-blue-800">
                                            <span class="flex h-5 w-5 items-center justify-center rounded bg-blue-100">
                                                <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                </svg>
                                            </span>
                                            {{ $lead->lead_number }}
                                        </a>
                                        <h3 class="mt-1 text-lg font-bold text-gray-900">
                                            {{ $lead->customer_name ?? 'Unknown' }}
                                        </h3>
                                    </div>
                                </div>

                                {{-- Contact Info --}}
                                <div class="mt-4 space-y-2">
                                    <div class="flex items-center gap-2 text-sm text-gray-600">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </span>
                                        <span class="font-medium">{{ $lead->phone_number }}</span>
                                    </div>
                                    @if($lead->email)
                                        <div class="flex items-center gap-2 text-sm text-gray-600">
                                            <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-purple-100 text-purple-600">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </span>
                                            <span class="font-medium truncate">{{ $lead->email }}</span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Tags --}}
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 24 24">
                                            @if($lead->source === 'WhatsApp')
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            @elseif($lead->source === 'Messenger')
                                                <path d="M12 0C5.373 0 0 4.974 0 11.111c0 3.498 1.744 6.614 4.469 8.654V24l4.088-2.242c1.092.3 2.246.464 3.443.464 6.627 0 12-4.974 12-11.111S18.627 0 12 0zm1.191 14.963l-3.055-3.26-5.963 3.26L10.732 8l3.131 3.259L19.752 8l-6.561 6.963z"/>
                                            @else
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/>
                                            @endif
                                        </svg>
                                        {{ $lead->source }}
                                    </span>
                                    <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-violet-500 to-purple-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                                        {{ $lead->service_interested }}
                                    </span>
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold shadow-sm
                                        @switch($lead->status)
                                            @case('New') bg-gray-200 text-gray-800 @break
                                            @case('Contacted') bg-blue-100 text-blue-800 @break
                                            @case('Qualified') bg-indigo-100 text-indigo-800 @break
                                            @case('Negotiation') bg-amber-100 text-amber-800 @break
                                            @case('Converted') bg-emerald-100 text-emerald-800 @break
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
                                <div class="mt-4 space-y-2 border-t border-gray-100 pt-4">
                                    @if($latestFollowUp)
                                        <div class="flex items-center justify-between gap-2 rounded-lg bg-gray-50 px-3 py-2">
                                            <div class="flex items-center gap-2">
                                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </span>
                                                <span class="text-xs font-medium text-gray-500">Follow-up:</span>
                                                @if($latestFollowUp->interest)
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                                        {{ \App\Http\Controllers\FollowUpController::INTEREST_STATUSES[$latestFollowUp->interest]['bg'] ?? 'bg-gray-100' }}
                                                        {{ \App\Http\Controllers\FollowUpController::INTEREST_STATUSES[$latestFollowUp->interest]['text'] ?? 'text-gray-800' }}">
                                                        {{ $latestFollowUp->interest }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold {{ $latestFollowUp->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                        {{ $latestFollowUp->status }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($latestFollowUp->price)
                                                <span class="text-xs font-bold text-emerald-600">৳{{ number_format($latestFollowUp->price, 0) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                    @if($latestMeeting)
                                        <div class="flex items-center justify-between gap-2 rounded-lg bg-gray-50 px-3 py-2">
                                            <div class="flex items-center gap-2">
                                                <span class="flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </span>
                                                <span class="text-xs font-medium text-gray-500">Meeting:</span>
                                                @if($latestMeeting->meeting_status)
                                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-semibold
                                                        {{ \App\Http\Controllers\MeetingController::MEETING_STATUSES[$latestMeeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                                        {{ \App\Http\Controllers\MeetingController::MEETING_STATUSES[$latestMeeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                                        {{ $latestMeeting->meeting_status }}
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-xs font-semibold text-gray-800">
                                                        {{ $latestMeeting->outcome }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($latestMeeting->price)
                                                <span class="text-xs font-bold text-emerald-600">৳{{ number_format($latestMeeting->price, 0) }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @endif

                                {{-- Assigned To --}}
                                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-4">
                                    <div class="flex items-center gap-2 text-sm">
                                        <span class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-gray-400 to-gray-600 text-xs font-bold text-white shadow-sm">
                                            {{ $lead->assignedTo ? strtoupper(substr($lead->assignedTo->name, 0, 1)) : '?' }}
                                        </span>
                                        <span class="font-medium text-gray-700">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        @if($lead->contacts->count() > 0)
                                            <span class="flex items-center gap-1 text-sm text-gray-500" title="Calls made">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                <span class="font-semibold">{{ $lead->contacts->count() }}</span>
                                            </span>
                                        @endif
                                        @if($lead->followUps->where('status', 'Pending')->count() > 0)
                                            <span class="flex items-center gap-1 text-sm text-amber-500" title="Pending follow-ups">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-semibold">{{ $lead->followUps->where('status', 'Pending')->count() }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Action Footer --}}
                            <div class="flex border-t border-gray-100 bg-gray-50/50">
                                <a href="{{ route('leads.show', $lead) }}"
                                   class="flex flex-1 items-center justify-center gap-1.5 py-3 text-sm font-semibold text-gray-600 transition-all duration-200 hover:bg-blue-50 hover:text-blue-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                                <a href="{{ route('leads.edit', $lead) }}"
                                   class="flex flex-1 items-center justify-center gap-1.5 border-l border-gray-100 py-3 text-sm font-semibold text-gray-600 transition-all duration-200 hover:bg-indigo-50 hover:text-indigo-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
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
