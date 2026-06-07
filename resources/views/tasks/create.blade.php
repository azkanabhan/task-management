<x-app-layout>
    <div class="page-container animate-fade-in">
        <x-page-header title="Create Task" description="Add a new task to your workflow">
            <x-slot name="actions">
                <a href="{{ route('tasks.index') }}" class="btn-ghost inline-flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    {{ __('Back to Tasks') }}
                </a>
            </x-slot>
        </x-page-header>

        <form method="POST" action="{{ route('tasks.store') }}" class="space-y-6 max-w-3xl">
            @csrf

            {{-- Task Details Section --}}
            <div class="card p-6">
                <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    {{ __('Task Details') }}
                </h2>

                <div class="space-y-5">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="input-label">{{ __('Title') }} <span class="text-rose-500">*</span></label>
                        <input
                            id="title"
                            name="title"
                            type="text"
                            value="{{ old('title') }}"
                            class="input-field mt-1"
                            placeholder="{{ __('Enter task title...') }}"
                            required
                            autofocus
                        >
                        <x-input-error class="mt-2" :messages="$errors->get('title')" />
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="input-label">{{ __('Description') }}</label>
                        <textarea
                            id="description"
                            name="description"
                            rows="4"
                            class="input-field mt-1"
                            placeholder="{{ __('Add a description for this task...') }}"
                        >{{ old('description') }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        {{-- Status --}}
                        <div>
                            <label for="status" class="input-label">{{ __('Status') }} <span class="text-rose-500">*</span></label>
                            <select id="status" name="status" class="input-field mt-1" required>
                                <option value="pending" @selected(old('status') === 'pending')>{{ __('Pending') }}</option>
                                <option value="in_progress" @selected(old('status') === 'in_progress')>{{ __('In Progress') }}</option>
                                <option value="done" @selected(old('status') === 'done')>{{ __('Done') }}</option>
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('status')" />
                        </div>

                        {{-- Deadline --}}
                        <div>
                            <label for="deadline" class="input-label">{{ __('Deadline') }}</label>
                            <input
                                id="deadline"
                                name="deadline"
                                type="date"
                                value="{{ old('deadline') }}"
                                class="input-field mt-1"
                            >
                            <x-input-error class="mt-2" :messages="$errors->get('deadline')" />
                        </div>
                    </div>

                    {{-- Category --}}
                    <div>
                        <label for="category_id" class="input-label">{{ __('Category') }}</label>
                        <select id="category_id" name="category_id" class="input-field mt-1">
                            <option value="">{{ __('No Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                    </div>
                </div>
            </div>

            {{-- Assignment Section --}}
            <div class="card p-6">
                <h2 class="text-base font-semibold text-slate-900 dark:text-slate-100 mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-sky-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ __('Assignment') }}
                </h2>

                @include('tasks.partials.team-assign-fields', [
                    'teams' => $teams,
                    'currentUserId' => $currentUserId,
                ])
            </div>

            {{-- Form Actions --}}
            <div class="flex items-center gap-4">
                <button type="submit" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    {{ __('Save Task') }}
                </button>
                <a href="{{ route('tasks.index') }}" class="text-sm font-medium text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                    {{ __('Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-app-layout>
