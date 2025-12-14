<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    {{ $lead->lead_number }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Created on {{ $lead->lead_date->format('F d, Y') }}
                </p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('leads.edit', $lead) }}"
                   class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('leads.daily', ['date' => $lead->lead_date->format('Y-m-d')]) }}"
                   class="inline-flex items-center rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                    Back to Daily
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Success Message --}}
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <div class="flex">
                        <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        <p class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="grid gap-6 lg:grid-cols-3">
                {{-- Main Lead Information --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Lead Details Card --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-gray-900">Lead Details</h3>
                        </div>
                        <div class="p-4">
                            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->customer_name ?? 'Not provided' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Phone Number</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <a href="tel:{{ $lead->phone_number }}" class="text-indigo-600 hover:text-indigo-500">
                                            {{ $lead->phone_number }}
                                        </a>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @if($lead->email)
                                            <a href="mailto:{{ $lead->email }}" class="text-indigo-600 hover:text-indigo-500">
                                                {{ $lead->email }}
                                            </a>
                                        @else
                                            Not provided
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Source</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            {{ $lead->source }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Service Interested</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2.5 py-0.5 text-xs font-medium text-purple-800">
                                            {{ $lead->service_interested }}
                                        </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Assigned To</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $lead->assignedTo?->name ?? 'Unassigned' }}</dd>
                                </div>
                            </dl>

                            @if($lead->initial_remarks)
                                <div class="mt-4 border-t pt-4">
                                    <dt class="text-sm font-medium text-gray-500">Initial Remarks</dt>
                                    <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $lead->initial_remarks }}</dd>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Contact History --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Contact History</h3>
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800">
                                {{ $lead->contacts->count() }} calls
                            </span>
                        </div>
                        <div class="p-4">
                            @if($lead->contacts->isEmpty())
                                <p class="text-sm text-gray-500 text-center py-4">No calls recorded yet.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($lead->contacts as $contact)
                                        <div class="flex items-start gap-3 border-b pb-4 last:border-b-0 last:pb-0">
                                            <div class="flex-shrink-0">
                                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full
                                                    @switch($contact->response_status)
                                                        @case('Interested') bg-green-100 text-green-600 @break
                                                        @case('Not Interested') bg-red-100 text-red-600 @break
                                                        @case('Call Back Later') bg-yellow-100 text-yellow-600 @break
                                                        @default bg-gray-100 text-gray-600
                                                    @endswitch">
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm font-medium text-gray-900">{{ $contact->response_status }}</span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $contact->call_date->format('M d') }}
                                                        @if($contact->call_time)
                                                            at {{ \Carbon\Carbon::parse($contact->call_time)->format('h:i A') }}
                                                        @endif
                                                    </span>
                                                </div>
                                                @if($contact->notes)
                                                    <p class="mt-1 text-sm text-gray-600">{{ $contact->notes }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Follow-ups --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Follow-ups</h3>
                            <span class="inline-flex items-center rounded-full bg-orange-100 px-2.5 py-0.5 text-xs font-medium text-orange-800">
                                {{ $lead->followUps->where('status', 'Pending')->count() }} pending
                            </span>
                        </div>
                        <div class="p-4">
                            @if($lead->followUps->isEmpty())
                                <p class="text-sm text-gray-500 text-center py-4">No follow-ups scheduled.</p>
                            @else
                                <div class="space-y-3">
                                    @foreach($lead->followUps as $followUp)
                                        <div class="flex items-center justify-between rounded-lg border p-3
                                            {{ $followUp->status === 'Pending' ? 'border-orange-200 bg-orange-50' : 'border-gray-200' }}">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-sm font-medium text-gray-900">
                                                        {{ $followUp->follow_up_date->format('M d, Y') }}
                                                    </span>
                                                    @if($followUp->follow_up_time)
                                                        <span class="text-sm text-gray-500">
                                                            {{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($followUp->notes)
                                                    <p class="mt-1 text-sm text-gray-600">{{ $followUp->notes }}</p>
                                                @endif
                                            </div>
                                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                                @switch($followUp->status)
                                                    @case('Pending') bg-orange-100 text-orange-800 @break
                                                    @case('Completed') bg-green-100 text-green-800 @break
                                                    @case('Cancelled') bg-gray-100 text-gray-800 @break
                                                @endswitch">
                                                {{ $followUp->status }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="space-y-6">
                    {{-- Status Card --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-gray-900">Status</h3>
                        </div>
                        <div class="p-4">
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-sm font-medium
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
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    @switch($lead->priority)
                                        @case('High') bg-red-100 text-red-800 @break
                                        @case('Medium') bg-yellow-100 text-yellow-800 @break
                                        @case('Low') bg-green-100 text-green-800 @break
                                    @endswitch">
                                    {{ $lead->priority }} Priority
                                </span>
                            </div>

                            @if($lead->conversion)
                                <div class="mt-4 rounded-lg bg-green-50 p-3 border border-green-200">
                                    <h4 class="text-sm font-medium text-green-800">Converted!</h4>
                                    <p class="mt-1 text-sm text-green-700">
                                        Deal Value: ৳{{ number_format($lead->conversion->deal_value) }}
                                    </p>
                                    <p class="text-sm text-green-700">
                                        Commission: ৳{{ number_format($lead->conversion->commission_amount) }}
                                    </p>
                                    <p class="mt-1 text-xs text-green-600">
                                        {{ $lead->conversion->conversion_date->format('M d, Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-gray-900">Activity</h3>
                        </div>
                        <div class="p-4">
                            <dl class="space-y-3">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Total Calls</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $lead->contacts->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Follow-ups</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $lead->followUps->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Meetings</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $lead->meetings->count() }}</dd>
                                </div>
                                <div class="flex justify-between border-t pt-3">
                                    <dt class="text-sm text-gray-500">Created</dt>
                                    <dd class="text-sm text-gray-900">{{ $lead->created_at->diffForHumans() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Last Updated</dt>
                                    <dd class="text-sm text-gray-900">{{ $lead->updated_at->diffForHumans() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    {{-- Danger Zone --}}
                    <div class="overflow-hidden rounded-lg bg-white shadow">
                        <div class="border-b border-red-200 bg-red-50 px-4 py-3">
                            <h3 class="text-lg font-medium text-red-800">Danger Zone</h3>
                        </div>
                        <div class="p-4">
                            <form action="{{ route('leads.destroy', $lead) }}" method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this lead? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                    Delete Lead
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
