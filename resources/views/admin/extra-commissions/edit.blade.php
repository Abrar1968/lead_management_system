<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Edit Extra Commission
            </h2>
            <a href="{{ route('extra-commissions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Back to List
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <form action="{{ route('extra-commissions.update', $extraCommission) }}" method="POST" class="p-6">
                    @csrf
                    @method('PUT')

                    {{-- User Selection --}}
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                        <select name="user_id" id="user_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id', $extraCommission->user_id) == $user->id ? 'selected' : '' }}>
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
                            @foreach(['Bonus', 'Incentive', 'Target Achievement', 'Referral', 'Other'] as $type)
                                <option value="{{ $type }}" {{ old('commission_type', $extraCommission->commission_type) === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
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
                            <input type="number" name="amount" id="amount"
                                   value="{{ old('amount', $extraCommission->amount) }}"
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
                               value="{{ old('date_earned', $extraCommission->date_earned->format('Y-m-d')) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('date_earned')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach(['Pending', 'Approved', 'Paid'] as $status)
                                <option value="{{ $status }}" {{ old('status', $extraCommission->status) === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $extraCommission->description) }}</textarea>
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
                            Update Commission
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
