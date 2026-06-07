@props(['icon' => null, 'value' => 0, 'label' => '', 'trend' => null, 'trendLabel' => '', 'color' => 'primary'])

@php
$colorMap = [
    'primary' => ['bg' => 'bg-rose-50 dark:bg-rose-500/10', 'icon' => 'text-rose-600 dark:text-rose-400', 'trend' => 'text-rose-600'],
    'blue' => ['bg' => 'bg-sky-50 dark:bg-sky-500/10', 'icon' => 'text-sky-600 dark:text-sky-400', 'trend' => 'text-sky-600'],
    'amber' => ['bg' => 'bg-amber-50 dark:bg-amber-500/10', 'icon' => 'text-amber-600 dark:text-amber-400', 'trend' => 'text-amber-600'],
    'emerald' => ['bg' => 'bg-emerald-50 dark:bg-emerald-500/10', 'icon' => 'text-emerald-600 dark:text-emerald-400', 'trend' => 'text-emerald-600'],
];
$c = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'card p-5 sm:p-6']) }}>
    <div class="flex items-start justify-between">
        <div class="space-y-3">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">{{ $label }}</p>
            <p class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-slate-50 tracking-tight">{{ $value }}</p>
            @if($trend !== null)
                <div class="flex items-center gap-1.5">
                    @if($trend > 0)
                        <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">+{{ $trend }}</span>
                    @elseif($trend < 0)
                        <svg class="w-4 h-4 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                        <span class="text-xs font-medium text-red-600 dark:text-red-400">{{ $trend }}</span>
                    @endif
                    @if($trendLabel)
                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $trendLabel }}</span>
                    @endif
                </div>
            @endif
        </div>
        @if($icon)
            <div class="shrink-0 p-3 rounded-xl {{ $c['bg'] }}">
                <span class="w-6 h-6 block {{ $c['icon'] }}">
                    {!! $icon !!}
                </span>
            </div>
        @endif
    </div>
</div>
