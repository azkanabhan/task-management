<x-app-layout>
    <div class="page-container animate-fade-in">
        <x-page-header title="Create Category" description="Organize your tasks with categories">
            <x-slot name="actions">
                <a href="{{ route('dashboard') }}" class="btn-ghost inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </x-slot>
        </x-page-header>

        <form method="POST" action="{{ route('categories.store') }}" class="max-w-xl">
            @csrf

            <div class="card p-6">
                <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    {{ __('Category Details') }}
                </h2>

                <div>
                    <label for="name" class="input-label">{{ __('Category Name') }} <span class="text-rose-500">*</span></label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        class="input-field mt-1"
                        placeholder="{{ __('Enter category name...') }}"
                        required
                        autofocus
                    >
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>
            </div>

            <div class="flex items-center gap-4 mt-6">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Save Category') }}
                </button>
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
