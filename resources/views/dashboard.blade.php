<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard - {{ now()->format('F j, Y') }}
        </h2>
    </x-slot>

    <!-- Today's Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <!-- Today's Leads -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Today's Leads</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_leads'] }}</p>
            <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}" class="text-xs text-blue-600 hover:underline">View →</a>
        </div>

        <!-- Today's Calls -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Calls Today</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_calls'] }}</p>
            <a href="{{ route('contacts.index') }}" class="text-xs text-green-600 hover:underline">View →</a>
        </div>

        <!-- Today's Meetings -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Meetings Today</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_meetings'] }}</p>
            <a href="{{ route('meetings.index') }}" class="text-xs text-indigo-600 hover:underline">View →</a>
        </div>

        <!-- Pending Follow-ups -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-amber-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Pending Follow-ups</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_follow_ups'] }}</p>
            <a href="{{ route('follow-ups.index') }}" class="text-xs text-amber-600 hover:underline">View →</a>
        </div>

        <!-- Today's Conversions -->
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-purple-500">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Conversions</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_conversions'] }}</p>
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow p-4 text-white">
            <p class="text-xs opacity-80 uppercase">Month Leads</p>
            <p class="text-2xl font-bold">{{ $stats['month_leads'] }}</p>
        </div>
        <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow p-4 text-white">
            <p class="text-xs opacity-80 uppercase">Month Calls</p>
            <p class="text-2xl font-bold">{{ $stats['month_calls'] }}</p>
        </div>
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-lg shadow p-4 text-white">
            <p class="text-xs opacity-80 uppercase">Conversions</p>
            <p class="text-2xl font-bold">{{ $stats['month_conversions'] }}</p>
        </div>
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-lg shadow p-4 text-white">
            <p class="text-xs opacity-80 uppercase">Revenue</p>
            <p class="text-2xl font-bold">৳{{ number_format($stats['month_revenue'], 0) }}</p>
        </div>
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 rounded-lg shadow p-4 text-white">
            <p class="text-xs opacity-80 uppercase">Commission</p>
            <p class="text-2xl font-bold">৳{{ number_format($stats['month_commission'], 0) }}</p>
        </div>
    </div>

    <!-- Advanced Analytics Section -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">📊 Performance Analytics (This Month)</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-6">
                <!-- Conversion Rate -->
                <div class="text-center">
                    <div class="relative inline-flex items-center justify-center w-20 h-20">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="36" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="40" cy="40" r="36" stroke="#10b981" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 226 * ($analytics['conversion_rate'] / 100) }} 226"
                                stroke-linecap="round"/>
                        </svg>
                        <span class="absolute text-lg font-bold text-gray-900">{{ $analytics['conversion_rate'] }}%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Conversion Rate</p>
                </div>

                <!-- Response Rate -->
                <div class="text-center">
                    <div class="relative inline-flex items-center justify-center w-20 h-20">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="36" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="40" cy="40" r="36" stroke="#3b82f6" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 226 * ($analytics['response_rate'] / 100) }} 226"
                                stroke-linecap="round"/>
                        </svg>
                        <span class="absolute text-lg font-bold text-gray-900">{{ $analytics['response_rate'] }}%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Positive Response</p>
                </div>

                <!-- Follow-up Completion -->
                <div class="text-center">
                    <div class="relative inline-flex items-center justify-center w-20 h-20">
                        <svg class="w-20 h-20 transform -rotate-90">
                            <circle cx="40" cy="40" r="36" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="40" cy="40" r="36" stroke="#f59e0b" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 226 * ($analytics['follow_up_completion_rate'] / 100) }} 226"
                                stroke-linecap="round"/>
                        </svg>
                        <span class="absolute text-lg font-bold text-gray-900">{{ $analytics['follow_up_completion_rate'] }}%</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Follow-up Done</p>
                </div>

                <!-- Calls per Lead -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mx-auto">
                        <span class="text-2xl font-bold text-gray-900">{{ $analytics['calls_per_lead'] }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Calls/Lead</p>
                </div>

                <!-- Avg Deal Value -->
                <div class="text-center">
                    <div class="flex items-center justify-center w-20 h-20 bg-purple-100 rounded-full mx-auto">
                        <span class="text-lg font-bold text-purple-700">৳{{ number_format($analytics['avg_deal_value'], 0) }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">Avg Deal Value</p>
                </div>
            </div>

            <!-- Lead Status & Source Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6 pt-6 border-t">
                <!-- Status Breakdown -->
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Lead Status Breakdown</h4>
                    <div class="space-y-2">
                        @php
                            $statusColors = [
                                'New' => 'gray',
                                'Contacted' => 'blue',
                                'Qualified' => 'indigo',
                                'Negotiation' => 'orange',
                                'Converted' => 'green',
                                'Lost' => 'red'
                            ];
                            $total = max($analytics['total_leads'], 1);
                        @endphp
                        @foreach(['New', 'Contacted', 'Qualified', 'Negotiation', 'Converted', 'Lost'] as $status)
                            @php $count = $analytics['status_breakdown'][$status] ?? 0; @endphp
                            <div class="flex items-center gap-2">
                                <span class="w-24 text-sm text-gray-600">{{ $status }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $statusColors[$status] ?? 'gray' }}-500 h-2 rounded-full" style="width: {{ ($count / $total) * 100 }}%"></div>
                                </div>
                                <span class="w-8 text-sm text-gray-600 text-right">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Source Breakdown -->
                <div>
                    <h4 class="font-medium text-gray-700 mb-3">Lead Source Breakdown</h4>
                    <div class="space-y-2">
                        @php $sourceColors = ['WhatsApp' => 'green', 'Messenger' => 'blue', 'Website' => 'purple', 'Referral' => 'amber', 'Phone' => 'gray']; @endphp
                        @foreach($analytics['source_breakdown'] as $source => $count)
                            @php $total = max($analytics['total_leads'], 1); @endphp
                            <div class="flex items-center gap-2">
                                <span class="w-20 text-sm text-gray-600">{{ $source }}</span>
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-{{ $sourceColors[$source] ?? 'gray' }}-500 h-2 rounded-full" style="width: {{ ($count / $total) * 100 }}%"></div>
                                </div>
                                <span class="w-8 text-sm text-gray-600 text-right">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Response Breakdown -->
    @if(count($responseBreakdown) > 0)
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">📞 Today's Call Responses</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @php
                    $responseColors = [
                        'Yes' => 'bg-green-500',
                        'Interested' => 'bg-green-600',
                        '50%' => 'bg-yellow-500',
                        'Call Later' => 'bg-blue-500',
                        'No Response' => 'bg-gray-400',
                        'No' => 'bg-red-500',
                        'Phone off' => 'bg-gray-500',
                    ];
                @endphp
                @foreach($responseBreakdown as $status => $count)
                    <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg">
                        <span class="w-3 h-3 rounded-full {{ $responseColors[$status] ?? 'bg-gray-400' }}"></span>
                        <span class="text-sm text-gray-700">{{ $status }}</span>
                        <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Three Column Layout for Pending Items -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Overdue Follow-ups -->
        @if(isset($overdueFollowUps) && $overdueFollowUps->count() > 0)
        <div class="bg-white rounded-lg shadow border-t-4 border-red-500">
            <div class="px-4 py-3 border-b border-gray-200 bg-red-50">
                <h3 class="font-semibold text-red-800">⚠️ Overdue Follow-ups</h3>
            </div>
            <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @foreach($overdueFollowUps as $followUp)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="block px-4 py-3 hover:bg-red-50">
                        <p class="font-medium text-gray-900 text-sm">{{ $followUp->lead->client_name }}</p>
                        <p class="text-xs text-red-600">Due: {{ $followUp->follow_up_date->format('M j') }}</p>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Today's Follow-ups -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900">📋 Today's Follow-ups</h3>
                <a href="{{ route('follow-ups.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @forelse($todayFollowUps as $followUp)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="block px-4 py-3 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </div>
                            <span class="text-xs font-medium text-blue-600">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</span>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-6 text-center text-gray-500 text-sm">
                        ✅ No follow-ups for today!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Meetings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900">📅 Today's Meetings</h3>
                <a href="{{ route('meetings.index') }}" class="text-xs text-blue-600 hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-200 max-h-64 overflow-y-auto">
                @forelse($todayMeetings as $meeting)
                    <a href="{{ route('leads.show', $meeting->lead) }}" class="block px-4 py-3 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $meeting->lead->client_name }}</p>
                                <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-medium text-indigo-600">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                                <p class="text-xs {{ $meeting->outcome === 'Pending' ? 'text-gray-500' : ($meeting->outcome === 'Successful' ? 'text-green-600' : 'text-red-600') }}">{{ $meeting->outcome }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-6 text-center text-gray-500 text-sm">
                        📅 No meetings scheduled for today
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Leads -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900">🆕 Recent Leads</h3>
            <a href="{{ route('leads.index') }}" class="text-sm text-blue-600 hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-200">
            @forelse($recentLeads as $lead)
                <a href="{{ route('leads.show', $lead) }}" class="block px-6 py-4 hover:bg-gray-50">
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
                                <span class="px-2 py-0.5 text-xs rounded-full
                                    @if($lead->status === 'New') bg-gray-100 text-gray-700
                                    @elseif($lead->status === 'Contacted') bg-blue-100 text-blue-700
                                    @elseif($lead->status === 'Qualified') bg-indigo-100 text-indigo-700
                                    @elseif($lead->status === 'Negotiation') bg-orange-100 text-orange-700
                                    @elseif($lead->status === 'Converted') bg-green-100 text-green-700
                                    @elseif($lead->status === 'Lost') bg-red-100 text-red-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ $lead->status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500">{{ $lead->phone_number }} • {{ $lead->service_interested }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ $lead->lead_date->format('M j, Y') }}</p>
                            @if($lead->assignedTo)
                                <p class="text-xs text-gray-500">{{ $lead->assignedTo->name }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">
                    <p>No leads yet. Start adding leads!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
