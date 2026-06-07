<x-app-layout>
    <div class="page-container animate-fade-in">
        {{-- Page Header --}}
        <x-page-header title="Notifications" description="Stay updated with your latest activity">
            <x-slot name="actions">
                @if (auth()->user()->unreadNotifications()->exists())
                    <form method="POST" action="{{ route('notifications.readAll') }}">
                        @csrf
                        <button type="submit" class="btn-secondary inline-flex items-center gap-2 text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ __('Mark all as read') }}
                        </button>
                    </form>
                @endif
            </x-slot>
        </x-page-header>

        {{-- Notifications List --}}
        <div class="max-w-3xl space-y-3 stagger-children">
            @forelse ($notifications as $notification)
                <div class="card p-0 overflow-hidden transition-all duration-200 hover:shadow-card-hover
                    {{ $notification->read_at
                        ? 'opacity-70 hover:opacity-100'
                        : 'border-l-4 border-l-rose-500 dark:border-l-rose-400 bg-rose-50/30 dark:bg-rose-500/5'
                    }}"
                >
                    <div class="flex items-start gap-4 px-5 py-4">
                        {{-- Unread Dot / Read Circle --}}
                        <div class="shrink-0 mt-1">
                            @unless($notification->read_at)
                                <span class="block w-2.5 h-2.5 rounded-full bg-rose-500 dark:bg-rose-400 shadow-sm shadow-rose-500/30"></span>
                            @else
                                <span class="block w-2.5 h-2.5 rounded-full border-2 border-slate-300 dark:border-slate-600"></span>
                            @endunless
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm {{ $notification->read_at ? 'text-slate-500 dark:text-slate-400' : 'text-slate-800 dark:text-slate-200 font-medium' }}">
                                {{ $notification->data['message'] ?? 'New notification' }}
                            </p>
                            <p class="mt-1 text-xs text-slate-400 dark:text-slate-500">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>

                        {{-- Mark as Read --}}
                        @unless($notification->read_at)
                            <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="shrink-0 self-center">
                                @csrf
                                <button
                                    type="submit"
                                    class="text-xs font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400 dark:hover:text-rose-300 whitespace-nowrap transition-colors px-2 py-1 rounded-lg hover:bg-rose-50 dark:hover:bg-rose-500/10"
                                >
                                    {{ __('Mark read') }}
                                </button>
                            </form>
                        @endunless
                    </div>
                </div>
            @empty
                <x-empty-state
                    title="No notifications yet"
                    description="You're all caught up! 🎉 New notifications will appear here."
                >
                    <x-slot name="icon">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </x-slot>
                </x-empty-state>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if ($notifications->hasPages())
            <div class="mt-6 max-w-3xl">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
