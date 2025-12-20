<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">
                            {{ $lead->lead_number }}
                        </h2>
                        <p class="mt-0.5 text-sm text-gray-500">
                            Created on {{ $lead->lead_date->format('F d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('leads.edit', $lead) }}"
                    class="group inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-indigo-500/30 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:scale-110" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('leads.daily', ['date' => $lead->lead_date->format('Y-m-d')]) }}"
                    class="group inline-flex items-center gap-2 rounded-xl bg-gray-100 px-4 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-300 hover:bg-gray-200 hover:-translate-x-1">
                    <svg class="h-4 w-4 transition-transform duration-300 group-hover:-translate-x-1" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Daily
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Success Message --}}


            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Main Lead Information --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Lead Details Card --}}
                    <div
                        class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Lead Details</h3>
                            </div>
                        </div>
                        <div class="p-6">
                            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <div class="group">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Customer
                                        Name</dt>
                                    <dd class="mt-2 text-base font-semibold text-gray-900">
                                        {{ $lead->client_name ?? 'Not provided' }}</dd>
                                </div>
                                <div class="group">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Phone
                                        Number</dt>
                                    <dd class="mt-2">
                                        <a href="tel:{{ $lead->phone_number }}"
                                            class="inline-flex items-center gap-2 text-base font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            {{ $lead->phone_number }}
                                        </a>
                                    </dd>
                                </div>
                                <div class="group">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Email</dt>
                                    <dd class="mt-2">
                                        @if ($lead->email)
                                            <a href="mailto:{{ $lead->email }}"
                                                class="inline-flex items-center gap-2 text-base font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                                {{ $lead->email }}
                                            </a>
                                        @else
                                            <span class="text-base text-gray-500">Not provided</span>
                                        @endif
                                    </dd>
                                </div>
                                <div class="group">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Source
                                    </dt>
                                    <dd class="mt-2">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-500 px-3 py-1.5 text-xs font-bold text-white shadow-lg shadow-cyan-500/30">
                                            {{ $lead->source }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="group">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Service
                                        Interested</dt>
                                    <dd class="mt-2">
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-xl bg-gradient-to-r from-purple-500 to-pink-500 px-3 py-1.5 text-xs font-bold text-white shadow-lg shadow-purple-500/30">
                                            {{ $lead->service->name ?? 'N/A' }}
                                        </span>
                                    </dd>
                                </div>
                                <div class="group">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Assigned
                                        To</dt>
                                    <dd class="mt-2 flex items-center gap-2">
                                        @if ($lead->assignedTo)
                                            <span
                                                class="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-xs font-bold text-white shadow-lg shadow-indigo-500/30">
                                                {{ substr($lead->assignedTo->name, 0, 1) }}
                                            </span>
                                            <span
                                                class="text-base font-semibold text-gray-900">{{ $lead->assignedTo->name }}</span>
                                        @else
                                            <span class="text-base text-gray-500">Unassigned</span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>

                            @if ($lead->initial_remarks)
                                <div class="mt-6 rounded-xl bg-gray-50 p-4 border border-gray-100">
                                    <dt class="text-xs font-semibold uppercase tracking-wider text-gray-400">Initial
                                        Remarks</dt>
                                    <dd class="mt-2 text-sm text-gray-700 whitespace-pre-line leading-relaxed">
                                        {{ $lead->initial_remarks }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Contact History --}}
                    <div
                        class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                        <div
                            class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Contact History</h3>
                            </div>
                            <span
                                class="inline-flex items-center rounded-xl bg-white/20 px-3 py-1.5 text-xs font-bold text-white">
                                {{ $lead->contacts->count() }} calls
                            </span>
                        </div>
                        <div class="p-6">
                            @if ($lead->contacts->isEmpty())
                                <div class="text-center py-8">
                                    <div
                                        class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No calls recorded yet.</p>
                                </div>
                            @else
                                <div class="space-y-4">
                                    @foreach ($lead->contacts as $contact)
                                        <div
                                            class="group flex items-start gap-4 rounded-xl border border-gray-100 p-4 transition-all duration-300 hover:border-gray-200 hover:shadow-md">
                                            <div class="flex-shrink-0">
                                                <span
                                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl shadow-lg
                                                    @switch($contact->response_status)
                                                        @case('Interested') bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                                        @case('Not Interested') bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-red-500/30 @break
                                                        @case('Call Back Later') bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                                        @default bg-gradient-to-br from-gray-400 to-gray-500 text-white shadow-gray-500/30
                                                    @endswitch">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <span
                                                        class="text-sm font-bold text-gray-900">{{ $contact->response_status }}</span>
                                                    <div class="flex items-center gap-2">
                                                        <span
                                                            class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">
                                                            {{ $contact->call_date->format('M d') }}
                                                            @if ($contact->call_time)
                                                                at
                                                                {{ \Carbon\Carbon::parse($contact->call_time)->format('h:i A') }}
                                                            @endif
                                                        </span>
                                                        <button type="button"
                                                            @click="$dispatch('open-modal', 'edit-contact'); $dispatch('set-edit-contact', {{ json_encode([
                                                                'id' => $contact->id,
                                                                'response_status' => $contact->response_status,
                                                                'notes' => $contact->notes,
                                                                'client_name' => $lead->client_name,
                                                            ]) }})"
                                                            class="text-[10px] font-bold uppercase tracking-wider text-indigo-600 hover:text-indigo-800 transition-colors">
                                                            Edit
                                                        </button>
                                                    </div>
                                                </div>
                                                @if ($contact->notes)
                                                    <p class="mt-2 text-sm text-gray-600 leading-relaxed">
                                                        {{ $contact->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Follow-ups --}}
                    <div
                        class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                        <div
                            class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-white">Follow-ups</h3>
                            </div>
                            <span
                                class="inline-flex items-center rounded-xl bg-white/20 px-3 py-1.5 text-xs font-bold text-white">
                                {{ $lead->followUps->where('status', 'Pending')->count() }} pending
                            </span>
                        </div>
                        <div class="p-6">
                            @if ($lead->followUps->isEmpty())
                                <div class="text-center py-8">
                                    <div
                                        class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                        <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-500">No follow-ups scheduled.</p>
                                </div>
                            @else
                                <div class="space-y-3">
                                    @foreach ($lead->followUps as $followUp)
                                        <div
                                            class="flex items-center justify-between rounded-xl border p-4 transition-all duration-300 hover:shadow-md
                                            {{ $followUp->status === 'Pending' ? 'border-amber-200 bg-gradient-to-r from-amber-50 to-orange-50' : 'border-gray-100 bg-gray-50' }}">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-bold text-gray-900">
                                                        {{ $followUp->follow_up_date->format('M d, Y') }}
                                                    </span>
                                                    @if ($followUp->follow_up_time)
                                                        <span class="text-sm font-medium text-gray-500">
                                                            {{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if ($followUp->notes)
                                                    <p class="mt-1 text-sm text-gray-600">{{ $followUp->notes }}</p>
                                                @endif
                                            </div>
                                            <span
                                                class="inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-bold shadow-lg
                                                @switch($followUp->status)
                                                    @case('Pending') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                                    @case('Completed') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                                    @case('Cancelled') bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-gray-500/30 @break
                                                @endswitch">
                                                {{ $followUp->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Sidebar --}}
                    <div class="space-y-6">
                        {{-- Status Card --}}
                        <div
                            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                            <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white">Status</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between gap-3">
                                    <span
                                        class="inline-flex items-center rounded-xl px-4 py-2 text-sm font-bold shadow-lg
                                    @switch($lead->status)
                                        @case('New') bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-gray-500/30 @break
                                        @case('Contacted') bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-blue-500/30 @break
                                        @case('Qualified') bg-gradient-to-r from-indigo-500 to-purple-600 text-white shadow-indigo-500/30 @break
                                        @case('Negotiation') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                        @case('Converted') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                        @case('Lost') bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-red-500/30 @break
                                    @endswitch">
                                        {{ $lead->status }}
                                    </span>
                                    <span
                                        class="inline-flex items-center rounded-xl px-3 py-1.5 text-xs font-bold shadow-lg
                                    @switch($lead->priority)
                                        @case('High') bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-red-500/30 @break
                                        @case('Medium') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @break
                                        @case('Low') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30 @break
                                    @endswitch">
                                        {{ $lead->priority }}
                                    </span>
                                </div>

                                @if ($lead->conversion)
                                    <div
                                        class="mt-6 rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 p-4 border border-emerald-200 shadow-lg shadow-emerald-500/10">
                                        <div class="flex items-center gap-2 mb-3">
                                            <div
                                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <h4 class="text-sm font-bold text-emerald-800">Converted!</h4>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex justify-between">
                                                <span class="text-sm text-emerald-700">Deal Value</span>
                                                <span
                                                    class="text-sm font-bold text-emerald-900">৳{{ number_format($lead->conversion->deal_value) }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-sm text-emerald-700">Commission</span>
                                                <span
                                                    class="text-sm font-bold text-emerald-900">৳{{ number_format($lead->conversion->commission_amount) }}</span>
                                            </div>
                                            <div class="pt-2 border-t border-emerald-200">
                                                <span class="text-xs font-medium text-emerald-600">
                                                    {{ $lead->conversion->conversion_date->format('M d, Y') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Quick Stats --}}
                        <div
                            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                            <div class="bg-gradient-to-r from-cyan-600 to-teal-600 px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white">Activity</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <dl class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <dt class="text-sm font-medium text-gray-500">Total Calls</dt>
                                        <dd
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-sm font-bold text-emerald-700">
                                            {{ $lead->contacts->count() }}</dd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <dt class="text-sm font-medium text-gray-500">Follow-ups</dt>
                                        <dd
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-amber-100 text-sm font-bold text-amber-700">
                                            {{ $lead->followUps->count() }}</dd>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <dt class="text-sm font-medium text-gray-500">Meetings</dt>
                                        <dd
                                            class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-sm font-bold text-blue-700">
                                            {{ $lead->meetings->count() }}</dd>
                                    </div>
                                    <div class="pt-4 border-t border-gray-100 space-y-3">
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Created</dt>
                                            <dd class="text-sm font-medium text-gray-900">
                                                {{ $lead->created_at->diffForHumans() }}</dd>
                                        </div>
                                        <div class="flex justify-between">
                                            <dt class="text-sm text-gray-500">Last Updated</dt>
                                            <dd class="text-sm font-medium text-gray-900">
                                                {{ $lead->updated_at->diffForHumans() }}</dd>
                                        </div>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        {{-- Danger Zone --}}
                        <div
                            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl">
                            <div class="bg-gradient-to-r from-red-500 to-rose-600 px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-white">Danger Zone</h3>
                                </div>
                            </div>
                            <div class="p-6">
                                <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this lead? This action cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-red-500 to-rose-600 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-red-500/30 transition-all duration-300 hover:shadow-xl hover:shadow-red-500/40 hover:-translate-y-0.5">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Delete Lead
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                        <span class="block text-sm font-medium text-gray-500 mt-1"
                            x-text="contact.client_name"></span>
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
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20">
                            @foreach ($statuses as $key => $statusData)
                                <option value="{{ $key }}">{{ $statusData['label'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Notes</label>
                        <textarea x-model="contact.notes" rows="4"
                            class="w-full rounded-xl border-gray-200 bg-gray-50 px-4 py-3 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-500/20"
                            placeholder="Add more detailed notes about the call..."></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" @click="$dispatch('close-modal', 'edit-contact')"
                            class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all disabled:opacity-50"
                            :disabled="loading">
                            <span x-show="!loading">Save Changes</span>
                            <span x-show="loading">Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </x-modal>
</x-app-layout>
