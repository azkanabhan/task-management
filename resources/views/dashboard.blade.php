<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>

            <div class="flex items-center gap-3">
                <a href="{{ route('categories.create') }}">
                    <x-primary-button>
                        {{ __('Create Category') }}
                    </x-primary-button>
                </a>

                <a href="{{ route('tasks.create') }}">
                    <x-primary-button>
                        {{ __('Create Task') }}
                    </x-primary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow-sm sm:rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <p class="text-gray-700 dark:text-gray-200">
                    {{ __('Welcome back! View and manage your tasks from the Tasks page.') }}
                </p>

                <div class="mt-6">
                    <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline font-medium">
                        {{ __('Go to Tasks') }} &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
