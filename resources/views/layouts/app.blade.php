<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches),
    sidebarOpen: false,
    init() {
        document.documentElement.classList.toggle('dark', this.darkMode);
        this.$watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            document.documentElement.classList.toggle('dark', val);
        });
    }
}" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskFlow') }}</title>
        <meta name="description" content="TaskFlow — Manage tasks, empower teams, ship faster.">

        <!-- Dark Mode Guard -->
        <script>
            if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-slate-50 dark:bg-slate-950 flex">

            {{-- ========== SIDEBAR (Desktop) ========== --}}
            <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 bg-slate-900 border-r border-slate-800 z-30">
                {{-- Logo --}}
                <div class="flex items-center h-16 px-5 border-b border-slate-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="10" fill="url(#sidebar-logo)"/>
                            <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                            <defs><linearGradient id="sidebar-logo" x1="0" y1="0" x2="40" y2="40"><stop stop-color="#e11d48"/><stop offset="1" stop-color="#ec4899"/></linearGradient></defs>
                        </svg>
                        <span class="text-lg font-bold text-white tracking-tight">TaskFlow</span>
                    </a>
                </div>

                {{-- Navigation --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-500">Menu</p>

                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>'>
                        {{ __('Dashboard') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'>
                        {{ __('Tasks') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('teams.index')" :active="request()->routeIs('teams.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>'>
                        {{ __('Teams') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('categories.create')" :active="request()->routeIs('categories.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>'>
                        {{ __('Categories') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('logs.index')" :active="request()->routeIs('logs.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>'>
                        {{ __('Activity Log') }}
                    </x-sidebar-link>
                </nav>

                {{-- User Section --}}
                <div class="px-3 py-4 border-t border-slate-800">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 transition-colors duration-200 group">
                        <x-avatar :name="Auth::user()->name" size="sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-200 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </a>
                </div>
            </aside>

            {{-- ========== MOBILE SIDEBAR OVERLAY ========== --}}
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-40 lg:hidden" @click="sidebarOpen = false" style="display: none;"></div>

            <aside x-show="sidebarOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 w-64 bg-slate-900 border-r border-slate-800 z-50 lg:hidden flex flex-col" style="display: none;">
                {{-- Mobile Logo + Close --}}
                <div class="flex items-center justify-between h-16 px-5 border-b border-slate-800">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                        <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="10" fill="url(#mobile-logo)"/>
                            <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                            <defs><linearGradient id="mobile-logo" x1="0" y1="0" x2="40" y2="40"><stop stop-color="#e11d48"/><stop offset="1" stop-color="#ec4899"/></linearGradient></defs>
                        </svg>
                        <span class="text-lg font-bold text-white tracking-tight">TaskFlow</span>
                    </a>
                    <button @click="sidebarOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Mobile Navigation --}}
                <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
                    <p class="px-3 mb-2 text-[10px] font-semibold uppercase tracking-wider text-slate-500">Menu</p>

                    <x-sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/></svg>'>
                        {{ __('Dashboard') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('tasks.index')" :active="request()->routeIs('tasks.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'>
                        {{ __('Tasks') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('teams.index')" :active="request()->routeIs('teams.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/></svg>'>
                        {{ __('Teams') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('categories.create')" :active="request()->routeIs('categories.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/></svg>'>
                        {{ __('Categories') }}
                    </x-sidebar-link>

                    <x-sidebar-link :href="route('logs.index')" :active="request()->routeIs('logs.*')"
                        icon='<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>'>
                        {{ __('Activity Log') }}
                    </x-sidebar-link>
                </nav>

                {{-- Mobile User --}}
                <div class="px-3 py-4 border-t border-slate-800">
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-800 transition-colors duration-200">
                        <x-avatar :name="Auth::user()->name" size="sm" />
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-200 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </a>
                </div>
            </aside>

            {{-- ========== MAIN CONTENT AREA ========== --}}
            <div class="flex-1 lg:pl-64 flex flex-col min-h-screen">

                {{-- Top Bar --}}
                <header class="sticky top-0 z-20 bg-white/80 dark:bg-slate-900/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-800">
                    <div class="flex items-center justify-between h-16 px-4 sm:px-6">
                        {{-- Left: Hamburger + Search --}}
                        <div class="flex items-center gap-3">
                            <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/></svg>
                            </button>
                        </div>

                        {{-- Right: Actions --}}
                        <div class="flex items-center gap-1 sm:gap-2">
                            {{-- Dark Mode Toggle --}}
                            <button @click="darkMode = !darkMode" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200" title="Toggle dark mode">
                                <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                <svg x-show="!darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                            </button>

                            {{-- Notifications --}}
                            <x-notification-bell />

                            {{-- User Dropdown --}}
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200">
                                        <x-avatar :name="Auth::user()->name" size="sm" />
                                        <svg class="w-4 h-4 text-slate-400 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/></svg>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-slate-500 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <x-dropdown-link :href="route('profile.edit')">
                                            {{ __('Profile Settings') }}
                                        </x-dropdown-link>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                                {{ __('Log Out') }}
                                            </x-dropdown-link>
                                        </form>
                                    </div>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </header>

                {{-- Page Content --}}
                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- Floating Toast Notifications --}}
        <div class="fixed bottom-5 right-5 sm:bottom-6 sm:right-6 z-50 flex flex-col gap-3 max-w-sm w-full">
            @if(session('success'))
                <x-alert type="success" :dismissible="true">{{ session('success') }}</x-alert>
            @endif
            @if(session('error'))
                <x-alert type="error" :dismissible="true">{{ session('error') }}</x-alert>
            @endif
            @if(session('warning'))
                <x-alert type="warning" :dismissible="true">{{ session('warning') }}</x-alert>
            @endif
            @if(session('info'))
                <x-alert type="info" :dismissible="true">{{ session('info') }}</x-alert>
            @endif
        </div>
    </body>
</html>
