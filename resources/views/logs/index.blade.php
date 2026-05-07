<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Activity Logs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                @if ($logs->isEmpty())
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('No activity logs yet.') }}
                    </p>
                @else
                    <div class="space-y-3">
                        @foreach ($logs as $log)
                            <div class="rounded-md border border-gray-200 dark:border-gray-700 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $log->action }}</p>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $log->description }}</p>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->created_at?->format('d M Y, H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-6">
                        {{ $logs->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
