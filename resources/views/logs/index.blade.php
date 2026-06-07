<x-app-layout>
    <div class="page-container animate-fade-in">
        <x-page-header title="Activity Log" description="Track all actions and changes across your workspace" />

        @if ($logs->isEmpty())
            <x-empty-state
                title="No activity yet"
                description="Your activity log is empty. Actions like creating tasks, updating statuses, and team changes will be recorded here."
            >
                <x-slot name="icon">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </x-slot>
            </x-empty-state>
        @else
            {{-- Timeline --}}
            <div class="max-w-3xl">
                <div class="relative pl-8 sm:pl-10 stagger-children">
                    {{-- Timeline Line --}}
                    <div class="absolute left-3 sm:left-4 top-2 bottom-2 w-px bg-slate-200 dark:bg-slate-700"></div>

                    @foreach ($logs as $log)
                        <div class="relative pb-8 last:pb-0 group">
                            {{-- Timeline Dot --}}
                            <div class="absolute -left-5 sm:-left-6 top-1 flex items-center justify-center">
                                <div class="w-2.5 h-2.5 rounded-full bg-rose-500 dark:bg-rose-400 ring-4 ring-white dark:ring-slate-900 group-hover:scale-125 transition-transform"></div>
                            </div>

                            {{-- Log Entry Card --}}
                            <div class="card p-4 sm:p-5 hover:shadow-card-hover transition-shadow duration-200">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-slate-900 dark:text-slate-100">
                                            {{ $log->action }}
                                        </p>
                                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ $log->description }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        <time>{{ $log->created_at?->format('d M Y, H:i:s') }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $logs->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
