<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Create New User
            </h2>
            <a href="{{ route('users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                ← Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <form action="{{ route('users.store') }}" method="POST" class="p-6"
                      x-data="{ commissionType: '{{ old('commission_type', 'fixed') }}' }">
                    @csrf

                    {{-- Name --}}
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" id="password" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Confirmation --}}
                    <div class="mb-4">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    {{-- Role --}}
                    <div class="mb-4">
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="sales_person" {{ old('role') === 'sales_person' ? 'selected' : '' }}>Sales Person</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commission Type --}}
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Commission Type</label>
                        <div class="mt-2 flex gap-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="commission_type" value="fixed"
                                       x-model="commissionType"
                                       class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Fixed Amount</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="commission_type" value="percentage"
                                       x-model="commissionType"
                                       class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Percentage</span>
                            </label>
                        </div>
                        @error('commission_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Commission Rate --}}
                    <div class="mb-6">
                        <label for="default_commission_rate" class="block text-sm font-medium text-gray-700">
                            <span x-show="commissionType === 'fixed'">Commission Amount (BDT)</span>
                            <span x-show="commissionType === 'percentage'">Commission Percentage (%)</span>
                        </label>
                        <div class="relative mt-1">
                            <span x-show="commissionType === 'fixed'" class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-500">৳</span>
                            <span x-show="commissionType === 'percentage'" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500">%</span>
                            <input type="number" name="default_commission_rate" id="default_commission_rate"
                                   value="{{ old('default_commission_rate', 500) }}"
                                   required min="0" step="0.01"
                                   :max="commissionType === 'percentage' ? 100 : 999999"
                                   :class="commissionType === 'fixed' ? 'pl-8' : 'pr-8'"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('default_commission_rate')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <div class="flex justify-end gap-3">
                        <a href="{{ route('users.index') }}"
                           class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                                class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                            Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
