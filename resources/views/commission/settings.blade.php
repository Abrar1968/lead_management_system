<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Commission Settings
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Settings Form --}}
                <div class="lg:col-span-1">
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-gray-900">Your Settings</h3>
                        </div>
                        <form action="{{ route('commission.update') }}" method="POST" class="p-4" x-data="{ type: '{{ $user->commission_type }}' }">
                            @csrf
                            @method('PUT')

                            {{-- Current Settings Display --}}
                            <div class="mb-6 rounded-lg bg-indigo-50 p-4">
                                <p class="text-sm text-gray-600">Current Rate</p>
                                <p class="text-2xl font-bold text-indigo-600">
                                    @if($user->commission_type === 'fixed')
                                        ৳{{ number_format($user->default_commission_rate, 2) }}
                                        <span class="text-sm font-normal text-gray-500">per conversion</span>
                                    @else
                                        {{ $user->default_commission_rate }}%
                                        <span class="text-sm font-normal text-gray-500">of deal value</span>
                                    @endif
                                </p>
                            </div>

                            {{-- Commission Type --}}
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Commission Type</label>
                                <div class="space-y-2">
                                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50"
                                           :class="type === 'fixed' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                        <input type="radio" name="commission_type" value="fixed" x-model="type"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-900">Fixed Amount</span>
                                            <span class="block text-xs text-gray-500">Earn same amount per conversion</span>
                                        </div>
                                    </label>
                                    <label class="flex items-center p-3 rounded-lg border cursor-pointer hover:bg-gray-50"
                                           :class="type === 'percentage' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                                        <input type="radio" name="commission_type" value="percentage" x-model="type"
                                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-900">Percentage</span>
                                            <span class="block text-xs text-gray-500">Earn % of deal value</span>
                                        </div>
                                    </label>
                                </div>
                                @error('commission_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Commission Rate --}}
                            <div class="mb-4">
                                <label for="commission_rate" class="block text-sm font-medium text-gray-700">
                                    <span x-show="type === 'fixed'">Amount (BDT)</span>
                                    <span x-show="type === 'percentage'">Percentage (%)</span>
                                </label>
                                <div class="relative mt-1">
                                    <span x-show="type === 'fixed'" class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">৳</span>
                                    <span x-show="type === 'percentage'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">%</span>
                                    <input type="number" name="commission_rate" id="commission_rate"
                                           value="{{ old('commission_rate', $user->default_commission_rate) }}"
                                           step="0.01" min="0"
                                           :max="type === 'percentage' ? 100 : 10000"
                                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                                           :class="type === 'fixed' ? 'pl-8' : 'pr-8'">
                                </div>
                                @error('commission_rate')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Info Box --}}
                            <div class="mb-4 rounded-lg bg-yellow-50 p-3 border border-yellow-200">
                                <p class="text-xs text-yellow-800">
                                    <strong>Note:</strong> Changing these settings only affects future conversions.
                                    Past conversions retain their original commission calculations.
                                </p>
                            </div>

                            <button type="submit"
                                    class="w-full rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500">
                                Update Settings
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Commission Summary --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Summary Cards --}}
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-500">This Month</p>
                            <p class="text-2xl font-bold text-green-600">৳{{ number_format($monthlyCommission['total']) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-500">Standard</p>
                            <p class="text-xl font-bold text-gray-900">৳{{ number_format($monthlyCommission['standard']) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-500">Extra/Bonus</p>
                            <p class="text-xl font-bold text-purple-600">৳{{ number_format($monthlyCommission['extra']) }}</p>
                        </div>
                        <div class="rounded-lg bg-white p-4 shadow">
                            <p class="text-sm text-gray-500">Year to Date</p>
                            <p class="text-xl font-bold text-blue-600">৳{{ number_format($yearlyCommission['total']) }}</p>
                        </div>
                    </div>

                    {{-- Month Selector --}}
                    <div class="flex items-center gap-4">
                        <form method="GET" class="flex items-center gap-2">
                            <select name="month" class="rounded-md border-gray-300 text-sm">
                                @foreach(range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="rounded-md border-gray-300 text-sm">
                                @foreach(range(now()->year - 2, now()->year) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                View
                            </button>
                        </form>
                    </div>

                    {{-- Conversions Table --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-gray-900">Standard Commissions</h3>
                        </div>
                        <div class="overflow-x-auto">
                            @if($breakdown['conversions']->isEmpty())
                                <p class="p-6 text-center text-sm text-gray-500">No conversions this month.</p>
                            @else
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Lead</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Deal Value</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500">Rate</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Commission</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($breakdown['conversions'] as $conversion)
                                            <tr>
                                                <td class="whitespace-nowrap px-4 py-3">
                                                    <a href="{{ route('leads.show', $conversion->lead_id) }}" class="text-indigo-600 hover:text-indigo-500">
                                                        {{ $conversion->lead->customer_name ?? $conversion->lead->lead_number }}
                                                    </a>
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">
                                                    {{ $conversion->conversion_date->format('M d, Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                                    ৳{{ number_format($conversion->deal_value) }}
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-center text-sm">
                                                    @if($conversion->commission_type_used === 'fixed')
                                                        ৳{{ number_format($conversion->commission_rate_used) }}
                                                    @else
                                                        {{ $conversion->commission_rate_used }}%
                                                    @endif
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-green-600">
                                                    ৳{{ number_format($conversion->commission_amount) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-right text-sm font-medium">Total:</td>
                                            <td class="px-4 py-3 text-right text-sm font-bold text-green-600">
                                                ৳{{ number_format($breakdown['conversion_commission']) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            @endif
                        </div>
                    </div>

                    {{-- Extra Commissions Table --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-gray-900">Extra Commissions (Bonus/Incentive)</h3>
                        </div>
                        <div class="overflow-x-auto">
                            @if($breakdown['extra_commissions']->isEmpty())
                                <p class="p-6 text-center text-sm text-gray-500">No extra commissions this month.</p>
                            @else
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Description</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                            <th class="px-4 py-3 text-center text-xs font-medium uppercase text-gray-500">Status</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium uppercase text-gray-500">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($breakdown['extra_commissions'] as $extra)
                                            <tr>
                                                <td class="whitespace-nowrap px-4 py-3 text-sm font-medium">
                                                    {{ $extra->commission_type }}
                                                </td>
                                                <td class="px-4 py-3 text-sm text-gray-500">
                                                    {{ Str::limit($extra->description, 50) }}
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-500">
                                                    {{ $extra->date_earned->format('M d, Y') }}
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-center">
                                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                        @switch($extra->status)
                                                            @case('Pending') bg-yellow-100 text-yellow-800 @break
                                                            @case('Approved') bg-green-100 text-green-800 @break
                                                            @case('Paid') bg-blue-100 text-blue-800 @break
                                                        @endswitch">
                                                        {{ $extra->status }}
                                                    </span>
                                                </td>
                                                <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-purple-600">
                                                    ৳{{ number_format($extra->amount) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="bg-gray-50">
                                        <tr>
                                            <td colspan="4" class="px-4 py-3 text-right text-sm font-medium">Approved Total:</td>
                                            <td class="px-4 py-3 text-right text-sm font-bold text-purple-600">
                                                ৳{{ number_format($breakdown['extra_commission']) }}
                                            </td>
                                        </tr>
                                        @if($breakdown['pending_extra'] > 0)
                                            <tr>
                                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-yellow-600">Pending:</td>
                                                <td class="px-4 py-3 text-right text-sm font-bold text-yellow-600">
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
        </div>
    </div>
</x-app-layout>
