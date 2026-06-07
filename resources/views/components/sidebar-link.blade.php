@props(['active' => false, 'icon' => '', 'href' => '#', 'badge' => null])

@php
$classes = $active
    ? 'group flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg bg-rose-500/10 text-rose-400 border-l-[3px] border-rose-500 -ml-[3px]'
    : 'group flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-lg text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-all duration-200';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    @if($icon)
        <span class="w-5 h-5 shrink-0 {{ $active ? 'text-rose-400' : 'text-slate-500 group-hover:text-slate-300' }} transition-colors duration-200">
            {!! $icon !!}
        </span>
    @endif
    <span class="truncate">{{ $slot }}</span>
    @if($badge)
        <span class="ml-auto inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full bg-rose-500 text-white">{{ $badge }}</span>
    @endif
</a>
