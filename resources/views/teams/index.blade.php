<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Teams') }}
            </h2>

            <a href="{{ route('teams.create') }}">
                <x-primary-button>
                    {{ __('Create Team') }}
                </x-primary-button>
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 shadow-sm sm:rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('My Teams') }}</h3>

                @if ($myTeams->isEmpty())
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ __("You haven't joined any teams yet.") }}
                    </p>
                @else
                    <div class="mt-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                    <th class="px-4 py-2 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Joined</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                @foreach ($myTeams as $team)
                                    <tr>
                                        <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                            <div class="font-medium">{{ $team->name }}</div>
                                            @if ($team->description)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $team->description }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                            {{ ucfirst((string) $team->pivot->role) }}
                                        </td>
                                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $team->pivot->joined_at ? \Illuminate\Support\Carbon::parse($team->pivot->joined_at)->format('d M Y, H:i') : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Join Teams') }}</h3>

                @if ($joinableTeams->isEmpty())
                    <p class="mt-4 text-sm text-gray-600 dark:text-gray-300">
                        {{ __('No available teams to join right now.') }}
                    </p>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($joinableTeams as $team)
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $team->name }}</div>
                                    @if ($team->description)
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $team->description }}</div>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('teams.join', $team) }}">
                                    @csrf
                                    @if (in_array($team->id, $pendingRequestTeamIds, true))
                                        <button type="button" class="inline-flex items-center px-4 py-2 rounded-md border border-gray-300 text-xs font-semibold uppercase tracking-widest text-gray-500 bg-gray-100 cursor-not-allowed">
                                            {{ __('Requested') }}
                                        </button>
                                    @else
                                        <x-primary-button>
                                            {{ __('Request Join') }}
                                        </x-primary-button>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($ownedTeams->isNotEmpty())
                <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Owner Panel') }}</h3>

                    @foreach ($ownedTeams as $ownedTeam)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-5">
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $ownedTeam->name }}</h4>
                                @if ($ownedTeam->description)
                                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $ownedTeam->description }}</p>
                                @endif
                            </div>

                            <div>
                                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Pending Requests') }}</h5>

                                @if ($ownedTeam->pendingUsers->isEmpty())
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('No pending requests.') }}</p>
                                @else
                                    <div class="mt-3 space-y-2">
                                        @foreach ($ownedTeam->pendingUsers as $requestUser)
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between rounded-md border border-gray-200 dark:border-gray-700 p-3">
                                                <div class="text-sm text-gray-800 dark:text-gray-100">
                                                    {{ $requestUser->name }} <span class="text-gray-500">({{ $requestUser->email }})</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <form method="POST" action="{{ route('teams.approve', $ownedTeam) }}">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $requestUser->id }}">
                                                        <x-primary-button>{{ __('Approve') }}</x-primary-button>
                                                    </form>
                                                    <form method="POST" action="{{ route('teams.reject', $ownedTeam) }}">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $requestUser->id }}">
                                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                                            {{ __('Reject') }}
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div>
                                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Members') }}</h5>
                                <div class="mt-3 space-y-2">
                                    @foreach ($ownedTeam->users as $member)
                                        <div class="flex flex-col gap-2 lg:flex-row lg:items-center lg:justify-between rounded-md border border-gray-200 dark:border-gray-700 p-3">
                                            <div class="text-sm text-gray-800 dark:text-gray-100">
                                                {{ $member->name }} <span class="text-gray-500">({{ $member->email }})</span>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                @if ($member->pivot->role !== 'owner')
                                                    <form method="POST" action="{{ route('teams.role.update', $ownedTeam) }}" class="flex items-center gap-2">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                        <select
                                                            name="role"
                                                            class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                                        >
                                                            <option value="member" @selected($member->pivot->role === 'member')>Member</option>
                                                            <option value="admin" @selected($member->pivot->role === 'admin')>Admin</option>
                                                        </select>
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                                                            {{ __('Update Role') }}
                                                        </button>
                                                    </form>

                                                    <form method="POST" action="{{ route('teams.kick', $ownedTeam) }}">
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                                            {{ __('Kick') }}
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-xs font-semibold uppercase tracking-wider text-indigo-600 dark:text-indigo-300">
                                                        {{ __('Owner') }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
