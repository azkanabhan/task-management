<x-app-layout>
    <div class="page-container animate-fade-in">
        {{-- Greeting --}}
        <div class="mb-8">
            @php
                $hour = (int) now()->format('H');
                if ($hour < 12) {
                    $greeting = __('Good morning');
                } elseif ($hour < 17) {
                    $greeting = __('Good afternoon');
                } else {
                    $greeting = __('Good evening');
                }
            @endphp
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">
                {{ $greeting }}, {{ Auth::user()->name }} 👋
            </h1>
            <p class="mt-1.5 text-sm sm:text-base text-slate-500 dark:text-slate-400">
                {{ __("Here's your overview for today") }}
            </p>
        </div>

        {{-- Quick Actions --}}
        <div class="flex flex-wrap items-center gap-3 mb-8">
            <a href="{{ route('tasks.create') }}" class="btn-primary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                {{ __('New Task') }}
            </a>
            <a href="{{ route('teams.create') }}" class="btn-secondary inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                {{ __('New Team') }}
            </a>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8 stagger-children">
            <x-stat-card :value="Auth::user()->createdTasks()->where('status', 'pending')->count()" label="Pending Tasks" color="amber">
                <x-slot name="icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card :value="Auth::user()->createdTasks()->where('status', 'in_progress')->count()" label="In Progress" color="blue">
                <x-slot name="icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card :value="Auth::user()->createdTasks()->where('status', 'done')->count()" label="Completed" color="emerald">
                <x-slot name="icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </x-slot>
            </x-stat-card>

            <x-stat-card :value="Auth::user()->teams()->count()" label="Teams" color="primary">
                <x-slot name="icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </x-slot>
            </x-stat-card>
        </div>

        {{-- Get Started / Recent Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 sm:gap-6">
            {{-- Quick Navigation --}}
            <div class="card p-6 animate-slide-up">
                <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100 mb-4">{{ __('Quick Navigation') }}</h2>
                <div class="space-y-3">
                    <a href="{{ route('tasks.index') }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="shrink-0 p-2.5 rounded-xl bg-rose-50 dark:bg-rose-500/10">
                            <svg class="w-5 h-5 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">{{ __('View All Tasks') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Manage and track your tasks') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-rose-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('teams.index') }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="shrink-0 p-2.5 rounded-xl bg-sky-50 dark:bg-sky-500/10">
                            <svg class="w-5 h-5 text-sky-600 dark:text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-sky-600 dark:group-hover:text-sky-400 transition-colors">{{ __('My Teams') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Collaborate with your teams') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-sky-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('categories.create') }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="shrink-0 p-2.5 rounded-xl bg-amber-50 dark:bg-amber-500/10">
                            <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-amber-600 dark:group-hover:text-amber-400 transition-colors">{{ __('Create Category') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Organize tasks by category') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-amber-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>

                    <a href="{{ route('notifications.index') }}" class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                        <div class="shrink-0 p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-500/10">
                            <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-slate-900 dark:text-slate-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ __('Notifications') }}</p>
                            <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Stay updated on changes') }}</p>
                        </div>
                        <svg class="w-4 h-4 text-slate-300 dark:text-slate-600 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>

            {{-- Recent Tasks --}}
            <div class="card p-6 animate-slide-up">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-slate-900 dark:text-slate-100">{{ __('Recent Tasks') }}</h2>
                    <a href="{{ route('tasks.index') }}" class="text-xs font-medium text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition-colors">
                        {{ __('View all') }} →
                    </a>
                </div>

                @php
                    $recentTasks = Auth::user()->createdTasks()->latest()->take(5)->get();
                @endphp

                @if($recentTasks->count() > 0)
                    <div class="space-y-2.5">
                        @foreach($recentTasks as $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="flex items-center gap-3 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group">
                                <div class="shrink-0">
                                    @if($task->status === 'done')
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                    @elseif($task->status === 'in_progress')
                                        <div class="w-8 h-8 rounded-lg bg-sky-50 dark:bg-sky-500/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-slate-900 dark:text-slate-100 truncate group-hover:text-rose-600 dark:group-hover:text-rose-400 transition-colors">{{ $task->title }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $task->created_at->diffForHumans() }}</p>
                                </div>
                                @php
                                    $statusVariant = match($task->status) {
                                        'pending' => 'pending',
                                        'in_progress' => 'progress',
                                        'done' => 'done',
                                        default => 'default'
                                    };
                                @endphp
                                <x-badge :variant="$statusVariant" size="xs">
                                    {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                </x-badge>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-12 h-12 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ __('No tasks yet') }}</p>
                        <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">{{ __('Create your first task to get started!') }}</p>
                        <a href="{{ route('tasks.create') }}" class="btn-primary mt-4 text-xs">
                            {{ __('Create Task') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
