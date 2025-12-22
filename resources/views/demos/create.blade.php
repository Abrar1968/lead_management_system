<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    Schedule Demo
                </h2>
                <p class="mt-1 text-sm text-gray-500">Create a new product demonstration session</p>
            </div>
            <a href="{{ route('demos.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 text-gray-700 bg-gray-100 rounded-xl hover:bg-gray-200 font-semibold transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Demos
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <form action="{{ route('demos.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-6 bg-white rounded-2xl shadow-lg shadow-gray-200/50 border border-gray-100 p-6">
                @csrf

                <!-- Demo Details Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Demo Details</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-semibold text-gray-700 mb-1">Title <span
                                    class="text-red-500">*</span></label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('title') border-red-500 @enderror"
                                placeholder="e.g., CRM Demo for ABC Company">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Lead -->
                        <div class="md:col-span-2">
                            <label for="lead_id" class="block text-sm font-semibold text-gray-700 mb-1">Associated
                                Lead</label>
                            <select id="lead_id" name="lead_id"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">-- Select Lead (Optional) --</option>
                                @foreach ($leads as $lead)
                                    <option value="{{ $lead->id }}"
                                        {{ old('lead_id', $selectedLead) == $lead->id ? 'selected' : '' }}>
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
                            <label for="description"
                                class="block text-sm font-semibold text-gray-700 mb-1">Description</label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Demo objectives, features to showcase...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Schedule Section -->
                <div class="space-y-4">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Schedule</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Demo Date -->
                        <div>
                            <label for="demo_date" class="block text-sm font-semibold text-gray-700 mb-1">Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" id="demo_date" name="demo_date"
                                value="{{ old('demo_date', date('Y-m-d')) }}" required
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('demo_date') border-red-500 @enderror">
                            @error('demo_date')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Demo Time -->
                        <div>
                            <label for="demo_time" class="block text-sm font-semibold text-gray-700 mb-1">Time</label>
                            <input type="time" id="demo_time" name="demo_time" value="{{ old('demo_time') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            @error('demo_time')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1">Type <span
                                    class="text-red-500">*</span></label>
                            <select id="type" name="type" required
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                x-data="{ selected: '{{ old('type', 'Online') }}' }" x-model="selected">
                                <option value="Online">Online</option>
                                <option value="Physical">Physical</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Location/Meeting Link Section -->
                <div class="space-y-4" x-data="{ type: '{{ old('type', 'Online') }}' }">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Venue</h3>

                    <div class="grid grid-cols-1 gap-4">
                        <!-- Meeting Link (Online) -->
                        <div
                            x-show="document.getElementById('type').value === 'Online' || '{{ old('type', 'Online') }}' === 'Online'">
                            <label for="meeting_link" class="block text-sm font-semibold text-gray-700 mb-1">Meeting
                                Link</label>
                            <input type="url" id="meeting_link" name="meeting_link"
                                value="{{ old('meeting_link') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="https://meet.google.com/abc-def-ghi">
                            @error('meeting_link')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Location (Physical) -->
                        <div>
                            <label for="location"
                                class="block text-sm font-semibold text-gray-700 mb-1">Location</label>
                            <input type="text" id="location" name="location" value="{{ old('location') }}"
                                class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                placeholder="Office address or meeting location">
                            @error('location')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Dynamic Fields Section -->
                @if ($dynamicFields->count() > 0)
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Additional
                            Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($dynamicFields as $field)
                                <div
                                    class="{{ $field->type === 'text' && strlen($field->label) > 30 ? 'md:col-span-2' : '' }}">
                                    <label for="dynamic_{{ $field->name }}"
                                        class="block text-sm font-semibold text-gray-700 mb-1">
                                        {{ $field->label }}
                                        @if ($field->required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>
                                @elseif($field->type === 'image')
                                    <input type="file" id="dynamic_{{ $field->name }}"
                                        name="dynamic_{{ $field->name }}" accept="image/*"
                                        {{ $field->required ? 'required' : '' }}
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @elseif($field->type === 'document')
                                    <input type="file" id="dynamic_{{ $field->name }}"
                                        name="dynamic_{{ $field->name }}" accept=".pdf,.doc,.docx,.xls,.xlsx,.txt"
                                        {{ $field->required ? 'required' : '' }}
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="mt-1 text-xs text-gray-500">Max 5MB. PDF, DOC, DOCX, XLS, XLSX, TXT</p>
                                @elseif($field->type === 'link')
                                    <input type="url" id="dynamic_{{ $field->name }}"
                                        name="dynamic_{{ $field->name }}"
                                        value="{{ old('dynamic_' . $field->name) }}"
                                        {{ $field->required ? 'required' : '' }}
                                        class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="https://...">
                                @else
                                    <input type="text" id="dynamic_{{ $field->name }}"
                                        name="dynamic_{{ $field->name }}"
                                        value="{{ old('dynamic_' . $field->name) }}"
                                        {{ $field->required ? 'required' : '' }}
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
            Schedule Demo
        </button>
    </div>
    </form>
    </div>
    </div>
</x-app-layout>
