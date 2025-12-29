<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-xl shadow-amber-500/30">
                    <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Commission Types</h2>
                    <p class="text-sm text-gray-500">Create and manage commission structures</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.commission-types.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:shadow-xl transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    New Commission Type
                </a>
                <a href="{{ route('admin.commissions.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Dashboard
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

    {{-- Commission Types Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($commissionTypes as $type)
            <div class="rounded-2xl bg-white shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden hover:shadow-2xl transition-all group">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br {{ $type->calculation_type === 'fixed' ? 'from-emerald-500 to-green-600' : 'from-amber-500 to-orange-600' }} text-white shadow-lg">
                                @if($type->calculation_type === 'fixed')
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                @else
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $type->name }}</h3>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-xs font-bold {{ $type->calculation_type === 'fixed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($type->calculation_type) }}
                                </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-1">
                            @if($type->is_default)
                                <span class="px-2 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold">Default</span>
                            @endif
                            @if(!$type->is_active)
                                <span class="px-2 py-1 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold">Inactive</span>
                            @endif
                        </div>
                    </div>

                    @if($type->description)
                        <p class="text-sm text-gray-500 mb-4">{{ Str::limit($type->description, 80) }}</p>
                    @endif

                    <div class="flex items-center justify-between py-3 border-t border-gray-100">
                        <div>
                            <p class="text-xs font-bold text-gray-500 uppercase">Default Rate</p>
                            <p class="text-2xl font-black {{ $type->calculation_type === 'fixed' ? 'text-emerald-600' : 'text-amber-600' }}">
                                @if($type->calculation_type === 'fixed')
                                    ৳{{ number_format($type->default_rate) }}
                                @else
                                    {{ $type->default_rate }}%
                                @endif
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-gray-500 uppercase">Assigned Users</p>
                            <p class="text-2xl font-black text-indigo-600">{{ $type->users_count }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.commission-types.edit', $type) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-100 text-indigo-700 text-sm font-semibold hover:bg-indigo-200 transition-all">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </a>
                        <a href="{{ route('admin.commission-types.users', $type) }}"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-purple-100 text-purple-700 text-sm font-semibold hover:bg-purple-200 transition-all">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Users
                        </a>
                        @if($type->users_count === 0)
                            <form action="{{ route('admin.commission-types.destroy', $type) }}" method="POST" class="inline"
                                onsubmit="return confirm('Are you sure you want to delete this commission type?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center justify-center p-2.5 rounded-xl bg-red-100 text-red-700 hover:bg-red-200 transition-all">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="rounded-2xl bg-white shadow-lg border border-gray-100 p-12 text-center">
                    <div class="mx-auto h-20 w-20 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 flex items-center justify-center mb-6">
                        <svg class="h-10 w-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">No Commission Types Yet</h3>
                    <p class="text-sm text-gray-500 mb-6">Create your first commission type to start assigning commissions to team members.</p>
                    <a href="{{ route('admin.commission-types.create') }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-600 to-orange-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:shadow-xl transition-all">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Create Commission Type
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</x-app-layout>
