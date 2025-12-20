<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30 text-lg font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-500">Performance Dashboard</p>
                </div>
            </div>
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    {{-- Month Filter --}}
    <div class="mb-6">
        <form method="GET" class="flex items-center gap-4">
            <input type="month" name="month" value="{{ $month }}"
                   class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
            <button type="submit" class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                View Period
            </button>
        </form>
    </div>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- User Info Card --}}
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    User Information
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600 text-2xl font-bold text-white shadow-lg shadow-purple-500/30">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-bold text-gray-900">{{ $user->name }}</h4>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="space-y-4 border-t border-gray-100 pt-5">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Role</span>
                        <span class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-bold shadow-sm
                            {{ $user->role === 'admin'
                                ? 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow-purple-500/30'
                                : 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-blue-500/30' }}">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Commission Type</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ ucfirst($user->commission_type) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Commission Rate</span>
                        <span class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-800">
                            @if($user->commission_type === 'fixed')
                                ৳{{ number_format($user->default_commission_rate) }}
                            @else
                                {{ $user->default_commission_rate }}%
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-500">Member Since</span>
                        <span class="text-sm font-bold text-gray-900">
                            {{ $user->created_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 pt-5 border-t border-gray-100">
                    <a href="{{ route('users.edit', $user) }}"
                       class="flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit User
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Monthly Stats --}}
            <div class="grid gap-4 sm:grid-cols-4">
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-gray-500 to-gray-600 text-white shadow-lg shadow-gray-500/30">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Leads</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($monthlyLeads) }}</p>
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
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ number_format($monthlyConversions) }}</p>
                </div>
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Deal Value</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">৳{{ number_format($monthlyDealValue) }}</p>
                </div>
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/30">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                    </div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Commission</p>
                    <p class="text-2xl font-bold text-amber-600 mt-1">৳{{ number_format($monthlyCommission) }}</p>
                </div>
            </div>

            {{-- Commission Breakdown --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Commission Breakdown
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid gap-6 sm:grid-cols-3">
                        <div class="text-center p-4 rounded-xl bg-gradient-to-br from-emerald-50 to-green-50 border border-emerald-100">
                            <p class="text-xs font-semibold text-emerald-600 uppercase tracking-wider">From Conversions</p>
                            <p class="text-2xl font-bold text-emerald-700 mt-2">৳{{ number_format($breakdown['conversion_commission']) }}</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-gradient-to-br from-purple-50 to-indigo-50 border border-purple-100">
                            <p class="text-xs font-semibold text-purple-600 uppercase tracking-wider">Extra Commissions</p>
                            <p class="text-2xl font-bold text-purple-700 mt-2">৳{{ number_format($breakdown['extra_commission']) }}</p>
                        </div>
                        <div class="text-center p-4 rounded-xl bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-100">
                            <p class="text-xs font-semibold text-amber-600 uppercase tracking-wider">Total Earned</p>
                            <p class="text-2xl font-bold text-amber-700 mt-2">৳{{ number_format($breakdown['total']) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Leads --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h3 class="font-semibold text-white flex items-center gap-2">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Recent Leads
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Lead</th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Customer</th>
                                <th class="px-5 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                                <th class="px-5 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($recentLeads as $lead)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="whitespace-nowrap px-5 py-4 text-sm">
                                        <a href="{{ route('leads.show', $lead) }}" class="font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                            {{ $lead->lead_number }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-900">
                                        {{ $lead->client_name }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500">
                                        {{ $lead->lead_date->format('M d, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-5 py-4 text-center">
                                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                            @switch($lead->status)
                                                @case('New') bg-gray-200 text-gray-800 @break
                                                @case('Contacted') bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-blue-500/30 @break
                                                @case('Qualified') bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-indigo-500/30 @break
                                                @case('Negotiation') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                                @case('Converted') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                                @case('Lost') bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-red-500/30 @break
                                                @default bg-gray-200 text-gray-800
                                            @endswitch">
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-12 text-center">
                                        <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-500">No leads assigned yet</p>
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
