<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true', mobileMenu: false }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="TaskFlow — The modern task management platform for teams. Create, assign, and track tasks effortlessly. Empower your team to ship faster.">
    <meta name="keywords" content="task management, project management, team collaboration, productivity, agile">
    <meta name="author" content="TaskFlow">
    <meta property="og:title" content="TaskFlow — Manage Tasks. Empower Teams. Ship Faster.">
    <meta property="og:description" content="The modern task management platform built for high-performing teams.">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">

    <title>{{ config('app.name', 'TaskFlow') }} — Manage Tasks. Empower Teams.</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }

        /* Floating animation */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        @keyframes float-delayed {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .animate-float { animation: float 6s ease-in-out infinite; }
        .animate-float-delayed { animation: float-delayed 7s ease-in-out infinite 1s; }
        .animate-float-slow { animation: float-slow 8s ease-in-out infinite 0.5s; }

        /* Gradient mesh background */
        .hero-gradient {
            background:
                radial-gradient(ellipse 80% 60% at 20% 60%, rgba(225, 29, 72, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 30%, rgba(236, 72, 153, 0.10) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 50% 80%, rgba(251, 113, 133, 0.08) 0%, transparent 70%);
        }
        .dark .hero-gradient {
            background:
                radial-gradient(ellipse 80% 60% at 20% 60%, rgba(225, 29, 72, 0.15) 0%, transparent 70%),
                radial-gradient(ellipse 60% 50% at 80% 30%, rgba(236, 72, 153, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse 50% 40% at 50% 80%, rgba(251, 113, 133, 0.06) 0%, transparent 70%);
        }

        /* Scroll reveal */
        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }
        .reveal.revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Stagger children for reveal */
        .reveal-stagger > * {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }
        .reveal-stagger.revealed > *:nth-child(1) { transition-delay: 0ms; opacity: 1; transform: translateY(0); }
        .reveal-stagger.revealed > *:nth-child(2) { transition-delay: 150ms; opacity: 1; transform: translateY(0); }
        .reveal-stagger.revealed > *:nth-child(3) { transition-delay: 300ms; opacity: 1; transform: translateY(0); }
        .reveal-stagger.revealed > *:nth-child(4) { transition-delay: 450ms; opacity: 1; transform: translateY(0); }
        .reveal-stagger.revealed > *:nth-child(5) { transition-delay: 600ms; opacity: 1; transform: translateY(0); }

        /* Glassmorphism for mockup cards */
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        .dark .glass-card {
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }

        /* Smooth scrolling for anchor links */
        html { scroll-behavior: smooth; }
    </style>
</head>
<body class="font-sans antialiased bg-white dark:bg-slate-950 text-slate-900 dark:text-slate-100 overflow-x-hidden">

    {{-- ===== NAVBAR ===== --}}
    <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
         x-data="{ scrolled: false }"
         @scroll.window="scrolled = window.scrollY > 20"
         :class="scrolled ? 'bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl shadow-sm border-b border-slate-200/50 dark:border-slate-700/50' : 'bg-transparent'">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            <div class="flex items-center justify-between h-16 sm:h-20">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-2.5 group">
                    <svg class="h-9 w-9 transition-transform duration-300 group-hover:scale-105" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="40" height="40" rx="10" fill="url(#nav-logo-gradient)"/>
                        <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                        <defs>
                            <linearGradient id="nav-logo-gradient" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#e11d48"/>
                                <stop offset="1" stop-color="#ec4899"/>
                            </linearGradient>
                        </defs>
                    </svg>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">TaskFlow</span>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden md:flex items-center gap-8">
                    <a href="#features" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors duration-200">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors duration-200">How It Works</a>
                </div>

                {{-- Right side --}}
                <div class="hidden md:flex items-center gap-3">
                    {{-- Dark mode toggle --}}
                    <button @click="darkMode = !darkMode"
                            class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200"
                            aria-label="Toggle dark mode">
                        <svg x-show="!darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                        </svg>
                    </button>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-rose-600 to-pink-600 text-white shadow-sm hover:shadow-md hover:brightness-110 transition-all duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-rose-600 to-pink-600 text-white shadow-sm hover:shadow-md hover:brightness-110 transition-all duration-200">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>

                {{-- Mobile menu button --}}
                <div class="flex items-center gap-2 md:hidden">
                    <button @click="darkMode = !darkMode"
                            class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200"
                            aria-label="Toggle dark mode">
                        <svg x-show="!darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                        </svg>
                        <svg x-show="darkMode" x-cloak class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                        </svg>
                    </button>
                    <button @click="mobileMenu = !mobileMenu"
                            class="p-2 rounded-lg text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors duration-200"
                            aria-label="Toggle menu">
                        <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        <svg x-show="mobileMenu" x-cloak class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="mobileMenu" x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-2"
                 class="md:hidden pb-4 border-t border-slate-200/50 dark:border-slate-700/50">
                <div class="flex flex-col gap-1 pt-3">
                    <a href="#features" @click="mobileMenu = false" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Features</a>
                    <a href="#how-it-works" @click="mobileMenu = false" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">How It Works</a>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="mt-2 inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-rose-600 to-pink-600 text-white">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="px-3 py-2 rounded-lg text-sm font-medium text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="mt-1 inline-flex items-center justify-center px-5 py-2.5 text-sm font-semibold rounded-lg bg-gradient-to-r from-rose-600 to-pink-600 text-white">Get Started</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- ===== HERO SECTION ===== --}}
    <section class="relative min-h-screen flex items-center hero-gradient overflow-hidden pt-20">
        {{-- Decorative blobs --}}
        <div class="absolute top-20 left-10 w-72 h-72 bg-rose-300/20 dark:bg-rose-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-pink-300/20 dark:bg-pink-500/10 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-rose-200/10 dark:bg-rose-800/10 rounded-full blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-20 w-full">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Left: Copy --}}
                <div class="text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-rose-100/80 dark:bg-rose-500/10 border border-rose-200/60 dark:border-rose-500/20 mb-6 text-xs font-semibold text-rose-700 dark:text-rose-400 animate-fade-in">
                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                        Now in public beta
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] mb-6 animate-fade-in">
                        Manage Tasks.<br>
                        Empower Teams.<br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-pink-600">Ship Faster.</span>
                    </h1>

                    <p class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0 animate-fade-in" style="animation-delay: 100ms;">
                        The intuitive task management platform that helps your team stay organized, collaborate seamlessly, and deliver projects on time — every time.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center gap-4 justify-center lg:justify-start animate-fade-in" style="animation-delay: 200ms;">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                               class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold rounded-xl bg-gradient-to-r from-rose-600 to-pink-600 text-white shadow-lg shadow-rose-500/25 hover:shadow-xl hover:shadow-rose-500/30 hover:brightness-110 transition-all duration-300 active:scale-[0.98]">
                                Get Started Free
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                </svg>
                            </a>
                        @endif
                        <a href="#features"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 text-base font-semibold rounded-xl border-2 border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:border-rose-300 dark:hover:border-rose-600 hover:text-rose-600 dark:hover:text-rose-400 transition-all duration-300">
                            Learn More
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Right: Dashboard Mockup --}}
                <div class="relative animate-fade-in" style="animation-delay: 300ms;">
                    {{-- Main mockup container --}}
                    <div class="relative">
                        {{-- Floating card 1 - Stats --}}
                        <div class="glass-card rounded-2xl p-5 shadow-xl animate-float max-w-[280px] sm:max-w-xs mx-auto lg:ml-auto">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-200">Project Overview</h3>
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            </div>
                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-white/60 dark:bg-slate-700/40 rounded-xl p-3">
                                    <p class="text-2xl font-bold text-slate-900 dark:text-white">24</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Active Tasks</p>
                                </div>
                                <div class="bg-white/60 dark:bg-slate-700/40 rounded-xl p-3">
                                    <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">89%</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">Completed</p>
                                </div>
                            </div>
                            {{-- Fake task items --}}
                            <div class="space-y-2.5">
                                <div class="flex items-center gap-3 bg-white/50 dark:bg-slate-700/30 rounded-lg p-2.5">
                                    <span class="w-4 h-4 rounded border-2 border-emerald-500 bg-emerald-500 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-2.5 h-2.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    </span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400 line-through">Design landing page</span>
                                    <span class="ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-emerald-100 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400">Done</span>
                                </div>
                                <div class="flex items-center gap-3 bg-white/50 dark:bg-slate-700/30 rounded-lg p-2.5">
                                    <span class="w-4 h-4 rounded border-2 border-sky-500 bg-sky-500/10 flex-shrink-0"></span>
                                    <span class="text-xs text-slate-700 dark:text-slate-300">Build API endpoints</span>
                                    <span class="ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-sky-100 dark:bg-sky-500/10 text-sky-700 dark:text-sky-400">In Progress</span>
                                </div>
                                <div class="flex items-center gap-3 bg-white/50 dark:bg-slate-700/30 rounded-lg p-2.5">
                                    <span class="w-4 h-4 rounded border-2 border-amber-400 bg-amber-400/10 flex-shrink-0"></span>
                                    <span class="text-xs text-slate-700 dark:text-slate-300">Write documentation</span>
                                    <span class="ml-auto px-2 py-0.5 text-[10px] font-semibold rounded-full bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400">Pending</span>
                                </div>
                            </div>
                        </div>

                        {{-- Floating card 2 - Team avatars --}}
                        <div class="glass-card rounded-2xl p-4 shadow-lg animate-float-delayed absolute -bottom-6 -left-4 sm:left-0 max-w-[180px]">
                            <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 mb-2">Team Members</p>
                            <div class="flex -space-x-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-pink-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-bold text-white">JD</div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-sky-400 to-blue-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-bold text-white">AK</div>
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-orange-500 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-bold text-white">MR</div>
                                <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-slate-600 border-2 border-white dark:border-slate-800 flex items-center justify-center text-[10px] font-medium text-slate-600 dark:text-slate-300">+5</div>
                            </div>
                        </div>

                        {{-- Floating card 3 - Notification --}}
                        <div class="glass-card rounded-2xl p-3.5 shadow-lg animate-float-slow absolute -top-4 -right-2 sm:right-0 max-w-[200px]">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[11px] font-semibold text-slate-700 dark:text-slate-200">Task Completed</p>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400">Just now</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== FEATURES SECTION ===== --}}
    <section id="features" class="relative py-20 sm:py-28 bg-slate-50/50 dark:bg-slate-900/50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            {{-- Section Header --}}
            <div class="text-center mb-14 sm:mb-20 reveal"
                 x-data
                 x-init="new IntersectionObserver((entries, obs) => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } }); }, { threshold: 0.25 }).observe($el)">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100/80 dark:bg-rose-500/10 border border-rose-200/60 dark:border-rose-500/20 text-xs font-semibold text-rose-700 dark:text-rose-400 mb-4">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.455 2.456L21.75 6l-1.036.259a3.375 3.375 0 00-2.455 2.456z"/></svg>
                    Features
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                    Everything you need to <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-pink-600">ship faster</span>
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Powerful features designed to streamline your workflow, enhance team collaboration, and deliver results that matter.
                </p>
            </div>

            {{-- Feature Cards --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 reveal-stagger"
                 x-data
                 x-init="new IntersectionObserver((entries, obs) => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } }); }, { threshold: 0.15 }).observe($el)">
                {{-- Feature 1 --}}
                <div class="group bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/50 rounded-2xl p-7 sm:p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-rose-200 dark:hover:border-rose-500/30">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center mb-5 shadow-lg shadow-rose-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Smart Task Management</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Create, assign, and categorize tasks with ease. Set priorities, due dates, and track progress from a single, intuitive dashboard.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="group bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/50 rounded-2xl p-7 sm:p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-sky-200 dark:hover:border-sky-500/30">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-sky-500 to-blue-600 flex items-center justify-center mb-5 shadow-lg shadow-sky-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Team Collaboration</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Invite team members, assign roles and permissions, and stay connected with real-time notifications that keep everyone in sync.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="group bg-white dark:bg-slate-800/80 border border-slate-200/80 dark:border-slate-700/50 rounded-2xl p-7 sm:p-8 transition-all duration-300 hover:shadow-xl hover:-translate-y-1 hover:border-amber-200 dark:hover:border-amber-500/30 sm:col-span-2 lg:col-span-1">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center mb-5 shadow-lg shadow-amber-500/20 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Activity Insights</h3>
                    <p class="text-slate-600 dark:text-slate-400 leading-relaxed">
                        Track every action with detailed activity logs. Review history, monitor progress, and ensure accountability across your entire team.
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section id="how-it-works" class="relative py-20 sm:py-28 bg-white dark:bg-slate-950">
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            {{-- Section Header --}}
            <div class="text-center mb-14 sm:mb-20 reveal"
                 x-data
                 x-init="new IntersectionObserver((entries, obs) => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } }); }, { threshold: 0.25 }).observe($el)">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-rose-100/80 dark:bg-rose-500/10 border border-rose-200/60 dark:border-rose-500/20 text-xs font-semibold text-rose-700 dark:text-rose-400 mb-4">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z"/></svg>
                    How It Works
                </span>
                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight mb-4">
                    Up and running in <span class="bg-clip-text text-transparent bg-gradient-to-r from-rose-600 to-pink-600">four simple steps</span>
                </h2>
                <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl mx-auto">
                    Getting started with TaskFlow is simple. No complex setup, no learning curve — just results.
                </p>
            </div>

            {{-- Steps Wrapper --}}
            <div class="relative">
                {{-- Connector line (desktop) --}}
                <div class="hidden lg:block absolute top-16 left-[12.5%] right-[12.5%] h-px bg-gradient-to-r from-rose-200 via-pink-300 to-rose-200 dark:from-rose-800/40 dark:via-pink-700/40 dark:to-rose-800/40"></div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 relative reveal-stagger"
                     x-data
                     x-init="new IntersectionObserver((entries, obs) => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } }); }, { threshold: 0.15 }).observe($el)">
                    
                    {{-- Step 1 --}}
                    <div class="text-center relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-rose-500/20 relative z-10">
                            <span class="text-2xl font-extrabold text-white">1</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Create Workspace</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto">
                            Sign up in seconds and configure your workspace. Create a dedicated space for your team projects.
                        </p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="text-center relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-rose-500/20 relative z-10">
                            <span class="text-2xl font-extrabold text-white">2</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Invite Your Team</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto">
                            Add team members with custom roles. Set access control (Owner, Admin, Member) to secure your data.
                        </p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="text-center relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-rose-500/20 relative z-10">
                            <span class="text-2xl font-extrabold text-white">3</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Organize Categories</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto">
                            Set up custom task categories (Design, QA, Code, etc.) to keep your workspace structured and neat.
                        </p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="text-center relative">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center mx-auto mb-6 shadow-xl shadow-rose-500/20 relative z-10">
                            <span class="text-2xl font-extrabold text-white">4</span>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-3">Start Shipping</h3>
                        <p class="text-slate-600 dark:text-slate-400 leading-relaxed max-w-xs mx-auto">
                            Create tasks, assign work, set deadlines, track logs, and watch your team's productivity soar.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===== CTA SECTION ===== --}}
    <section class="relative py-20 sm:py-28 overflow-hidden">
        {{-- Background gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-rose-600 via-pink-600 to-rose-700"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wNSI+PHBhdGggZD0iTTM2IDM0djZoLTZWMzRoNnptMC0zMHY2aC02VjRoNnptMCAxMnY2aC02VjE2aDZ6bTAgMTJ2Nmgtdlwy4aDZ6bTEyLTI0djZoLTZWNGg2em0wIDEydjZoLTZWMTZoNnptMCAxMnY2aC02VjI4aDZ6bTAgMTJ2NmgtNlY0MGg2ek0yNCA0djZoLTZWNGg2em0wIDEydjZoLTZWMTZoNnptMCAxMnY2aC02VjI4aDZ6bTAgMTJ2NmgtNlY0MGg2ek0xMiA0djZINlY0aDZ6bTAgMTJ2Nkg2VjE2aDZ6bTAgMTJ2Nkg2VjI4aDZ6bTAgMTJ2Nkg2VjQwaDZ6TTAgNHY2aC02VjRoNnptMCAxMnY2aC02VjE2aDZ6bTAgMTJ2NmgtNlYyOGg2em0wIDEydjZoLTZWNDBoNnoiLz48L2c+PC9nPjwvc3ZnPg==')] opacity-30"></div>
        {{-- Decorative circles --}}
        <div class="absolute top-0 left-1/4 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-80 h-80 bg-white/5 rounded-full blur-3xl"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center relative z-10 reveal"
             x-data
             x-init="new IntersectionObserver((entries, obs) => { entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } }); }, { threshold: 0.25 }).observe($el)">
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight text-white mb-6">
                Ready to get started?
            </h2>
            <p class="text-lg sm:text-xl text-rose-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                Join thousands of teams who are already using TaskFlow to streamline their workflow and ship faster than ever.
            </p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                   class="inline-flex items-center justify-center gap-2 px-10 py-4 text-lg font-bold rounded-xl bg-white text-rose-600 shadow-xl shadow-black/10 hover:shadow-2xl hover:bg-rose-50 hover:scale-[1.02] transition-all duration-300 active:scale-[0.98]">
                    Create Free Account
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                    </svg>
                </a>
            @endif
            <p class="mt-5 text-sm text-rose-200">No credit card required · Free forever for small teams</p>
        </div>
    </section>

    {{-- ===== FOOTER ===== --}}
    <footer class="bg-slate-50 dark:bg-slate-900 border-t border-slate-200/80 dark:border-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 sm:py-16">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 sm:gap-10 mb-10 sm:mb-12">
                {{-- Brand --}}
                <div class="sm:col-span-2 lg:col-span-1">
                    <a href="/" class="flex items-center gap-2.5 mb-4">
                        <svg class="h-8 w-8" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="10" fill="url(#footer-logo-gradient)"/>
                            <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
                            <defs>
                                <linearGradient id="footer-logo-gradient" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                                    <stop stop-color="#e11d48"/>
                                    <stop offset="1" stop-color="#ec4899"/>
                                </linearGradient>
                            </defs>
                        </svg>
                        <span class="text-lg font-bold tracking-tight text-slate-900 dark:text-white">TaskFlow</span>
                    </a>
                    <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed max-w-xs">
                        The modern task management platform built for teams who want to ship faster.
                    </p>
                </div>

                {{-- Product --}}
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Product</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#features" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">Features</a></li>
                        <li><a href="#how-it-works" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">How It Works</a></li>
                    </ul>
                </div>

                {{-- Company --}}
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Company</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">About</a></li>
                        <li><a href="#" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">Terms of Service</a></li>
                    </ul>
                </div>

                {{-- Account --}}
                <div>
                    <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4">Account</h4>
                    <ul class="space-y-2.5">
                        @if (Route::has('login'))
                            <li><a href="{{ route('login') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">Log in</a></li>
                        @endif
                        @if (Route::has('register'))
                            <li><a href="{{ route('register') }}" class="text-sm text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors">Register</a></li>
                        @endif
                    </ul>
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="pt-8 border-t border-slate-200/80 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    &copy; {{ date('Y') }} TaskFlow. All rights reserved.
                </p>
                <div class="flex items-center gap-4">
                    <a href="#" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" aria-label="GitHub">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="#" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" aria-label="Twitter">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
