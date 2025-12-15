<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Delete User</h2>
                    <p class="text-sm text-gray-500">Confirm user deletion</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-2xl">
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-6 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/20">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white">Delete User Confirmation</h3>
                </div>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-4">
                    <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-red-100 to-rose-100 text-red-600 shadow-lg shadow-red-500/20">
                        <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm text-gray-600">
                            <p class="text-base">Are you sure you want to delete <strong class="font-bold text-gray-900">{{ $user->name }}</strong>?</p>
                            <p class="text-gray-500 mt-1">{{ $user->email }}</p>
                            <p class="mt-3 font-bold text-red-600">This action cannot be undone.</p>
                        </div>

                        @if ($user->leads_count > 0)
                            <div class="mt-6 overflow-hidden rounded-2xl border-2 border-amber-200 bg-gradient-to-r from-amber-50 to-yellow-50">
                                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-3">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-bold text-white">{{ $user->leads_count }} Assigned Lead(s)</span>
                                    </div>
                                </div>
                                <div class="p-5">
                                    <p class="text-sm font-semibold text-amber-800 mb-4">What should happen to these leads?</p>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" id="delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <div class="space-y-3">
                                            <label class="relative cursor-pointer block">
                                                <input type="radio" name="lead_action" value="reassign" required class="peer sr-only">
                                                <div class="rounded-xl border-2 border-gray-200 bg-white p-4 transition-all duration-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:shadow-lg peer-checked:shadow-indigo-500/20">
                                                    <div class="flex items-start gap-3">
                                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="block text-sm font-bold text-gray-900">Reassign all leads to Admin</span>
                                                            <span class="block text-xs text-gray-500 mt-1">Leads will be transferred to an admin user</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>

                                            <label class="relative cursor-pointer block">
                                                <input type="radio" name="lead_action" value="delete" required class="peer sr-only">
                                                <div class="rounded-xl border-2 border-gray-200 bg-white p-4 transition-all duration-200 peer-checked:border-red-500 peer-checked:bg-red-50 peer-checked:shadow-lg peer-checked:shadow-red-500/20">
                                                    <div class="flex items-start gap-3">
                                                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <span class="block text-sm font-bold text-red-900">Delete all leads permanently</span>
                                                            <span class="block text-xs text-red-600 mt-1">All {{ $user->leads_count }} leads will be permanently deleted</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @else
                            <form action="{{ route('users.destroy', $user) }}" method="POST" id="delete-form" class="hidden">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="lead_action" value="none">
                            </form>
                        @endif

                        <div class="mt-6 flex gap-3 pt-4 border-t border-gray-100">
                            <button type="submit" form="delete-form"
                                class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-red-600 to-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-500/30 hover:shadow-xl transition-all">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Yes, Delete User
                            </button>
                            <a href="{{ route('users.index') }}"
                                class="inline-flex items-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-200 transition-all">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
