<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                📞 Call Log
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
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-green-500">
            <p class="text-xs text-gray-500 uppercase">Calls Today</p>
            <p class="text-2xl font-bold text-gray-900">{{ $stats['today_calls'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-blue-500">
            <p class="text-xs text-gray-500 uppercase">Positive Response</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['positive_responses'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="text-xs text-gray-500 uppercase">Negative Response</p>
            <p class="text-2xl font-bold text-red-600">{{ $stats['negative_responses'] }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-amber-500">
            <p class="text-xs text-gray-500 uppercase">Pending Callbacks</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pending_callbacks'] }}</p>
        </div>
    </div>

    <!-- Response Breakdown -->
    @if(count($responseBreakdown) > 0)
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <h3 class="text-sm font-medium text-gray-700 mb-3">Today's Response Breakdown</h3>
        <div class="flex flex-wrap gap-3">
            @foreach($responseBreakdown as $status => $count)
                <div class="flex items-center gap-2 px-3 py-2 bg-gray-50 rounded-lg">
                    <span class="w-3 h-3 rounded-full {{ $statuses[$status]['bg'] ?? 'bg-gray-400' }}"></span>
                    <span class="text-sm text-gray-700">{{ $status }}</span>
                    <span class="text-sm font-bold text-gray-900">{{ $count }}</span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="date" value="{{ $currentDate }}"
                    class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Response Status</label>
                <select name="response_status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Responses</option>
                    @foreach($statuses as $key => $status)
                        <option value="{{ $key }}" {{ request('response_status') === $key ? 'selected' : '' }}>
                            {{ $status['label'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Filter
                </button>
                <a href="{{ route('contacts.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Calls Table -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Call Records</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lead</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Response</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Caller</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contacts as $contact)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('leads.show', $contact->lead) }}" class="hover:text-blue-600">
                                    <p class="font-medium text-gray-900">{{ $contact->lead->client_name }}</p>
                                    <p class="text-sm text-gray-500">{{ $contact->lead->phone_number }}</p>
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $contact->call_date->format('M j, Y') }}</p>
                                <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($contact->call_time)->format('h:i A') }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs rounded-full {{ $statuses[$contact->response_status]['bg'] ?? 'bg-gray-500' }} text-white">
                                    {{ $contact->response_status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm text-gray-900">{{ $contact->caller->name ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-gray-600">{{ Str::limit($contact->notes, 50) }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <form action="{{ route('contacts.destroy', $contact) }}" method="POST" class="inline" onsubmit="return confirm('Delete this call record?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                No call records found for this date.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($contacts->hasPages())
            <div class="px-6 py-4 border-t">
                {{ $contacts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
