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
                    <div class="px-4 py-3" x-data="{ editing: false }">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="block hover:text-blue-600">
                                <p class="font-medium text-gray-900">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                <p class="text-xs text-red-600 font-medium">{{ $followUp->follow_up_date->format('M j') }}</p>
                                @if($followUp->price)
                                    <p class="text-xs font-semibold text-green-600">৳{{ number_format($followUp->price, 0) }}</p>
                                @endif
                            </div>
                        </div>
                        @if($followUp->interest)
                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium mt-1
                                {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                {{ $followUp->interest }}
                            </span>
                        @endif
                        @if($followUp->notes)
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($followUp->notes, 60) }}</p>
                        @endif
                        <div class="flex gap-2 mt-2">
                            <button @click="editing = !editing" class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded hover:bg-amber-200">
                                ✎ Update
                            </button>
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                View Lead
                            </a>
                        </div>
                        <!-- Inline Edit Form -->
                        <div x-show="editing" x-cloak class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="space-y-2">
                                @csrf
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Interest</label>
                                        <select name="interest" class="w-full text-sm rounded-md border-gray-300">
                                            <option value="">Select...</option>
                                            @foreach($interestStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $followUp->interest === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Price (৳)</label>
                                        <input type="number" name="price" value="{{ $followUp->price }}" step="0.01" class="w-full text-sm rounded-md border-gray-300" placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" rows="2" class="w-full text-sm rounded-md border-gray-300">{{ $followUp->notes }}</textarea>
                                </div>
                                <button type="submit" class="w-full text-sm px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700">
                                    ✓ Complete Follow-up
                                </button>
                            </form>
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
                    <div class="px-4 py-3" x-data="{ editing: false }">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="block hover:text-blue-600">
                                <p class="font-medium text-gray-900">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                @if($followUp->follow_up_time)
                                    <p class="text-xs text-amber-600 font-medium">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                                @endif
                                @if($followUp->price)
                                    <p class="text-xs font-semibold text-green-600">৳{{ number_format($followUp->price, 0) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-1">
                            @if($followUp->interest)
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                    {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                    {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                    {{ $followUp->interest }}
                                </span>
                            @endif
                            <span class="text-xs px-2 py-0.5 rounded-full {{ $followUp->status === 'Completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $followUp->status }}
                            </span>
                        </div>
                        @if($followUp->notes)
                            <p class="text-xs text-gray-600 mt-1">{{ Str::limit($followUp->notes, 80) }}</p>
                        @endif
                        @if($followUp->status === 'Pending')
                        <div class="flex gap-2 mt-2">
                            <button @click="editing = !editing" class="text-xs px-2 py-1 bg-amber-100 text-amber-700 rounded hover:bg-amber-200">
                                ✎ Update
                            </button>
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="text-xs px-2 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                View Lead
                            </a>
                        </div>
                        <!-- Inline Edit Form -->
                        <div x-show="editing" x-cloak class="mt-3 p-3 bg-gray-50 rounded-lg">
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="space-y-2">
                                @csrf
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Interest</label>
                                        <select name="interest" class="w-full text-sm rounded-md border-gray-300">
                                            <option value="">Select...</option>
                                            @foreach($interestStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $followUp->interest === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Price (৳)</label>
                                        <input type="number" name="price" value="{{ $followUp->price }}" step="0.01" class="w-full text-sm rounded-md border-gray-300" placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-gray-700">Notes</label>
                                    <textarea name="notes" rows="2" class="w-full text-sm rounded-md border-gray-300">{{ $followUp->notes }}</textarea>
                                </div>
                                <button type="submit" class="w-full text-sm px-3 py-1.5 bg-green-600 text-white rounded hover:bg-green-700">
                                    ✓ Complete Follow-up
                                </button>
                            </form>
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
                                @if($followUp->interest)
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium mt-1
                                        {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                        {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                        {{ $followUp->interest }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-blue-600 font-medium">{{ $followUp->follow_up_date->format('M j') }}</p>
                                @if($followUp->follow_up_time)
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                                @endif
                                @if($followUp->price)
                                    <p class="text-xs font-semibold text-green-600">৳{{ number_format($followUp->price, 0) }}</p>
                                @endif
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
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Client</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Interest</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                    @forelse($followUps as $followUp)
                    <tbody x-data="{ editing: false }" class="bg-white divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <a href="{{ route('leads.show', $followUp->lead) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">
                                    {{ $followUp->lead->client_name }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $followUp->lead->phone_number }}
                            </td>
                            <td class="px-4 py-3">
                                @if($followUp->interest)
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                        {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                        {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                        {{ $followUp->interest }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">
                                @if($followUp->price)
                                    <span class="font-semibold text-green-600">৳{{ number_format($followUp->price, 0) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">
                                {{ $followUp->follow_up_date->format('M j, Y') }}
                                @if($followUp->follow_up_time)
                                    <br><span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium
                                    @if($followUp->status === 'Completed') bg-green-100 text-green-800
                                    @elseif($followUp->status === 'Cancelled') bg-red-100 text-red-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $followUp->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 max-w-xs truncate">
                                {{ Str::limit($followUp->notes, 40) }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex justify-end gap-1">
                                    <button @click="editing = !editing" class="text-xs px-2 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                        Edit
                                    </button>
                                    @if($followUp->status === 'Pending')
                                        <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded hover:bg-green-200">
                                                ✓
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('follow-ups.destroy', $followUp) }}" method="POST" class="inline" onsubmit="return confirm('Delete this follow-up?')">
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
                                <form action="{{ route('follow-ups.update', $followUp) }}" method="POST" class="flex flex-wrap gap-3 items-end">
                                    @csrf
                                    @method('PATCH')
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Interest</label>
                                        <select name="interest" class="text-sm rounded-md border-gray-300">
                                            <option value="">Select...</option>
                                            @foreach($interestStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $followUp->interest === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Price</label>
                                        <input type="number" name="price" value="{{ $followUp->price }}" step="0.01" class="w-28 text-sm rounded-md border-gray-300" placeholder="0.00">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Date</label>
                                        <input type="date" name="follow_up_date" value="{{ $followUp->follow_up_date->format('Y-m-d') }}" class="text-sm rounded-md border-gray-300">
                                    </div>
                                    <div>
                                        <label class="text-xs font-medium text-gray-700">Status</label>
                                        <select name="status" class="text-sm rounded-md border-gray-300">
                                            <option value="Pending" {{ $followUp->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Completed" {{ $followUp->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $followUp->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-xs font-medium text-gray-700">Notes</label>
                                        <input type="text" name="notes" value="{{ $followUp->notes }}" class="w-full text-sm rounded-md border-gray-300">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700">Save</button>
                                        <button type="button" @click="editing = false" class="px-3 py-1.5 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300">Cancel</button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    </tbody>
                    @empty
                    <tbody class="bg-white">
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                No follow-ups found.
                            </td>
                        </tr>
                    </tbody>
                    @endforelse
            </table>
        </div>
        @if($followUps->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $followUps->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
