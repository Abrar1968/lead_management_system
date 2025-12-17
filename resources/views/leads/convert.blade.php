<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">
                        Convert Lead
                    </h2>
                    <p class="mt-0.5 text-sm font-medium text-gray-500">{{ $lead->lead_number }}</p>
                </div>
            </div>
            <a href="{{ route('leads.show', $lead) }}"
               class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:-translate-x-1">
                <svg class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 p-4 border border-red-200 shadow-lg shadow-red-500/10"
                     x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/30">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <p class="flex-1 text-sm font-semibold text-red-800">{{ session('error') }}</p>
                        <button @click="show = false" class="text-red-500 hover:text-red-700 transition-colors">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-5">
                {{-- Lead Summary --}}
                <div class="lg:col-span-2 space-y-6">
                    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Lead Summary</h3>
                            </div>
                        </div>
                        <div class="divide-y divide-gray-100 p-4">
                            <div class="flex justify-between py-3">
                                <span class="text-sm font-medium text-gray-500">Customer</span>
                                <span class="text-sm font-bold text-gray-900">{{ $lead->customer_name }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-sm font-medium text-gray-500">Phone</span>
                                <span class="text-sm font-bold text-gray-900">{{ $lead->phone_number }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-sm font-medium text-gray-500">Service</span>
                                <span class="inline-flex items-center rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 px-2.5 py-1 text-xs font-bold text-white shadow-lg shadow-purple-500/30">{{ $lead->service->name ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-sm font-medium text-gray-500">Source</span>
                                <span class="inline-flex items-center rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 px-2.5 py-1 text-xs font-bold text-white shadow-lg shadow-cyan-500/30">{{ $lead->source }}</span>
                            </div>
                            <div class="flex justify-between py-3">
                                <span class="text-sm font-medium text-gray-500">Lead Date</span>
                                <span class="text-sm font-bold text-gray-900">{{ $lead->lead_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Commission Info Card --}}
                    <div class="overflow-hidden rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:shadow-xl">
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Your Commission</h3>
                            </div>
                            <div class="text-white/80">
                                @if($user->commission_type === 'fixed')
                                    <p class="text-sm">Fixed Rate</p>
                                    <p class="text-3xl font-bold text-white mt-1">৳{{ number_format($user->default_commission_rate) }}</p>
                                    <p class="text-xs text-white/60 mt-1">per conversion</p>
                                @else
                                    <p class="text-sm">Percentage Rate</p>
                                    <p class="text-3xl font-bold text-white mt-1">{{ $user->default_commission_rate }}%</p>
                                    <p class="text-xs text-white/60 mt-1">of deal value</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Conversion Form --}}
                <div class="lg:col-span-3">
                    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Conversion Details</h3>
                            </div>
                        </div>

                        <form action="{{ route('conversions.store', $lead) }}" method="POST" class="p-6"
                              x-data="{
                                  dealValue: {{ old('deal_value', 0) }},
                                  commissionType: '{{ $user->commission_type }}',
                                  commissionRate: {{ $user->default_commission_rate }},
                                  get commission() {
                                      if (this.commissionType === 'fixed') {
                                          return this.commissionRate;
                                      }
                                      return (this.dealValue * this.commissionRate) / 100;
                                  },
                                  formatCurrency(value) {
                                      return '৳' + new Intl.NumberFormat('en-BD').format(value);
                                  }
                              }">
                            @csrf

                            {{-- Deal Value --}}
                            <div class="mb-6 space-y-2">
                                <label for="deal_value" class="block text-sm font-semibold text-gray-700">Deal Value (BDT) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">৳</span>
                                    <input type="number" name="deal_value" id="deal_value"
                                           x-model="dealValue"
                                           value="{{ old('deal_value') }}"
                                           required min="0" step="0.01"
                                           placeholder="0.00"
                                           class="w-full rounded-xl border-gray-200 bg-gray-50 pl-10 pr-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                                </div>
                                @error('deal_value')
                                    <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Conversion Date --}}
                            <div class="mb-6 space-y-2">
                                <label for="conversion_date" class="block text-sm font-semibold text-gray-700">Conversion Date <span class="text-red-500">*</span></label>
                                <input type="date" name="conversion_date" id="conversion_date"
                                       value="{{ old('conversion_date', now()->format('Y-m-d')) }}"
                                       required max="{{ now()->format('Y-m-d') }}"
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20">
                                @error('conversion_date')
                                    <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="mb-6 space-y-2">
                                <label for="notes" class="block text-sm font-semibold text-gray-700">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-emerald-500 focus:bg-white focus:ring-2 focus:ring-emerald-500/20"
                                          placeholder="Any notes about this conversion...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="text-sm font-medium text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Commission Preview --}}
                            <div class="mb-8 rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 p-5 border border-emerald-200 shadow-lg shadow-emerald-500/10">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-bold text-emerald-800">Your Commission</p>
                                        </div>
                                        <p class="text-xs font-medium text-emerald-600 mt-1">
                                            @if($user->commission_type === 'fixed')
                                                Fixed rate per conversion
                                            @else
                                                {{ $user->default_commission_rate }}% of deal value
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-3xl font-bold text-emerald-700" x-text="formatCurrency(commission)">৳0</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                                <a href="{{ route('leads.show', $lead) }}"
                                   class="rounded-xl bg-gray-100 px-6 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-emerald-600 to-green-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5">
                                    <svg class="h-5 w-5 transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Convert Lead
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
