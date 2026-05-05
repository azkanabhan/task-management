@props(['tasks'])

<div class="space-y-4">
    @forelse ($tasks as $task)
        <div class="p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $task->title }}</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">{{ $task->description ?: 'No description' }}</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Category: {{ $task->category?->name ?? 'No Category' }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Deadline: {{ $task->deadline ?? 'No Deadline' }}
                    </p>
                    <div class="mt-3 flex items-center gap-3">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                            Edit
                        </a>

                        <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:underline">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200">
                    {{ str_replace('_', ' ', ucfirst($task->status)) }}
                </span>
            </div>
        </div>
    @empty
        <div class="p-4 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
            <p class="text-sm text-gray-600 dark:text-gray-300">No tasks yet. Create your first task.</p>
        </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $tasks->links() }}
</div>
