<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br {{ $commissionType->calculation_type === 'fixed' ? 'from-emerald-500 to-green-600' : 'from-amber-500 to-orange-600' }} text-white shadow-xl">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $commissionType->name }}</h2>
                    <p class="text-sm text-gray-500">Manage user assignments for this commission type</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.commission-types.edit', $commissionType) }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-100 px-5 py-2.5 text-sm font-semibold text-indigo-700 hover:bg-indigo-200 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Type
                </a>
                <a href="{{ route('admin.commission-types.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to List
                </a>
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

    @if(session('error'))
        <div class="mb-6 rounded-2xl bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-100">
                    <svg class="h-5 w-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Commission Type Info --}}
        <div class="lg:col-span-1">
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden sticky top-6">
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold {{ $commissionType->calculation_type === 'fixed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                            {{ ucfirst($commissionType->calculation_type) }}
                        </span>
                        @if($commissionType->is_default)
                            <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold">Default</span>
                        @endif
                        @if(!$commissionType->is_active)
                            <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold">Inactive</span>
                        @endif
                    </div>

                    @if($commissionType->description)
                        <p class="text-sm text-gray-500 mb-4">{{ $commissionType->description }}</p>
                    @endif

                    <div class="py-4 border-y border-gray-100">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Default Rate</p>
                        <p class="text-3xl font-black {{ $commissionType->calculation_type === 'fixed' ? 'text-emerald-600' : 'text-amber-600' }}">
                            @if($commissionType->calculation_type === 'fixed')
                                ৳{{ number_format($commissionType->default_rate) }}
                            @else
                                {{ $commissionType->default_rate }}%
                            @endif
                        </p>
                    </div>

                    <div class="pt-4">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-1">Assigned Users</p>
                        <p class="text-3xl font-black text-indigo-600">{{ $commissionType->users->count() }}</p>
                    </div>
                </div>

                {{-- Add User Form --}}
                <div class="bg-gray-50 p-6 border-t border-gray-100">
                    <h4 class="font-bold text-gray-900 mb-4">Assign User</h4>
                    <form action="{{ route('admin.commission-types.assign', $commissionType) }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <select name="user_id"
                                class="w-full rounded-xl border-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm @error('user_id') border-red-300 @enderror">
                                <option value="">Select a user...</option>
                                @foreach($availableUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="custom_rate" class="block text-xs font-bold text-gray-500 uppercase mb-1">
                                Custom Rate <span class="font-normal text-gray-400">(optional)</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="custom_rate" id="custom_rate" step="0.01" min="0"
                                    {{ $commissionType->calculation_type === 'percentage' ? 'max=100' : '' }}
                                    class="w-full rounded-xl border-gray-200 shadow-sm focus:border-amber-500 focus:ring-amber-500 text-sm pr-12 @error('custom_rate') border-red-300 @enderror"
                                    placeholder="Leave blank to use default">
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                                    <span class="text-gray-400 text-sm">{{ $commissionType->calculation_type === 'percentage' ? '%' : 'BDT' }}</span>
                                </div>
                            </div>
                            @error('custom_rate')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="is_primary" value="1" class="rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                <span class="text-sm text-gray-700">Set as primary commission type</span>
                            </label>
                        </div>

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:shadow-xl transition-all"
                            {{ $availableUsers->isEmpty() ? 'disabled' : '' }}>
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            Assign User
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Assigned Users List --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Assigned Users</h3>
                </div>

                @if($commissionType->users->count() > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($commissionType->users as $user)
                            <div class="p-4 hover:bg-gray-50 transition-all">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <h4 class="font-bold text-gray-900">{{ $user->name }}</h4>
                                                @if($user->pivot->is_primary)
                                                    <span class="px-2 py-0.5 rounded-lg bg-amber-100 text-amber-700 text-xs font-bold">Primary</span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-4">
                                        <div class="text-right">
                                            <p class="text-xs font-bold text-gray-500 uppercase">Rate</p>
                                            <p class="text-lg font-bold {{ $commissionType->calculation_type === 'fixed' ? 'text-emerald-600' : 'text-amber-600' }}">
                                                @if($commissionType->calculation_type === 'fixed')
                                                    ৳{{ number_format($user->pivot->custom_rate ?? $commissionType->default_rate) }}
                                                @else
                                                    {{ $user->pivot->custom_rate ?? $commissionType->default_rate }}%
                                                @endif
                                                @if($user->pivot->custom_rate)
                                                    <span class="text-xs text-gray-400 font-normal">(custom)</span>
                                                @endif
                                            </p>
                                        </div>

                                        <form action="{{ route('admin.commission-types.remove', [$commissionType, $user]) }}" method="POST"
                                            onsubmit="return confirm('Remove {{ $user->name }} from this commission type?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center p-2.5 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 transition-all">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-12 text-center">
                        <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1">No Users Assigned</h4>
                        <p class="text-sm text-gray-500">Use the form on the left to assign users to this commission type.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
