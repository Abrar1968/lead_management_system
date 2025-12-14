<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Monthly Overview
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Month Navigation --}}
            <div class="mb-6 flex items-center justify-between rounded-lg bg-white p-4 shadow">
                <a href="{{ route('leads.monthly', ['year' => $prevMonth->year, 'month' => $prevMonth->month]) }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <svg class="mr-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ $prevMonth->format('M Y') }}
                </a>

                <div class="text-center">
                    <div class="text-xl font-bold text-gray-900">{{ $currentMonth->format('F Y') }}</div>
                </div>

                @if($nextMonth->lte(now()))
                    <a href="{{ route('leads.monthly', ['year' => $nextMonth->year, 'month' => $nextMonth->month]) }}"
                       class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        {{ $nextMonth->format('M Y') }}
                        <svg class="ml-1 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <div class="w-24"></div>
                @endif
            </div>

            {{-- Monthly Summary Stats --}}
            <div class="mb-6 grid grid-cols-2 gap-4 sm:grid-cols-5">
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-gray-900">{{ $summary['total_leads'] }}</div>
                    <div class="text-sm text-gray-500">Total Leads</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-blue-600">{{ $summary['total_calls'] }}</div>
                    <div class="text-sm text-gray-500">Calls Made</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-green-600">{{ $summary['total_conversions'] }}</div>
                    <div class="text-sm text-gray-500">Conversions</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-purple-600">৳{{ number_format($summary['total_revenue']) }}</div>
                    <div class="text-sm text-gray-500">Revenue</div>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <div class="text-2xl font-bold text-orange-600">৳{{ number_format($summary['total_commission']) }}</div>
                    <div class="text-sm text-gray-500">Commission</div>
                </div>
            </div>

            {{-- Calendar Grid --}}
            <div class="overflow-hidden rounded-lg bg-white shadow">
                {{-- Day Headers --}}
                <div class="grid grid-cols-7 border-b bg-gray-50">
                    @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $dayName)
                        <div class="py-3 text-center text-sm font-semibold text-gray-600">
                            {{ $dayName }}
                        </div>
                    @endforeach
                </div>

                {{-- Calendar Days --}}
                @foreach($calendarData as $week)
                    <div class="grid grid-cols-7 border-b last:border-b-0">
                        @foreach($week as $day)
                            @if($day === null)
                                <div class="min-h-24 border-r bg-gray-50 last:border-r-0"></div>
                            @else
                                <a href="{{ !$day['isFuture'] ? route('leads.daily', ['date' => $day['date']]) : '#' }}"
                                   class="min-h-24 border-r p-2 last:border-r-0 transition-colors
                                          {{ $day['isFuture'] ? 'bg-gray-100 cursor-not-allowed' : 'hover:bg-indigo-50' }}
                                          {{ $day['isToday'] ? 'bg-indigo-100' : '' }}
                                          {{ $day['isWeekend'] && !$day['isToday'] ? 'bg-gray-50' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full text-sm font-medium
                                                     {{ $day['isToday'] ? 'bg-indigo-600 text-white' : 'text-gray-900' }}">
                                            {{ $day['day'] }}
                                        </span>
                                        @if($day['count'] > 0)
                                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">
                                                {{ $day['count'] }}
                                            </span>
                                        @endif
                                    </div>
                                    @if($day['count'] > 0 && !$day['isFuture'])
                                        <div class="mt-2">
                                            <div class="h-1 w-full overflow-hidden rounded-full bg-gray-200">
                                                <div class="h-full bg-indigo-600" style="width: {{ min($day['count'] * 10, 100) }}%"></div>
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">{{ $day['count'] }} lead{{ $day['count'] > 1 ? 's' : '' }}</p>
                                        </div>
                                    @endif
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endforeach
            </div>

            {{-- Legend --}}
            <div class="mt-4 flex items-center justify-center gap-6 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <span class="inline-flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 text-xs text-white">1</span>
                    <span>Today</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">5</span>
                    <span>Lead count</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-block h-4 w-4 rounded bg-gray-100"></span>
                    <span>Future (no leads)</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
