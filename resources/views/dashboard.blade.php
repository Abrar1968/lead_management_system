<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-2xl text-gray-900 leading-tight">
                    Dashboard
                </h2>
                <p class="text-sm text-gray-500 mt-1">{{ now()->format('l, F j, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('leads.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-600 hover:to-indigo-700 shadow-lg shadow-blue-500/25 hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-200 hover:-translate-y-0.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Lead
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Today's Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <!-- Today's Leads -->
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-5 border border-gray-100 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-blue-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Today's Leads</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_leads'] }}</p>
                <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}" class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-700 font-medium mt-2 group-hover:gap-2 transition-all">
                    View all <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Today's Calls -->
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-5 border border-gray-100 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-emerald-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Calls Today</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_calls'] }}</p>
                <a href="{{ route('contacts.index') }}" class="inline-flex items-center gap-1 text-xs text-emerald-600 hover:text-emerald-700 font-medium mt-2 group-hover:gap-2 transition-all">
                    View all <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Today's Meetings -->
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-5 border border-gray-100 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-indigo-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Meetings Today</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_meetings'] }}</p>
                <a href="{{ route('meetings.index') }}" class="inline-flex items-center gap-1 text-xs text-indigo-600 hover:text-indigo-700 font-medium mt-2 group-hover:gap-2 transition-all">
                    View all <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Pending Follow-ups -->
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-5 border border-gray-100 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Pending Follow-ups</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['pending_follow_ups'] }}</p>
                <a href="{{ route('follow-ups.index') }}" class="inline-flex items-center gap-1 text-xs text-amber-600 hover:text-amber-700 font-medium mt-2 group-hover:gap-2 transition-all">
                    View all <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>

        <!-- Today's Conversions -->
        <div class="group bg-white rounded-2xl shadow-sm hover:shadow-xl p-5 border border-gray-100 relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-purple-500/10 to-transparent rounded-bl-full"></div>
            <div class="relative">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-3 shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <p class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Conversions</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_conversions'] }}</p>
                <span class="inline-flex items-center text-xs text-purple-600 font-medium mt-2">Today's wins 🎉</span>
            </div>
        </div>
    </div>

    <!-- Monthly Stats -->
    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="group bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-600 rounded-2xl shadow-lg shadow-blue-500/25 p-5 text-white relative overflow-hidden hover:shadow-xl hover:shadow-blue-500/30 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative">
                <p class="text-xs text-white/70 uppercase tracking-wider font-semibold">Month Leads</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['month_leads'] }}</p>
            </div>
        </div>
        <div class="group bg-gradient-to-br from-emerald-500 via-green-500 to-teal-600 rounded-2xl shadow-lg shadow-emerald-500/25 p-5 text-white relative overflow-hidden hover:shadow-xl hover:shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative">
                <p class="text-xs text-white/70 uppercase tracking-wider font-semibold">Month Calls</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['month_calls'] }}</p>
            </div>
        </div>
        <div class="group bg-gradient-to-br from-indigo-500 via-purple-500 to-violet-600 rounded-2xl shadow-lg shadow-indigo-500/25 p-5 text-white relative overflow-hidden hover:shadow-xl hover:shadow-indigo-500/30 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative">
                <p class="text-xs text-white/70 uppercase tracking-wider font-semibold">Conversions</p>
                <p class="text-3xl font-bold mt-1">{{ $stats['month_conversions'] }}</p>
            </div>
        </div>
        <div class="group bg-gradient-to-br from-purple-500 via-pink-500 to-rose-500 rounded-2xl shadow-lg shadow-purple-500/25 p-5 text-white relative overflow-hidden hover:shadow-xl hover:shadow-purple-500/30 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative">
                <p class="text-xs text-white/70 uppercase tracking-wider font-semibold">Revenue</p>
                <p class="text-3xl font-bold mt-1">৳{{ number_format($stats['month_revenue'], 0) }}</p>
            </div>
        </div>
        <div class="group bg-gradient-to-br from-amber-500 via-orange-500 to-red-500 rounded-2xl shadow-lg shadow-amber-500/25 p-5 text-white relative overflow-hidden hover:shadow-xl hover:shadow-amber-500/30 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute -top-4 -right-4 w-24 h-24 bg-white/10 rounded-full blur-xl"></div>
            <div class="relative">
                <p class="text-xs text-white/70 uppercase tracking-wider font-semibold">Commission</p>
                <p class="text-3xl font-bold mt-1">৳{{ number_format($stats['month_commission'], 0) }}</p>
            </div>
        </div>
    </div>

    <!-- Advanced Analytics Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
                Performance Analytics
                <span class="text-sm font-normal text-gray-500">(This Month)</span>
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-8">
                <!-- Conversion Rate -->
                <div class="text-center group">
                    <div class="relative inline-flex items-center justify-center w-24 h-24 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-24 h-24 transform -rotate-90">
                            <circle cx="48" cy="48" r="42" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="48" cy="48" r="42" stroke="url(#gradient-green)" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 264 * ($analytics['conversion_rate'] / 100) }} 264"
                                stroke-linecap="round" class="transition-all duration-1000"/>
                        </svg>
                        <span class="absolute text-xl font-bold text-gray-900">{{ $analytics['conversion_rate'] }}%</span>
                        <defs>
                            <linearGradient id="gradient-green" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#10b981"/>
                                <stop offset="100%" style="stop-color:#059669"/>
                            </linearGradient>
                        </defs>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mt-3">Conversion Rate</p>
                </div>

                <!-- Response Rate -->
                <div class="text-center group">
                    <div class="relative inline-flex items-center justify-center w-24 h-24 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-24 h-24 transform -rotate-90">
                            <circle cx="48" cy="48" r="42" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="48" cy="48" r="42" stroke="url(#gradient-blue)" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 264 * ($analytics['response_rate'] / 100) }} 264"
                                stroke-linecap="round" class="transition-all duration-1000"/>
                        </svg>
                        <span class="absolute text-xl font-bold text-gray-900">{{ $analytics['response_rate'] }}%</span>
                        <defs>
                            <linearGradient id="gradient-blue" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#3b82f6"/>
                                <stop offset="100%" style="stop-color:#6366f1"/>
                            </linearGradient>
                        </defs>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mt-3">Positive Response</p>
                </div>

                <!-- Follow-up Completion -->
                <div class="text-center group">
                    <div class="relative inline-flex items-center justify-center w-24 h-24 group-hover:scale-105 transition-transform duration-300">
                        <svg class="w-24 h-24 transform -rotate-90">
                            <circle cx="48" cy="48" r="42" stroke="#e5e7eb" stroke-width="8" fill="none"/>
                            <circle cx="48" cy="48" r="42" stroke="url(#gradient-amber)" stroke-width="8" fill="none"
                                stroke-dasharray="{{ 264 * ($analytics['follow_up_completion_rate'] / 100) }} 264"
                                stroke-linecap="round" class="transition-all duration-1000"/>
                        </svg>
                        <span class="absolute text-xl font-bold text-gray-900">{{ $analytics['follow_up_completion_rate'] }}%</span>
                        <defs>
                            <linearGradient id="gradient-amber" x1="0%" y1="0%" x2="100%" y2="0%">
                                <stop offset="0%" style="stop-color:#f59e0b"/>
                                <stop offset="100%" style="stop-color:#d97706"/>
                            </linearGradient>
                        </defs>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mt-3">Follow-up Done</p>
                </div>

                <!-- Calls per Lead -->
                <div class="text-center group">
                    <div class="flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl mx-auto shadow-inner group-hover:scale-105 transition-transform duration-300">
                        <span class="text-3xl font-bold text-gray-900">{{ $analytics['calls_per_lead'] }}</span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mt-3">Calls/Lead</p>
                </div>

                <!-- Avg Deal Value -->
                <div class="text-center group">
                    <div class="flex items-center justify-center w-24 h-24 bg-gradient-to-br from-purple-100 to-pink-50 rounded-2xl mx-auto shadow-inner group-hover:scale-105 transition-transform duration-300">
                        <span class="text-lg font-bold text-purple-700">৳{{ number_format($analytics['avg_deal_value'], 0) }}</span>
                    </div>
                    <p class="text-sm font-medium text-gray-600 mt-3">Avg Deal Value</p>
                </div>
            </div>

            <!-- Lead Status & Source Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8 pt-8 border-t border-gray-100">
                <!-- Status Breakdown -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                        Lead Status Breakdown
                    </h4>
                    <div class="space-y-3">
                        @php
                            $leadStatuses = \App\Models\Lead::STATUSES;
                            $total = max($analytics['total_leads'], 1);
                        @endphp
                        @foreach($leadStatuses as $status => $config)
                            @php $count = $analytics['status_breakdown'][$status] ?? 0; @endphp
                            <div class="flex items-center gap-3 group">
                                <span class="w-24 text-sm text-gray-600 font-medium">{{ $status }}</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-{{ $config['color'] }}-500 to-{{ $config['color'] }}-400 h-2.5 rounded-full transition-all duration-500 group-hover:opacity-80" style="width: {{ ($count / $total) * 100 }}%"></div>
                                </div>
                                <span class="w-10 text-sm font-semibold text-gray-700 text-right">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Source Breakdown -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-4 flex items-center gap-2">
                        <span class="w-2 h-2 bg-emerald-500 rounded-full"></span>
                        Lead Source Breakdown
                    </h4>
                    <div class="space-y-3">
                        @php $sourceColors = ['WhatsApp' => 'green', 'Messenger' => 'blue', 'Website' => 'purple', 'Referral' => 'amber', 'Phone' => 'gray']; @endphp
                        @foreach($analytics['source_breakdown'] as $source => $count)
                            @php $total = max($analytics['total_leads'], 1); @endphp
                            <div class="flex items-center gap-3 group">
                                <span class="w-24 text-sm text-gray-600 font-medium">{{ $source }}</span>
                                <div class="flex-1 bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-gradient-to-r from-{{ $sourceColors[$source] ?? 'gray' }}-500 to-{{ $sourceColors[$source] ?? 'gray' }}-400 h-2.5 rounded-full transition-all duration-500 group-hover:opacity-80" style="width: {{ ($count / $total) * 100 }}%"></div>
                                </div>
                                <span class="w-10 text-sm font-semibold text-gray-700 text-right">{{ $count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Response Breakdown -->
    @if(count($responseBreakdown) > 0)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 mb-8 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </span>
                Today's Call Responses
            </h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                @php
                    $responseColors = [
                        'Yes' => 'from-green-500 to-emerald-500',
                        'Interested' => 'from-emerald-500 to-teal-500',
                        '50%' => 'from-yellow-400 to-amber-500',
                        'Call Later' => 'from-blue-500 to-indigo-500',
                        'No Response' => 'from-gray-400 to-gray-500',
                        'No' => 'from-red-500 to-rose-500',
                        'Phone off' => 'from-gray-500 to-slate-500',
                    ];
                @endphp
                @foreach($responseBreakdown as $status => $count)
                    <div class="flex items-center gap-2 px-4 py-2.5 bg-gray-50 rounded-xl border border-gray-100 hover:shadow-md transition-all duration-200 group">
                        <span class="w-3 h-3 rounded-full bg-gradient-to-br {{ $responseColors[$status] ?? 'from-gray-400 to-gray-500' }} shadow-sm"></span>
                        <span class="text-sm text-gray-700 font-medium">{{ $status }}</span>
                        <span class="text-sm font-bold text-gray-900 bg-white px-2 py-0.5 rounded-lg shadow-sm">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Three Column Layout for Pending Items -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Overdue Follow-ups -->
        @if(isset($overdueFollowUps) && $overdueFollowUps->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-red-100 bg-gradient-to-r from-red-50 to-rose-50">
                <h3 class="font-bold text-red-800 flex items-center gap-2">
                    <span class="w-7 h-7 bg-gradient-to-br from-red-500 to-rose-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </span>
                    Overdue Follow-ups
                </h3>
            </div>
            <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                @foreach($overdueFollowUps as $followUp)
                    @if($followUp->lead)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="block px-5 py-4 hover:bg-red-50/50 transition-colors group">
                        <p class="font-semibold text-gray-900 text-sm group-hover:text-red-700 transition-colors">{{ $followUp->lead->client_name }}</p>
                        <p class="text-xs text-red-600 mt-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Due: {{ $followUp->follow_up_date->format('M j') }}
                        </p>
                    </a>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Today's Follow-ups -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-7 h-7 bg-gradient-to-br from-amber-500 to-orange-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </span>
                    Today's Follow-ups
                </h3>
                <a href="{{ route('follow-ups.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                @forelse($todayFollowUps as $followUp)
                    @if($followUp->lead)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="block px-5 py-4 hover:bg-amber-50/50 transition-colors group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm group-hover:text-amber-700 transition-colors">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $followUp->lead->phone_number }}</p>
                            </div>
                            <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-lg">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</span>
                        </div>
                    </a>
                    @endif
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No follow-ups for today!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Today's Meetings -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center bg-gradient-to-r from-gray-50 to-white">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <span class="w-7 h-7 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </span>
                    Today's Meetings
                </h3>
                <a href="{{ route('meetings.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold hover:underline">View all</a>
            </div>
            <div class="divide-y divide-gray-100 max-h-72 overflow-y-auto">
                @forelse($todayMeetings as $meeting)
                    @if($meeting->lead)
                    <a href="{{ route('leads.show', $meeting->lead) }}" class="block px-5 py-4 hover:bg-indigo-50/50 transition-colors group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm group-hover:text-indigo-700 transition-colors">{{ $meeting->lead->client_name }}</p>
                                <span class="inline-flex items-center text-xs px-2 py-0.5 rounded-lg bg-purple-100 text-purple-700 mt-1">{{ $meeting->meeting_type }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded-lg">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                                <p class="text-xs mt-1 {{ $meeting->outcome === 'Pending' ? 'text-gray-500' : ($meeting->outcome === 'Successful' ? 'text-green-600' : 'text-red-600') }} font-medium">{{ $meeting->outcome }}</p>
                            </div>
                        </div>
                    </a>
                    @endif
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <p class="text-gray-500 text-sm font-medium">No meetings scheduled</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Leads -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white">
            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                <span class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                Recent Leads
            </h3>
            <a href="{{ route('leads.index') }}" class="inline-flex items-center gap-1 text-sm text-blue-600 hover:text-blue-700 font-semibold group">
                View all <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentLeads as $lead)
                <a href="{{ route('leads.show', $lead) }}" class="block px-6 py-4 hover:bg-gray-50/50 transition-colors group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-100 to-gray-50 rounded-xl flex items-center justify-center group-hover:from-blue-100 group-hover:to-indigo-50 transition-all">
                                <span class="text-sm font-bold text-gray-600 group-hover:text-blue-600 transition-colors">{{ substr($lead->client_name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors">{{ $lead->client_name }}</p>
                                    <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
                                        @if($lead->source === 'WhatsApp') bg-green-100 text-green-700
                                        @elseif($lead->source === 'Messenger') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $lead->source }}
                                    </span>
                                    <span class="px-2.5 py-0.5 text-xs rounded-full font-medium
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
                                <p class="text-sm text-gray-500 mt-0.5">{{ $lead->phone_number }} • {{ $lead->service_interested }}</p>
                            </div>
                        </div>
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-600">{{ $lead->lead_date->format('M j, Y') }}</p>
                            @if($lead->assignedTo)
                                <p class="text-xs text-gray-500">{{ $lead->assignedTo->name }}</p>
                            @endif
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium">No leads yet</p>
                    <a href="{{ route('leads.create') }}" class="inline-flex items-center gap-2 mt-4 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-xl hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add your first lead
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
