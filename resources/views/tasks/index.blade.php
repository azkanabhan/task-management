<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tasks') }}
            </h2>

            <a href="{{ route('tasks.create') }}">
                <x-primary-button>
                    {{ __('Create Task') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow-sm sm:rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-8 p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <form id="task-filter-form" method="GET" action="{{ route('tasks.index') }}" class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:items-end">
                    <div class="sm:col-span-2 lg:col-span-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Search Name') }}
                        </label>
                        <input
                            id="search"
                            name="search"
                            type="text"
                            value="{{ $search }}"
                            placeholder="{{ __('Type task name...') }}"
                            autocomplete="off"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>

                    <x-filter-dropdown
                        name="category_id"
                        label="Category"
                        :options="$categories->pluck('name', 'id')->toArray()"
                        :selected="$categoryId"
                        placeholder="All Categories"
                    />

                    <x-filter-dropdown
                        name="status"
                        label="Status"
                        :options="[
                            'pending' => 'Pending',
                            'in_progress' => 'In Progress',
                            'done' => 'Done',
                        ]"
                        :selected="$status"
                        placeholder="All Statuses"
                    />

                    <div class="flex items-center gap-2">
                        <x-primary-button type="submit">
                            {{ __('Apply Filter') }}
                        </x-primary-button>
                    </div>

                    <div class="flex items-center">
                        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                            {{ __('Clear Filter') }}
                        </a>
                    </div>
                </form>
            </div>

            <section id="created-tasks" class="mb-12">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Created by Me') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Tasks you have created.') }}
                    </p>
                </div>

                <x-task-list
                    :tasks="$createdTasks"
                    scope="created"
                    :empty-message="__('You have not created any tasks yet.')"
                />
            </section>

            <section id="assigned-tasks">
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        {{ __('Assigned to Me') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Tasks assigned to you by others or yourself.') }}
                    </p>
                </div>

                <x-task-list
                    :tasks="$assignedTasks"
                    scope="assigned"
                    :empty-message="__('No tasks are assigned to you yet.')"
                />
            </section>
        </div>
    </div>

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
