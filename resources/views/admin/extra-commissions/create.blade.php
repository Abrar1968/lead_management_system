<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Add Extra Commission
            </h2>
            <a href="{{ route('extra-commissions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <form action="{{ route('extra-commissions.store') }}" method="POST" class="p-6">
                    @csrf

                    {{-- User Selection --}}
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                        <select name="user_id" id="user_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select User</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->role }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commission Type --}}
                    <div class="mb-4">
                        <label for="commission_type" class="block text-sm font-medium text-gray-700">Commission Type</label>
                        <select name="commission_type" id="commission_type" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Type</option>
                            <option value="Bonus" {{ old('commission_type') === 'Bonus' ? 'selected' : '' }}>Bonus</option>
                            <option value="Incentive" {{ old('commission_type') === 'Incentive' ? 'selected' : '' }}>Incentive</option>
                            <option value="Target Achievement" {{ old('commission_type') === 'Target Achievement' ? 'selected' : '' }}>Target Achievement</option>
                            <option value="Referral" {{ old('commission_type') === 'Referral' ? 'selected' : '' }}>Referral</option>
                            <option value="Other" {{ old('commission_type') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('commission_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Amount --}}
                    <div class="mb-4">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Amount (BDT)</label>
                        <div class="relative mt-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">৳</span>
                            <input type="number" name="amount" id="amount" value="{{ old('amount') }}"
                                   required min="1" step="0.01"
                                   class="block w-full rounded-md border-gray-300 pl-8 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Date Earned --}}
                    <div class="mb-4">
                        <label for="date_earned" class="block text-sm font-medium text-gray-700">Date Earned</label>
                        <input type="date" name="date_earned" id="date_earned"
                               value="{{ old('date_earned', now()->format('Y-m-d')) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('date_earned')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Reason for this commission...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('extra-commissions.index') }}"
                           class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Create Commission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
