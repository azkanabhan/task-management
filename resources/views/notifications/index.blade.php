<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Notifications') }}
            </h2>
            @if (auth()->user()->unreadNotifications()->exists())
                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition"
                    >
                        Mark all as read
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-3">

            @if (session('success'))
                <div class="rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 px-4 py-3 text-sm text-green-700 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @forelse ($notifications as $notification)
                <div class="relative flex items-start gap-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border
                    {{ $notification->read_at ? 'border-gray-100 dark:border-gray-700 opacity-60' : 'border-indigo-200 dark:border-indigo-700' }}
                    px-5 py-4 transition hover:shadow-md"
                >
                    {{-- Unread dot --}}
                    @unless($notification->read_at)
                        <span class="mt-1.5 shrink-0 w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    @else
                        <span class="mt-1.5 shrink-0 w-2.5 h-2.5 rounded-full bg-transparent border border-gray-300 dark:border-gray-600"></span>
                    @endunless

                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-800 dark:text-gray-200">
                            {{ $notification->data['message'] ?? 'New notification' }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </p>
                    </div>

                    {{-- Action: mark as read / follow link --}}
                    @unless($notification->read_at)
                        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" class="shrink-0 self-center">
                            @csrf
                            <button
                                type="submit"
                                class="text-xs text-indigo-500 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300 whitespace-nowrap transition"
                            >
                                Mark read
                            </button>
                        </form>
                    @endunless
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 text-center">
                    <svg class="w-14 h-14 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 font-medium">No notifications yet</p>
                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">You're all caught up! 🎉</p>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if ($notifications->hasPages())
                <div class="pt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
