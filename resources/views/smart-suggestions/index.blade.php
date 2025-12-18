<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Smart Suggestions</h2>
                <p class="mt-1 text-sm text-gray-500">AI-powered follow-up and assignment recommendations</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Pending Follow-ups -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Suggested Follow-ups</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_follow_ups'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Unassigned Leads -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Unassigned Leads</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['unassigned_leads'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Active Sales Persons -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Active Sales Persons</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['active_sales_persons'] }}</p>
                        </div>
                    </div>
                </div>

                <!-- Average Capacity -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Avg. Team Capacity</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['avg_capacity'], 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <a href="{{ route('follow-up-rules.index') }}" class="block bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-6 text-white hover:from-purple-600 hover:to-indigo-700 transition-all shadow-lg shadow-purple-500/30">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Auto Follow-up Rules</h3>
                            <p class="text-white/80 text-sm">Configure automatic follow-up suggestions based on lead conditions</p>
                        </div>
                        <svg class="w-6 h-6 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <a href="{{ route('smart-assign.index') }}" class="block bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl p-6 text-white hover:from-blue-600 hover:to-cyan-700 transition-all shadow-lg shadow-blue-500/30">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-xl bg-white/20 flex items-center justify-center">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold">Smart Lead Assignment</h3>
                            <p class="text-white/80 text-sm">Performance-based lead assignment and workload balancing</p>
                        </div>
                        <svg class="w-6 h-6 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>
            </div>

            <!-- Two Column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Follow-up Suggestions -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 inline-block mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Suggested Follow-ups
                        </h3>
                        <a href="{{ route('follow-up-rules.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                            Manage Rules →
                        </a>
                    </div>
                    @if($followUpSuggestions->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700">No Suggestions</h4>
                            <p class="text-gray-500 mt-1">All leads are on track!</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                            @foreach($followUpSuggestions->take(5) as $suggestion)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $suggestion['lead']->lead_number }}</p>
                                            <p class="text-sm text-gray-500">{{ $suggestion['lead']->client_name ?? $suggestion['lead']->phone_number }}</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="px-2.5 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-lg">
                                                {{ $suggestion['rule']->follow_up_type ?? 'Call' }}
                                            </span>
                                            <p class="text-xs text-gray-400 mt-1">{{ $suggestion['rule']->name }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex gap-2">
                                        <a href="{{ route('leads.show', $suggestion['lead']) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                            View Lead
                                        </a>
                                        <a href="{{ route('follow-ups.quick-add', $suggestion['lead']) }}"
                                           onclick="event.preventDefault(); document.getElementById('quick-followup-{{ $suggestion['lead']->id }}').submit();"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-green-600 bg-green-50 rounded-lg hover:bg-green-100 transition-colors">
                                            Schedule Follow-up
                                        </a>
                                        <form id="quick-followup-{{ $suggestion['lead']->id }}"
                                              action="{{ route('follow-ups.quick-add', $suggestion['lead']) }}"
                                              method="POST" class="hidden">
                                            @csrf
                                            <input type="hidden" name="follow_up_type" value="{{ $suggestion['rule']->follow_up_type ?? 'Call' }}">
                                            <input type="hidden" name="notes" value="Auto-suggested by: {{ $suggestion['rule']->name }}">
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($followUpSuggestions->count() > 5)
                            <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 text-center">
                                <a href="{{ route('follow-up-rules.index') }}" class="text-sm text-purple-600 hover:text-purple-800 font-medium">
                                    View all {{ $followUpSuggestions->count() }} suggestions →
                                </a>
                            </div>
                        @endif
                    @endif
                </div>

                <!-- Assignment Recommendations -->
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-cyan-50 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 inline-block mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Top Performers
                        </h3>
                        <a href="{{ route('smart-assign.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            View All →
                        </a>
                    </div>
                    @if($assignmentRecommendations->isEmpty())
                        <div class="p-8 text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                            </div>
                            <h4 class="text-lg font-semibold text-gray-700">No Sales Persons</h4>
                            <p class="text-gray-500 mt-1">Add sales persons to see recommendations.</p>
                        </div>
                    @else
                        <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                            @foreach($assignmentRecommendations->take(5) as $index => $rec)
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-center gap-4">
                                        @if($index === 0)
                                            <span class="w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 flex items-center justify-center font-bold text-sm">🥇</span>
                                        @elseif($index === 1)
                                            <span class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center font-bold text-sm">🥈</span>
                                        @elseif($index === 2)
                                            <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">🥉</span>
                                        @else
                                            <span class="w-8 h-8 rounded-full bg-gray-50 text-gray-500 flex items-center justify-center font-semibold text-sm">{{ $index + 1 }}</span>
                                        @endif
                                        <div class="flex-1 min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">{{ $rec['user']->name }}</p>
                                            <div class="flex items-center gap-3 text-xs text-gray-500">
                                                <span>Score: {{ $rec['score'] }}</span>
                                                <span>•</span>
                                                <span>{{ $rec['workload']['active_leads'] }} active</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @php
                                                $capacity = $rec['workload']['capacity_percentage'];
                                                $capColor = $capacity >= 50 ? 'bg-green-100 text-green-700' : ($capacity >= 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-semibold {{ $capColor }} rounded-lg">
                                                {{ $capacity }}% free
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Unassigned Leads Quick Action -->
            @if($unassignedLeads->isNotEmpty())
                <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-red-50 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <svg class="w-5 h-5 inline-block mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                            Unassigned Leads ({{ $unassignedLeads->count() }})
                        </h3>
                        @if($assignmentRecommendations->isNotEmpty())
                            <form action="{{ route('smart-assign.bulk-assign') }}" method="POST" class="inline">
                                @csrf
                                @foreach($unassignedLeads as $lead)
                                    <input type="hidden" name="leads[]" value="{{ $lead->id }}">
                                @endforeach
                                <input type="hidden" name="auto" value="1">
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg shadow-blue-500/30 transition-all text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Auto-assign All
                                </button>
                            </form>
                        @endif
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($unassignedLeads as $lead)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ $lead->lead_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $lead->phone_number }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $lead->service?->name ?? $lead->service_interested }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $priorityColors = [
                                                    'High' => 'bg-red-100 text-red-700',
                                                    'Medium' => 'bg-yellow-100 text-yellow-700',
                                                    'Low' => 'bg-green-100 text-green-700',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $priorityColors[$lead->priority] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $lead->priority }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            {{ $lead->lead_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($assignmentRecommendations->isNotEmpty())
                                                <form action="{{ route('smart-assign.assign', $lead) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $assignmentRecommendations->first()['user']->id }}">
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                                        Assign to {{ $assignmentRecommendations->first()['user']->name }}
                                                    </button>
                                                </form>
                                            @else
                                                <a href="{{ route('leads.edit', $lead) }}"
                                                   class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                                    Edit Lead
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
