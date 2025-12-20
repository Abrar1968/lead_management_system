<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    All Leads
                </h2>
                <p class="mt-1 text-sm text-gray-500">Complete list of all leads in the system</p>
            </div>
            <a href="{{ route('leads.create') }}"
                class="group inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-blue-500/40 hover:-translate-y-0.5">
                <span
                    class="flex h-6 w-6 items-center justify-center rounded-lg bg-white/20 transition-transform duration-300 group-hover:rotate-90">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                </span>
                Add Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
            {{-- Notice --}}
            <div
                class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 p-5 border border-blue-200 shadow-lg shadow-blue-500/10">
                <div
                    class="absolute -right-8 -top-8 h-32 w-32 rounded-full bg-gradient-to-br from-blue-200/40 to-indigo-200/40">
                </div>
                <div class="relative flex items-start gap-4">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-blue-800">
                            For a better experience, use the
                            <a href="{{ route('leads.daily') }}"
                                class="inline-flex items-center gap-1 font-semibold text-blue-600 underline decoration-2 underline-offset-2 transition-colors hover:text-blue-700">
                                Daily View
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            or
                            <a href="{{ route('leads.monthly') }}"
                                class="inline-flex items-center gap-1 font-semibold text-blue-600 underline decoration-2 underline-offset-2 transition-colors hover:text-blue-700">
                                Monthly View
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                            to browse leads by date.
                        </p>
                    </div>
                </div>
            </div>

            @if (auth()->user()->role === 'admin')
                {{-- Bulk Actions Bar --}}
                <div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 p-4 shadow-lg shadow-indigo-500/30"
                    id="bulkActionsBar" style="display: none;">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <span class="text-sm font-medium text-white">
                            <strong id="selectedCount" class="text-lg">0</strong> lead(s) selected
                        </span>
                        <div class="flex flex-wrap gap-2">
                            <button type="button" onclick="showReassignModal()"
                                class="inline-flex items-center gap-2 rounded-xl bg-white/20 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition-all duration-300 hover:bg-white/30 hover:shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                </svg>
                                Reassign
                            </button>
                            <button type="button" onclick="showStatusModal()"
                                class="inline-flex items-center gap-2 rounded-xl bg-white/20 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition-all duration-300 hover:bg-white/30 hover:shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                Update Status
                            </button>
                            <button type="button" onclick="confirmBulkDelete()"
                                class="inline-flex items-center gap-2 rounded-xl bg-red-500 px-4 py-2 text-sm font-semibold text-white transition-all duration-300 hover:bg-red-600 hover:shadow-lg">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Leads Table --}}
            <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                            <tr>
                                @if (auth()->user()->role === 'admin')
                                    <th scope="col" class="px-4 py-4 text-left">
                                        <input type="checkbox" id="selectAll" onchange="toggleAll(this)"
                                            class="h-4 w-4 rounded-lg border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all duration-200">
                                    </th>
                                @endif
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Lead
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Contact
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Source / Service
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Follow-up
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Meeting
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Assigned To
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-600">
                                    Date
                                </th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @forelse($leads as $lead)
                                <tr
                                    class="group transition-all duration-200 hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-indigo-50/50">
                                    @if (auth()->user()->role === 'admin')
                                        <td class="whitespace-nowrap px-4 py-4">
                                            <input type="checkbox"
                                                class="lead-checkbox h-4 w-4 rounded-lg border-gray-300 text-indigo-600 focus:ring-indigo-500 transition-all duration-200"
                                                value="{{ $lead->id }}" data-lead-id="{{ $lead->id }}"
                                                onchange="updateBulkActions()">
                                        </td>
                                    @endif
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-sm font-bold text-white shadow-lg shadow-blue-500/25">
                                                {{ strtoupper(substr($lead->customer_name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('leads.show', $lead) }}"
                                                    class="text-sm font-semibold text-blue-600 transition-colors duration-200 hover:text-blue-800">
                                                    {{ $lead->lead_number }}
                                                </a>
                                                <p class="text-sm font-medium text-gray-700">
                                                    {{ $lead->customer_name ?? 'Unknown' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2 text-sm font-medium text-gray-900">
                                                <svg class="h-4 w-4 text-blue-500" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ $lead->phone_number }}
                                            </div>
                                            @if ($lead->email)
                                                <div class="flex items-center gap-2 text-sm text-gray-500">
                                                    <svg class="h-4 w-4 text-purple-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    {{ $lead->email }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            <span
                                                class="inline-flex items-center rounded-lg bg-gradient-to-r from-emerald-500 to-green-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                                                {{ $lead->source }}
                                            </span>
                                            <span
                                                class="inline-flex items-center rounded-lg bg-gradient-to-r from-violet-500 to-purple-600 px-2.5 py-1 text-xs font-semibold text-white shadow-sm">
                                                {{ $lead->service->name ?? 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        {{-- Status Dropdown with Auto-Contact --}}
                                        <div x-data="{
                                            status: '{{ $lead->status }}',
                                            async changeStatus(newStatus, leadId) {
                                                this.status = newStatus;
                                        
                                                // 1. Update lead status
                                                try {
                                                    const statusResponse = await fetch(`/leads/${leadId}`, {
                                                        method: 'POST',
                                                        headers: {
                                                            'Content-Type': 'application/json',
                                                            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                            'Accept': 'application/json'
                                                        },
                                                        body: JSON.stringify({
                                                            _method: 'PATCH',
                                                            status: newStatus
                                                        })
                                                    });
                                        
                                                    if (!statusResponse.ok) throw new Error('Failed to update status');
                                        
                                                    // 2. If Contacted, create log
                                                    if (newStatus === 'Contacted') {
                                                        const contactResponse = await fetch('/contacts', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                                'Accept': 'application/json'
                                                            },
                                                            body: JSON.stringify({
                                                                lead_id: leadId,
                                                                call_date: new Date().toISOString().split('T')[0],
                                                                call_time: new Date().toTimeString().split(' ')[0].substring(0, 5),
                                                                response_status: 'Call Later',
                                                                notes: 'Auto-created from status change'
                                                            })
                                                        });
                                        
                                                        if (!contactResponse.ok) {
                                                            const err = await contactResponse.json();
                                                            console.error('Contact creation failed:', err);
                                                            alert('Status updated but failed to create contact log: ' + (err.message || JSON.stringify(err)));
                                                        }
                                                    }
                                        
                                                    location.reload();
                                                } catch (error) {
                                                    console.error('Error:', error);
                                                    alert('An error occurred: ' + error.message);
                                                }
                                            }
                                        }" class="relative inline-block">
                                            <select @change="changeStatus($event.target.value, {{ $lead->id }})"
                                                x-model="status"
                                                class="appearance-none cursor-pointer rounded-lg px-3 py-1.5 pr-7 text-xs font-semibold shadow-sm border-0 focus:ring-2 focus:ring-offset-0 transition-all"
                                                :class="{
                                                    'bg-gray-100 text-gray-800': status === 'New',
                                                    'bg-blue-100 text-blue-800': status === 'Contacted',
                                                    'bg-indigo-100 text-indigo-800': status === 'Qualified',
                                                    'bg-amber-100 text-amber-800': status === 'Negotiation',
                                                    'bg-emerald-100 text-emerald-800': status === 'Converted',
                                                    'bg-red-100 text-red-800': status === 'Lost'
                                                }">
                                                <option value="New">New</option>
                                                <option value="Contacted">Contacted</option>
                                                <option value="Qualified">Qualified</option>
                                                <option value="Negotiation">Negotiation</option>
                                                <option value="Converted">Converted</option>
                                                <option value="Lost">Lost</option>
                                            </select>
                                            <svg class="absolute right-1 top-1/2 h-3 w-3 -translate-y-1/2 pointer-events-none text-gray-600"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $latestFollowUp = $lead->followUps->first();
                                            $followUpCount = $lead->followUps->count();
                                        @endphp
                                        <div x-data="{ showFollowUpForm: false }" class="flex items-center gap-2">
                                            @if ($latestFollowUp)
                                                <div class="space-y-1 flex-1">
                                                    @if ($latestFollowUp->interest)
                                                        <span
                                                            class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-semibold
                                                            {{ \App\Http\Controllers\FollowUpController::INTEREST_STATUSES[$latestFollowUp->interest]['bg'] ?? 'bg-gray-100' }}
                                                            {{ \App\Http\Controllers\FollowUpController::INTEREST_STATUSES[$latestFollowUp->interest]['text'] ?? 'text-gray-800' }}">
                                                            {{ $latestFollowUp->interest }}
                                                        </span>
                                                    @endif
                                                    @if ($latestFollowUp->price)
                                                        <span
                                                            class="block text-xs font-bold text-emerald-600">৳{{ number_format($latestFollowUp->price, 0) }}</span>
                                                    @endif
                                                    <span
                                                        class="block text-xs font-medium text-gray-400">{{ $followUpCount }}
                                                        follow-up{{ $followUpCount > 1 ? 's' : '' }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-300 flex-1">—</span>
                                            @endif

                                            {{-- Quick Follow-up Button --}}
                                            <div class="relative">
                                                <button @click="showFollowUpForm = !showFollowUpForm" type="button"
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-amber-100 text-amber-600 transition-all duration-200 hover:bg-amber-200 hover:shadow-md">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                                <div x-show="showFollowUpForm"
                                                    @click.outside="showFollowUpForm = false" x-cloak
                                                    class="absolute left-0 z-50 mt-2 w-80 rounded-xl bg-white p-4 shadow-xl border border-gray-200">
                                                    <h4 class="text-sm font-bold text-gray-900 mb-3">Quick Follow-up
                                                    </h4>
                                                    <form action="{{ route('follow-ups.store') }}" method="POST"
                                                        class="space-y-3">
                                                        @csrf
                                                        <input type="hidden" name="lead_id"
                                                            value="{{ $lead->id }}">
                                                        <input type="hidden" name="redirect_back" value="1">
                                                        <div>
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 mb-1">Follow-up
                                                                Date</label>
                                                            <input type="date" name="follow_up_date" required
                                                                class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                                                                value="{{ today()->format('Y-m-d') }}">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                                            <textarea name="notes" rows="2"
                                                                class="w-full rounded-lg border-gray-300 text-sm focus:border-amber-500 focus:ring-amber-500"
                                                                placeholder="Add notes..."></textarea>
                                                        </div>
                                                        <button type="submit"
                                                            class="w-full rounded-lg bg-gradient-to-r from-amber-500 to-orange-600 px-4 py-2 text-xs font-semibold text-white transition-all hover:shadow-lg">
                                                            Create Follow-up
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        @php
                                            $latestMeeting = $lead->meetings->first();
                                            $meetingCount = $lead->meetings->count();
                                        @endphp
                                        <div x-data="{ showMeetingForm: false }" class="flex items-center gap-2">
                                            @if ($latestMeeting)
                                                <div class="space-y-1 flex-1">
                                                    @if ($latestMeeting->meeting_status)
                                                        <span
                                                            class="inline-flex items-center rounded-lg px-2 py-1 text-xs font-semibold
                                                            {{ \App\Http\Controllers\MeetingController::MEETING_STATUSES[$latestMeeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                                            {{ \App\Http\Controllers\MeetingController::MEETING_STATUSES[$latestMeeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                                            {{ $latestMeeting->meeting_status }}
                                                        </span>
                                                    @endif
                                                    @if ($latestMeeting->price)
                                                        <span
                                                            class="block text-xs font-bold text-emerald-600">৳{{ number_format($latestMeeting->price, 0) }}</span>
                                                    @endif
                                                    <span
                                                        class="block text-xs font-medium text-gray-400">{{ $meetingCount }}
                                                        meeting{{ $meetingCount > 1 ? 's' : '' }}</span>
                                                </div>
                                            @else
                                                <span class="text-xs text-gray-300 flex-1">—</span>
                                            @endif

                                            {{-- Quick Meeting Button --}}
                                            <div class="relative">
                                                <button @click="showMeetingForm = !showMeetingForm" type="button"
                                                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-indigo-100 text-indigo-600 transition-all duration-200 hover:bg-indigo-200 hover:shadow-md">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                                <div x-show="showMeetingForm" @click.outside="showMeetingForm = false"
                                                    x-cloak
                                                    class="absolute left-0 z-50 mt-2 w-80 rounded-xl bg-white p-4 shadow-xl border border-gray-200">
                                                    <h4 class="text-sm font-bold text-gray-900 mb-3">Quick Meeting</h4>
                                                    <form action="{{ route('meetings.store') }}" method="POST"
                                                        class="space-y-3">
                                                        @csrf
                                                        <input type="hidden" name="lead_id"
                                                            value="{{ $lead->id }}">
                                                        <input type="hidden" name="redirect_back" value="1">
                                                        <input type="hidden" name="meeting_type" value="Online">
                                                        <div>
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 mb-1">Meeting
                                                                Date</label>
                                                            <input type="date" name="meeting_date" required
                                                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                value="{{ today()->format('Y-m-d') }}">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                                                            <input type="text" name="location"
                                                                class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                                placeholder="Meeting location">
                                                        </div>
                                                        <button type="submit"
                                                            class="w-full rounded-lg bg-gradient-to-r from-indigo-500 to-purple-600 px-4 py-2 text-xs font-semibold text-white transition-all hover:shadow-lg">
                                                            Schedule Meeting
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="flex h-7 w-7 items-center justify-center rounded-full bg-gradient-to-br from-gray-400 to-gray-600 text-xs font-bold text-white shadow-sm">
                                                {{ $lead->assignedTo ? strtoupper(substr($lead->assignedTo->name, 0, 1)) : '?' }}
                                            </span>
                                            <span
                                                class="text-sm font-medium text-gray-700">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</span>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('leads.daily', ['date' => $lead->lead_date->format('Y-m-d')]) }}"
                                            class="inline-flex items-center gap-1.5 text-sm font-medium text-blue-600 transition-colors duration-200 hover:text-blue-800">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ $lead->lead_date->format('M d, Y') }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <a href="{{ route('leads.edit', $lead) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-gray-100 px-3 py-1.5 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-indigo-100 hover:text-indigo-700">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-16 text-center">
                                        <div
                                            class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <h3 class="mt-4 text-lg font-semibold text-gray-900">No leads found</h3>
                                        <p class="mt-1 text-sm text-gray-500">Get started by creating your first lead
                                        </p>
                                        <a href="{{ route('leads.create') }}"
                                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:shadow-xl">
                                            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path
                                                    d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                                            </svg>
                                            Create Lead
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if (auth()->user()->role === 'admin')
            <!-- Hidden Forms for Bulk Actions -->
            <form id="bulkDeleteForm" action="{{ route('leads.bulk-delete') }}" method="POST"
                style="display: none;">
                @csrf
                <div id="deleteLeadIds"></div>
            </form>

            <form id="bulkReassignForm" action="{{ route('leads.bulk-reassign') }}" method="POST"
                style="display: none;">
                @csrf
                <div id="reassignLeadIds"></div>
                <input type="hidden" name="assigned_to" id="reassignUserId">
            </form>

            <form id="bulkStatusForm" action="{{ route('leads.bulk-status') }}" method="POST"
                style="display: none;">
                @csrf
                <div id="statusLeadIds"></div>
                <input type="hidden" name="status" id="newStatus">
            </form>

            <!-- Reassign Modal -->
            <div id="reassignModal" class="fixed inset-0 z-50 overflow-y-auto hidden" x-data x-transition>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                        onclick="closeReassignModal()"></div>
                    <div
                        class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg shadow-indigo-500/30">
                                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">Reassign Selected Leads</h3>
                                <p class="mt-1 text-sm text-gray-500">Choose a team member to reassign the selected
                                    leads</p>
                                <div class="mt-5">
                                    <label for="reassign_assigned_to"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Assign to:</label>
                                    <select id="reassign_assigned_to" required
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                                        <option value="">Select a user...</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->role }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" onclick="closeReassignModal()"
                                        class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                                        Cancel
                                    </button>
                                    <button type="button" onclick="submitReassign()"
                                        class="rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-200 hover:shadow-xl">
                                        Reassign Leads
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Modal -->
            <div id="statusModal" class="fixed inset-0 z-50 overflow-y-auto hidden" x-data x-transition>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"
                        onclick="closeStatusModal()"></div>
                    <div
                        class="relative w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 shadow-2xl transition-all">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex h-14 w-14 flex-shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 shadow-lg shadow-amber-500/30">
                                <svg class="h-7 w-7 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900">Update Lead Status</h3>
                                <p class="mt-1 text-sm text-gray-500">Change the status of all selected leads at once
                                </p>
                                <div class="mt-5">
                                    <label for="status_select"
                                        class="block text-sm font-semibold text-gray-700 mb-2">New Status:</label>
                                    <select id="status_select" required
                                        class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20">
                                        <option value="">Select a status...</option>
                                        <option value="New">New</option>
                                        <option value="Contacted">Contacted</option>
                                        <option value="Qualified">Qualified</option>
                                        <option value="Negotiation">Negotiation</option>
                                        <option value="Converted">Converted</option>
                                        <option value="Lost">Lost</option>
                                    </select>
                                </div>
                                <div class="mt-6 flex gap-3 justify-end">
                                    <button type="button" onclick="closeStatusModal()"
                                        class="rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                                        Cancel
                                    </button>
                                    <button type="button" onclick="submitStatus()"
                                        class="rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-200 hover:shadow-xl">
                                        Update Status
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function getSelectedLeadIds() {
                    const checkboxes = document.querySelectorAll('.lead-checkbox:checked');
                    return Array.from(checkboxes).map(cb => cb.value);
                }

                function updateBulkActions() {
                    const selectedIds = getSelectedLeadIds();
                    const bulkBar = document.getElementById('bulkActionsBar');
                    const countDisplay = document.getElementById('selectedCount');
                    const selectAllCheckbox = document.getElementById('selectAll');

                    if (selectedIds.length > 0) {
                        bulkBar.style.display = 'flex';
                        countDisplay.textContent = selectedIds.length;
                    } else {
                        bulkBar.style.display = 'none';
                    }

                    // Update select all checkbox
                    const totalCheckboxes = document.querySelectorAll('.lead-checkbox').length;
                    selectAllCheckbox.checked = selectedIds.length === totalCheckboxes && totalCheckboxes > 0;
                }

                function toggleAll(checkbox) {
                    const checkboxes = document.querySelectorAll('.lead-checkbox');
                    checkboxes.forEach(cb => {
                        cb.checked = checkbox.checked;
                    });
                    updateBulkActions();
                }

                function confirmBulkDelete() {
                    const selectedIds = getSelectedLeadIds();
                    if (selectedIds.length === 0) {
                        alert('Please select at least one lead.');
                        return;
                    }

                    if (confirm(
                            `Are you sure you want to delete ${selectedIds.length} lead(s)? This action cannot be undone and will also delete all related contacts, follow-ups, and meetings.`
                            )) {
                        const form = document.getElementById('bulkDeleteForm');
                        const idsContainer = document.getElementById('deleteLeadIds');
                        idsContainer.innerHTML = selectedIds.map(id => `<input type="hidden" name="lead_ids[]" value="${id}">`)
                            .join('');
                        form.submit();
                    }
                }

                function showReassignModal() {
                    const selectedIds = getSelectedLeadIds();
                    if (selectedIds.length === 0) {
                        alert('Please select at least one lead.');
                        return;
                    }
                    document.getElementById('reassignModal').classList.remove('hidden');
                }

                function closeReassignModal() {
                    document.getElementById('reassignModal').classList.add('hidden');
                    document.getElementById('reassign_assigned_to').value = '';
                }

                function submitReassign() {
                    const userId = document.getElementById('reassign_assigned_to').value;
                    if (!userId) {
                        alert('Please select a user.');
                        return;
                    }

                    const selectedIds = getSelectedLeadIds();
                    console.log('Reassign - Selected IDs:', selectedIds);
                    console.log('Reassign - User ID:', userId);

                    const form = document.getElementById('bulkReassignForm');
                    const idsContainer = document.getElementById('reassignLeadIds');

                    idsContainer.innerHTML = selectedIds.map(id => `<input type="hidden" name="lead_ids[]" value="${id}">`).join(
                    '');
                    document.getElementById('reassignUserId').value = userId;

                    console.log('Reassign - Form action:', form.action);
                    console.log('Reassign - Form data:', new FormData(form));
                    form.submit();
                }

                function showStatusModal() {
                    const selectedIds = getSelectedLeadIds();
                    if (selectedIds.length === 0) {
                        alert('Please select at least one lead.');
                        return;
                    }
                    document.getElementById('statusModal').classList.remove('hidden');
                }

                function closeStatusModal() {
                    document.getElementById('statusModal').classList.add('hidden');
                    document.getElementById('status_select').value = '';
                }

                function submitStatus() {
                    const status = document.getElementById('status_select').value;
                    if (!status) {
                        alert('Please select a status.');
                        return;
                    }

                    const selectedIds = getSelectedLeadIds();
                    console.log('Status - Selected IDs:', selectedIds);
                    console.log('Status - New Status:', status);

                    const form = document.getElementById('bulkStatusForm');
                    const idsContainer = document.getElementById('statusLeadIds');

                    idsContainer.innerHTML = selectedIds.map(id => `<input type="hidden" name="lead_ids[]" value="${id}">`).join(
                    '');
                    document.getElementById('newStatus').value = status;

                    console.log('Status - Form action:', form.action);
                    console.log('Status - Form data:', new FormData(form));
                    form.submit();
                }
            </script>
        @endif
    </div>
</x-app-layout>
