<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Add Extra Commission</h2>
                    <p class="text-sm text-gray-500">Create bonus or incentive payment</p>
                </div>
            </div>
            <a href="{{ route('admin.extra-commissions.index') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to List
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white">Commission Details</h3>
                <p class="text-sm text-purple-100">Fill in the information below</p>
            </div>

            <form action="{{ route('admin.extra-commissions.store') }}" method="POST" class="p-6 space-y-5">
                @csrf

                {{-- User Selection --}}
                <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">User</label>
                    <select name="user_id" id="user_id" required
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->role }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Commission Type --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Commission Type</label>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach(['Bonus', 'Incentive', 'Target Achievement', 'Referral', 'Other'] as $type)
                            <label class="relative flex cursor-pointer">
                                <input type="radio" name="commission_type" value="{{ $type }}"
                                       class="peer sr-only" {{ old('commission_type') === $type ? 'checked' : '' }}>
                                <div class="flex w-full items-center justify-center rounded-xl border-2 border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition-all duration-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:text-purple-700 peer-checked:shadow-lg peer-checked:shadow-purple-500/20 hover:border-gray-300">
                                    {{ $type }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('commission_type')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Amount --}}
                <div>
                    <label for="amount" class="block text-sm font-semibold text-gray-700 mb-2">Amount (BDT)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-lg font-bold text-purple-500">৳</span>
                        <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                               required min="1" step="0.01" placeholder="0.00"
                               class="w-full rounded-xl border-gray-200 bg-gray-50 pl-10 pr-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                    </div>
                    @error('amount')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Date Earned --}}
                <div>
                    <label for="date_earned" class="block text-sm font-semibold text-gray-700 mb-2">Date Earned</label>
                    <input type="date" name="date_earned" id="date_earned"
                           value="{{ old('date_earned', now()->format('Y-m-d')) }}" required
                           class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                    @error('date_earned')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="3"
                              placeholder="Reason for this commission..."
                              class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20 resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.extra-commissions.index') }}"
                       class="rounded-xl border-2 border-gray-200 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-500/30 hover:shadow-xl hover:-translate-y-0.5 transition-all">
                        Create Commission
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
