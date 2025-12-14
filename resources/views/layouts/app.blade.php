<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'WhatsApp CRM') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-gray-100">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/80 z-40 lg:hidden"
                 @click="sidebarOpen = false">
            </div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-blue-900 transform transition-transform duration-300 ease-in-out lg:translate-x-0">

                <!-- Logo -->
                <div class="flex items-center justify-center h-16 bg-blue-950">
                    <h1 class="text-white text-xl font-bold">WhatsApp CRM</h1>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 px-4">
                    <div class="space-y-2">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}"
                           class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('dashboard') ? 'bg-blue-800' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>

                        <!-- Daily Leads (Primary) -->
                        <a href="{{ route('leads.daily') }}"
                           class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('leads.daily') ? 'bg-blue-800' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Daily Leads
                            <span class="ml-auto bg-blue-500 text-xs px-2 py-1 rounded-full">Today</span>
                        </a>

                        <!-- Monthly View -->
                        <a href="{{ route('leads.monthly') }}"
                           class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('leads.monthly') ? 'bg-blue-800' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            Monthly View
                        </a>

                        <!-- Add New Lead -->
                        <a href="{{ route('leads.create') }}"
                           class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('leads.create') ? 'bg-blue-800' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add New Lead
                        </a>

                        <!-- All Leads -->
                        <a href="{{ route('leads.index') }}"
                           class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('leads.index') ? 'bg-blue-800' : '' }}">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                            </svg>
                            All Leads
                        </a>

                        <!-- Divider -->
                        <div class="pt-4 mt-4 border-t border-blue-800">
                            <p class="px-4 text-xs text-blue-400 uppercase tracking-wider">Activity</p>

                            <!-- Follow-ups -->
                            <a href="{{ route('follow-ups.index') }}"
                               class="flex items-center px-4 py-3 mt-2 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('follow-ups.*') ? 'bg-blue-800' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Follow-ups
                                @php
                                    $pendingFollowUps = \App\Models\FollowUp::where('status', 'Pending')
                                        ->where('follow_up_date', '<=', now()->format('Y-m-d'))
                                        ->when(!auth()->user()->isAdmin(), fn($q) => $q->whereHas('lead', fn($q) => $q->where('assigned_to', auth()->id())))
                                        ->count();
                                @endphp
                                @if($pendingFollowUps > 0)
                                    <span class="ml-auto bg-orange-500 text-xs px-2 py-1 rounded-full">{{ $pendingFollowUps }}</span>
                                @endif
                            </a>

                            <!-- Meetings -->
                            <a href="{{ route('meetings.index') }}"
                               class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('meetings.*') ? 'bg-blue-800' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Meetings
                                @php
                                    $todayMeetings = \App\Models\Meeting::where('meeting_date', now()->format('Y-m-d'))
                                        ->where('outcome', 'Pending')
                                        ->when(!auth()->user()->isAdmin(), fn($q) => $q->whereHas('lead', fn($q) => $q->where('assigned_to', auth()->id())))
                                        ->count();
                                @endphp
                                @if($todayMeetings > 0)
                                    <span class="ml-auto bg-indigo-500 text-xs px-2 py-1 rounded-full">{{ $todayMeetings }}</span>
                                @endif
                            </a>
                        </div>

                        @if(auth()->user()->isAdmin())
                        <div class="pt-4 mt-4 border-t border-blue-800">
                            <p class="px-4 text-xs text-blue-400 uppercase tracking-wider">Admin</p>

                            <!-- Users -->
                            <a href="{{ route('users.index') }}"
                               class="flex items-center px-4 py-3 mt-2 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('users.*') ? 'bg-blue-800' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                Users
                            </a>

                            <!-- Reports -->
                            <a href="{{ route('reports.index') }}"
                               class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-blue-800 {{ request()->routeIs('reports.*') ? 'bg-blue-800' : '' }}">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Reports
                            </a>
                        </div>
                        @endif
                    </div>
                </nav>

                <!-- User Info at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-blue-800">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-700 rounded-full flex items-center justify-center">
                            <span class="text-white font-medium">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3">
                            <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                            <p class="text-blue-400 text-xs">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="lg:ml-64">
                <!-- Top Navigation Bar -->
                <header class="bg-white shadow-sm border-b border-gray-200">
                    <div class="flex items-center justify-between px-4 py-3">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true" class="lg:hidden text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Date Navigation -->
                        <div class="flex items-center space-x-2" x-data="{ showDatePicker: false }">
                            <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}"
                               class="px-3 py-1.5 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 font-medium">
                                Today
                            </a>
                            <a href="{{ route('leads.daily', ['date' => now()->subDay()->format('Y-m-d')]) }}"
                               class="px-3 py-1.5 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                                Yesterday
                            </a>
                            <div class="relative">
                                <input type="date"
                                       value="{{ request('date', now()->format('Y-m-d')) }}"
                                       onchange="window.location.href='{{ route('leads.daily') }}?date=' + this.value"
                                       class="px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Right side: Search & Profile -->
                        <div class="flex items-center space-x-4">
                            <!-- Global Search -->
                            <div class="hidden md:block relative">
                                <input type="text"
                                       placeholder="Search leads by phone..."
                                       class="w-64 pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center space-x-2 text-gray-700 hover:text-gray-900">
                                    <span class="hidden md:block text-sm">{{ auth()->user()->name }}</span>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                    <a href="{{ route('commission.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Commission Settings</a>
                                    <hr class="my-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white border-b border-gray-200">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
