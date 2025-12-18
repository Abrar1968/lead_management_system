<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Smart Lead Assignment</h2>
                <p class="mt-1 text-sm text-gray-500">Performance-based lead assignment recommendations</p>
            </div>
            <div class="flex gap-3">
                <form action="{{ route('smart-assign.recalculate') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 text-purple-600 bg-purple-50 rounded-xl hover:bg-purple-100 font-semibold transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Recalculate
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Settings Panel -->
            <div x-data="{ open: false }" class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <button @click="open = !open" class="w-full px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-semibold text-gray-900">Assignment Settings</span>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 transform transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div x-show="open" x-collapse class="border-t border-gray-100 px-6 py-4">
                    <form action="{{ route('smart-assign.settings') }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Assignment Mode</label>
                                <select name="assignment_mode" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="performance" {{ $settings['assignment_mode'] === 'performance' ? 'selected' : '' }}>Best Performer</option>
                                    <option value="balanced" {{ $settings['assignment_mode'] === 'balanced' ? 'selected' : '' }}>Balanced (Score + Workload)</option>
                                    <option value="round_robin" {{ $settings['assignment_mode'] === 'round_robin' ? 'selected' : '' }}>Round Robin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Max Active Leads</label>
                                <input type="number" name="max_active_leads" value="{{ $settings['max_active_leads'] }}" min="1" max="100"
                                       class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-end">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="auto_assign_enabled" value="1" {{ $settings['auto_assign_enabled'] ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="text-sm font-semibold text-gray-700">Auto-assign enabled</span>
                                </label>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Scoring Weights (must sum to 100)</label>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                @foreach($settings['scoring_weights'] as $key => $value)
                                    <div>
                                        <label class="block text-xs text-gray-500 mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                        <input type="number" name="scoring_weights[{{ $key }}]" value="{{ $value }}" min="0" max="100"
                                               class="w-full rounded-lg border-gray-300 text-sm focus:border-blue-500 focus:ring-blue-500">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold transition-colors">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sales Person Rankings -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-blue-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-gray-900">Sales Person Performance Rankings</h3>
                </div>
                @if($recommendations->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-700">No Active Sales Persons</h4>
                        <p class="text-gray-500 mt-1">Add sales persons to see recommendations.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rank</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Sales Person</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Score</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Conversion Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Response Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Active Leads</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Capacity</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($recommendations as $index => $rec)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            @if($index === 0)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-yellow-100 text-yellow-700 font-bold">
                                                    🥇
                                                </span>
                                            @elseif($index === 1)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 text-gray-600 font-bold">
                                                    🥈
                                                </span>
                                            @elseif($index === 2)
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-orange-100 text-orange-600 font-bold">
                                                    🥉
                                                </span>
                                            @else
                                                <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-50 text-gray-500 font-semibold">
                                                    {{ $index + 1 }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ $rec['user']->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $rec['user']->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                                    <div class="h-2 rounded-full {{ $rec['score'] >= 70 ? 'bg-green-500' : ($rec['score'] >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}"
                                                         style="width: {{ $rec['score'] }}%"></div>
                                                </div>
                                                <span class="font-semibold text-gray-900">{{ $rec['score'] }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-900">{{ $rec['metrics']['conversion_rate'] ?? 0 }}%</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-900">{{ $rec['metrics']['response_rate'] ?? 0 }}%</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-medium text-gray-900">{{ $rec['workload']['active_leads'] }} / {{ $rec['workload']['max_leads'] }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $capacity = $rec['workload']['capacity_percentage'];
                                                $capacityColor = $capacity >= 50 ? 'bg-green-100 text-green-700' : ($capacity >= 20 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $capacityColor }}">
                                                {{ $capacity }}% Available
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Unassigned Leads -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-orange-50 to-red-50 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Unassigned Leads
                        <span class="ml-2 px-2 py-0.5 text-sm font-bold bg-orange-100 text-orange-700 rounded-full">
                            {{ $unassignedLeads->count() }}
                        </span>
                    </h3>
                    @if($unassignedLeads->isNotEmpty())
                        <form action="{{ route('smart-assign.bulk-assign') }}" method="POST" class="inline">
                            @csrf
                            @foreach($unassignedLeads as $lead)
                                <input type="hidden" name="leads[]" value="{{ $lead->id }}">
                            @endforeach
                            <input type="hidden" name="auto" value="1">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg shadow-blue-500/30 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Auto-assign All
                            </button>
                        </form>
                    @endif
                </div>

                @if($unassignedLeads->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-green-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-700">All Leads Assigned!</h4>
                        <p class="text-gray-500 mt-1">No leads are currently waiting for assignment.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Recommended</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" x-data>
                                @foreach($unassignedLeads as $lead)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ $lead->lead_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $lead->phone_number }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-gray-700">{{ $lead->service?->name ?? $lead->service_interested }}</span>
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
                                            @if($recommendations->isNotEmpty())
                                                <span class="text-sm font-medium text-blue-600">{{ $recommendations->first()['user']->name }}</span>
                                            @else
                                                <span class="text-sm text-gray-400">N/A</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($recommendations->isNotEmpty())
                                                <form action="{{ route('smart-assign.assign', $lead) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $recommendations->first()['user']->id }}">
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                        Assign
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
