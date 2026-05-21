<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $canEditFull ? __('Edit Task') : __('Update Task Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                @if (! $canEditFull)
                    <p class="mb-6 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('You can only update the status of tasks assigned to you by someone else.') }}
                    </p>
                @endif

                <form method="POST" action="{{ route('tasks.update', $task->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @if ($canEditFull)
                        <div>
                            <x-input-label for="title" :value="__('Title')" />
                            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title', $task->title)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                        </div>

                        <div>
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                            >{{ old('description', $task->description) }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                        </div>
                    @else
                        <div class="rounded-md border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Title') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->title }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Description') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->description ?: __('No description') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Created by') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->creator?->name ?? __('Unknown') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Category') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->category?->name ?? __('No Category') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Deadline') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->deadline ?? __('No Deadline') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Team') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->team?->name ?? __('No Team') }}</p>
                            </div>
                            <div>
                                <p class="text-xs font-medium text-gray-500 dark:text-gray-400">{{ __('Assigned to') }}</p>
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $task->assignee?->name ?? __('Unassigned') }}</p>
                            </div>
                        </div>
                    @endif

                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select
                            id="status"
                            name="status"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                            required
                            @if (! $canEditFull) autofocus @endif
                        >
                            <option value="pending" @selected(old('status', $task->status) === 'pending')>Pending</option>
                            <option value="in_progress" @selected(old('status', $task->status) === 'in_progress')>In Progress</option>
                            <option value="done" @selected(old('status', $task->status) === 'done')>Done</option>
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>

                    @if ($canEditFull)
                        <div>
                            <x-input-label for="deadline" :value="__('Deadline')" />
                            <x-text-input id="deadline" name="deadline" type="date" class="mt-1 block w-full" :value="old('deadline', $task->deadline)" />
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select
                                id="category_id"
                                name="category_id"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">No Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected((string) old('category_id', $task->category_id) === (string) $category->id)>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        @include('tasks.partials.team-assign-fields', [
                            'teams' => $teams,
                            'currentUserId' => $currentUserId,
                            'selectedTeamId' => $task->team_id,
                            'selectedAssignTo' => $task->assign_to,
                        ])
                    @endif

                    <div class="flex items-center gap-3">
                        <x-primary-button>
                            {{ $canEditFull ? __('Update Task') : __('Update Status') }}
                        </x-primary-button>
                        <a href="{{ route('tasks.index') }}" class="text-sm text-gray-600 dark:text-gray-300 hover:underline">
                            {{ __('Back to Tasks') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
