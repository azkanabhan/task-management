<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)
}" x-init="document.documentElement.classList.toggle('dark', darkMode)" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskFlow') }}</title>

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
        <div class="min-h-screen flex">
            {{-- Left Panel: Brand/Decorative --}}
            <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-rose-600 via-pink-600 to-fuchsia-600">
                {{-- Decorative elements --}}
                <div class="absolute inset-0">
                    <div class="absolute top-1/4 -left-20 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-1/4 right-0 w-96 h-96 bg-fuchsia-400/20 rounded-full blur-3xl"></div>
                    <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-pink-300/10 rounded-full blur-2xl"></div>
                </div>

                {{-- Content --}}
                <div class="relative z-10 flex flex-col justify-between w-full p-12">
                    {{-- Logo --}}
                    <div class="flex items-center gap-2.5">
                        <svg class="h-9 w-9" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="10" fill="rgba(255,255,255,0.2)"/>
                            <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                        </svg>
                        <span class="text-xl font-bold text-white tracking-tight">TaskFlow</span>
                    </div>

                    {{-- Center content --}}
                    <div class="max-w-md">
                        <h2 class="text-3xl font-bold text-white leading-tight mb-4">
                            Manage tasks,<br>empower your team.
                        </h2>
                        <p class="text-lg text-white/70 leading-relaxed">
                            The modern way to organize work, collaborate with your team, and ship faster together.
                        </p>

                        {{-- Floating cards --}}
                        <div class="mt-10 space-y-3">
                            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10 animate-float">
                                <div class="w-8 h-8 rounded-lg bg-emerald-400/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">Task completed</p>
                                    <p class="text-xs text-white/50">Homepage redesign — just now</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10 animate-float" style="animation-delay: 2s;">
                                <div class="w-8 h-8 rounded-lg bg-sky-400/20 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-sky-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-white">New team member</p>
                                    <p class="text-xs text-white/50">Alex joined Frontend team</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Bottom --}}
                    <p class="text-xs text-white/40">&copy; {{ date('Y') }} TaskFlow. All rights reserved.</p>
                </div>
            </div>

            {{-- Right Panel: Form --}}
            <div class="flex-1 flex flex-col justify-center items-center px-6 py-12 bg-white dark:bg-slate-900">
                {{-- Mobile logo --}}
                <div class="lg:hidden mb-8">
                    <a href="/" class="flex items-center gap-2.5">
                        <svg class="h-10 w-10" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="10" fill="url(#guest-logo)"/>
                            <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                            <defs><linearGradient id="guest-logo" x1="0" y1="0" x2="40" y2="40"><stop stop-color="#e11d48"/><stop offset="1" stop-color="#ec4899"/></linearGradient></defs>
                        </svg>
                        <span class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">TaskFlow</span>
                    </a>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
