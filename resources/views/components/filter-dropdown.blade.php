@props(['label' => '', 'name' => '', 'options' => []])

<div>
    @if($label)
        <label for="{{ $name }}" class="input-label">{{ $label }}</label>
    @endif
    <select id="{{ $name }}" name="{{ $name }}" {{ $attributes->merge(['class' => 'input-field appearance-none bg-no-repeat bg-[right_0.75rem_center] bg-[length:1rem_1rem] pe-10']) }}
        style="background-image: url(&quot;data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e&quot;);">
        {{ $slot }}
    </select>
</div>
