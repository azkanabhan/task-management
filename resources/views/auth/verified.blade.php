<x-guest-layout>
    <div class="text-center py-6 px-2">
        <!-- Animated Success Checkmark Icon -->
        <div class="relative flex justify-center items-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-tr from-green-400 to-emerald-500 rounded-full flex items-center justify-center shadow-lg shadow-green-200 dark:shadow-none animate-bounce">
                <svg class="w-10 h-10 text-white stroke-[3] animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <!-- Glow effect -->
            <div class="absolute w-24 h-24 bg-green-400/20 dark:bg-emerald-500/10 rounded-full filter blur-xl -z-10 animate-ping"></div>
        </div>

        <!-- Heading -->
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">
            {{ __('Account Activated!') }}
        </h2>

        <!-- Description -->
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-8 leading-relaxed max-w-sm mx-auto">
            {{ __('Thank you! Your email address has been successfully verified. Your account is now fully active, and you can access all features.') }}
        </p>

        <!-- Redirect Countdown Progress Indicator -->
        <div class="mb-8 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-100 dark:border-gray-800/80">
            <div class="flex items-center justify-center space-x-2 text-sm text-gray-500 dark:text-gray-400 mb-3">
                <svg class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>
                    {{ __('Redirecting to dashboard in') }} <span id="countdown" class="font-semibold text-indigo-600 dark:text-indigo-400">3</span> {{ __('seconds...') }}
                </span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full bg-gray-200 dark:bg-gray-800 h-1.5 rounded-full overflow-hidden">
                <div id="progress-bar" class="bg-gradient-to-r from-indigo-500 to-purple-600 h-full rounded-full transition-all duration-1000 ease-linear" style="width: 100%"></div>
            </div>
        </div>

        <!-- Action Button -->
        <div>
            <a href="{{ route('dashboard') }}" class="w-full inline-flex justify-center items-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 shadow-md shadow-indigo-100 dark:shadow-none transition duration-150 ease-in-out transform hover:-translate-y-0.5">
                {{ __('Go to Dashboard Now') }}
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </a>
        </div>
    </div>

    <!-- Redirect Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let timeLeft = 3;
            const countdownEl = document.getElementById('countdown');
            const progressBarEl = document.getElementById('progress-bar');
            
            // Start the progress bar shrinking immediately
            setTimeout(() => {
                progressBarEl.style.width = '0%';
            }, 50);

            const interval = setInterval(() => {
                timeLeft -= 1;
                if (countdownEl) {
                    countdownEl.textContent = timeLeft;
                }
                
                if (timeLeft <= 0) {
                    clearInterval(interval);
                    window.location.href = "{{ route('dashboard') }}";
                }
            }, 1000);
        });
    </script>
</x-guest-layout>
