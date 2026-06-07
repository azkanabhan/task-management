<div x-data="{
    open: false,
    unreadCount: {{ auth()->user()->unreadNotifications()->count() }},
    notifications: [],
    fetchNotifications() {
        fetch('{{ route('notifications.unread') }}')
            .then(r => r.json())
            .then(data => {
                this.unreadCount = data.count;
                this.notifications = data.notifications;
            });
    },
    init() {
        this.fetchNotifications();
        setInterval(() => this.fetchNotifications(), 30000);
    },
    markAllRead() {
        fetch('{{ route('notifications.readAll') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            }
        }).then(() => {
            this.unreadCount = 0;
            this.notifications = [];
        });
    }
}" @click.outside="open = false" class="relative">
    {{-- Bell Button --}}
    <button @click="open = !open" class="relative p-2 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all duration-200">
        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
        </svg>
        {{-- Badge --}}
        <span x-cloak
              x-show="unreadCount > 0"
              x-transition:enter="transition ease-out duration-200"
              x-transition:enter-start="scale-0"
              x-transition:enter-end="scale-100"
              class="absolute -top-0.5 -right-0.5 flex items-center justify-center min-w-[18px] h-[18px] px-1 text-[10px] font-bold text-white bg-rose-500 rounded-full ring-2 ring-white dark:ring-slate-900"
              x-text="unreadCount > 9 ? '9+' : unreadCount">
        </span>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 sm:w-96 rounded-xl shadow-elevated border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 z-50 overflow-hidden"
         style="display: none;">

        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 dark:border-slate-700">
            <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100">Notifications</h3>
            <div class="flex items-center gap-2">
                <button x-show="unreadCount > 0" @click="markAllRead()" class="text-xs font-medium text-rose-600 dark:text-rose-400 hover:text-rose-700 dark:hover:text-rose-300 transition-colors">
                    Mark all read
                </button>
                <a href="{{ route('notifications.index') }}" class="text-xs font-medium text-slate-500 hover:text-slate-700 dark:hover:text-slate-400 transition-colors">
                    View all
                </a>
            </div>
        </div>

        {{-- Notifications List --}}
        <div class="max-h-80 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center">
                    <svg class="w-10 h-10 mx-auto text-slate-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                    <p class="text-sm text-slate-400 dark:text-slate-500">You're all caught up! 🎉</p>
                </div>
            </template>
            <template x-for="notification in notifications" :key="notification.id">
                <form :action="'/notifications/' + notification.id + '/read'" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors border-b border-slate-50 dark:border-slate-700/50 last:border-0">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 w-2 h-2 rounded-full bg-rose-500 shrink-0"></span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-slate-700 dark:text-slate-300 line-clamp-2" x-text="notification.message"></p>
                                <p class="text-xs text-slate-400 dark:text-slate-500 mt-1" x-text="notification.created_at"></p>
                            </div>
                        </div>
                    </button>
                </form>
            </template>
        </div>
    </div>
</div>
