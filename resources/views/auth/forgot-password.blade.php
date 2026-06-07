<x-guest-layout>
    <div class="animate-fade-in">
        <!-- Icon -->
        <div class="mb-6 flex justify-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-rose-100 to-pink-100 dark:from-rose-900/30 dark:to-pink-900/30 flex items-center justify-center">
                <svg class="w-8 h-8 text-rose-600 dark:text-rose-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
        </div>

        <!-- Header -->
        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">
                {{ __('Forgot your password?') }}
            </h1>
            <p class="mt-2 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                {{ __("No worries! Enter the email address associated with your account, and we'll send you a link to reset your password.") }}
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email address')" />
                <div class="relative mt-1">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400 dark:text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <x-text-input id="email" class="block w-full pl-11" type="email" name="email" :value="old('email')" required autofocus placeholder="you@example.com" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit -->
            <div>
                <x-primary-button class="w-full justify-center py-2.5">
                    {{ __('Send reset link') }}
                </x-primary-button>
            </div>

            <!-- Back to Login -->
            <p class="text-center text-sm text-slate-600 dark:text-slate-400">
                {{ __('Remember your password?') }}
                <a href="{{ route('login') }}" class="font-semibold text-rose-600 hover:text-rose-500 dark:text-rose-400 dark:hover:text-rose-300 transition-colors">
                    {{ __('Back to sign in') }}
                </a>
            </p>
        </form>
    </div>
</x-guest-layout>
