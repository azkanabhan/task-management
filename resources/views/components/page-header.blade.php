@props(['title' => '', 'description' => '', 'actions' => null])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 sm:mb-8']) }}>
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">{{ $title }}</h1>
        @if($description)
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">{{ $description }}</p>
        @endif
    </div>
    @if($actions)
        <div class="flex items-center gap-3 shrink-0">
            {{ $actions }}
        </div>
    @endif
</div>
