<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📋 Follow-ups
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
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-amber-500">
            <p class="text-xs text-gray-500 uppercase">Today's Follow-ups</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500 uppercase">Overdue</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase">This Week</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['this_week'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase">Completed Today</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['completed_today'] }}</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('follow-ups.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Overdue Follow-ups -->
        @if($overdueFollowUps->count() > 0)
        <div class="bg-white rounded-lg shadow border-t-4 border-red-500">
            <div class="px-4 py-3 border-b border-gray-200 bg-red-50">
                <h3 class="font-semibold text-red-800">⚠️ Overdue ({{ $overdueFollowUps->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @foreach($overdueFollowUps as $followUp)
                    <div class="px-4 py-3" x-data="{ showActions: false }">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="block hover:text-blue-600">
                                <p class="font-medium text-gray-900">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                <p class="text-xs text-red-600 font-medium">{{ $followUp->follow_up_date->format('M j') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                            </div>
                        </div>
                        @if($followUp->notes)
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($followUp->notes, 60) }}</p>
                        @endif
                        <div class="flex gap-2 mt-2">
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                    ✓ Complete
                                </button>
                            </form>
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                View Lead
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Today's Follow-ups -->
        <div class="bg-white rounded-lg shadow border-t-4 border-amber-500 {{ $overdueFollowUps->count() == 0 ? 'lg:col-span-2' : '' }}">
            <div class="px-4 py-3 border-b border-gray-200 bg-amber-50">
                <h3 class="font-semibold text-amber-800">📅 Today ({{ $todayFollowUps->count() }})</h3>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($todayFollowUps as $followUp)
                    <div class="px-4 py-3">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="block hover:text-blue-600">
                                <p class="font-medium text-gray-900">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                <p class="text-xs text-amber-600 font-medium">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                                <span class="text-xs px-2 py-0.5 rounded-full {{ $followUp->status === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $followUp->status }}
                                </span>
                            </div>
                        </div>
                        @if($followUp->notes)
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($followUp->notes, 80) }}</p>
                        @endif
                        @if($followUp->status === 'Pending')
                        <div class="flex gap-2 mt-2">
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                    ✓ Complete
                                </button>
                            </form>
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                View Lead
                            </a>
                        </div>
                        @endif
                    </div>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        <p>✅ No follow-ups scheduled for today!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Follow-ups -->
        <div class="bg-white rounded-lg shadow {{ $overdueFollowUps->count() == 0 ? 'lg:col-span-1' : '' }}">
            <div class="px-4 py-3 border-b border-gray-200">
                <h3 class="font-semibold text-gray-900">📆 Upcoming</h3>
            </div>
            <div class="divide-y divide-gray-200 max-h-96 overflow-y-auto">
                @forelse($upcomingFollowUps as $followUp)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="block px-4 py-3 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-medium text-gray-900 text-sm">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-blue-600 font-medium">{{ $followUp->follow_up_date->format('M j') }}</p>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-4 py-8 text-center text-gray-500">
                        <p>No upcoming follow-ups</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- All Follow-ups Table -->
    <div class="bg-white rounded-lg shadow mt-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">All Follow-ups</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lead</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($followUps as $followUp)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('leads.show', $followUp->lead) }}" class="hover:text-blue-600">
                                    <p class="font-medium text-gray-900">{{ $followUp->lead->client_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $followUp->lead->phone_number }}</p>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $followUp->follow_up_date->format('M j, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full
                                    @if($followUp->status === 'Completed') bg-green-100 text-green-700
                                    @elseif($followUp->status === 'Cancelled') bg-red-100 text-red-700
                                    @else bg-amber-100 text-amber-700 @endif">
                                    {{ $followUp->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ Str::limit($followUp->notes, 50) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($followUp->status === 'Pending')
                                    <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-900 mr-3">Complete</button>
                                    </form>
                                @endif
                                <form action="{{ route('follow-ups.destroy', $followUp) }}" method="POST" class="inline" onsubmit="return confirm('Delete this follow-up?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                No follow-ups found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($followUps->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $followUps->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
