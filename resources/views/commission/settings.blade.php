<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Commission Settings</h2>
                <p class="text-sm text-gray-500">Manage your commission rates and view earnings</p>
            </div>
        </div>
    </x-slot>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="mb-6 overflow-hidden rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Settings Form --}}
        <div class="lg:col-span-1">
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Your Settings</h3>
                    </div>
                </div>
                <form action="{{ route('commission.update') }}" method="POST" class="p-5" x-data="{ type: '{{ $user->commission_type }}' }">
                    @csrf
                    @method('PUT')

                    {{-- Current Settings Display --}}
                    <div class="mb-6 rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 p-5 border border-indigo-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Current Rate</p>
                        <p class="text-3xl font-bold text-indigo-600 mt-2">
                            @if($user->commission_type === 'fixed')
                                ৳{{ number_format($user->default_commission_rate, 2) }}
                            @else
                                {{ $user->default_commission_rate }}%
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            @if($user->commission_type === 'fixed')
                                per conversion
                            @else
                                of deal value
                            @endif
                        </p>
                    </div>

                    {{-- Commission Type --}}
                    <div class="mb-5 space-y-3">
                        <label class="block text-sm font-semibold text-gray-700">Commission Type</label>
                        <div class="space-y-3">
                            <label class="flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                   :class="type === 'fixed' ? 'border-indigo-500 bg-indigo-50 shadow-lg shadow-indigo-500/10' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                <input type="radio" name="commission_type" value="fixed" x-model="type"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-gray-900">Fixed Amount</span>
                                    <span class="block text-xs text-gray-500">Earn same amount per conversion</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 rounded-xl border-2 cursor-pointer transition-all duration-200"
                                   :class="type === 'percentage' ? 'border-indigo-500 bg-indigo-50 shadow-lg shadow-indigo-500/10' : 'border-gray-200 hover:border-gray-300 hover:bg-gray-50'">
                                <input type="radio" name="commission_type" value="percentage" x-model="type"
                                       class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                <div class="ml-4">
                                    <span class="block text-sm font-bold text-gray-900">Percentage</span>
                                    <span class="block text-xs text-gray-500">Earn % of deal value</span>
                                </div>
                            </label>
                        </div>
                        @error('commission_type')
                            <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commission Rate --}}
                    <div class="mb-5 space-y-2">
                        <label for="commission_rate" class="block text-sm font-semibold text-gray-700">
                            <span x-show="type === 'fixed'">Amount (BDT)</span>
                            <span x-show="type === 'percentage'">Percentage (%)</span>
                        </label>
                        <div class="relative">
                            <span x-show="type === 'fixed'" class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-semibold">৳</span>
                            <span x-show="type === 'percentage'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 font-semibold">%</span>
                            <input type="number" name="commission_rate" id="commission_rate"
                                   value="{{ old('commission_rate', $user->default_commission_rate) }}"
                                   step="0.01" min="0"
                                   :max="type === 'percentage' ? 100 : 10000"
                                   class="block w-full rounded-xl border-gray-200 bg-gray-50 py-3 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20"
                                   :class="type === 'fixed' ? 'pl-10' : 'pr-10'">
                        </div>
                        @error('commission_rate')
                            <p class="text-sm text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Info Box --}}
                    <div class="mb-5 rounded-xl bg-gradient-to-br from-amber-50 to-yellow-50 p-4 border border-amber-200">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-amber-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-xs text-amber-800">
                                <strong>Note:</strong> Changing these settings only affects future conversions.
                                Past conversions retain their original commission calculations.
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                            class="w-full rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                        Update Settings
                    </button>
                </form>
            </div>
        </div>

        {{-- Commission Summary --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Summary Cards --}}
            <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">This Month</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-2">৳{{ number_format($monthlyCommission['total']) }}</p>
                </div>
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Standard</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">৳{{ number_format($monthlyCommission['standard']) }}</p>
                </div>
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Extra/Bonus</p>
                    <p class="text-2xl font-bold text-purple-600 mt-2">৳{{ number_format($monthlyCommission['extra']) }}</p>
                </div>
                <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Year to Date</p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">৳{{ number_format($yearlyCommission['total']) }}</p>
                </div>
            </div>

            {{-- Month Selector --}}
            <div class="flex items-center gap-4">
                <form method="GET" class="flex items-center gap-3">
                    <select name="month" class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                    <select name="year" class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                        @foreach(range(now()->year - 2, now()->year) as $y)
                            <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-colors">
                        View
                    </button>
                </form>
            </div>
                            </button>
                        </form>
                    </div>

                    {{-- Conversions Table --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Standard Commissions</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    @if($breakdown['conversions']->isEmpty())
                        <div class="p-12 text-center">
                            <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500">No conversions this month.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Lead</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Deal Value</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-500">Rate</th>
                                    <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Commission</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($breakdown['conversions'] as $conversion)
                                    @if($conversion->lead)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <a href="{{ route('leads.show', $conversion->lead_id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                                {{ $conversion->lead->customer_name ?? $conversion->lead->lead_number }}
                                            </a>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-600">
                                            {{ $conversion->conversion_date->format('M d, Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-medium">
                                            ৳{{ number_format($conversion->deal_value) }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-center">
                                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold bg-gray-100 text-gray-700">
                                                @if($conversion->commission_type_used === 'fixed')
                                                    ৳{{ number_format($conversion->commission_rate_used) }}
                                                @else
                                                    {{ $conversion->commission_rate_used }}%
                                                @endif
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-bold text-emerald-600">
                                            ৳{{ number_format($conversion->commission_amount) }}
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-right text-sm font-bold text-gray-700">Total:</td>
                                    <td class="px-4 py-4 text-right text-lg font-bold text-emerald-600">
                                        ৳{{ number_format($breakdown['conversion_commission']) }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>
            </div>

            {{-- Extra Commissions Table --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Extra Commissions (Bonus/Incentive)</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    @if($breakdown['extra_commissions']->isEmpty())
                        <div class="p-12 text-center">
                            <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-500">No extra commissions this month.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Type</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Description</th>
                                    <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                                    <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($breakdown['extra_commissions'] as $extra)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold bg-purple-100 text-purple-700">
                                                {{ $extra->commission_type }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                            {{ Str::limit($extra->description, 50) }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-sm font-medium text-gray-600">
                                            {{ $extra->date_earned->format('M d, Y') }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-center">
                                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                                @switch($extra->status)
                                                    @case('Pending') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                                    @case('Approved') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                                    @case('Paid') bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-blue-500/30 @break
                                                @endswitch">
                                                {{ $extra->status }}
                                            </span>
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-4 text-right text-sm font-bold text-purple-600">
                                            ৳{{ number_format($extra->amount) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gradient-to-r from-gray-50 to-gray-100">
                                <tr>
                                    <td colspan="4" class="px-4 py-4 text-right text-sm font-bold text-gray-700">Approved Total:</td>
                                    <td class="px-4 py-4 text-right text-lg font-bold text-purple-600">
                                        ৳{{ number_format($breakdown['extra_commission']) }}
                                    </td>
                                </tr>
                                @if($breakdown['pending_extra'] > 0)
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-right text-sm font-bold text-amber-600">Pending:</td>
                                        <td class="px-4 py-4 text-right text-lg font-bold text-amber-600">
                                            ৳{{ number_format($breakdown['pending_extra']) }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
