<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('admin.commissions.index') }}"
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-gray-100 text-gray-600 hover:bg-gray-200 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-xl shadow-indigo-500/30">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit Commission Settings</h2>
                    <p class="text-sm text-gray-500">{{ $user->name }} • {{ $user->email }}</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold
                    {{ $user->role === 'admin'
                        ? 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30'
                        : 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-500/30' }}">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
        </div>
    </x-slot>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-100">
                    <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Edit Form --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10">
                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Commission Configuration</h3>
                            <p class="text-sm text-gray-400">Set how this team member earns commission</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.commissions.update', $user) }}" class="p-6">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">
                        {{-- Commission Type Selection --}}
                        <div x-data="{ type: '{{ old('commission_type', $user->commission_type) }}' }">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Commission Type</label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="commission_type" value="fixed" x-model="type" class="peer sr-only">
                                    <div class="p-5 rounded-xl border-2 border-gray-200 bg-white hover:border-emerald-300 hover:bg-emerald-50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100">
                                                <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">Fixed Amount</h4>
                                                <p class="text-sm text-gray-500">Same amount per conversion</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 right-3 hidden peer-checked:block">
                                            <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </label>

                                <label class="relative cursor-pointer">
                                    <input type="radio" name="commission_type" value="percentage" x-model="type" class="peer sr-only">
                                    <div class="p-5 rounded-xl border-2 border-gray-200 bg-white hover:border-amber-300 hover:bg-amber-50 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                                        <div class="flex items-center gap-4">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-100">
                                                <svg class="h-6 w-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-gray-900">Percentage</h4>
                                                <p class="text-sm text-gray-500">% of deal value</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-3 right-3 hidden peer-checked:block">
                                            <svg class="h-5 w-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </div>
                                </label>
                            </div>
                            @error('commission_type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            {{-- Commission Rate --}}
                            <div class="mt-6">
                                <label for="default_commission_rate" class="block text-sm font-bold text-gray-700 mb-2">
                                    <span x-show="type === 'fixed'">Fixed Amount (৳)</span>
                                    <span x-show="type === 'percentage'">Percentage Rate (%)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500" x-show="type === 'fixed'">৳</span>
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500" x-show="type === 'percentage'">%</span>
                                    <input type="number"
                                        name="default_commission_rate"
                                        id="default_commission_rate"
                                        value="{{ old('default_commission_rate', $user->default_commission_rate) }}"
                                        step="0.01"
                                        min="0"
                                        :max="type === 'percentage' ? 100 : 999999"
                                        class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-lg font-semibold"
                                        :placeholder="type === 'fixed' ? 'e.g., 500' : 'e.g., 10'"
                                        required>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    <span x-show="type === 'fixed'">The fixed amount earned per successful conversion.</span>
                                    <span x-show="type === 'percentage'">Percentage of deal value earned per conversion (0-100).</span>
                                </p>
                                @error('default_commission_rate')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Preview Calculation --}}
                        <div class="rounded-xl bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-100 p-5">
                            <h4 class="font-bold text-indigo-900 mb-3">Example Calculation</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                                    <p class="text-gray-500">If deal value is</p>
                                    <p class="text-lg font-black text-gray-900">৳50,000</p>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-indigo-100">
                                    <p class="text-gray-500">Commission earned</p>
                                    <p class="text-lg font-black text-indigo-600" x-data="{
                                        type: '{{ old('commission_type', $user->commission_type) }}',
                                        rate: {{ old('default_commission_rate', $user->default_commission_rate) }}
                                    }"
                                    x-text="type === 'fixed' ? '৳' + Number(rate).toLocaleString() : '৳' + Math.round(50000 * rate / 100).toLocaleString()">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="mt-8 flex items-center gap-4">
                        <button type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Save Commission Settings
                        </button>
                        <a href="{{ route('admin.commissions.index') }}"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-gray-100 px-6 py-3.5 text-sm font-bold text-gray-700 hover:bg-gray-200 transition-all">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- User Stats Sidebar --}}
        <div class="space-y-6">
            {{-- Current Earnings --}}
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-5 py-4">
                    <h3 class="text-lg font-bold text-white">Current Period Earnings</h3>
                    <p class="text-sm text-emerald-100">{{ now()->format('F Y') }}</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">From Conversions</span>
                        <span class="text-lg font-black text-emerald-600">৳{{ number_format($monthlyStats['standard']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Bonuses/Extras</span>
                        <span class="text-lg font-black text-purple-600">৳{{ number_format($monthlyStats['extra']) }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total Earned</span>
                        <span class="text-xl font-black text-gray-900">৳{{ number_format($monthlyStats['total']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Year to Date --}}
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4">
                    <h3 class="text-lg font-bold text-white">Year to Date</h3>
                    <p class="text-sm text-blue-100">{{ now()->format('Y') }}</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Conversions</span>
                        <span class="text-lg font-black text-blue-600">{{ $totalConversions }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Total Deal Value</span>
                        <span class="text-lg font-black text-gray-900">৳{{ number_format($totalDealValue) }}</span>
                    </div>
                    <hr class="border-gray-100">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-bold text-gray-700">Total Earned</span>
                        <span class="text-xl font-black text-blue-600">৳{{ number_format($yearlyStats['total']) }}</span>
                    </div>
                </div>
            </div>

            {{-- Recent Conversions --}}
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="bg-gradient-to-r from-gray-800 to-gray-900 px-5 py-4">
                    <h3 class="text-lg font-bold text-white">Recent Conversions</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentConversions as $conversion)
                        <div class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $conversion->lead->lead_number ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-500">{{ $conversion->conversion_date->format('M d, Y') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-emerald-600">৳{{ number_format($conversion->commission_amount) }}</p>
                                    <p class="text-xs text-gray-400">৳{{ number_format($conversion->deal_value) }} deal</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center">
                            <div class="mx-auto h-12 w-12 rounded-xl bg-gray-100 flex items-center justify-center mb-3">
                                <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500">No conversions yet</p>
                        </div>
                    @endforelse
                </div>
                @if($recentConversions->count() > 0)
                    <div class="p-3 bg-gray-50 border-t border-gray-100">
                        <a href="{{ route('users.show', $user) }}"
                            class="block text-center text-sm font-medium text-indigo-600 hover:text-indigo-800">
                            View Full History →
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
