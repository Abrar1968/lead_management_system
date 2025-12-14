<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard - {{ now()->format('F j, Y') }}
        </h2>
    </x-slot>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Today's Leads -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Today's Leads</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today_leads'] }}</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}" class="text-sm text-blue-600 hover:underline mt-2 inline-block">View all →</a>
        </div>

        <!-- Today's Calls -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Calls Made Today</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today_calls'] }}</p>
                </div>
                <div class="bg-green-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Pending Follow-ups -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Pending Follow-ups</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_follow_ups'] }}</p>
                </div>
                <div class="bg-amber-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Today's Conversions -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 uppercase tracking-wide">Today's Conversions</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $stats['today_conversions'] }}</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-full">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow p-6 text-white">
            <p class="text-sm opacity-80 uppercase tracking-wide">This Month Leads</p>
            <p class="text-3xl font-bold">{{ $stats['month_leads'] }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow p-6 text-white">
            <p class="text-sm opacity-80 uppercase tracking-wide">This Month Conversions</p>
            <p class="text-3xl font-bold">{{ $stats['month_conversions'] }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg shadow p-6 text-white">
            <p class="text-sm opacity-80 uppercase tracking-wide">This Month Revenue</p>
            <p class="text-3xl font-bold">৳{{ number_format($stats['month_revenue'], 0) }}</p>
        </div>
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-lg shadow p-6 text-white">
            <p class="text-sm opacity-80 uppercase tracking-wide">This Month Commission</p>
            <p class="text-3xl font-bold">৳{{ number_format($stats['month_commission'], 0) }}</p>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Follow-ups -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Today's Follow-ups</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($todayFollowUps as $followUp)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900">{{ $followUp->lead->client_name }}</p>
                                <p class="text-sm text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-blue-600">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->service_interested }}</p>
                            </div>
                        </div>
                        @if($followUp->notes)
                            <p class="text-sm text-gray-600 mt-2">{{ Str::limit($followUp->notes, 100) }}</p>
                        @endif
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>No pending follow-ups for today!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Leads -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Leads</h3>
                <a href="{{ route('leads.index') }}" class="text-sm text-blue-600 hover:underline">View all →</a>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentLeads as $lead)
                    <div class="px-6 py-4 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center space-x-2">
                                    <p class="font-medium text-gray-900">{{ $lead->client_name }}</p>
                                    <span class="px-2 py-0.5 text-xs rounded-full
                                        @if($lead->source === 'WhatsApp') bg-green-100 text-green-700
                                        @elseif($lead->source === 'Messenger') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $lead->source }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">{{ $lead->phone_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">{{ $lead->lead_date->format('M j') }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    @if($lead->priority === 'High') bg-red-100 text-red-700
                                    @elseif($lead->priority === 'Medium') bg-amber-100 text-amber-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ $lead->priority }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <p>No leads yet. Start adding leads!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
