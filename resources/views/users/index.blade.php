<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                User Management
            </h2>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path
                        d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add User
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        showDeleteModal: false,
        deleteUserId: null,
        deleteUserName: '',
        leadsCount: 0,
        leadAction: 'cancel',
        deleteUrl: '',
        confirmDelete(id, name, leads, url) {
            this.deleteUserId = id;
            this.deleteUserName = name;
            this.leadsCount = leads;
            this.deleteUrl = url;
            this.leadAction = 'cancel';
            this.showDeleteModal = true;
        }
    }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 p-4 border border-red-200">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            {{-- Filters --}}
            <div class="mb-6 flex flex-wrap gap-4">
                <form method="GET" class="flex flex-wrap gap-2">
                    <select name="role" class="rounded-md border-gray-300 text-sm">
                        <option value="">All Roles</option>
                        <option value="admin" {{ $currentRole === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="sales_person" {{ $currentRole === 'sales_person' ? 'selected' : '' }}>Sales
                            Person</option>
                    </select>
                    <input type="text" name="search" value="{{ $currentSearch }}"
                        placeholder="Search name or email..." class="rounded-md border-gray-300 text-sm">
                    <button type="submit"
                        class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Filter
                    </button>
                    <a href="{{ route('users.index') }}"
                        class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Users Table --}}
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Email</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">Role</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">Commission
                                </th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">Leads</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">
                                    Conversions</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        <a href="{{ route('users.show', $user) }}" class="hover:text-indigo-600">
                                            {{ $user->name }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $user->email }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                        @if ($user->commission_type === 'fixed')
                                            ৳{{ number_format($user->default_commission_rate) }}
                                        @else
                                            {{ $user->default_commission_rate }}%
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $user->leads_count }}
                                    </td>
                                    <td
                                        class="whitespace-nowrap px-6 py-4 text-center text-sm font-semibold text-green-600">
                                        {{ $user->conversions_count }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('users.show', $user) }}"
                                                class="text-gray-600 hover:text-gray-900">View</a>
                                            <a href="{{ route('users.edit', $user) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            @if ($user->id !== auth()->id())
                                                <button type="button" class="text-red-600 hover:text-red-900 ml-2"
                                                    @click="confirmDelete({{ $user->id }}, '{{ addslashes($user->name) }}', {{ $user->leads_count }}, '{{ route('users.destroy', $user) }}')">
                                                    Delete
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No users found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($users->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Delete User Modal -->
        <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title"
            role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="showDeleteModal = false">
                </div>

                <!-- Modal panel -->
                <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div
                                class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Delete User
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Are you sure you want to delete <strong x-text="deleteUserName"></strong>?
                                    </p>

                                    <!-- Show options when user has leads -->
                                    <template x-if="leadsCount > 0">
                                        <div class="mt-4 space-y-3 border-t pt-4">
                                            <p class="text-sm font-medium text-amber-700">
                                                ⚠️ This user has <span x-text="leadsCount"></span> assigned lead(s).
                                                What should happen to them?
                                            </p>
                                            <div class="space-y-2">
                                                <label class="flex items-center gap-2">
                                                    <input type="radio" name="lead_action_modal" value="reassign"
                                                        x-model="leadAction"
                                                        class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                                    <span class="text-sm text-gray-700">Reassign all leads to
                                                        Admin</span>
                                                </label>
                                                <label class="flex items-center gap-2">
                                                    <input type="radio" name="lead_action_modal" value="delete"
                                                        x-model="leadAction"
                                                        class="h-4 w-4 border-gray-300 text-red-600 focus:ring-red-500">
                                                    <span class="text-sm text-red-700">Delete all leads
                                                        permanently</span>
                                                </label>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                        <form :action="deleteUrl" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="lead_action" :value="leadsCount > 0 ? leadAction : 'none'">
                            <button type="submit"
                                :disabled="!deleteUrl || (leadsCount > 0 && (leadAction === 'cancel' ||
                                    leadAction === ''))"
                                class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                Delete User
                            </button>
                        </form>
                        <button type="button" @click="showDeleteModal = false; leadAction = 'cancel'"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
