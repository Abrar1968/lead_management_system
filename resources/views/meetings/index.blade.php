<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 text-white shadow-lg shadow-purple-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Meetings</h2>
                    <p class="text-sm text-gray-500">Manage all your scheduled meetings</p>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Flash Messages -->
    @if (session('success'))
        <div
            class="mb-6 overflow-hidden rounded-xl bg-gradient-to-r from-emerald-50 to-green-50 border border-emerald-200 p-4">
            <div class="flex items-center gap-3">
                <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-500 text-white">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-white shadow-lg shadow-indigo-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Today's Meetings</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['today_meetings'] }}</p>
            </div>
        </div>
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Successful Today</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1">{{ $stats['successful_today'] }}</p>
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
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Pending</p>
                <p class="text-3xl font-bold text-amber-600 mt-1">{{ $stats['pending'] }}</p>
            </div>
        </div>
        <div
            class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="p-5">
                <div class="flex items-center justify-between mb-3">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 text-white shadow-lg shadow-blue-500/30">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                </div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Upcoming (7 Days)</p>
                <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['upcoming_week'] }}</p>
            </div>
        </div>
    </div>

    <!-- Today's & Upcoming Meetings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Today's Meetings -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white">Today's Meetings</h3>
                </div>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($todayMeetings as $meeting)
                    @if ($meeting->lead)
                        <div class="px-5 py-4" x-data="{ showOutcome: false }">
                            <div class="flex justify-between items-start">
                                <a href="{{ route('leads.show', $meeting->lead) }}" class="block group">
                                    <p
                                        class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $meeting->lead->client_name }}</p>
                                    <p class="text-xs text-gray-500">{{ $meeting->lead->phone_number }}</p>
                                </a>
                                <div class="text-right">
                                    <p class="text-sm font-bold text-indigo-600">
                                        {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                                    @if ($meeting->price)
                                        <p class="text-xs font-bold text-emerald-600">
                                            ৳{{ number_format($meeting->price, 0) }}</p>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-3 flex flex-wrap items-center gap-2">
                                <span
                                    class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                                @if ($meeting->meeting_status)
                                    <span
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold
                                    {{ $meetingStatuses[$meeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                    {{ $meetingStatuses[$meeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                        {{ $meeting->meeting_status }}
                                    </span>
                                @endif
                                <span
                                    class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                @if ($meeting->outcome === 'Successful') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30
                                @elseif($meeting->outcome === 'Cancelled' || $meeting->outcome === 'No Show') bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-red-500/30
                                @elseif($meeting->outcome === 'Pending') bg-gray-200 text-gray-700
                                @else bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @endif">
                                    {{ $meeting->outcome }}
                                </span>
                            </div>

                            @if ($meeting->outcome === 'Pending')
                                <button @click="showOutcome = !showOutcome"
                                    class="mt-3 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    Update Status & Outcome →
                                </button>
                            @endif

                            <!-- Outcome Update Form -->
                            <div x-show="showOutcome" x-cloak x-transition
                                class="mt-4 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
                                <form action="{{ route('meetings.update-outcome', $meeting) }}" method="POST"
                                    class="space-y-3">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Meeting Status</label>
                                            <select name="meeting_status"
                                                class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                                @foreach ($meetingStatuses as $key => $status)
                                                    <option value="{{ $key }}"
                                                        {{ $meeting->meeting_status === $key ? 'selected' : '' }}>
                                                        {{ $status['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Outcome</label>
                                            <select name="outcome"
                                                class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                                @foreach ($outcomes as $key => $outcome)
                                                    <option value="{{ $key }}"
                                                        {{ $meeting->outcome === $key ? 'selected' : '' }}>
                                                        {{ $outcome['label'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Price (৳)</label>
                                        <input type="number" name="price" value="{{ $meeting->price }}"
                                            step="0.01" class="w-full text-sm rounded-lg border-gray-200 bg-white"
                                            placeholder="0.00">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Notes</label>
                                        <textarea name="notes" placeholder="Notes..." rows="2"
                                            class="w-full text-sm rounded-lg border-gray-200 bg-white">{{ $meeting->notes }}</textarea>
                                    </div>
                                    <button type="submit"
                                        class="w-full text-sm px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg font-semibold shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                                        Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="px-5 py-12 text-center">
                        <div class="mx-auto h-16 w-16 rounded-2xl bg-indigo-100 flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">No meetings scheduled for today</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Meetings -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-600 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white">Upcoming This Week</h3>
                </div>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($upcomingMeetings as $meeting)
                    @if ($meeting->lead)
                        <a href="{{ route('leads.show', $meeting->lead) }}"
                            class="block px-5 py-4 transition-colors hover:bg-gray-50 group">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p
                                        class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">
                                        {{ $meeting->lead->client_name }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        <span
                                            class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                                        @if ($meeting->meeting_status)
                                            <span
                                                class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold
                                            {{ $meetingStatuses[$meeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                            {{ $meetingStatuses[$meeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                                {{ $meeting->meeting_status }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs font-bold text-blue-600">
                                        {{ $meeting->meeting_date->format('M j') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                                    @if ($meeting->price)
                                        <p class="text-xs font-bold text-emerald-600 mt-1">
                                            ৳{{ number_format($meeting->price, 0) }}</p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endif
                @empty
                    <div class="px-5 py-12 text-center">
                        <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">No upcoming meetings this week</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 p-6 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Date</label>
                <input type="date" name="date" value="{{ $currentDate }}"
                    class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Meeting Status</label>
                <select name="meeting_status"
                    class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">All Statuses</option>
                    @foreach ($meetingStatuses as $key => $status)
                        <option value="{{ $key }}"
                            {{ request('meeting_status') === $key ? 'selected' : '' }}>
                            {{ $status['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Outcome</label>
                <select name="outcome"
                    class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium transition-all duration-200 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-500/20">
                    <option value="">All Outcomes</option>
                    @foreach ($outcomes as $key => $outcome)
                        <option value="{{ $key }}" {{ request('outcome') === $key ? 'selected' : '' }}>
                            {{ $outcome['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="px-6 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-semibold text-sm shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">
                    Filter
                </button>
                <a href="{{ route('meetings.index') }}"
                    class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold text-sm hover:bg-gray-200 transition-colors">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- All Meetings Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white">All Meetings</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Client
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Phone
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                            Meeting Time</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Price
                        </th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">
                            Outcome</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Notes
                        </th>
                        <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">
                            Actions</th>
                    </tr>
                </thead>
                @forelse($meetings as $meeting)
                    @if ($meeting->lead)
                        <tbody x-data="{ editing: false }" class="bg-white divide-y divide-gray-100">
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-4">
                                    <a href="{{ route('leads.show', $meeting->lead) }}"
                                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                        {{ $meeting->lead->client_name }}
                                    </a>
                                </td>
                                <td class="px-4 py-4 text-sm font-medium text-gray-600">
                                    {{ $meeting->lead->phone_number }}
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    <p class="text-gray-900 font-medium">
                                        {{ $meeting->meeting_date->format('M j, Y') }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                                </td>
                                <td class="px-4 py-4">
                                    @if ($meeting->meeting_status)
                                        <span
                                            class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold
                                        {{ $meetingStatuses[$meeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                        {{ $meetingStatuses[$meeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                            {{ $meeting->meeting_status }}
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm">
                                    @if ($meeting->price)
                                        <span
                                            class="font-bold text-emerald-600">৳{{ number_format($meeting->price, 0) }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                    @if ($meeting->outcome === 'Successful') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30
                                    @elseif($meeting->outcome === 'Pending') bg-gray-200 text-gray-700
                                    @elseif($meeting->outcome === 'Follow-up Needed') bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30
                                    @elseif($meeting->outcome === 'Rescheduled') bg-gradient-to-r from-blue-500 to-indigo-600 text-white shadow-blue-500/30
                                    @elseif($meeting->outcome === 'Cancelled') bg-gradient-to-r from-red-500 to-rose-600 text-white shadow-red-500/30
                                    @elseif($meeting->outcome === 'No Show') bg-gradient-to-r from-red-600 to-rose-700 text-white shadow-red-500/30
                                    @else bg-gray-200 text-gray-700 @endif">
                                        {{ $meeting->outcome }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                    {{ Str::limit($meeting->notes, 40) }}
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="flex justify-end gap-1">
                                        <button @click="editing = !editing"
                                            class="text-xs px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                                            Edit
                                        </button>
                                        <form action="{{ route('meetings.destroy', $meeting) }}" method="POST"
                                            class="inline" onsubmit="return confirm('Delete this meeting?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs px-3 py-1.5 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-colors">
                                                ✕
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <!-- Inline Edit Row -->
                            <tr x-show="editing" x-cloak x-transition class="bg-gray-50">
                                <td colspan="8" class="px-4 py-4">
                                    <form action="{{ route('meetings.update', $meeting) }}" method="POST"
                                        class="flex flex-wrap gap-4 items-end">
                                        @csrf
                                        @method('PATCH')
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Date & Time</label>
                                            <div class="flex gap-2">
                                                <input type="date" name="meeting_date"
                                                    value="{{ $meeting->meeting_date->format('Y-m-d') }}"
                                                    class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                                <input type="time" name="meeting_time"
                                                    value="{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('H:i') }}"
                                                    class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                            </div>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Meeting Status</label>
                                            <select name="meeting_status"
                                                class="text-sm rounded-lg border-gray-200 bg-white">
                                                @foreach ($meetingStatuses as $key => $status)
                                                    <option value="{{ $key }}"
                                                        {{ $meeting->meeting_status === $key ? 'selected' : '' }}>
                                                        {{ $status['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Price</label>
                                            <input type="number" name="price" value="{{ $meeting->price }}"
                                                step="0.01"
                                                class="w-28 text-sm rounded-lg border-gray-200 bg-white"
                                                placeholder="0.00">
                                        </div>
                                        <div class="space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Outcome</label>
                                            <select name="outcome"
                                                class="text-sm rounded-lg border-gray-200 bg-white">
                                                @foreach ($outcomes as $key => $outcome)
                                                    <option value="{{ $key }}"
                                                        {{ $meeting->outcome === $key ? 'selected' : '' }}>
                                                        {{ $outcome['label'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="flex-1 space-y-1">
                                            <label class="text-xs font-semibold text-gray-700">Notes</label>
                                            <input type="text" name="notes" value="{{ $meeting->notes }}"
                                                class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="submit"
                                                class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-lg shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">Save</button>
                                            <button type="button" @click="editing = false"
                                                class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    @endif
                @empty
                    <tbody class="bg-white">
                        <tr>
                            <td colspan="8" class="px-4 py-12 text-center">
                                <div
                                    class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No meetings found for this date.</p>
                            </td>
                        </tr>
                    </tbody>
                @endforelse
            </table>
        </div>
        @if ($meetings->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $meetings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
