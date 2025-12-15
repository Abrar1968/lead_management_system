<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Reports - {{ \Carbon\Carbon::parse($month)->format('F Y') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Month Filter --}}
            <div class="mb-6">
                <form method="GET" class="flex items-center gap-4">
                    <input type="month" name="month" value="{{ $month }}"
                        class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit"
                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        Generate Report
                    </button>
                    <a href="{{ route('reports.print', ['month' => $month]) }}" target="_blank"
                        class="rounded-md bg-gray-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-gray-500 flex items-center gap-2">
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
                <div class="rounded-lg bg-white p-4 shadow">
                    <p class="text-sm font-medium text-gray-500">Total Leads</p>
                    <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($totalLeads) }}</p>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <p class="text-sm font-medium text-gray-500">Calls Made</p>
                    <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($totalCalls) }}</p>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <p class="text-sm font-medium text-gray-500">Conversions</p>
                    <p class="mt-1 text-2xl font-bold text-green-600">{{ number_format($totalConversions) }}</p>
                    <p class="text-xs text-gray-500">{{ $conversionRate }}% rate</p>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <p class="text-sm font-medium text-gray-500">Total Deal Value</p>
                    <p class="mt-1 text-2xl font-bold text-purple-600">৳{{ number_format($totalDealValue) }}</p>
                </div>
                <div class="rounded-lg bg-white p-4 shadow">
                    <p class="text-sm font-medium text-gray-500">Total Commission</p>
                    <p class="mt-1 text-2xl font-bold text-orange-600">৳{{ number_format($totalCommission) }}</p>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Left Column: Breakdowns --}}
                <div class="space-y-6">
                    {{-- Source Breakdown --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-3">
                            <h3 class="text-sm font-semibold text-gray-900">Lead Sources</h3>
                        </div>
                        <div class="p-4">
                            @forelse($sourceBreakdown as $source => $count)
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600">{{ $source }}</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                </div>
                            @empty
                                <p class="py-4 text-center text-sm text-gray-500">No data</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Service Breakdown --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-3">
                            <h3 class="text-sm font-semibold text-gray-900">Services Interested</h3>
                        </div>
                        <div class="p-4">
                            @forelse($serviceBreakdown as $service => $count)
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600">{{ $service }}</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $count }}</span>
                                </div>
                            @empty
                                <p class="py-4 text-center text-sm text-gray-500">No data</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Status Breakdown --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-3">
                            <h3 class="text-sm font-semibold text-gray-900">Lead Status</h3>
                        </div>
                        <div class="p-4">
                            @forelse($statusBreakdown as $status => $count)
                                <div class="flex items-center justify-between py-2">
                                    <span class="text-sm text-gray-600">{{ $status }}</span>
                                    <span
                                        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        @switch($status)
                                            @case('New') bg-gray-100 text-gray-800 @break
                                            @case('Contacted') bg-blue-100 text-blue-800 @break
                                            @case('Qualified') bg-indigo-100 text-indigo-800 @break
                                            @case('Negotiation') bg-orange-100 text-orange-800 @break
                                            @case('Converted') bg-green-100 text-green-800 @break
                                            @case('Lost') bg-red-100 text-red-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch">
                                        {{ $count }}
                                    </span>
                                </div>
                            @empty
                                <p class="py-4 text-center text-sm text-gray-500">No data</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Right Column: Charts & Lists --}}
                <div class="space-y-6 lg:col-span-2">
                    {{-- Daily Leads Chart (Simple bar representation) --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-3">
                            <h3 class="text-sm font-semibold text-gray-900">Daily Lead Count</h3>
                        </div>
                        <div class="p-4">
                            <div class="flex h-40 items-end gap-1">
                                @php
                                    $maxValue = max(array_values($dailyData) ?: [1]);
                                @endphp
                                @foreach ($dailyData as $day => $count)
                                    <div class="group relative flex flex-1 flex-col items-center">
                                        <div class="w-full rounded-t bg-indigo-500 transition-all hover:bg-indigo-600"
                                            style="height: {{ $maxValue > 0 ? ($count / $maxValue) * 100 : 0 }}%"
                                            title="Day {{ $day }}: {{ $count }} leads">
                                        </div>
                                        @if ($loop->iteration % 5 == 1 || $loop->last)
                                            <span class="mt-1 text-[10px] text-gray-500">{{ $day }}</span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Top Performers (Admin only) --}}
                    @if ($isAdmin && $topPerformers->isNotEmpty())
                        <div class="overflow-hidden rounded-lg bg-white shadow">
                            <div class="border-b bg-gray-50 px-6 py-3">
                                <h3 class="text-sm font-semibold text-gray-900">Top Performers</h3>
                            </div>
                            <div class="divide-y">
                                @foreach ($topPerformers as $index => $performer)
                                    <div class="flex items-center gap-4 px-6 py-3">
                                        <span
                                            class="flex h-8 w-8 items-center justify-center rounded-full
                                            @switch($index)
                                                @case(0) bg-yellow-100 text-yellow-800 @break
                                                @case(1) bg-gray-200 text-gray-700 @break
                                                @case(2) bg-orange-100 text-orange-800 @break
                                                @default bg-gray-100 text-gray-600
                                            @endswitch
                                            text-sm font-bold">
                                            {{ $index + 1 }}
                                        </span>
                                        <div class="flex-1">
                                            <p class="text-sm font-medium text-gray-900">{{ $performer->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $performer->email }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-green-600">
                                                {{ $performer->conversions_count }}</p>
                                            <p class="text-xs text-gray-500">conversions</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Recent Conversions --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-3">
                            <h3 class="text-sm font-semibold text-gray-900">Conversions This Month</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Date
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Lead
                                        </th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                            Deal Value</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">
                                            Commission</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($conversions as $conversion)
                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">
                                                {{ $conversion->conversion_date->format('M d') }}
                                            </td>
                                            <td class="px-4 py-3 text-sm">
                                                <a href="{{ route('leads.show', $conversion->lead) }}"
                                                    class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $conversion->lead->customer_name }}
                                                </a>
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium text-gray-900">
                                                ৳{{ number_format($conversion->deal_value) }}
                                            </td>
                                            <td
                                                class="whitespace-nowrap px-4 py-3 text-right text-sm font-medium text-green-600">
                                                ৳{{ number_format($conversion->commission_amount) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                                No conversions this month
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
