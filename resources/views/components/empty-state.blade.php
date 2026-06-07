@props(['icon' => null, 'title' => '', 'description' => '', 'action' => null, 'actionUrl' => '#', 'actionText' => ''])

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center py-12 px-6 text-center']) }}>
    @if($icon)
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-5">
            <span class="w-8 h-8 text-slate-400 dark:text-slate-500">
                {!! $icon !!}
            </span>
        </div>
    @else
        <div class="w-16 h-16 rounded-2xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center mb-5">
            <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
        </div>
    @endif

    <h3 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-1.5">{{ $title }}</h3>
    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm">{{ $description }}</p>

    @if($actionText)
        <a href="{{ $actionUrl }}" class="btn-primary mt-5 text-sm">
            {{ $actionText }}
        </a>
    @endif

    @if($action)
        <div class="mt-5">
            {{ $action }}
        </div>
    @endif
</div>
