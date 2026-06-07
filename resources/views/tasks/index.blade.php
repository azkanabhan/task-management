<x-app-layout>
    <div class="page-container animate-fade-in">
        {{-- Page Header --}}
        <x-page-header title="Tasks" description="Manage and organize all your tasks">
            <x-slot name="actions">
                <a href="{{ route('tasks.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    {{ __('New Task') }}
                </a>
            </x-slot>
        </x-page-header>

        {{-- Filter Bar --}}
        <div class="card p-4 sm:p-5 mb-6">
            <form id="task-filter-form" method="GET" action="{{ route('tasks.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-3 sm:gap-4">
                {{-- Search --}}
                <div class="flex-1 min-w-0">
                    <label for="search" class="input-label">{{ __('Search') }}</label>
                    <div class="relative mt-1">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-4 w-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input
                            id="search"
                            name="search"
                            type="text"
                            value="{{ $search }}"
                            placeholder="{{ __('Search tasks...') }}"
                            autocomplete="off"
                            class="input-field pl-9"
                        >
                    </div>
                </div>

                {{-- Category Filter --}}
                <div class="sm:w-44">
                    <x-filter-dropdown name="category_id" label="Category">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-filter-dropdown>
                </div>

                {{-- Status Filter --}}
                <div class="sm:w-40">
                    <x-filter-dropdown name="status" label="Status">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="pending" @selected($status === 'pending')>{{ __('Pending') }}</option>
                        <option value="in_progress" @selected($status === 'in_progress')>{{ __('In Progress') }}</option>
                        <option value="done" @selected($status === 'done')>{{ __('Done') }}</option>
                    </x-filter-dropdown>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 sm:pb-0">
                    <button type="submit" class="btn-primary text-sm">
                        {{ __('Filter') }}
                    </button>
                    <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors whitespace-nowrap">
                        {{ __('Clear') }}
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabbed Task Lists --}}
        <div x-data="{ tab: 'created' }">
            {{-- Tab Buttons --}}
            <div class="flex items-center gap-1 p-1 bg-slate-100 dark:bg-slate-800/50 rounded-xl w-fit mb-6">
                <x-tab-button
                    @click="tab = 'created'"
                    ::class="tab === 'created' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800/50'"
                    x-bind:active="tab === 'created'"
                >
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        {{ __('Created by Me') }}
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                            {{ $createdTasks->total() }}
                        </span>
                    </span>
                </x-tab-button>

                <x-tab-button
                    @click="tab = 'assigned'"
                    ::class="tab === 'assigned' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800/50'"
                    x-bind:active="tab === 'assigned'"
                >
                    <span class="inline-flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ __('Assigned to Me') }}
                        <span class="inline-flex items-center justify-center px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300">
                            {{ $assignedTasks->total() }}
                        </span>
                    </span>
                </x-tab-button>
            </div>

            {{-- Created Tasks Tab --}}
            <div x-show="tab === 'created'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <x-task-list
                    :tasks="$createdTasks"
                    type="created"
                    :empty-title="__('No tasks created yet')"
                    :empty-description="__('Create your first task to get started.')"
                />
            </div>

            {{-- Assigned Tasks Tab --}}
            <div x-show="tab === 'assigned'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <x-task-list
                    :tasks="$assignedTasks"
                    type="assigned"
                    :empty-title="__('No tasks assigned to you')"
                    :empty-description="__('Tasks assigned to you by others will appear here.')"
                />
            </div>
        </div>
    </div>

    {{-- Auto-submit filter logic --}}
    <script>
        (() => {
            const form = document.getElementById('task-filter-form');
            const searchInput = document.getElementById('search');
            const filterInputs = form?.querySelectorAll('select');
            let debounceTimer;

            if (!form || !searchInput) {
                return;
            }

            searchInput.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => form.requestSubmit(), 350);
            });

            filterInputs?.forEach((input) => {
                input.addEventListener('change', () => form.requestSubmit());
            });
        })();
    </script>
</x-app-layout>
