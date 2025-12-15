<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Monthly Overview
                </h2>
                <p class="mt-1 text-sm text-gray-500">Calendar view of leads and performance metrics</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Month Navigation --}}
            <div class="relative overflow-hidden rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 via-transparent to-purple-500/5"></div>
                <div class="relative flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('leads.monthly', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                       class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:shadow-md hover:-translate-x-1">
                        <svg class="h-5 w-5 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ $prevMonth->format('M Y') }}
                    </a>

                    <div class="text-center">
                        <div class="flex items-center justify-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div class="text-2xl font-bold text-gray-900">{{ $currentMonth->format('F Y') }}</div>
                        </div>
                    </div>

                    @if($nextMonth->lte(now()))
                        <a href="{{ route('leads.monthly', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                           class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:shadow-md hover:translate-x-1">
                            {{ $nextMonth->format('M Y') }}
                            <svg class="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    @else
                        <div class="w-28"></div>
                    @endif
                </div>
            </div>

            {{-- Monthly Summary Stats --}}
            <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-gray-500/10 to-gray-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-700 shadow-lg shadow-gray-500/30">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-900">{{ $summary['total_leads'] }}</div>
                            <div class="text-sm font-medium text-gray-500">Total Leads</div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-blue-500/10 to-blue-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 shadow-lg shadow-blue-500/30">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $summary['total_calls'] }}</div>
                            <div class="text-sm font-medium text-gray-500">Calls Made</div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-emerald-500/10 to-emerald-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-700 shadow-lg shadow-emerald-500/30">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-emerald-600">{{ $summary['total_conversions'] }}</div>
                            <div class="text-sm font-medium text-gray-500">Conversions</div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-violet-500/10 to-purple-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-violet-500 to-purple-700 shadow-lg shadow-violet-500/30">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-violet-600">৳{{ number_format($summary['total_revenue']) }}</div>
                            <div class="text-sm font-medium text-gray-500">Revenue</div>
                        </div>
                    </div>
                </div>

                <div class="group relative overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 col-span-2 lg:col-span-1">
                    <div class="absolute -right-4 -top-4 h-24 w-24 rounded-full bg-gradient-to-br from-amber-500/10 to-orange-600/10 transition-transform duration-300 group-hover:scale-110"></div>
                    <div class="relative flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-amber-600">৳{{ number_format($summary['total_commission']) }}</div>
                            <div class="text-sm font-medium text-gray-500">Commission</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                {{-- Day Headers --}}
                <div class="grid grid-cols-7 border-b bg-gradient-to-r from-gray-50 to-gray-100">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                        <div class="py-4 text-center text-sm font-bold text-gray-700 {{ $dayName === 'Sun' || $dayName === 'Sat' ? 'text-red-500' : '' }}">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                {{-- Calendar Days --}}
                @foreach($calendarData as $week)
                    <div class="grid grid-cols-7 border-b last:border-b-0">
                        @foreach($week as $day)
                            @if($day === null)
                                <div class="min-h-28 border-r bg-gray-50/50 last:border-r-0"></div>
                            @else
                                <a href="{{ !$day['isFuture'] ? route('leads.daily', ['date' => $day['date']]) : '#' }}"
                                   class="group relative min-h-28 border-r p-3 last:border-r-0 transition-all duration-300
                                          {{ $day['isFuture'] ? 'bg-gray-100/50 cursor-not-allowed' : 'hover:bg-gradient-to-br hover:from-blue-50 hover:to-indigo-50 hover:shadow-inner' }}
                                          {{ $day['isToday'] ? 'bg-gradient-to-br from-blue-50 to-indigo-100' : '' }}
                                          {{ $day['isWeekend'] && !$day['isToday'] ? 'bg-red-50/30' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl text-sm font-bold transition-all duration-300
                                                     {{ $day['isToday'] ? 'bg-gradient-to-br from-blue-600 to-indigo-600 text-white shadow-lg shadow-blue-500/30' : 'text-gray-700 group-hover:bg-gray-100' }}">
                                            {{ $day['day'] }}
                                        </span>
                                        @if($day['count'] > 0)
                                            <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 px-2.5 py-1 text-xs font-bold text-white shadow-md shadow-blue-500/25 transition-transform duration-300 group-hover:scale-110">
                                                {{ $day['count'] }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($day['count'] > 0 && !$day['isFuture'])
                                        <div class="mt-3">
                                            <div class="h-2 w-full overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all duration-500" style="width: {{ min($day['count'] * 10, 100) }}%"></div>
                                            </div>
                                            <p class="mt-1.5 text-xs font-medium text-gray-500">{{ $day['count'] }} lead{{ $day['count'] > 1 ? 's' : '' }}</p>
                                        </div>
                                    @endif
                                    {{-- Hover indicator --}}
                                    @if(!$day['isFuture'])
                                        <div class="absolute bottom-2 right-2 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                            <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            {{-- Legend --}}
            <div class="flex flex-wrap items-center justify-center gap-6 rounded-2xl bg-white p-4 shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-7 w-7 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-xs font-bold text-white shadow-md">1</span>
                    <span class="text-sm font-medium text-gray-600">Today</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-lg bg-gradient-to-r from-blue-500 to-indigo-600 px-2.5 py-1 text-xs font-bold text-white shadow-md">5</span>
                    <span class="text-sm font-medium text-gray-600">Lead count</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block h-5 w-5 rounded-lg bg-gray-100 border border-gray-200"></span>
                    <span class="text-sm font-medium text-gray-600">Future (no leads)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block h-5 w-5 rounded-lg bg-red-50 border border-red-100"></span>
                    <span class="text-sm font-medium text-gray-600">Weekend</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
