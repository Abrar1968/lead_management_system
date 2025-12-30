<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-xl shadow-amber-500/30">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Create Commission Type</h2>
                    <p class="text-sm text-gray-500">Define a new commission structure</p>
                </div>
            </div>
            <a href="{{ route('admin.commission-types.index') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl">
        <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.commission-types.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Commission Type Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('name') border-red-300 @enderror"
                        placeholder="e.g., Sales Commission, Referral Bonus, Target Achievement">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full rounded-xl border-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('description') border-red-300 @enderror"
                        placeholder="Describe when this commission type is applied...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Calculation Type --}}
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Calculation Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="calculation_type" value="fixed" class="peer sr-only" {{ old('calculation_type', 'fixed') === 'fixed' ? 'checked' : '' }}>
                            <div class="w-full rounded-xl border-2 border-gray-200 p-4 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 peer-checked:bg-emerald-500 peer-checked:text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Fixed Amount</p>
                                        <p class="text-xs text-gray-500">Same amount per conversion</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative flex cursor-pointer">
                            <input type="radio" name="calculation_type" value="percentage" class="peer sr-only" {{ old('calculation_type') === 'percentage' ? 'checked' : '' }}>
                            <div class="w-full rounded-xl border-2 border-gray-200 p-4 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600 peer-checked:bg-amber-500 peer-checked:text-white">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold text-gray-900">Percentage</p>
                                        <p class="text-xs text-gray-500">% of deal value</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('calculation_type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Default Rate --}}
                <div>
                    <label for="default_rate" class="block text-sm font-bold text-gray-700 mb-2">Default Rate</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                            <span class="text-gray-500 font-semibold" id="rate-prefix">৳</span>
                        </div>
                        <input type="number" name="default_rate" id="default_rate" value="{{ old('default_rate', 0) }}"
                            step="0.01" min="0"
                            class="w-full rounded-xl border-gray-200 pl-10 pr-16 shadow-sm focus:border-amber-500 focus:ring-amber-500 @error('default_rate') border-red-300 @enderror"
                            placeholder="0.00">
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                            <span class="text-gray-500 font-semibold" id="rate-suffix">BDT</span>
                        </div>
                    </div>
                    <p class="mt-1 text-xs text-gray-500" id="rate-help">Fixed amount paid per conversion</p>
                    @error('default_rate')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Settings --}}
                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all">
                        <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500" {{ old('is_active', true) ? 'checked' : '' }}>
                        <div>
                            <p class="font-semibold text-gray-900">Active</p>
                            <p class="text-xs text-gray-500">Can be assigned to users</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-4 rounded-xl border border-gray-200 cursor-pointer hover:bg-gray-50 transition-all">
                        <input type="checkbox" name="is_default" value="1" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500" {{ old('is_default') ? 'checked' : '' }}>
                        <div>
                            <p class="font-semibold text-gray-900">Default</p>
                            <p class="text-xs text-gray-500">Auto-assign to new users</p>
                        </div>
                    </label>
                </div>

                {{-- Submit --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:shadow-xl transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Create Commission Type
                    </button>
                    <a href="{{ route('admin.commission-types.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeRadios = document.querySelectorAll('input[name="calculation_type"]');
            const ratePrefix = document.getElementById('rate-prefix');
            const rateSuffix = document.getElementById('rate-suffix');
            const rateHelp = document.getElementById('rate-help');
            const rateInput = document.getElementById('default_rate');

            function updateRateUI() {
                const isPercentage = document.querySelector('input[name="calculation_type"]:checked').value === 'percentage';

                if (isPercentage) {
                    ratePrefix.textContent = '';
                    rateSuffix.textContent = '%';
                    rateHelp.textContent = 'Percentage of deal value (max 100%)';
                    rateInput.max = 100;
                } else {
                    ratePrefix.textContent = '৳';
                    rateSuffix.textContent = 'BDT';
                    rateHelp.textContent = 'Fixed amount paid per conversion';
                    rateInput.removeAttribute('max');
                }
            }

            typeRadios.forEach(radio => {
                radio.addEventListener('change', updateRateUI);
            });

            updateRateUI();
        });
    </script>
    @endpush
</x-app-layout>
