@props(['type' => 'success', 'dismissible' => true])

@php
$styles = [
    'success' => 'bg-white/95 dark:bg-slate-900/95 border-emerald-500/30 text-slate-800 dark:text-slate-200 shadow-emerald-500/10 dark:shadow-emerald-500/5',
    'error' => 'bg-white/95 dark:bg-slate-900/95 border-rose-500/30 text-slate-800 dark:text-slate-200 shadow-rose-500/10 dark:shadow-rose-500/5',
    'warning' => 'bg-white/95 dark:bg-slate-900/95 border-amber-500/30 text-slate-800 dark:text-slate-200 shadow-amber-500/10 dark:shadow-amber-500/5',
    'info' => 'bg-white/95 dark:bg-slate-900/95 border-rose-500/30 text-slate-800 dark:text-slate-200 shadow-rose-500/10 dark:shadow-rose-500/5',
];
$badgeColors = [
    'success' => 'bg-emerald-500 text-white',
    'error' => 'bg-rose-500 text-white',
    'warning' => 'bg-amber-500 text-white',
    'info' => 'bg-rose-500 text-white',
];
$icons = [
    'success' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>',
    'error' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>',
    'warning' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
    'info' => '<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 111.063.852l-.708 2.836a.75.75 0 001.063.852l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z"/></svg>',
];
@endphp

<div x-data="{ show: true }"
     x-show="show"
     x-init="setTimeout(() => show = false, 5000)"
     x-transition:enter="transition ease-out duration-300 transform"
     x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
     x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
     x-transition:leave="transition ease-in duration-200 transform"
     x-transition:leave-start="opacity-100 translate-x-0"
     x-transition:leave-end="opacity-0 translate-x-4"
     {{ $attributes->merge(['class' => 'flex items-center gap-3.5 p-4 rounded-2xl border backdrop-blur-md shadow-lg max-w-sm w-full transition-all duration-300 ' . ($styles[$type] ?? $styles['info'])]) }}>
    
    {{-- Left Icon Badge --}}
    <div class="flex items-center justify-center w-8 h-8 rounded-xl shrink-0 {{ $badgeColors[$type] ?? $badgeColors['info'] }}">
        {!! $icons[$type] ?? $icons['info'] !!}
    </div>
    
    {{-- Message Slot --}}
    <div class="flex-1 text-sm font-semibold leading-snug">{{ $slot }}</div>
    
    {{-- Dismiss button --}}
    @if($dismissible)
        <button @click="show = false" class="shrink-0 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    @endif
</div>
