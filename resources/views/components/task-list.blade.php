@props(['tasks', 'type' => 'created', 'emptyTitle' => 'No tasks yet', 'emptyDescription' => 'Create your first task to get started.'])

@if($tasks->count() > 0)
    <div class="space-y-3 stagger-children">
        @foreach($tasks as $task)
            <div class="card-hover p-4 sm:p-5 group">
                <div class="flex items-start justify-between gap-4">
                    {{-- Left: Task Info --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2.5 flex-wrap mb-2">
                            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 truncate">
                                {{ $task->title }}
                            </h3>
                            @php
                                $statusVariant = match($task->status) {
                                    'pending' => 'pending',
                                    'in_progress' => 'progress',
                                    'done' => 'done',
                                    default => 'default'
                                };
                            @endphp
                            <x-badge :variant="$statusVariant">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </x-badge>
                        </div>

                        @if($task->description)
                            <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-3">{{ $task->description }}</p>
                        @endif

                        <div class="flex items-center gap-4 flex-wrap text-xs text-slate-400 dark:text-slate-500">
                            @if($task->category)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    {{ $task->category->name }}
                                </span>
                            @endif

                            @if($task->deadline)
                                @php
                                    $deadline = \Carbon\Carbon::parse($task->deadline);
                                    $isOverdue = $deadline->isPast() && $task->status !== 'done';
                                    $isToday = $deadline->isToday();
                                    $isTomorrow = $deadline->isTomorrow();
                                    $deadlineColor = $isOverdue ? 'text-red-500' : ($isToday ? 'text-amber-500' : ($isTomorrow ? 'text-amber-400' : 'text-slate-400 dark:text-slate-500'));
                                @endphp
                                <span class="inline-flex items-center gap-1 {{ $deadlineColor }}">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    @if($isOverdue) Overdue
                                    @elseif($isToday) Today
                                    @elseif($isTomorrow) Tomorrow
                                    @else {{ $deadline->format('M d, Y') }}
                                    @endif
                                </span>
                            @endif

                            @if($task->team)
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    {{ $task->team->name }}
                                </span>
                            @endif

                            @if($type === 'created' && $task->assignee)
                                <span class="inline-flex items-center gap-1.5">
                                    <x-avatar :name="$task->assignee->name" size="xs" />
                                    {{ $task->assignee->name }}
                                </span>
                            @elseif($type === 'assigned' && $task->creator)
                                <span class="inline-flex items-center gap-1.5">
                                    <span class="text-slate-400">by</span>
                                    <x-avatar :name="$task->creator->name" size="xs" />
                                    {{ $task->creator->name }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Right: Actions --}}
                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity duration-200 shrink-0">
                        @can('update', $task)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition-all duration-150" title="Edit">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                        @endcan
                        @can('delete', $task)
                            <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all duration-150" title="Delete">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        @endcan
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $tasks->links() }}
    </div>
@else
    <x-empty-state
        :title="$emptyTitle"
        :description="$emptyDescription"
        actionText="Create Task"
        actionUrl="{{ route('tasks.create') }}"
    >
        <x-slot name="icon">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
        </x-slot>
    </x-empty-state>
@endif
