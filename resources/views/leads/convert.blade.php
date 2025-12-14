<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Convert Lead: {{ $lead->lead_number }}
            </h2>
            <a href="{{ route('leads.show', $lead) }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Back to Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            @if(session('error'))
                <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-5">
                {{-- Lead Summary --}}
                <div class="lg:col-span-2">
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-4">
                            <h3 class="text-sm font-semibold text-gray-900">Lead Summary</h3>
                        </div>
                        <div class="divide-y p-4">
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500">Customer</span>
                                <span class="text-sm font-medium text-gray-900">{{ $lead->customer_name }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500">Phone</span>
                                <span class="text-sm font-medium text-gray-900">{{ $lead->phone_number }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500">Service</span>
                                <span class="text-sm font-medium text-gray-900">{{ $lead->service_interested }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500">Source</span>
                                <span class="text-sm font-medium text-gray-900">{{ $lead->source }}</span>
                            </div>
                            <div class="flex justify-between py-2">
                                <span class="text-sm text-gray-500">Lead Date</span>
                                <span class="text-sm font-medium text-gray-900">{{ $lead->lead_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Commission Info Card --}}
                    <div class="mt-4 overflow-hidden rounded-lg bg-indigo-50 shadow">
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-indigo-900">Your Commission Settings</h3>
                            <div class="mt-2 text-sm text-indigo-700">
                                @if($user->commission_type === 'fixed')
                                    <p>Fixed Rate: <span class="font-bold">৳{{ number_format($user->default_commission_rate) }}</span> per conversion</p>
                                @else
                                    <p>Percentage: <span class="font-bold">{{ $user->default_commission_rate }}%</span> of deal value</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Conversion Form --}}
                <div class="lg:col-span-3">
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b bg-gray-50 px-6 py-4">
                            <h3 class="text-sm font-semibold text-gray-900">Conversion Details</h3>
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
                            <div class="mb-4">
                                <label for="deal_value" class="block text-sm font-medium text-gray-700">Deal Value (BDT)</label>
                                <div class="relative mt-1">
                                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">৳</span>
                                    <input type="number" name="deal_value" id="deal_value"
                                           x-model="dealValue"
                                           value="{{ old('deal_value') }}"
                                           required min="0" step="0.01"
                                           class="block w-full rounded-md border-gray-300 pl-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                @error('deal_value')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Conversion Date --}}
                            <div class="mb-4">
                                <label for="conversion_date" class="block text-sm font-medium text-gray-700">Conversion Date</label>
                                <input type="date" name="conversion_date" id="conversion_date"
                                       value="{{ old('conversion_date', now()->format('Y-m-d')) }}"
                                       required max="{{ now()->format('Y-m-d') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @error('conversion_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Notes --}}
                            <div class="mb-6">
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Any notes about this conversion...">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Commission Preview --}}
                            <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-green-800">Your Commission</p>
                                        <p class="text-xs text-green-600">
                                            @if($user->commission_type === 'fixed')
                                                Fixed rate per conversion
                                            @else
                                                {{ $user->default_commission_rate }}% of deal value
                                            @endif
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-2xl font-bold text-green-700" x-text="formatCurrency(commission)">৳0</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('leads.show', $lead) }}"
                                   class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                    Cancel
                                </a>
                                <button type="submit"
                                        class="rounded-md bg-green-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500">
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
