<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">User Management</h2>
                    <p class="text-sm text-gray-500">{{ $users->total() }} total users</p>
                </div>
            </div>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all hover:shadow-xl hover:-translate-y-0.5">
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add User
            </a>
        </div>
    </x-slot>

    {{-- Filters --}}
    <div class="mb-6 overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-5">
        <form method="GET" class="flex flex-wrap items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-purple-100">
                    <svg class="h-4 w-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </div>
                <span class="text-sm font-semibold text-gray-700">Filters</span>
            </div>
            <select name="role"
                class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20">
                <option value="">All Roles</option>
                <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="sales_person" {{ $currentRole === 'sales_person' ? 'selected' : '' }}>Sales Person
                </option>
            </select>
            <input type="text" name="search" value="{{ $currentSearch }}" placeholder="Search name or email..."
                class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-purple-500 focus:bg-white focus:ring-2 focus:ring-purple-500/20 w-64">
            <button type="submit"
                class="rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-purple-500/30 hover:shadow-xl transition-all">
                Apply Filters
            </button>
            <a href="{{ route('users.index') }}"
                class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                Reset
            </a>
        </form>
    </div>

    {{-- Users Table --}}
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-700 to-gray-800">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-white">User</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white">Role
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white">
                            Commission</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white">Leads
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider text-white">
                            Conversions</th>
                        <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-white">Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="whitespace-nowrap px-6 py-4">
                                <a href="{{ route('users.show', $user) }}" class="flex items-center gap-3 group">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30 font-bold">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p
                                            class="text-sm font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                            {{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                    </div>
                                </a>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center rounded-lg px-3 py-1.5 text-xs font-bold shadow-sm
                                    {{ $user->role === 'admin'
                                        ? 'bg-gradient-to-r from-purple-500 to-indigo-600 text-white shadow-purple-500/30'
                                        : 'bg-gradient-to-r from-blue-500 to-cyan-600 text-white shadow-blue-500/30' }}">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center rounded-lg bg-amber-100 px-3 py-1.5 text-xs font-bold text-amber-800">
                                    @if ($user->commission_type === 'fixed')
                                        ৳{{ number_format($user->default_commission_rate) }}
                                    @else
                                        {{ $user->default_commission_rate }}%
                                    @endif
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span class="text-sm font-bold text-gray-900">{{ $user->leads_count }}</span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-center">
                                <span
                                    class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 px-3 py-1.5 text-xs font-bold text-white shadow-sm shadow-emerald-500/30">
                                    {{ $user->conversions_count }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <a href="{{ route('users.show', $user) }}"
                                        class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 hover:text-gray-900 transition-all"
                                        title="View">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    <a href="{{ route('users.edit', $user) }}"
                                        class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-indigo-100 text-indigo-600 hover:bg-indigo-200 hover:text-indigo-800 transition-all"
                                        title="Edit">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>
                                    @if ($user->id !== auth()->id())
                                        <a href="{{ route('users.delete', $user) }}"
                                            class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 hover:text-red-800 transition-all"
                                            title="Delete">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div
                                    class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No users found</p>
                                <p class="text-xs text-gray-400 mt-1">Try adjusting your filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="border-t border-gray-100 bg-gray-50 px-6 py-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
