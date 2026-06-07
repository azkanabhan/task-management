@props(['active' => false])

@php
$classes = $active
    ? 'px-4 py-2.5 text-sm font-semibold rounded-lg bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-700'
    : 'px-4 py-2.5 text-sm font-medium rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-800/50 transition-all duration-200';
@endphp

<button {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
