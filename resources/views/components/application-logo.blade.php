@props(['size' => 'default'])

@php
$sizeClasses = match($size) {
    'sm' => 'h-6 w-auto',
    'lg' => 'h-12 w-auto',
    default => 'h-8 w-auto',
};
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2 ' . $sizeClasses]) }}>
    <svg class="h-full w-auto" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <rect width="40" height="40" rx="10" fill="url(#logo-gradient)"/>
        <path d="M12 14.5L18 20.5L28 10.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M12 21.5L18 27.5L28 17.5" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" opacity="0.5"/>
        <defs>
            <linearGradient id="logo-gradient" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                <stop stop-color="#e11d48"/>
                <stop offset="1" stop-color="#ec4899"/>
            </linearGradient>
        </defs>
    </svg>
    <span class="font-bold text-lg tracking-tight text-slate-900 dark:text-white">TaskFlow</span>
</div>
