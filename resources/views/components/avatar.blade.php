@props(['name' => '', 'size' => 'md'])

@php
$colors = [
    'A' => 'bg-rose-100 text-rose-700 dark:bg-rose-500/20 dark:text-rose-400',
    'B' => 'bg-sky-100 text-sky-700 dark:bg-sky-500/20 dark:text-sky-400',
    'C' => 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400',
    'D' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400',
    'E' => 'bg-purple-100 text-purple-700 dark:bg-purple-500/20 dark:text-purple-400',
    'F' => 'bg-pink-100 text-pink-700 dark:bg-pink-500/20 dark:text-pink-400',
    'G' => 'bg-indigo-100 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-400',
    'H' => 'bg-teal-100 text-teal-700 dark:bg-teal-500/20 dark:text-teal-400',
    'I' => 'bg-orange-100 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400',
    'J' => 'bg-cyan-100 text-cyan-700 dark:bg-cyan-500/20 dark:text-cyan-400',
];

$initial = strtoupper(substr($name, 0, 1));
$colorKey = $initial ?: 'A';
$color = $colors[$colorKey] ?? $colors[array_keys($colors)[ord($colorKey) % count($colors)]];

$sizes = [
    'xs' => 'w-6 h-6 text-[10px]',
    'sm' => 'w-8 h-8 text-xs',
    'md' => 'w-10 h-10 text-sm',
    'lg' => 'w-12 h-12 text-base',
    'xl' => 'w-16 h-16 text-lg',
];
$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div {{ $attributes->merge(['class' => "inline-flex items-center justify-center rounded-full font-bold $sizeClass $color"]) }}
     title="{{ $name }}">
    {{ $initial }}
</div>
