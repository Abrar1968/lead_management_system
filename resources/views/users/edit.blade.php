<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30 text-lg font-bold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Edit User</h2>
                    <p class="text-sm text-gray-500">{{ $user->name }}</p>
                </div>
            </div>
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <h3 class="font-semibold text-white flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit User Information
                </h3>
            </div>
            <form action="{{ route('users.update', $user) }}" method="POST" class="p-6"
                  x-data="{ commissionType: '{{ old('commission_type', $user->commission_type) }}' }">
                @csrf
                @method('PUT')

                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">Full Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20"
                           placeholder="Enter full name">
                    @error('name')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20"
                           placeholder="name@example.com">
                    @error('email')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        New Password
                        <span class="text-gray-400 font-normal">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20"
                           placeholder="••••••••">
                    @error('password')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password Confirmation --}}
                <div class="mb-5">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20"
                           placeholder="••••••••">
                </div>

                {{-- Role --}}
                <div class="mb-5">
                    <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <select name="role" id="role" required
                            class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                        <option value="sales_person" {{ old('role', $user->role) === 'sales_person' ? 'selected' : '' }}>Sales Person</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Commission Type --}}
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Commission Type</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative cursor-pointer">
                            <input type="radio" name="commission_type" value="fixed" x-model="commissionType" class="peer sr-only">
                            <div class="rounded-xl border-2 border-gray-200 bg-gray-50 p-4 transition-all duration-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-lg peer-checked:shadow-purple-500/20">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-amber-100 text-amber-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">Fixed Amount</p>
                                        <p class="text-xs text-gray-500">Per conversion</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                        <label class="relative cursor-pointer">
                            <input type="radio" name="commission_type" value="percentage" x-model="commissionType" class="peer sr-only">
                            <div class="rounded-xl border-2 border-gray-200 bg-gray-50 p-4 transition-all duration-200 peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-lg peer-checked:shadow-purple-500/20">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-900">Percentage</p>
                                        <p class="text-xs text-gray-500">Of deal value</p>
                                    </div>
                                </div>
                            </div>
                        </label>
                    </div>
                    @error('commission_type')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Commission Rate --}}
                <div class="mb-6">
                    <label for="default_commission_rate" class="block text-sm font-semibold text-gray-700 mb-2">
                        <span x-show="commissionType === 'fixed'">Commission Amount (BDT)</span>
                        <span x-show="commissionType === 'percentage'">Commission Percentage (%)</span>
                    </label>
                    <div class="relative">
                        <span x-show="commissionType === 'fixed'" class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500 font-medium">৳</span>
                        <span x-show="commissionType === 'percentage'" class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-500 font-medium">%</span>
                        <input type="number" name="default_commission_rate" id="default_commission_rate"
                               value="{{ old('default_commission_rate', $user->default_commission_rate) }}"
                               required min="0" step="0.01"
                               :max="commissionType === 'percentage' ? 100 : 999999"
                               :class="commissionType === 'fixed' ? 'pl-10' : 'pr-10'"
                               class="block w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                    </div>
                    @error('default_commission_rate')
                        <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('users.index') }}"
                       class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                        Cancel
                    </a>
                    <button type="submit"
                            class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-500/30 hover:shadow-xl transition-all">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
