<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                {{ $user->name }} - Performance
            </h2>
            <a href="{{ route('users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Month Filter --}}
            <div class="mb-6">
                <form method="GET" class="flex items-center gap-4">
                    <input type="month" name="month" value="{{ $month }}"
                           class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        View
                    </button>
                </form>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- User Info Card --}}
                <div class="overflow-hidden rounded-lg bg-white shadow">
                    <div class="border-b bg-gray-50 px-6 py-4">
                        <h3 class="text-sm font-semibold text-gray-900">User Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-xl font-bold text-indigo-600">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $user->name }}</h4>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>

                        <div class="space-y-3 border-t pt-4">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Role</span>
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Commission Type</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($user->commission_type) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Commission Rate</span>
                                <span class="text-sm font-medium text-gray-900">
                                    @if($user->commission_type === 'fixed')
                                        ৳{{ number_format($user->default_commission_rate) }}
                                    @else
                                        {{ $user->default_commission_rate }}%
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Member Since</span>
                                <span class="text-sm font-medium text-gray-900">
                                    {{ $user->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t">
                            <a href="{{ route('users.edit', $user) }}"
                               class="block w-full rounded-md bg-indigo-600 px-4 py-2 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                                Edit User
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Monthly Stats --}}
                    <div class="grid gap-4 sm:grid-cols-4">
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm font-medium text-gray-500">Leads This Month</p>
                            <p class="mt-1 text-2xl font-bold text-gray-900">{{ number_format($monthlyLeads) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm font-medium text-gray-500">Conversions</p>
                            <p class="mt-1 text-2xl font-bold text-green-600">{{ number_format($monthlyConversions) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm font-medium text-gray-500">Deal Value</p>
                            <p class="mt-1 text-2xl font-bold text-purple-600">৳{{ number_format($monthlyDealValue) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm font-medium text-gray-500">Commission</p>
                            <p class="mt-1 text-2xl font-bold text-orange-600">৳{{ number_format($monthlyCommission) }}</p>
                        </div>
                    </div>

                    {{-- Commission Breakdown --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-4">
                            <h3 class="text-sm font-semibold text-gray-900">Commission Breakdown</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid gap-4 sm:grid-cols-3">
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">From Conversions</p>
                                    <p class="text-xl font-bold text-green-600">৳{{ number_format($breakdown['conversion_commission']) }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Extra Commissions</p>
                                    <p class="text-xl font-bold text-purple-600">৳{{ number_format($breakdown['extra_commission']) }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-xs text-gray-500">Total</p>
                                    <p class="text-xl font-bold text-orange-600">৳{{ number_format($breakdown['total']) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Recent Leads --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-4">
                            <h3 class="text-sm font-semibold text-gray-900">Recent Leads</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Lead</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Customer</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @forelse($recentLeads as $lead)
                                        <tr>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                                <a href="{{ route('leads.show', $lead) }}" class="text-indigo-600 hover:text-indigo-900">
                                                    {{ $lead->lead_number }}
                                                </a>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                                {{ $lead->customer_name }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">
                                                {{ $lead->lead_date->format('M d, Y') }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-center">
                                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                    @switch($lead->status)
                                                        @case('New') bg-blue-100 text-blue-800 @break
                                                        @case('Contacted') bg-yellow-100 text-yellow-800 @break
                                                        @case('Qualified') bg-purple-100 text-purple-800 @break
                                                        @case('Converted') bg-green-100 text-green-800 @break
                                                        @case('Lost') bg-red-100 text-red-800 @break
                                                        @default bg-gray-100 text-gray-800
                                                    @endswitch">
                                                    {{ $lead->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-sm text-gray-500">
                                                No leads assigned yet
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
