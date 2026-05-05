@props([
    'name',
    'label',
    'options' => [],
    'selected' => '',
    'placeholder' => 'All',
])

<div>
    <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        {{ $label }}
    </label>

    <select
        id="{{ $name }}"
        name="{{ $name }}"
        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @selected((string) $selected === (string) $value)>
                {{ $text }}
            </option>
        @endforeach
    </select>
</div>
