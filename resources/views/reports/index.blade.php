<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Reports</h2>
                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    {{-- Month Filter --}}
    <div class="mb-6">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <input type="month" name="month" value="{{ $month }}"
                class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
            <button type="submit"
                class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                Generate Report
            </button>
            <a href="{{ route('reports.print', ['month' => $month]) }}" target="_blank"
                class="rounded-xl bg-gray-700 px-5 py-2.5 text-sm font-semibold text-white shadow-lg hover:bg-gray-800 transition-all flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2-4h6a2 2 0 012 2v2m-6-6h6m-6 0H6m6 0a2 2 0 012-2V5a2 2 0 01-2-2H9a2 2 0 01-2 2v6a2 2 0 012 2zm3 6v4a2 2 0 01-2 2H9a2 2 0 01-2-2v-4h6z" />
                </svg>
                Print
            </a>
        </form>
    </div>

    {{-- Summary Stats --}}
    <div class="mb-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 text-white shadow-lg shadow-gray-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Leads</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($totalLeads) }}</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Calls Made</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ number_format($totalCalls) }}</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Conversions</p>
            <p class="text-3xl font-bold text-emerald-600 mt-1">{{ number_format($totalConversions) }}</p>
            <p class="text-xs text-gray-500 font-medium mt-1">{{ $conversionRate }}% rate</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Deal Value</p>
            <p class="text-3xl font-bold text-purple-600 mt-1">৳{{ number_format($totalDealValue) }}</p>
        </div>
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center justify-between mb-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Commission</p>
            <p class="text-3xl font-bold text-amber-600 mt-1">৳{{ number_format($totalCommission) }}</p>
        </div>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Left Column: Breakdowns --}}
        <div class="space-y-6">
            {{-- Source Breakdown --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Lead Sources</h3>
                    </div>
                </div>
                <div class="p-5">
                    @forelse($sourceBreakdown as $source => $count)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <span class="text-sm font-medium text-gray-700">{{ $source }}</span>
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold bg-gray-100 text-gray-900">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-sm font-medium text-gray-500">No data</p>
                    @endforelse
                </div>
            </div>

            {{-- Service Breakdown --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Services Interested</h3>
                    </div>
                </div>
                <div class="p-5">
                    @forelse($serviceBreakdown as $service => $count)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <span class="text-sm font-medium text-gray-700">{{ $service }}</span>
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold bg-gray-100 text-gray-900">{{ $count }}</span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-sm font-medium text-gray-500">No data</p>
                    @endforelse
                </div>
            </div>

            {{-- Status Breakdown --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Lead Status</h3>
                    </div>
                </div>
                <div class="p-5">
                    @forelse($statusBreakdown as $status => $count)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                            <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                @switch($status)
                                    @case('New') bg-gray-200 text-gray-800 @break
                                    @case('Contacted') bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-blue-500/30 @break
                                    @case('Qualified') bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-indigo-500/30 @break
                                    @case('Negotiation') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                    @case('Converted') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                    @case('Lost') bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-red-500/30 @break
                                    @default bg-gray-200 text-gray-800
                                @endswitch">
                                {{ $count }}
                            </span>
                        </div>
                    @empty
                        <p class="py-8 text-center text-sm font-medium text-gray-500">No data</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Right Column: Charts & Lists --}}
        <div class="space-y-6 lg:col-span-2">
            {{-- Daily Leads Chart --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Daily Lead Count</h3>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex h-40 items-end gap-1">
                        @php
                            $maxValue = max(array_values($dailyData) ?: [1]);
                        @endphp
                        @foreach ($dailyData as $day => $count)
                            <div class="group relative flex flex-1 flex-col items-center">
                                <div class="w-full rounded-t bg-gradient-to-t from-indigo-600 to-purple-500 transition-all hover:from-indigo-500 hover:to-purple-400"
                                    style="height: {{ $maxValue > 0 ? ($count / $maxValue) * 100 : 0 }}%"
                                    title="Day {{ $day }}: {{ $count }} leads">
                                </div>
                                @if ($loop->iteration % 5 == 1 || $loop->last)
                                    <span class="mt-2 text-[10px] font-medium text-gray-500">{{ $day }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Top Performers (Admin only) --}}
            @if ($isAdmin && $topPerformers->isNotEmpty())
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                    <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                                <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                                </svg>
                            </div>
                            <h3 class="font-semibold text-white">Top Performers</h3>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($topPerformers as $index => $performer)
                            <div class="flex items-center gap-4 px-5 py-4 transition-colors hover:bg-gray-50">
                                <span class="flex h-10 w-10 items-center justify-center rounded-xl shadow-lg text-sm font-bold
                                    @switch($index)
                                        @case(0) bg-gradient-to-br from-amber-400 to-yellow-500 text-white shadow-amber-500/30 @break
                                        @case(1) bg-gradient-to-br from-gray-300 to-gray-400 text-gray-800 shadow-gray-500/30 @break
                                        @case(2) bg-gradient-to-br from-amber-600 to-orange-700 text-white shadow-orange-500/30 @break
                                        @default bg-gray-100 text-gray-600
                                    @endswitch">
                                    {{ $index + 1 }}
                                </span>
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-900">{{ $performer->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $performer->email }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-emerald-600">{{ $performer->conversions_count }}</p>
                                    <p class="text-xs text-gray-500">conversions</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Recent Conversions --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Conversions This Month</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Lead</th>
                                <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Deal Value</th>
                                <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Commission</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($conversions as $conversion)
                                @if($conversion->lead)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-600">
                                        {{ $conversion->conversion_date->format('M d') }}
                                    </td>
                                    <td class="px-4 py-4 text-sm">
                                        <a href="{{ route('leads.show', $conversion->lead) }}"
                                            class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                            {{ $conversion->lead->client_name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-bold text-gray-900">
                                        ৳{{ number_format($conversion->deal_value) }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-bold text-emerald-600">
                                        ৳{{ number_format($conversion->commission_amount) }}
                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-12 text-center">
                                        <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">No conversions this month</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
