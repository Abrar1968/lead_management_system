<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/30">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Follow-ups</h2>
                    <p class="text-sm text-gray-500">Manage your scheduled follow-ups</p>
                </div>
            </div>
        </div>
    </x-slot>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-6 rounded-2xl bg-gradient-to-r from-emerald-50 to-green-50 p-4 border border-emerald-200 shadow-lg shadow-emerald-500/10"
             x-data="{ show: true }" x-show="show" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <p class="flex-1 text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
                <button @click="show = false" class="text-emerald-500 hover:text-emerald-700 transition-colors">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="group overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 text-white shadow-lg shadow-amber-500/30 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Today's Follow-ups</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['today'] }}</p>
                </div>
            </div>
        </div>
        <div class="group overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-red-500 to-rose-600 text-white shadow-lg shadow-red-500/30 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Overdue</p>
                    <p class="text-2xl font-bold text-red-600">{{ $stats['overdue'] }}</p>
                </div>
            </div>
        </div>
        <div class="group overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white shadow-lg shadow-blue-500/30 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">This Week</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['this_week'] }}</p>
                </div>
            </div>
        </div>
        <div class="group overflow-hidden rounded-2xl bg-white p-5 shadow-lg shadow-gray-200/50 border border-gray-100 transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 text-white shadow-lg shadow-emerald-500/30 transition-transform duration-300 group-hover:scale-110">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Completed Today</p>
                    <p class="text-2xl font-bold text-emerald-600">{{ $stats['completed_today'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="overflow-hidden rounded-2xl bg-white p-6 shadow-lg shadow-gray-200/50 border border-gray-100 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Date</label>
                <input type="date" name="date" value="{{ $currentDate }}"
                    class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-gray-700">Status</label>
                <select name="status" class="rounded-xl border-gray-200 bg-gray-50 px-4 py-2.5 text-sm font-medium text-gray-700 transition-all duration-200 focus:border-amber-500 focus:bg-white focus:ring-2 focus:ring-amber-500/20">
                    <option value="">All Statuses</option>
                    <option value="Pending" {{ request('status') === 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Completed" {{ request('status') === 'Completed' ? 'selected' : '' }}>Completed</option>
                    <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('follow-ups.index') }}" class="inline-flex items-center rounded-xl bg-gray-100 px-5 py-2.5 text-sm font-semibold text-gray-700 transition-all duration-200 hover:bg-gray-200">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Overdue Follow-ups -->
        @if($overdueFollowUps->count() > 0)
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100">
            <div class="bg-gradient-to-r from-red-500 to-rose-600 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Overdue</h3>
                    </div>
                    <span class="inline-flex items-center rounded-lg bg-white/20 px-2.5 py-1 text-xs font-bold text-white">{{ $overdueFollowUps->count() }}</span>
                </div>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @foreach($overdueFollowUps as $followUp)
                    @if($followUp->lead)
                    <div class="px-5 py-4 transition-colors hover:bg-gray-50" x-data="{ editing: false }">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="block group">
                                <p class="font-semibold text-gray-900 group-hover:text-red-600 transition-colors">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                <p class="text-xs font-bold text-red-600">{{ $followUp->follow_up_date->format('M j') }}</p>
                                @if($followUp->price)
                                    <p class="text-xs font-bold text-emerald-600">৳{{ number_format($followUp->price, 0) }}</p>
                                @endif
                            </div>
                        </div>
                        @if($followUp->interest)
                            <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold mt-2
                                {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                {{ $followUp->interest }}
                            </span>
                        @endif
                        @if($followUp->notes)
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">{{ Str::limit($followUp->notes, 60) }}</p>
                        @endif
                        <div class="flex gap-2 mt-3">
                            <button @click="editing = !editing" class="text-xs px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg font-semibold hover:bg-amber-200 transition-colors">
                                Update
                            </button>
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="text-xs px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-colors">
                                View Lead
                            </a>
                        </div>
                        <!-- Inline Edit Form -->
                        <div x-show="editing" x-cloak x-transition class="mt-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Interest</label>
                                        <select name="interest" class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                            <option value="">Select...</option>
                                            @foreach($interestStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $followUp->interest === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Price (৳)</label>
                                        <input type="number" name="price" value="{{ $followUp->price }}" step="0.01" class="w-full text-sm rounded-lg border-gray-200 bg-white" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-gray-700">Notes</label>
                                    <textarea name="notes" rows="2" class="w-full text-sm rounded-lg border-gray-200 bg-white">{{ $followUp->notes }}</textarea>
                                </div>
                                <button type="submit" class="w-full text-sm px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl transition-all">
                                    Complete Follow-up
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        </div>
        @endif

        <!-- Today's Follow-ups -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 {{ $overdueFollowUps->count() == 0 ? 'lg:col-span-2' : '' }}">
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-5 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                            <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="font-semibold text-white">Today</h3>
                    </div>
                    <span class="inline-flex items-center rounded-lg bg-white/20 px-2.5 py-1 text-xs font-bold text-white">{{ $todayFollowUps->count() }}</span>
                </div>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($todayFollowUps as $followUp)
                    @if($followUp->lead)
                    <div class="px-5 py-4 transition-colors hover:bg-gray-50" x-data="{ editing: false }">
                        <div class="flex justify-between items-start">
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="block group">
                                <p class="font-semibold text-gray-900 group-hover:text-amber-600 transition-colors">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                            </a>
                            <div class="text-right">
                                @if($followUp->follow_up_time)
                                    <p class="text-xs font-bold text-amber-600">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                                @endif
                                @if($followUp->price)
                                    <p class="text-xs font-bold text-emerald-600">৳{{ number_format($followUp->price, 0) }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            @if($followUp->interest)
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold
                                    {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                    {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                    {{ $followUp->interest }}
                                </span>
                            @endif
                            <span class="text-xs px-2.5 py-1 rounded-lg font-semibold {{ $followUp->status === 'Completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $followUp->status }}
                            </span>
                        </div>
                        @if($followUp->notes)
                            <p class="text-xs text-gray-600 mt-2 leading-relaxed">{{ Str::limit($followUp->notes, 80) }}</p>
                        @endif
                        @if($followUp->status === 'Pending')
                        <div class="flex gap-2 mt-3">
                            <button @click="editing = !editing" class="text-xs px-3 py-1.5 bg-amber-100 text-amber-700 rounded-lg font-semibold hover:bg-amber-200 transition-colors">
                                Update
                            </button>
                            <a href="{{ route('leads.show', $followUp->lead) }}" class="text-xs px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg font-semibold hover:bg-blue-200 transition-colors">
                                View Lead
                            </a>
                        </div>
                        <!-- Inline Edit Form -->
                        <div x-show="editing" x-cloak x-transition class="mt-3 p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="space-y-3">
                                @csrf
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Interest</label>
                                        <select name="interest" class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                            <option value="">Select...</option>
                                            @foreach($interestStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $followUp->interest === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Price (৳)</label>
                                        <input type="number" name="price" value="{{ $followUp->price }}" step="0.01" class="w-full text-sm rounded-lg border-gray-200 bg-white" placeholder="0.00">
                                    </div>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-xs font-semibold text-gray-700">Notes</label>
                                    <textarea name="notes" rows="2" class="w-full text-sm rounded-lg border-gray-200 bg-white">{{ $followUp->notes }}</textarea>
                                </div>
                                <button type="submit" class="w-full text-sm px-4 py-2 bg-gradient-to-r from-emerald-500 to-green-600 text-white rounded-lg font-semibold shadow-lg shadow-emerald-500/30 hover:shadow-xl transition-all">
                                    Complete Follow-up
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endif
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="mx-auto h-16 w-16 rounded-2xl bg-emerald-100 flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">No follow-ups scheduled for today!</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Upcoming Follow-ups -->
        <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 {{ $overdueFollowUps->count() == 0 ? 'lg:col-span-1' : '' }}">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-5 py-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-white/20">
                        <svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-white">Upcoming</h3>
                </div>
            </div>
            <div class="divide-y divide-gray-100 max-h-96 overflow-y-auto">
                @forelse($upcomingFollowUps as $followUp)
                    @if($followUp->lead)
                    <a href="{{ route('leads.show', $followUp->lead) }}" class="block px-5 py-4 transition-colors hover:bg-gray-50 group">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900 text-sm group-hover:text-blue-600 transition-colors">{{ $followUp->lead->client_name }}</p>
                                <p class="text-xs text-gray-500">{{ $followUp->lead->phone_number }}</p>
                                @if($followUp->interest)
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold mt-2
                                        {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                        {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                        {{ $followUp->interest }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-blue-600">{{ $followUp->follow_up_date->format('M j') }}</p>
                                @if($followUp->follow_up_time)
                                    <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</p>
                                @endif
                                @if($followUp->price)
                                    <p class="text-xs font-bold text-emerald-600 mt-1">৳{{ number_format($followUp->price, 0) }}</p>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endif
                @empty
                    <div class="px-5 py-10 text-center">
                        <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                            <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">No upcoming follow-ups</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- All Follow-ups Table -->
    <div class="overflow-hidden rounded-2xl bg-white shadow-lg shadow-gray-200/50 border border-gray-100 mt-6">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-6 py-4">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/20">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white">All Follow-ups</h3>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Client</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Phone</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Interest</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Price</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Date</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Status</th>
                        <th class="px-4 py-4 text-left text-xs font-bold uppercase tracking-wider text-gray-500">Notes</th>
                        <th class="px-4 py-4 text-right text-xs font-bold uppercase tracking-wider text-gray-500">Actions</th>
                    </tr>
                </thead>
                    @forelse($followUps as $followUp)
                    @if($followUp->lead)
                    <tbody x-data="{ editing: false }" class="bg-white divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-4">
                                <a href="{{ route('leads.show', $followUp->lead) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition-colors">
                                    {{ $followUp->lead->client_name }}
                                </a>
                            </td>
                            <td class="px-4 py-4 text-sm font-medium text-gray-600">
                                {{ $followUp->lead->phone_number }}
                            </td>
                            <td class="px-4 py-4">
                                @if($followUp->interest)
                                    <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold
                                        {{ $interestStatuses[$followUp->interest]['bg'] ?? 'bg-gray-100' }}
                                        {{ $interestStatuses[$followUp->interest]['text'] ?? 'text-gray-800' }}">
                                        {{ $followUp->interest }}
                                    </span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                @if($followUp->price)
                                    <span class="font-bold text-emerald-600">৳{{ number_format($followUp->price, 0) }}</span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-700 font-medium">
                                {{ $followUp->follow_up_date->format('M j, Y') }}
                                @if($followUp->follow_up_time)
                                    <br><span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($followUp->follow_up_time)->format('h:i A') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-bold shadow-sm
                                    @if($followUp->status === 'Completed') bg-gradient-to-r from-emerald-500 to-green-600 text-white shadow-emerald-500/30
                                    @elseif($followUp->status === 'Cancelled') bg-gradient-to-r from-gray-400 to-gray-500 text-white shadow-gray-500/30
                                    @else bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-amber-500/30 @endif">
                                    {{ $followUp->status }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-600 max-w-xs truncate">
                                {{ Str::limit($followUp->notes, 40) }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex justify-end gap-1">
                                    <button @click="editing = !editing" class="text-xs px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg font-semibold hover:bg-gray-200 transition-colors">
                                        Edit
                                    </button>
                                    @if($followUp->status === 'Pending')
                                        <form action="{{ route('follow-ups.complete', $followUp) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-xs px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg font-semibold hover:bg-emerald-200 transition-colors">
                                                ✓
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('follow-ups.destroy', $followUp) }}" method="POST" class="inline" onsubmit="return confirm('Delete this follow-up?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs px-3 py-1.5 bg-red-100 text-red-700 rounded-lg font-semibold hover:bg-red-200 transition-colors">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Inline Edit Row -->
                        <tr x-show="editing" x-cloak x-transition class="bg-gray-50">
                            <td colspan="8" class="px-4 py-4">
                                <form action="{{ route('follow-ups.update', $followUp) }}" method="POST" class="flex flex-wrap gap-4 items-end">
                                    @csrf
                                    @method('PATCH')
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Interest</label>
                                        <select name="interest" class="text-sm rounded-lg border-gray-200 bg-white">
                                            <option value="">Select...</option>
                                            @foreach($interestStatuses as $key => $status)
                                                <option value="{{ $key }}" {{ $followUp->interest === $key ? 'selected' : '' }}>{{ $status['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Price</label>
                                        <input type="number" name="price" value="{{ $followUp->price }}" step="0.01" class="w-28 text-sm rounded-lg border-gray-200 bg-white" placeholder="0.00">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Date</label>
                                        <input type="date" name="follow_up_date" value="{{ $followUp->follow_up_date->format('Y-m-d') }}" class="text-sm rounded-lg border-gray-200 bg-white">
                                    </div>
                                    <div class="space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Status</label>
                                        <select name="status" class="text-sm rounded-lg border-gray-200 bg-white">
                                            <option value="Pending" {{ $followUp->status === 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Completed" {{ $followUp->status === 'Completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="Cancelled" {{ $followUp->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    <div class="flex-1 space-y-1">
                                        <label class="text-xs font-semibold text-gray-700">Notes</label>
                                        <input type="text" name="notes" value="{{ $followUp->notes }}" class="w-full text-sm rounded-lg border-gray-200 bg-white">
                                    </div>
                                    <div class="flex gap-2">
                                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white text-sm font-semibold rounded-lg shadow-lg shadow-indigo-500/30 hover:shadow-xl transition-all">Save</button>
                                        <button type="button" @click="editing = false" class="px-4 py-2 bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg hover:bg-gray-300 transition-colors">Cancel</button>
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
                                <div class="mx-auto h-16 w-16 rounded-2xl bg-gray-100 flex items-center justify-center mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-500">No follow-ups found.</p>
                            </td>
                        </tr>
                    </tbody>
                    @endforelse
            </table>
        </div>
        @if($followUps->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $followUps->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
