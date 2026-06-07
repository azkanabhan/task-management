<x-guest-layout>
    <div class="animate-fade-in text-center py-4">
        <!-- Animated Success Checkmark Icon -->
        <div class="relative flex justify-center items-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-tr from-rose-500 to-pink-500 rounded-full flex items-center justify-center shadow-lg shadow-rose-200 dark:shadow-rose-900/30 animate-scale-in">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <!-- Glow effect -->
            <div class="absolute w-28 h-28 bg-rose-400/20 dark:bg-rose-500/10 rounded-full filter blur-xl -z-10 animate-ping"></div>
            <!-- Outer ring -->
            <div class="absolute w-24 h-24 rounded-full border-2 border-rose-200 dark:border-rose-800/50 animate-pulse"></div>
        </div>

        <!-- Heading -->
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">
            {{ __('Email Verified!') }}
        </h1>

        <!-- Description -->
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-8 leading-relaxed max-w-sm mx-auto">
            {{ __('Your email address has been successfully verified. Your account is now fully active and you can access all features.') }}
        </p>

        <!-- Redirect Countdown -->
        <div x-data="{ countdown: 3, progress: 100 }"
             x-init="
                let timer = setInterval(() => {
                    countdown--;
                    progress = (countdown / 3) * 100;
                    if (countdown <= 0) {
                        clearInterval(timer);
                        window.location.href = '{{ route('dashboard') }}';
                    }
                }, 1000);
             "
             class="mb-8 p-4 bg-slate-50 dark:bg-slate-800/50 rounded-xl border border-slate-200 dark:border-slate-700/50">
            <div class="flex items-center justify-center space-x-2 text-sm text-slate-500 dark:text-slate-400 mb-3">
                <svg class="w-4 h-4 text-rose-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>
                    {{ __('Redirecting to dashboard in') }}
                    <span x-text="countdown" class="font-semibold text-rose-600 dark:text-rose-400"></span>
                    {{ __('seconds...') }}
                </span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-slate-200 dark:bg-slate-700 h-1.5 rounded-full overflow-hidden">
                <div class="bg-gradient-to-r from-rose-600 to-pink-500 h-full rounded-full transition-all duration-1000 ease-linear"
                     :style="'width: ' + progress + '%'"></div>
            </div>
        </div>

        <!-- Action Button -->
        <a href="{{ route('dashboard') }}" class="btn-primary w-full inline-flex justify-center items-center px-6 py-2.5 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-rose-600 to-pink-600 hover:from-rose-500 hover:to-pink-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 dark:focus:ring-offset-slate-900 shadow-md shadow-rose-100 dark:shadow-none transition duration-150 ease-in-out transform hover:-translate-y-0.5">
            {{ __('Go to Dashboard') }}
            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
            </svg>
        </a>
    </div>
</x-guest-layout>
