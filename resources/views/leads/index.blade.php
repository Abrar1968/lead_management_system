<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                All Leads
            </h2>
            <a href="{{ route('leads.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Lead
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            {{-- Notice --}}
            <div class="mb-6 rounded-lg bg-blue-50 p-4 border border-blue-200">
                <div class="flex">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            For a better experience, use the
                            <a href="{{ route('leads.daily') }}" class="font-medium underline hover:text-blue-600">Daily View</a>
                            or
                            <a href="{{ route('leads.monthly') }}" class="font-medium underline hover:text-blue-600">Monthly View</a>
                            to browse leads by date.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Leads Table --}}
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Lead
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Contact
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Source / Service
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Assigned To
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Date
                                </th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($leads as $lead)
                                <tr class="hover:bg-gray-50">
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div>
                                            <a href="{{ route('leads.show', $lead) }}"
                                               class="text-sm font-medium text-indigo-600 hover:text-indigo-500">
                                                {{ $lead->lead_number }}
                                            </a>
                                            <p class="text-sm text-gray-500">{{ $lead->customer_name ?? 'Unknown' }}</p>
                                        </div>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $lead->phone_number }}</div>
                                        @if($lead->email)
                                            <div class="text-sm text-gray-500">{{ $lead->email }}</div>
                                        @endif
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">
                                            {{ $lead->source }}
                                        </span>
                                        <span class="inline-flex items-center rounded-full bg-purple-100 px-2 py-0.5 text-xs font-medium text-purple-800">
                                            {{ $lead->service_interested }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
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
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $lead->assignedTo?->name ?? 'Unassigned' }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        <a href="{{ route('leads.daily', ['date' => $lead->lead_date->format('Y-m-d')]) }}"
                                           class="text-indigo-600 hover:text-indigo-500">
                                            {{ $lead->lead_date->format('M d, Y') }}
                                        </a>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <a href="{{ route('leads.edit', $lead) }}"
                                           class="text-indigo-600 hover:text-indigo-500">
                                            Edit
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No leads found. <a href="{{ route('leads.create') }}" class="text-indigo-600 hover:underline">Create your first lead</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
