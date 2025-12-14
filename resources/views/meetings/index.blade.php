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
                                @if($meeting->price)
                                    <p class="text-xs font-semibold text-green-600">৳{{ number_format($meeting->price, 0) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                            @if($meeting->meeting_status)
                                <span class="text-xs px-2 py-1 rounded-full
                                    {{ $meetingStatuses[$meeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                    {{ $meetingStatuses[$meeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                    {{ $meeting->meeting_status }}
                                </span>
                            @endif
                            <span class="text-xs px-2 py-1 rounded-full
                                @if($meeting->outcome === 'Successful') bg-green-100 text-green-700
                                @elseif($meeting->outcome === 'Cancelled' || $meeting->outcome === 'No Show') bg-red-100 text-red-700
                                @elseif($meeting->outcome === 'Pending') bg-gray-100 text-gray-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ $meeting->outcome }}
                            </span>
                        </div>

                        @if($meeting->outcome === 'Pending')
                            <button @click="showOutcome = !showOutcome" class="mt-2 text-xs text-blue-600 hover:underline">
                                Update Status & Outcome
                            </button>
                        @endif

                        <!-- Outcome Update Form -->
                        <div x-show="showOutcome" x-cloak class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <form action="{{ route('meetings.update-outcome', $meeting) }}" method="POST" class="space-y-2">
                                @csrf
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Meeting Status</label>
                                        <select name="meeting_status" class="w-full text-sm rounded-md border-gray-300">
                                            @foreach($meetingStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $meeting->meeting_status === $key ? 'selected' : '' }}>
                                                    {{ $status['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Outcome</label>
                                        <select name="outcome" class="w-full text-sm rounded-md border-gray-300">
                                            @foreach($outcomes as $key => $outcome)
                                                <option value="{{ $key }}" {{ $meeting->outcome === $key ? 'selected' : '' }}>
                                                    {{ $outcome['label'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-700">Price (৳)</label>
                                    <input type="number" name="price" value="{{ $meeting->price }}" step="0.01" class="w-full text-sm rounded-md border-gray-300" placeholder="0.00">
                                </div>
                                <textarea name="notes" placeholder="Notes..." rows="2" class="w-full text-sm rounded-md border-gray-300">{{ $meeting->notes }}</textarea>
                                <button type="submit" class="w-full text-sm px-3 py-1.5 bg-blue-600 text-white rounded hover:bg-blue-700">
                                    Save Changes
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
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs px-2 py-0.5 rounded bg-purple-100 text-purple-700">{{ $meeting->meeting_type }}</span>
                                    @if($meeting->meeting_status)
                                        <span class="text-xs px-2 py-0.5 rounded-full
                                            {{ $meetingStatuses[$meeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                            {{ $meetingStatuses[$meeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                            {{ $meeting->meeting_status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-blue-600 font-medium">{{ $meeting->meeting_date->format('M j') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                                @if($meeting->price)
                                    <p class="text-xs font-semibold text-green-600">৳{{ number_format($meeting->price, 0) }}</p>
                                @endif
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Meeting Status</label>
                <select name="meeting_status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    @foreach($meetingStatuses as $key => $status)
                        <option value="{{ $key }}" {{ request('meeting_status') === $key ? 'selected' : '' }}>
                            {{ $status['label'] }}
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Meeting Time</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outcome</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($meetings as $meeting)
                        <tr class="hover:bg-gray-50" x-data="{ editing: false }">
                            <td class="px-4 py-3">
                                <a href="{{ route('leads.show', $meeting->lead) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    {{ $meeting->lead->client_name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $meeting->lead->phone_number }}
                            </td>
                            <td class="px-4 py-3 text-sm">
                                <p class="text-gray-900">{{ $meeting->meeting_date->format('M j, Y') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @if($meeting->meeting_status)
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $meetingStatuses[$meeting->meeting_status]['bg'] ?? 'bg-gray-100' }}
                                        {{ $meetingStatuses[$meeting->meeting_status]['text'] ?? 'text-gray-800' }}">
                                        {{ $meeting->meeting_status }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($meeting->price)
                                    <span class="font-semibold text-green-600">৳{{ number_format($meeting->price, 0) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 text-xs rounded-full {{ $outcomes[$meeting->outcome]['bg'] ?? 'bg-gray-500' }} text-white">
                                    {{ $meeting->outcome }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">
                                {{ Str::limit($meeting->notes, 40) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <button @click="editing = !editing" class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        Edit
                                    </button>
                                    <form action="{{ route('meetings.destroy', $meeting) }}" method="POST" class="inline" onsubmit="return confirm('Delete this meeting?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded hover:bg-red-200">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Inline Edit Row -->
                        <tr x-show="editing" x-cloak class="bg-gray-50">
                            <td colspan="8" class="px-4 py-3">
                                <form action="{{ route('meetings.update', $meeting) }}" method="POST" class="flex flex-wrap gap-3 items-end">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Meeting Status</label>
                                        <select name="meeting_status" class="text-sm rounded-md border-gray-300">
                                            @foreach($meetingStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $meeting->meeting_status === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Price</label>
                                        <input type="number" name="price" value="{{ $meeting->price }}" step="0.01" class="w-28 text-sm rounded-md border-gray-300" placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Outcome</label>
                                        <select name="outcome" class="text-sm rounded-md border-gray-300">
                                            @foreach($outcomes as $key => $outcome)
                                                <option value="{{ $key }}" {{ $meeting->outcome === $key ? 'selected' : '' }}>{{ $outcome['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-700">Notes</label>
                                        <input type="text" name="notes" value="{{ $meeting->notes }}" class="w-full text-sm rounded-md border-gray-300">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Save</button>
                                        <button type="button" @click="editing = false" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
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
