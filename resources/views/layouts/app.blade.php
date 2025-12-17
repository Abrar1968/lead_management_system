<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Lead MS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            /* Alpine.js cloak */
            [x-cloak] { display: none !important; }

            /* Custom animations */
            @keyframes slideInLeft {
                from { opacity: 0; transform: translateX(-20px); }
                to { opacity: 1; transform: translateX(0); }
            }
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            @keyframes pulse-glow {
                0%, 100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
                50% { box-shadow: 0 0 0 8px rgba(59, 130, 246, 0); }
            }
            .animate-slide-in { animation: slideInLeft 0.3s ease-out forwards; }
            .animate-fade-up { animation: fadeInUp 0.4s ease-out forwards; }
            .animate-pulse-glow { animation: pulse-glow 2s infinite; }
            .nav-item { transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); }
            .nav-item:hover { transform: translateX(4px); }
            .glass-effect { backdrop-filter: blur(12px); background: rgba(255, 255, 255, 0.1); }
            .gradient-sidebar { background: linear-gradient(180deg, #1e3a5f 0%, #0f172a 100%); }
            .badge-pulse { animation: pulse-glow 2s infinite; }
        </style>
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }" style="font-family: 'Inter', sans-serif;">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-slate-100">
            <!-- Mobile sidebar backdrop -->
            <div x-show="sidebarOpen"
                 x-transition:enter="transition-opacity ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden"
                 @click="sidebarOpen = false">
            </div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-72 gradient-sidebar shadow-2xl transform transition-all duration-300 ease-out lg:translate-x-0">

                <!-- Logo -->
                <div class="flex items-center justify-between h-20 px-6 border-b border-white/10">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-white text-lg font-bold tracking-tight">Lead MS</h1>
                            <p class="text-blue-300/60 text-xs">Lead Management</p>
                        </div>
                    </div>
                    <button @click="sidebarOpen = false" class="lg:hidden p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Navigation -->
                <nav class="mt-6 px-4 overflow-y-auto" style="max-height: calc(100vh - 180px);">
                    <div class="space-y-1">
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}"
                           class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                </svg>
                            </div>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <!-- Daily Leads (Primary) -->
                        <a href="{{ route('leads.daily') }}"
                           class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('leads.daily') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <span class="font-medium">Daily Leads</span>
                            <span class="ml-auto bg-gradient-to-r from-emerald-400 to-teal-500 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm badge-pulse">Today</span>
                        </a>

                        <!-- Monthly View -->
                        <a href="{{ route('leads.monthly') }}"
                           class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('leads.monthly') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="font-medium">Monthly View</span>
                        </a>

                        <!-- Add New Lead -->
                        <a href="{{ route('leads.create') }}"
                           class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('leads.create') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                            </div>
                            <span class="font-medium">Add New Lead</span>
                        </a>

                        <!-- All Leads -->
                        <a href="{{ route('leads.index') }}"
                           class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('leads.index') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                            <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-slate-500 to-gray-600 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </div>
                            <span class="font-medium">All Leads</span>
                        </a>

                        <!-- Activity Section -->
                        <div class="pt-6 mt-4">
                            <p class="px-4 text-xs text-blue-300/50 uppercase tracking-wider font-semibold mb-3">Activity</p>

                            <!-- Follow-ups -->
                            <a href="{{ route('follow-ups.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('follow-ups.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Follow-ups</span>
                                @php
                                    $pendingFollowUps = \App\Models\FollowUp::where('status', 'Pending')
                                        ->where('follow_up_date', '<=', now()->format('Y-m-d'))
                                        ->whereHas('lead', function($q) {
                                            $q->whereNull('deleted_at');
                                            if (!auth()->user()->isAdmin()) {
                                                $q->where('assigned_to', auth()->id());
                                            }
                                        })
                                        ->count();
                                @endphp
                                @if($pendingFollowUps > 0)
                                    <span class="ml-auto bg-gradient-to-r from-red-500 to-rose-500 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm badge-pulse">{{ $pendingFollowUps }}</span>
                                @endif
                            </a>

                            <!-- Meetings -->
                            <a href="{{ route('meetings.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('meetings.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-pink-500 to-rose-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Meetings</span>
                                @php
                                    $todayMeetings = \App\Models\Meeting::where('meeting_date', now()->format('Y-m-d'))
                                        ->where('outcome', 'Pending')
                                        ->whereHas('lead', function($q) {
                                            $q->whereNull('deleted_at');
                                            if (!auth()->user()->isAdmin()) {
                                                $q->where('assigned_to', auth()->id());
                                            }
                                        })
                                        ->count();
                                @endphp
                                @if($todayMeetings > 0)
                                    <span class="ml-auto bg-gradient-to-r from-indigo-500 to-purple-500 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm">{{ $todayMeetings }}</span>
                                @endif
                            </a>

                            <!-- Contacts/Calls -->
                            <a href="{{ route('contacts.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('contacts.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Contacts</span>
                                @php
                                    $todayContacts = \App\Models\LeadContact::whereDate('call_date', now()->format('Y-m-d'))
                                        ->whereHas('lead', fn($q) => $q->whereNull('deleted_at'))
                                        ->when(!auth()->user()->isAdmin(), fn($q) => $q->whereHas('lead', fn($lq) => $lq->where('assigned_to', auth()->id())))
                                        ->count();
                                @endphp
                                @if($todayContacts > 0)
                                    <span class="ml-auto bg-gradient-to-r from-green-500 to-emerald-500 text-white text-xs px-2.5 py-1 rounded-full font-semibold shadow-sm">{{ $todayContacts }}</span>
                                @endif
                            </a>
                        </div>

                        @if(auth()->user()->isAdmin())
                        <div class="pt-6 mt-4">
                            <p class="px-4 text-xs text-blue-300/50 uppercase tracking-wider font-semibold mb-3">Admin</p>

                            <!-- Users -->
                            <a href="{{ route('users.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('users.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-cyan-500 to-blue-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Users</span>
                            </a>

                            <!-- Reports -->
                            <a href="{{ route('reports.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('reports.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-teal-500 to-emerald-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Reports</span>
                            </a>

                            <!-- Extra Commissions -->
                            <a href="{{ route('admin.extra-commissions.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('admin.extra-commissions.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-yellow-500 to-amber-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Extra Commissions</span>
                            </a>

                            <!-- Services -->
                            <a href="{{ route('services.index') }}"
                               class="nav-item flex items-center px-4 py-3 text-white/90 rounded-xl hover:bg-white/10 hover:text-white group {{ request()->routeIs('services.*') ? 'bg-white/15 text-white shadow-lg' : '' }}">
                                <div class="w-9 h-9 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center mr-3 shadow-md group-hover:shadow-lg group-hover:scale-105 transition-all">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                                <span class="font-medium">Services</span>
                            </a>
                        </div>
                        @endif
                    </div>
                </nav>

                <!-- User Info at Bottom -->
                <div class="absolute bottom-0 left-0 right-0 p-5 border-t border-white/10 glass-effect">
                    <div class="flex items-center">
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">{{ substr(auth()->user()->name, 0, 1) }}</span>
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <p class="text-white font-semibold truncate">{{ auth()->user()->name }}</p>
                            <p class="text-blue-300/60 text-xs">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}" class="ml-2">
                            @csrf
                            <button type="submit" class="p-2 text-white/50 hover:text-white hover:bg-white/10 rounded-lg transition-all" title="Logout">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="lg:ml-72 transition-all duration-300">
                <!-- Top Navigation Bar -->
                <header class="bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200/50 sticky top-0 z-30">
                    <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                        <!-- Mobile menu button -->
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>

                        <!-- Date Navigation -->
                        <div class="flex items-center gap-2 flex-wrap" x-data="{ showDatePicker: false }">
                            <a href="{{ route('leads.daily', ['date' => now()->format('Y-m-d')]) }}"
                               class="px-4 py-2 text-sm bg-gradient-to-r from-blue-500 to-indigo-500 text-white rounded-xl hover:from-blue-600 hover:to-indigo-600 font-medium shadow-sm hover:shadow-md transition-all duration-200">
                                Today
                            </a>
                            <a href="{{ route('leads.daily', ['date' => now()->subDay()->format('Y-m-d')]) }}"
                               class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 font-medium transition-all duration-200">
                                Yesterday
                            </a>
                            <div class="relative">
                                <input type="date"
                                       value="{{ request('date', now()->format('Y-m-d')) }}"
                                       onchange="window.location.href='{{ route('leads.daily') }}?date=' + this.value"
                                       class="px-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-gray-50 hover:bg-white transition-all duration-200 cursor-pointer">
                            </div>
                        </div>

                        <!-- Right side: Search & Profile -->
                        <div class="flex items-center gap-3">
                            <!-- Global Search -->
                            <div class="hidden md:block relative group">
                                <input type="text"
                                       placeholder="Search leads..."
                                       class="w-64 pl-11 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 bg-gray-50 group-hover:bg-white transition-all duration-200 placeholder:text-gray-400">
                                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-3 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>

                            <!-- Profile Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl transition-all duration-200">
                                    <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name }}</span>
                                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-lg flex items-center justify-center shadow-sm">
                                        <span class="text-white text-sm font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div x-show="open"
                                     @click.away="open = false"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                                     x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50 overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            Profile
                                        </a>
                                        <a href="{{ route('commission.settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            Commission Settings
                                        </a>
                                    </div>
                                    <div class="border-t border-gray-100 py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/50 backdrop-blur-sm border-b border-gray-100">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="p-4 sm:p-6 lg:p-8 animate-fade-up">
                    <!-- Flash Messages -->
                    @if(session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="mb-6 p-4 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <p class="text-emerald-800 font-medium">{{ session('success') }}</p>
                            <button @click="show = false" class="ml-auto text-emerald-600 hover:text-emerald-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                             x-transition:leave="transition ease-in duration-300"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="mb-6 p-4 bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <p class="text-red-800 font-medium">{{ session('error') }}</p>
                            <button @click="show = false" class="ml-auto text-red-600 hover:text-red-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    @endif

                    {{ $slot }}
                </main>

                <!-- Footer -->
                <footer class="py-6 px-4 sm:px-6 lg:px-8 border-t border-gray-100 bg-white/30">
                    <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-4 text-sm text-gray-500">
                        <p>© {{ date('Y') }} Lead MS. All rights reserved.</p>
                        <p class="text-xs">Built with ❤️ for Lead Management</p>
                    </div>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
