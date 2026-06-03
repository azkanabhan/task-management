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

            @if (isset($receivedInvitations) && $receivedInvitations->isNotEmpty())
                <div class="p-6 bg-indigo-50 dark:bg-indigo-900/30 border border-indigo-100 dark:border-indigo-800/50 shadow-sm sm:rounded-lg space-y-4">
                    <h3 class="text-lg font-bold text-indigo-900 dark:text-indigo-200 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l8-4.796a2 2 0 012.22 0l8 4.796A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-2.25-1.5a2 2 0 00-2.22 0l-2.25 1.5"></path>
                        </svg>
                        {{ __('Received Team Invitations') }}
                    </h3>
                    <div class="divide-y divide-indigo-100 dark:divide-indigo-900/50">
                        @foreach ($receivedInvitations as $invitation)
                            <div class="py-3 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between first:pt-0 last:pb-0">
                                <div>
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $invitation->team->name }}</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ __('invited you to join as') }} <strong class="text-indigo-600 dark:text-indigo-400">{{ ucfirst($invitation->role) }}</strong>
                                    </span>
                                    @if ($invitation->team->description)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 italic">"{{ $invitation->team->description }}"</p>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <form method="POST" action="{{ route('teams.invitations.accept', $invitation) }}">
                                        @csrf
                                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-500 dark:bg-indigo-500 dark:hover:bg-indigo-400">
                                            {{ __('Accept') }}
                                        </x-primary-button>
                                    </form>
                                    <form method="POST" action="{{ route('teams.invitations.decline', $invitation) }}">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                            {{ __('Decline') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
                                            <div class="font-medium">{{ $team->name }} <span class="text-xs text-gray-500 font-normal">({{ $team->code }})</span></div>
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

            <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Join Teams') }}</h3>

                <!-- Search Bar -->
                <form method="GET" action="{{ route('teams.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <div class="flex-1">
                        <x-text-input 
                            type="text" 
                            name="search" 
                            value="{{ $search ?? '' }}" 
                            class="w-full text-sm" 
                            placeholder="Enter Team Name or Team Code (e.g., T-XXXXXX)..." 
                        />
                    </div>
                    <div class="flex gap-2">
                        <x-primary-button type="submit" class="justify-center">
                            {{ __('Search') }}
                        </x-primary-button>
                        @if (!empty($search))
                            <a href="{{ route('teams.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>

                @if (!empty($search))
                    <div class="border-t border-gray-100 dark:border-gray-700/50 pt-4">
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-gray-400 mb-3">
                            {{ __('Search Results for') }}: <span class="font-bold text-gray-900 dark:text-white">"{{ $search }}"</span>
                        </h4>

                        @if ($joinableTeams->isEmpty())
                            <p class="text-sm text-gray-600 dark:text-gray-300">
                                {{ __('No teams found matching your search.') }}
                            </p>
                        @else
                            <div class="space-y-3">
                                @foreach ($joinableTeams as $team)
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-gray-50/50 dark:bg-gray-900/30">
                                        <div>
                                            <div class="font-medium text-gray-900 dark:text-gray-100">
                                                {{ $team->name }} 
                                                <span class="text-xs text-gray-500 font-normal">({{ $team->code }})</span>
                                            </div>
                                            @if ($team->description)
                                                <div class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ $team->description }}</div>
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
                @else
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Enter a team name or exact Team Code (UID) above to find teams to join.') }}
                    </p>
                @endif
            </div>

            @if ($ownedTeams->isNotEmpty())
                <div class="p-6 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg space-y-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Owner Panel') }}</h3>

                    @foreach ($ownedTeams as $ownedTeam)
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-5">
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $ownedTeam->name }} <span class="text-xs text-gray-500 font-normal">({{ $ownedTeam->code }})</span></h4>
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

                            <!-- Invite Member Form -->
                            <div class="border-t border-gray-100 dark:border-gray-700/50 pt-4">
                                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300 mb-3">{{ __('Invite Member') }}</h5>
                                <form method="POST" action="{{ route('teams.invite', $ownedTeam) }}" class="flex flex-col sm:flex-row gap-3 items-end">
                                    @csrf
                                    <div class="flex-1 w-full">
                                        <x-input-label for="email_{{ $ownedTeam->id }}" :value="__('Email Address')" class="sr-only" />
                                        <x-text-input 
                                            id="email_{{ $ownedTeam->id }}" 
                                            type="email" 
                                            name="email" 
                                            class="w-full text-sm" 
                                            placeholder="member@example.com" 
                                            required 
                                        />
                                    </div>
                                    <div class="w-full sm:w-auto">
                                        <x-input-label for="role_{{ $ownedTeam->id }}" :value="__('Role')" class="sr-only" />
                                        <select 
                                            id="role_{{ $ownedTeam->id }}" 
                                            name="role" 
                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                                        >
                                            <option value="member">Member</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <x-primary-button class="w-full sm:w-auto justify-center">
                                        {{ __('Invite') }}
                                    </x-primary-button>
                                </form>
                                @if ($errors->any())
                                    <div class="mt-2 text-xs text-red-600">
                                        {{ $errors->first() }}
                                    </div>
                                @endif
                            </div>

                            <!-- Pending Sent Invitations -->
                            <div class="border-t border-gray-100 dark:border-gray-700/50 pt-4">
                                <h5 class="text-sm font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-300">{{ __('Pending Sent Invitations') }}</h5>
                                @if ($ownedTeam->invitations->isEmpty())
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">{{ __('No pending invitations.') }}</p>
                                @else
                                    <div class="mt-3 space-y-2">
                                        @foreach ($ownedTeam->invitations as $sentInvite)
                                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between rounded-md border border-gray-200 dark:border-gray-700 p-3">
                                                <div class="text-sm text-gray-800 dark:text-gray-100">
                                                    {{ $sentInvite->email }} 
                                                    <span class="text-xs ml-1.5 px-2 py-0.5 rounded-full bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-300 border border-indigo-100 dark:border-indigo-800/40">
                                                        {{ ucfirst($sentInvite->role) }}
                                                    </span>
                                                </div>
                                                <form method="POST" action="{{ route('teams.invitations.destroy', $sentInvite) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                                        {{ __('Revoke') }}
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="border-t border-gray-100 dark:border-gray-700/50 pt-4">
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
