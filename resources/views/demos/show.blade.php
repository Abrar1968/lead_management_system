<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    {{ $demo->title }}
                </h2>
                <p class="mt-1 text-sm text-gray-500">Demo details and information</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('demos.edit', $demo) }}"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 font-semibold shadow-lg transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
                <a href="{{ route('demos.index') }}"
                    class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 space-y-6">



            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Demo Details Card -->
                    <div
                        class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white">Demo Information</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Status</label>
                                    @php
                                        $statusColors = [
                                            'Scheduled' => 'bg-blue-100 text-blue-800',
                                            'Completed' => 'bg-emerald-100 text-emerald-800',
                                            'Cancelled' => 'bg-red-100 text-red-800',
                                            'Rescheduled' => 'bg-yellow-100 text-yellow-800',
                                        ];
                                    @endphp
                                    <p class="mt-1">
                                        <span
                                            class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusColors[$demo->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $demo->status }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Type</label>
                                    <p class="mt-1">
                                        @if ($demo->type === 'Online')
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold bg-cyan-100 text-cyan-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                                Online
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                                Physical
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Date</label>
                                    <p class="mt-1 text-gray-900 font-semibold">{{ $demo->demo_date->format('F d, Y') }}
                                    </p>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Time</label>
                                    <p class="mt-1 text-gray-900 font-semibold">
                                        {{ $demo->demo_time ? $demo->demo_time->format('h:i A') : 'Not set' }}</p>
                                </div>
                            </div>

                            @if ($demo->description)
                                <div class="pt-4 border-t border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Description</label>
                                    <p class="mt-1 text-gray-700">{{ $demo->description }}</p>
                                </div>
                            @endif

                            @if ($demo->meeting_link)
                                <div class="pt-4 border-t border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Meeting Link</label>
                                    <p class="mt-1">
                                        <a href="{{ $demo->meeting_link }}" target="_blank"
                                            class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                            {{ Str::limit($demo->meeting_link, 50) }}
                                        </a>
                                    </p>
                                </div>
                            @endif

                            @if ($demo->location)
                                <div class="pt-4 border-t border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Location</label>
                                    <p class="mt-1 text-gray-700">{{ $demo->location }}</p>
                                </div>
                            @endif

                            @if ($demo->outcome_notes)
                                <div class="pt-4 border-t border-gray-200">
                                    <label class="text-xs font-semibold text-gray-500 uppercase">Outcome Notes</label>
                                    <p class="mt-1 text-gray-700">{{ $demo->outcome_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Dynamic Fields Card -->
                    @if ($dynamicFields->count() > 0)
                        <div
                            class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                                <h3 class="text-lg font-bold text-white">Additional Information</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @foreach ($dynamicFields as $field)
                                        @php $value = $demo->getFieldValue($field->id); @endphp
                                        <div class="{{ $field->type === 'image' ? 'md:col-span-2' : '' }}">
                                            <label
                                                class="text-xs font-semibold text-gray-500 uppercase">{{ $field->label }}</label>
                                            @if ($field->type === 'image')
                                                @if ($value)
                                                    <div class="mt-2">
                                                        <img src="{{ asset('storage/' . $value) }}"
                                                            alt="{{ $field->label }}"
                                                            class="max-w-sm h-auto rounded-xl shadow-md">
                                                    </div>
                                                @else
                                                    <p class="mt-1 text-gray-400 italic">No image uploaded</p>
                                                @endif
                                            @elseif($field->type === 'document')
                                                @if ($value)
                                                    <div class="mt-2">
                                                        <a href="{{ route('demos.preview-document', ['demo' => $demo, 'fieldId' => $field->id]) }}" target="_blank"
                                                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-all border border-blue-100">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                            </svg>
                                                            View Document
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="mt-1 text-gray-400 italic">No document uploaded</p>
                                                @endif
                                            @elseif($field->type === 'link')
                                                @if ($value)
                                                    <p class="mt-1">
                                                        <a href="{{ $value }}" target="_blank"
                                                            class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                            </svg>
                                                            {{ Str::limit($value, 40) }}
                                                        </a>
                                                    </p>
                                                @else
                                                    <p class="mt-1 text-gray-400 italic">Not provided</p>
                                                @endif
                                            @else
                                                <p class="mt-1 text-gray-900">{{ $value ?? '-' }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">

                    <!-- Lead Card -->
                    <div
                        class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white">Associated Lead</h3>
                        </div>
                        <div class="p-6">
                            @if ($demo->lead)
                                <div class="flex items-center gap-4 mb-4">
                                    <div
                                        class="h-14 w-14 rounded-full bg-gradient-to-br from-emerald-500 to-teal-500 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-emerald-500/30">
                                        {{ strtoupper(substr($demo->lead->client_name ?? $demo->lead->phone_number, 0, 1)) }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $demo->lead->client_name ?? 'N/A' }}
                                        </h4>
                                        <p class="text-gray-600">{{ $demo->lead->phone_number }}</p>
                                    </div>
                                </div>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Source</span>
                                        <span class="font-medium text-gray-900">{{ $demo->lead->source }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Status</span>
                                        <span class="font-medium text-gray-900">{{ $demo->lead->status }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Service</span>
                                        <span
                                            class="font-medium text-gray-900">{{ $demo->lead->service_interested }}</span>
                                    </div>
                                </div>
                                <a href="{{ route('leads.show', $demo->lead) }}"
                                    class="mt-4 inline-flex items-center gap-2 w-full justify-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 font-semibold transition-all">
                                    View Lead
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <div class="text-center py-4">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <p class="mt-2 text-gray-500">No lead linked</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Created By Card -->
                    <div
                        class="bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 overflow-hidden">
                        <div class="bg-gradient-to-r from-gray-600 to-gray-700 px-6 py-4">
                            <h3 class="text-lg font-bold text-white">Created By</h3>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="h-12 w-12 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center text-white text-lg font-bold">
                                    {{ strtoupper(substr($demo->createdBy->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $demo->createdBy->name ?? 'Unknown' }}</h4>
                                    <p class="text-sm text-gray-500">{{ $demo->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Delete Demo -->
                    @if (auth()->user()->isAdmin())
                        <div class="bg-red-50 rounded-2xl border border-red-200 p-6">
                            <h4 class="font-semibold text-red-800 mb-2">Danger Zone</h4>
                            <p class="text-sm text-red-600 mb-4">Permanently delete this demo. This action cannot be
                                undone.</p>
                            <form action="{{ route('demos.destroy', $demo) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to delete this demo?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-semibold transition-all">
                                    Delete Demo
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
