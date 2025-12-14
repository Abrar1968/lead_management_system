<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                All Leads
            </h2>
            <a href="{{ route('leads.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="{
        selectedLeads: [],
        selectAll: false,
        showBulkModal: false,
        bulkAction: '',
        targetUser: '',
        targetStatus: '',
        toggleAll() {
            if (this.selectAll) {
                this.selectedLeads = [...document.querySelectorAll('[data-lead-id]')].map(el => el.dataset.leadId);
            } else {
                this.selectedLeads = [];
            }
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

            {{-- Notice --}}
            <div class="mb-6 rounded-lg bg-blue-50 p-4 border border-blue-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            For a better experience, use the
                            <a href="{{ route('leads.daily') }}" class="font-medium underline hover:text-blue-600">Daily View</a>
                            or
                            <a href="{{ route('leads.monthly') }}" class="font-medium underline hover:text-blue-600">Monthly View</a>
                            to browse leads by date.
                        </p>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role === 'admin')
            {{-- Bulk Actions Bar --}}
            <div class="mb-4 flex items-center justify-between" x-show="selectedLeads.length > 0" x-cloak>
                <span class="text-sm text-gray-600">
                    <strong x-text="selectedLeads.length"></strong> lead(s) selected
                </span>
                <div class="flex gap-2">
                    <button type="button"
                            @click="bulkAction = 'reassign'; showBulkModal = true"
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                        Reassign
                    </button>
                    <button type="button"
                            @click="bulkAction = 'status'; showBulkModal = true"
                            class="inline-flex items-center rounded-md bg-amber-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500">
                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        Update Status
                    </button>
                    <button type="button"
                            @click="bulkAction = 'delete'; showBulkModal = true"
                            class="inline-flex items-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                        <svg class="mr-1.5 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
            @endif

            {{-- Leads Table --}}
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                @if(auth()->user()->role === 'admin')
                                <th scope="col" class="px-3 py-3 text-left">
                                    <input type="checkbox"
                                           x-model="selectAll"
                                           @change="toggleAll()"
                                           class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                @endif
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Lead
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Source / Service
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Assigned To
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Date
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($leads as $lead)
                                <tr class="hover:bg-gray-50" :class="{ 'bg-indigo-50': selectedLeads.includes('{{ $lead->id }}') }">
                                    @if(auth()->user()->role === 'admin')
                                    <td class="whitespace-nowrap px-3 py-4">
                                        <input type="checkbox"
                                               data-lead-id="{{ $lead->id }}"
                                               value="{{ $lead->id }}"
                                               x-model="selectedLeads"
                                               class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    @endif
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div>
                                            <a href="{{ route('leads.show', $lead) }}"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                {{ $lead->lead_number }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ $lead->customer_name ?? 'Unknown' }}</p>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $lead->phone_number }}</div>
                                        @if($lead->email)
                                            <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                            {{ $lead->source }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">
                                            {{ $lead->service_interested }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            @switch($lead->status)
                                                @case('New') bg-gray-100 text-gray-800 @break
                                                @case('Contacted') bg-blue-100 text-blue-800 @break
                                                @case('Qualified') bg-indigo-100 text-indigo-800 @break
                                                @case('Negotiation') bg-yellow-100 text-yellow-800 @break
                                                @case('Converted') bg-green-100 text-green-800 @break
                                                @case('Lost') bg-red-100 text-red-800 @break
                                            @endswitch">
                                            {{ $lead->status }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $lead->assignedTo?->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <a href="{{ route('leads.daily', ['date' => $lead->lead_date->format('Y-m-d')]) }}"
                                           class="text-indigo-600 hover:text-indigo-500">
                                            {{ $lead->lead_date->format('M d, Y') }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('leads.edit', $lead) }}"
                                           class="text-indigo-600 hover:text-indigo-500">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No leads found. <a href="{{ route('leads.create') }}" class="text-indigo-600 hover:underline">Create your first lead</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'admin')
        <!-- Bulk Action Modal -->
        <div x-show="showBulkModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <div class="flex min-h-screen items-center justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div x-show="showBulkModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                     @click="showBulkModal = false"></div>

                <!-- Modal panel -->
                <div x-show="showBulkModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block transform overflow-hidden rounded-lg bg-white text-left align-bottom shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:align-middle">

                    <!-- Delete Action -->
                    <template x-if="bulkAction === 'delete'">
                        <form action="{{ route('leads.bulk-delete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <template x-for="id in selectedLeads" :key="id">
                                <input type="hidden" name="lead_ids[]" :value="id">
                            </template>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Delete Selected Leads</h3>
                                        <div class="mt-2">
                                            <p class="text-sm text-gray-500">
                                                Are you sure you want to delete <strong x-text="selectedLeads.length"></strong> lead(s)?
                                                This action cannot be undone and will also delete all related contacts, follow-ups, and meetings.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                <button type="submit" class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">
                                    Delete Leads
                                </button>
                                <button type="button" @click="showBulkModal = false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </template>

                    <!-- Reassign Action -->
                    <template x-if="bulkAction === 'reassign'">
                        <form action="{{ route('leads.bulk-reassign') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <template x-for="id in selectedLeads" :key="id">
                                <input type="hidden" name="lead_ids[]" :value="id">
                            </template>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-indigo-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Reassign Selected Leads</h3>
                                        <div class="mt-4">
                                            <label for="assigned_to" class="block text-sm font-medium text-gray-700">Assign to:</label>
                                            <select name="assigned_to" id="assigned_to" required x-model="targetUser"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="">Select a user...</option>
                                                @foreach(\App\Models\User::all() as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->role }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                <button type="submit" :disabled="!targetUser" class="inline-flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    Reassign Leads
                                </button>
                                <button type="button" @click="showBulkModal = false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </template>

                    <!-- Update Status Action -->
                    <template x-if="bulkAction === 'status'">
                        <form action="{{ route('leads.bulk-status') }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <template x-for="id in selectedLeads" :key="id">
                                <input type="hidden" name="lead_ids[]" :value="id">
                            </template>
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-amber-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                        </svg>
                                    </div>
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <h3 class="text-lg font-medium leading-6 text-gray-900">Update Lead Status</h3>
                                        <div class="mt-4">
                                            <label for="status" class="block text-sm font-medium text-gray-700">New Status:</label>
                                            <select name="status" id="status" required x-model="targetStatus"
                                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                                <option value="">Select a status...</option>
                                                <option value="New">New</option>
                                                <option value="Contacted">Contacted</option>
                                                <option value="Qualified">Qualified</option>
                                                <option value="Negotiation">Negotiation</option>
                                                <option value="Converted">Converted</option>
                                                <option value="Lost">Lost</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                                <button type="submit" :disabled="!targetStatus" class="inline-flex w-full justify-center rounded-md border border-transparent bg-amber-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-amber-700 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    Update Status
                                </button>
                                <button type="button" @click="showBulkModal = false" class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </template>
                </div>
            </div>
        </div>
        @endif
    </div>
</x-app-layout>
