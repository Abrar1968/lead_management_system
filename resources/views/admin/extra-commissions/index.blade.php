<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Extra Commissions</h2>
                    <p class="text-sm text-gray-500">Manage bonus and extra commission payments</p>
                </div>
            </div>
            <a href="{{ route('admin.extra-commissions.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-500/30 transition-all hover:shadow-xl hover:-translate-y-0.5">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Extra Commission
            </a>
        </div>
    </x-slot>

    @if(session('success'))
        <div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 p-4 border border-emerald-200 shadow-lg shadow-emerald-500/10">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    {{-- Filters --}}
    <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Filters</span>
            </div>
            <select name="status"
                class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                <option value="">All Statuses</option>
                <option value="Pending" {{ $currentStatus === 'Pending' ? 'selected' : '' }}>Pending</option>
                <option value="Approved" {{ $currentStatus === 'Approved' ? 'selected' : '' }}>Approved</option>
                <option value="Paid" {{ $currentStatus === 'Paid' ? 'selected' : '' }}>Paid</option>
            </select>
            <select name="user_id"
                class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                <option value="">All Users</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $currentUserId == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-500/30 hover:shadow-xl transition-all">
                Apply Filters
            </button>
            <a href="{{ route('admin.extra-commissions.index') }}"
                class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                Reset
            </a>
        </form>
    </div>

    {{-- Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-700 to-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white">User</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white">Type</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white">Description</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-white">Amount</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-white">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($commissions as $commission)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gradient-to-br from-purple-500 to-indigo-600 text-white text-xs font-bold shadow-lg shadow-purple-500/30">
                                        {{ strtoupper(substr($commission->user->name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm font-bold text-gray-900">{{ $commission->user->name }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <span class="inline-flex items-center rounded-lg bg-indigo-100 px-2.5 py-1 text-xs font-bold text-indigo-800">
                                    {{ $commission->commission_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ Str::limit($commission->description, 40) }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-600">
                                {{ $commission->date_earned->format('M d, Y') }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                    @switch($commission->status)
                                        @case('Pending') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                        @case('Approved') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                        @case('Paid') bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-blue-500/30 @break
                                    @endswitch">
                                    {{ $commission->status }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <span class="text-lg font-bold text-purple-600">৳{{ number_format($commission->amount) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    @if($commission->status === 'Pending')
                                        <form action="{{ route('admin.extra-commissions.approve', $commission) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-emerald-100 text-emerald-600 hover:bg-emerald-200 hover:text-emerald-800 transition-all"
                                                title="Approve">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </button>
                                        </form>
                                    @elseif($commission->status === 'Approved')
                                        <form action="{{ route('admin.extra-commissions.mark-paid', $commission) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 hover:text-blue-800 transition-all"
                                                title="Mark Paid">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.extra-commissions.edit', $commission) }}"
                                        class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-800 transition-all"
                                        title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.extra-commissions.destroy', $commission) }}" method="POST"
                                          onsubmit="return confirm('Delete this commission?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition-all"
                                            title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No extra commissions found</p>
                                <p class="text-xs text-gray-400 mt-1">Add a new commission to get started</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($commissions->hasPages())
            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                {{ $commissions->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
