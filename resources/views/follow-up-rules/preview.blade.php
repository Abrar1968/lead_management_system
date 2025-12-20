<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Preview: {{ $followUpRule->name }}</h2>
                <p class="mt-1 text-sm text-gray-500">Leads matching this rule's conditions</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('follow-up-rules.edit', $followUpRule) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Rule
                </a>
                <a href="{{ route('follow-up-rules.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Rules
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            <!-- Rule Summary -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-6">
                <div class="flex flex-wrap items-center gap-4 mb-4">
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold {{ $followUpRule->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                        @if($followUpRule->is_active)
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        {{ $followUpRule->is_active ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">
                        Priority: {{ $followUpRule->priority }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-semibold bg-purple-100 text-purple-700">
                        Logic: {{ $followUpRule->logic_type }}
                    </span>
                </div>

                @if($followUpRule->description)
                    <p class="text-gray-600 text-sm mb-4">{{ $followUpRule->description }}</p>
                @endif

                <div class="border-t border-gray-100 pt-4">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Conditions:</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($followUpRule->conditions as $condition)
                            <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                <span class="font-semibold">{{ str_replace('_', ' ', ucfirst($condition->field)) }}</span>
                                <span class="mx-1 text-gray-400">{{ str_replace('_', ' ', $condition->operator) }}</span>
                                @if(!in_array($condition->operator, ['is_null', 'is_not_null']))
                                    <span class="font-semibold text-blue-600">{{ is_array($condition->value) ? implode(', ', $condition->value) : $condition->value }}</span>
                                @endif
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Matching Leads -->
            <div class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-indigo-50">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Matching Leads
                        <span class="ml-2 px-2 py-0.5 text-sm font-bold bg-purple-100 text-purple-700 rounded-full">
                            {{ $matchingLeads->count() }}
                        </span>
                    </h3>
                </div>

                @if($matchingLeads->isEmpty())
                    <div class="p-12 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-700">No Matching Leads</h4>
                        <p class="text-gray-500 mt-1">No leads currently match this rule's conditions.</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lead</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Last Contact</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($matchingLeads as $lead)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ $lead->lead_number }}</div>
                                            <div class="text-sm text-gray-500">{{ $lead->lead_date->format('M d, Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $lead->client_name ?: 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $lead->phone_number }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $statusColors = [
                                                    'New' => 'bg-blue-100 text-blue-700',
                                                    'Contacted' => 'bg-yellow-100 text-yellow-700',
                                                    'Qualified' => 'bg-purple-100 text-purple-700',
                                                    'Negotiation' => 'bg-orange-100 text-orange-700',
                                                    'Converted' => 'bg-green-100 text-green-700',
                                                    'Lost' => 'bg-red-100 text-red-700',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-semibold {{ $statusColors[$lead->status] ?? 'bg-gray-100 text-gray-700' }}">
                                                {{ $lead->status }}
                                            </span>
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
                                        <td class="px-6 py-4">
                                            @if($lead->contacts->isNotEmpty())
                                                @php $lastContact = $lead->contacts->sortByDesc('call_date')->first(); @endphp
                                                <div class="text-sm text-gray-900">{{ $lastContact->call_date->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $lastContact->response_status }}</div>
                                            @else
                                                <span class="text-sm text-gray-400">No contacts yet</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('leads.show', $lead) }}"
                                               class="inline-flex items-center gap-1 px-3 py-1.5 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                View
                                            </a>
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
