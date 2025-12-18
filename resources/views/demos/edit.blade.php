<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Edit Demo
                </h2>
                <p class="mt-1 text-sm text-gray-500">{{ $demo->title }}</p>
            </div>
            <a href="{{ route('demos.show', $demo) }}"
               class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Demo
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('demos.update', $demo) }}" method="POST" enctype="multipart/form-data"
                  class="space-y-6 bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-6">
                @csrf
                @method('PUT')

                <!-- Demo Details Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Demo Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Title <span class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title', $demo->title) }}" required
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lead -->
                        <div class="md:col-span-2">
                            <label for="lead_id" class="block text-sm font-semibold text-gray-700 mb-1">Associated Lead</label>
                            <select id="lead_id" name="lead_id"
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Select Lead (Optional) --</option>
                                @foreach($leads as $lead)
                                    <option value="{{ $lead->id }}" {{ old('lead_id', $demo->lead_id) == $lead->id ? 'selected' : '' }}>
                                        {{ $lead->client_name ?? 'N/A' }} - {{ $lead->phone_number }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lead_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                      class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('description', $demo->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Schedule</h3>

                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Demo Date -->
                        <div>
                            <label for="demo_date" class="block text-sm font-semibold text-gray-700 mb-1">Date <span class="text-red-500">*</span></label>
                            <input type="date" id="demo_date" name="demo_date" value="{{ old('demo_date', $demo->demo_date->format('Y-m-d')) }}" required
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('demo_date') border-red-500 @enderror">
                            @error('demo_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Demo Time -->
                        <div>
                            <label for="demo_time" class="block text-sm font-semibold text-gray-700 mb-1">Time</label>
                            <input type="time" id="demo_time" name="demo_time" value="{{ old('demo_time', $demo->demo_time ? $demo->demo_time->format('H:i') : '') }}"
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('demo_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" required
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Online" {{ old('type', $demo->type) === 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Physical" {{ old('type', $demo->type) === 'Physical' ? 'selected' : '' }}>Physical</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1">Status <span class="text-red-500">*</span></label>
                            <select id="status" name="status" required
                                    class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="Scheduled" {{ old('status', $demo->status) === 'Scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="Completed" {{ old('status', $demo->status) === 'Completed' ? 'selected' : '' }}>Completed</option>
                                <option value="Cancelled" {{ old('status', $demo->status) === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="Rescheduled" {{ old('status', $demo->status) === 'Rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location/Meeting Link Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Venue</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Meeting Link (Online) -->
                        <div>
                            <label for="meeting_link" class="block text-sm font-semibold text-gray-700 mb-1">Meeting Link</label>
                            <input type="url" id="meeting_link" name="meeting_link" value="{{ old('meeting_link', $demo->meeting_link) }}"
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="https://meet.google.com/abc-def-ghi">
                            @error('meeting_link')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location (Physical) -->
                        <div>
                            <label for="location" class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location', $demo->location) }}"
                                   class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                   placeholder="Office address or meeting location">
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Outcome Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Outcome</h3>

                    <div>
                        <label for="outcome_notes" class="block text-sm font-semibold text-gray-700 mb-1">Outcome Notes</label>
                        <textarea id="outcome_notes" name="outcome_notes" rows="3"
                                  class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                  placeholder="What was the result of this demo?">{{ old('outcome_notes', $demo->outcome_notes) }}</textarea>
                        @error('outcome_notes')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Dynamic Fields Section -->
                @if($dynamicFields->count() > 0)
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Additional Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($dynamicFields as $field)
                                @php $value = $demo->getFieldValue($field->id); @endphp
                                <div class="{{ $field->type === 'image' ? 'md:col-span-2' : '' }}">
                                    <label for="dynamic_{{ $field->name }}" class="block text-sm font-semibold text-gray-700 mb-1">
                                        {{ $field->label }}
                                        @if($field->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @if($field->type === 'image')
                                        @if($value)
                                            <div class="mb-2 flex items-center gap-4">
                                                <img src="{{ asset('storage/' . $value) }}" alt="{{ $field->label }}"
                                                     class="h-20 w-auto rounded-lg shadow">
                                                <form action="{{ route('demos.remove-image', $demo) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="field_id" value="{{ $field->id }}">
                                                    <button type="submit" onclick="return confirm('Remove this image?')"
                                                            class="text-red-600 hover:text-red-800 text-sm font-medium">
                                                        Remove
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                        <input type="file" id="dynamic_{{ $field->name }}" name="dynamic_{{ $field->name }}"
                                               accept="image/*"
                                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    @elseif($field->type === 'link')
                                        <input type="url" id="dynamic_{{ $field->name }}" name="dynamic_{{ $field->name }}"
                                               value="{{ old('dynamic_' . $field->name, $value) }}" {{ $field->required && !$value ? 'required' : '' }}
                                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                               placeholder="https://...">
                                    @else
                                        <input type="text" id="dynamic_{{ $field->name }}" name="dynamic_{{ $field->name }}"
                                               value="{{ old('dynamic_' . $field->name, $value) }}" {{ $field->required && !$value ? 'required' : '' }}
                                               class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    @endif

                                    @error('dynamic_' . $field->name)
                                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Submit Button -->
                <div class="flex justify-end pt-4 border-t border-gray-200">
                    <button type="submit"
                            class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl hover:from-blue-700 hover:to-indigo-700 font-semibold shadow-lg shadow-blue-500/30 transition-all">
                        Update Demo
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
