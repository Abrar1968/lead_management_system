<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Extra Commissions
            </h2>
            <a href="{{ route('extra-commissions.create') }}"
               class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                Add Extra Commission
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 rounded-lg bg-green-50 p-4 border border-green-200">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Filters --}}
            <div class="mb-6 flex flex-wrap gap-4">
                <form method="GET" class="flex flex-wrap gap-2">
                    <select name="status" class="rounded-md border-gray-300 text-sm">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ $currentStatus === 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Approved" {{ $currentStatus === 'Approved' ? 'selected' : '' }}>Approved</option>
                        <option value="Paid" {{ $currentStatus === 'Paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                    <select name="user_id" class="rounded-md border-gray-300 text-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ $currentUserId == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Filter
                    </button>
                    <a href="{{ route('extra-commissions.index') }}" class="rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                        Reset
                    </a>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-hidden rounded-lg bg-white shadow">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">User</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase text-gray-500">Date</th>
                                <th class="px-6 py-3 text-center text-xs font-medium uppercase text-gray-500">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Amount</th>
                                <th class="px-6 py-3 text-right text-xs font-medium uppercase text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse($commissions as $commission)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                        {{ $commission->user->name }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $commission->commission_type }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        {{ Str::limit($commission->description, 40) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                        {{ $commission->date_earned->format('M d, Y') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-center">
                                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                            @switch($commission->status)
                                                @case('Pending') bg-yellow-100 text-yellow-800 @break
                                                @case('Approved') bg-green-100 text-green-800 @break
                                                @case('Paid') bg-blue-100 text-blue-800 @break
                                            @endswitch">
                                            {{ $commission->status }}
                                        </span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-semibold text-purple-600">
                                        ৳{{ number_format($commission->amount) }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                        <div class="flex justify-end gap-2">
                                            @if($commission->status === 'Pending')
                                                <form action="{{ route('extra-commissions.approve', $commission) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                                </form>
                                            @elseif($commission->status === 'Approved')
                                                <form action="{{ route('extra-commissions.mark-paid', $commission) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900">Mark Paid</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('extra-commissions.edit', $commission) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('extra-commissions.destroy', $commission) }}" method="POST"
                                                  onsubmit="return confirm('Delete this commission?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No extra commissions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($commissions->hasPages())
                    <div class="border-t px-6 py-4">
                        {{ $commissions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
