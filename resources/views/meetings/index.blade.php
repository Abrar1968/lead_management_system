<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📅 Meetings
            </h2>
        </div>
    </x-slot>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-indigo-500">
            <p class="text-xs text-gray-500 uppercase">Today's Meetings</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_meetings'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase">Successful Today</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['successful_today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-amber-500">
            <p class="text-xs text-gray-500 uppercase">Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase">Upcoming (7 Days)</p>
            <p class="text-2xl font-bold text-blue-600">{{ $stats['upcoming_week'] }}</p>
        </div>
    </div>

    <!-- Today's & Upcoming Meetings -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Today's Meetings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200 bg-indigo-50">
                <h3 class="font-semibold text-indigo-800">📅 Today's Meetings</h3>
            </div>
            <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                @forelse($todayMeetings as $meeting)
                    <div class="px-4 py-3" x-data="{ showOutcome: false }">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $meeting->lead) }}" class="block hover:text-blue-600">
                                <p class="font-medium text-gray-900">{{ $meeting->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $meeting->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                <p class="text-sm font-medium text-indigo-600">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                                <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($meeting->outcome === 'Successful') bg-green-100 text-green-700
                                @elseif($meeting->outcome === 'Cancelled' || $meeting->outcome === 'No Show') bg-red-100 text-red-700
                                @elseif($meeting->outcome === 'Pending') bg-gray-100 text-gray-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ $meeting->outcome }}
                            </span>

                            @if($meeting->outcome === 'Pending')
                                <button @click="showOutcome = !showOutcome" class="text-xs text-blue-600 hover:underline">
                                    Update Outcome
                                </button>
                            @endif
                        </div>

                        <!-- Outcome Update Form -->
                        <div x-show="showOutcome" x-cloak class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <form action="{{ route('meetings.update-outcome', $meeting) }}" method="POST" class="space-y-2">
                                @csrf
                                <select name="outcome" class="w-full text-sm rounded-md border-gray-300">
                                    @foreach($outcomes as $key => $outcome)
                                        <option value="{{ $key }}" {{ $meeting->outcome === $key ? 'selected' : '' }}>
                                            {{ $outcome['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <textarea name="notes" placeholder="Notes..." rows="2" class="w-full text-sm rounded-md border-gray-300">{{ $meeting->notes }}</textarea>
                                <button type="submit" class="w-full text-sm px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Save Outcome
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        <p>📅 No meetings scheduled for today</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Meetings -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">📆 Upcoming This Week</h3>
            </div>
            <div class="divide-y divide-gray-200 max-h-80 overflow-y-auto">
                @forelse($upcomingMeetings as $meeting)
                    <a href="{{ route('leads.show', $meeting->lead) }}" class="block px-4 py-3 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $meeting->lead->client_name }}</p>
                                <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-blue-600 font-medium">{{ $meeting->meeting_date->format('M j') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        <p>No upcoming meetings this week</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ $currentDate }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Type</label>
                <select name="meeting_type" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Types</option>
                    @foreach($meetingTypes as $key => $type)
                        <option value="{{ $key }}" {{ request('meeting_type') === $key ? 'selected' : '' }}>
                            {{ $type['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Outcome</label>
                <select name="outcome" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Outcomes</option>
                    @foreach($outcomes as $key => $outcome)
                        <option value="{{ $key }}" {{ request('outcome') === $key ? 'selected' : '' }}>
                            {{ $outcome['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('meetings.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- All Meetings Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Meetings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lead</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outcome</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($meetings as $meeting)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('leads.show', $meeting->lead) }}" class="hover:text-blue-600">
                                    <p class="font-medium text-gray-900">{{ $meeting->lead->client_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $meeting->lead->phone_number }}</p>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $meeting->meeting_date->format('M j, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded bg-purple-100 text-purple-700">
                                    {{ $meeting->meeting_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $outcomes[$meeting->outcome]['bg'] ?? 'bg-gray-500' }} text-white">
                                    {{ $meeting->outcome }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ Str::limit($meeting->notes, 50) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="inline" onsubmit="return confirm('Delete this meeting?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No meetings found for this date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($meetings->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $meetings->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
