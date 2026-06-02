<div
    x-data="{
        open: false,
        count: 0,
        notifications: [],
        async fetchUnread() {
            try {
                const res = await fetch('{{ route('notifications.unread') }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await res.json();
                this.count = data.count;
                this.notifications = data.notifications;
            } catch (e) {}
        }
    }"
    x-init="fetchUnread(); setInterval(() => fetchUnread(), 30000)"
    class="relative"
>
    {{-- Bell Button --}}
    <button
        @click="open = !open"
        class="relative inline-flex items-center justify-center w-9 h-9 rounded-full text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition duration-150"
        aria-label="Notifications"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        {{-- Unread Badge --}}
        <span
            x-show="count > 0"
            x-text="count > 9 ? '9+' : count"
            class="absolute -top-0.5 -right-0.5 inline-flex items-center justify-center min-w-[1.1rem] h-[1.1rem] px-1 text-[10px] font-bold text-white bg-red-500 rounded-full leading-none"
            style="display: none;"
        ></span>
    </button>

    {{-- Dropdown --}}
    <div
        x-show="open"
        @click.outside="open = false"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 z-50 overflow-hidden"
        style="display: none;"
    >
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-gray-700">
            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Notifications</span>
            @if (auth()->user()->unreadNotifications()->exists())
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button type="submit" class="text-xs text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition">
                        Mark all read
                    </button>
                </form>
            @endif
        </div>

        {{-- Notification items --}}
        <ul class="max-h-72 overflow-y-auto divide-y divide-gray-50 dark:divide-gray-700">
            <template x-if="notifications.length === 0">
                <li class="px-4 py-6 text-center text-sm text-gray-400 dark:text-gray-500">
                    You're all caught up! 🎉
                </li>
            </template>
            <template x-for="n in notifications" :key="n.id">
                <li>
                    <form :action="`/notifications/${n.id}/read`" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition group">
                            <p class="text-sm text-gray-700 dark:text-gray-300 leading-snug" x-text="n.message"></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="n.created_at"></p>
                        </button>
                    </form>
                </li>
            </template>
        </ul>

        {{-- Footer --}}
        <div class="border-t border-gray-100 dark:border-gray-700 px-4 py-2 text-center">
            <a
                href="{{ route('notifications.index') }}"
                @click="open = false"
                class="text-xs text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 transition"
            >
                View all notifications
            </a>
        </div>
    </div>
</div>
