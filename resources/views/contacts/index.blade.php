<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Call Log</h2>
                    <p class="text-sm text-gray-500">Track all client communications</p>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Flash Messages -->
    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 text-white shadow-lg shadow-green-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Calls Today</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_calls'] }}</p>
            </div>
        </div>
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Positive Response</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['positive_responses'] }}</p>
            </div>
        </div>
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018a2 2 0 01.485.06l3.76.94m-7 10v5a2 2 0 002 2h.096c.5 0 .905-.405.905-.904 0-.715.211-1.413.608-2.008L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Negative Response</p>
                <p class="text-3xl font-bold text-red-600 mt-1">{{ $stats['negative_responses'] }}</p>
            </div>
        </div>
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending Callbacks</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['pending_callbacks'] }}</p>
            </div>
        </div>
    </div>

    <!-- Response Breakdown -->
    @if (count($responseBreakdown) > 0)
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-6 mb-6">
            <h3 class="text-sm font-semibold text-gray-700 mb-4">Today's Response Breakdown</h3>
            <div class="flex flex-wrap gap-3">
                @foreach ($responseBreakdown as $status => $count)
                    <div
                        class="flex items-center gap-3 px-4 py-3 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                        <span class="w-3 h-3 rounded-full {{ $statuses[$status]['bg'] ?? 'bg-gray-400' }}"></span>
                        <span class="text-sm font-medium text-gray-700">{{ $status }}</span>
                        <span
                            class="text-sm font-bold text-gray-900 bg-white px-2 py-0.5 rounded-lg shadow-sm">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Date</label>
                <input type="date" name="date" value="{{ $currentDate }}"
                    class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Response Status</label>
                <select name="response_status"
                    class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20">
                    <option value="">All Responses</option>
                    @foreach ($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('response_status') === $key ? 'selected' : '' }}>
                            {{ $status['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-green-500/30 hover:shadow-xl transition-all">
                    Filter
                </button>
                <a href="{{ route('contacts.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Calls Table -->
    @php
        $contactsJson = $contacts
            ->getCollection()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'response_status' => $contact->response_status,
                    'notes' => $contact->notes,
                    'client_name' => $contact->lead->client_name ?? 'N/A',
                ];
            })
            ->keyBy('id');
    @endphp
    <script>
        window.contactsData = {!! json_encode($contactsJson) !!};
    </script>
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white">Call Records</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Lead
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date &
                            Time</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                            Response</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Caller
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Notes
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($contacts as $contact)
                        @if ($contact->lead)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="{{ route('leads.show', $contact->lead) }}" class="group">
                                        <p
                                            class="font-semibold text-gray-900 group-hover:text-green-600 transition-colors">
                                            {{ $contact->lead->client_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $contact->lead->phone_number }}</p>
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $contact->call_date->format('M j, Y') }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ \Carbon\Carbon::parse($contact->call_time)->format('h:i A') }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap" x-data="{
                                    status: '{{ $contact->response_status }}',
                                    async updateStatus(newStatus) {
                                        try {
                                            const response = await fetch('{{ route('contacts.update', $contact) }}', {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                    'Accept': 'application/json',
                                                    'Content-Type': 'application/json'
                                                },
                                                body: JSON.stringify({
                                                    _method: 'PATCH',
                                                    response_status: newStatus
                                                })
                                            });
                                
                                            if (response.ok) {
                                                this.status = newStatus;
                                                // Optional: reload to see lead status changes or toast
                                            }
                                        } catch (e) { console.error(e); }
                                    }
                                }">
                                    <div class="relative inline-block">
                                        <select @change="updateStatus($event.target.value)" x-model="status"
                                            class="appearance-none cursor-pointer rounded-lg px-3 py-1.5 pr-8 text-xs font-bold shadow-sm border-0 focus:ring-2 focus:ring-offset-0 transition-all text-white"
                                            :class="{
                                                'bg-green-600': status === 'Interested' || status === 'Yes',
                                                'bg-yellow-500': status === '50%',
                                                'bg-blue-500': status === 'Call Later',
                                                'bg-gray-500': status === 'No Response',
                                                'bg-red-500': status === 'No',
                                                'bg-gray-400': status === 'Phone off'
                                            }">
                                            @foreach ($statuses as $key => $statusData)
                                                <option value="{{ $key }}" class="bg-white text-gray-900">
                                                    {{ $statusData['label'] }}</option>
                                            @endforeach
                                        </select>
                                        <svg class="absolute right-2 top-1/2 h-3.5 w-3.5 -translate-y-1/2 pointer-events-none text-white/80"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 text-xs font-bold text-gray-600">
                                            {{ substr($contact->caller->name ?? 'N', 0, 1) }}
                                        </div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $contact->caller->name ?? 'N/A' }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-gray-600 max-w-xs truncate">
                                        {{ Str::limit($contact->notes, 50) }}</p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap flex items-center gap-2">
                                    <button type="button"
                                        x-on:click="$dispatch('open-modal', 'edit-contact'); $dispatch('set-edit-contact', window.contactsData[{{ $contact->id }}])"
                                        class="text-xs px-3 py-1.5 bg-indigo-100 text-indigo-700 rounded-lg font-semibold hover:bg-indigo-200 transition-colors">
                                        Edit
                                    </button>
                                    <form action="{{ route('contacts.destroy', $contact) }}" method="POST"
                                        class="inline" onsubmit="return confirm('Delete this call record?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-xs px-3 py-1.5 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div
                                    class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No call records found for this date.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($contacts->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
    <!-- Edit Contact Modal -->
    <x-modal name="edit-contact" focusable>
        <div x-data="{
            contact: {},
            loading: false,
            async submit() {
                this.loading = true;
                try {
                    const response = await fetch(`/contacts/${this.contact.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            _method: 'PATCH',
                            response_status: this.contact.response_status,
                            notes: this.contact.notes
                        })
                    });
        
                    if (response.ok) {
                        window.location.reload();
                    }
                } catch (e) {
                    console.error(e);
                    alert('Failed to update contact record.');
                } finally {
                    this.loading = false;
                }
            }
        }" x-on:set-edit-contact.window="contact = $event.detail" class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">
                    Edit Call Record
                    <span class="block text-sm font-medium text-gray-500 mt-1" x-text="contact.client_name"></span>
                </h2>
                <button @click="$dispatch('close-modal', 'edit-contact')"
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Response Status</label>
                    <select x-model="contact.response_status" required
                        class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20">
                        @foreach ($statuses as $key => $statusData)
                            <option value="{{ $key }}">{{ $statusData['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                    <textarea x-model="contact.notes" rows="4"
                        class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500/20"
                        placeholder="Add more detailed notes about the call..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" @click="$dispatch('close-modal', 'edit-contact')"
                        class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-6 py-2.5 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-green-500/30 hover:shadow-xl transition-all disabled:opacity-50"
                        :disabled="loading">
                        <span x-show="!loading">Save Changes</span>
                        <span x-show="loading">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>
