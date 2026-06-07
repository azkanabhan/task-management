<x-app-layout>
    <div class="page-container" x-data="{ tab: 'my-teams' }">
        {{-- Page Header --}}
        <x-page-header title="Teams" description="Collaborate with your teammates and manage team memberships.">
            <x-slot name="actions">
                <a href="{{ route('teams.create') }}" class="btn-primary inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    {{ __('Create Team') }}
                </a>
            </x-slot>
        </x-page-header>

        {{-- Tab Navigation --}}
        <div class="flex items-center gap-1.5 p-1.5 bg-slate-100 dark:bg-slate-800/60 rounded-xl mb-6 overflow-x-auto">
            <button @click="tab = 'my-teams'"
                    :class="tab === 'my-teams'
                        ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-600'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-700/50'"
                    class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                </svg>
                {{ __('My Teams') }}
            </button>

            <button @click="tab = 'discover'"
                    :class="tab === 'discover'
                        ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-600'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-700/50'"
                    class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                </svg>
                {{ __('Discover') }}
            </button>

            <button @click="tab = 'invitations'"
                    :class="tab === 'invitations'
                        ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-600'
                        : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-700/50'"
                    class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
                {{ __('Invitations') }}
                @if (isset($receivedInvitations) && $receivedInvitations->isNotEmpty())
                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[11px] font-bold rounded-full bg-rose-500 text-white">
                        {{ $receivedInvitations->count() }}
                    </span>
                @endif
            </button>

            @if ($ownedTeams->isNotEmpty())
                <button @click="tab = 'manage'"
                        :class="tab === 'manage'
                            ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-slate-100 shadow-sm border border-slate-200 dark:border-slate-600'
                            : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-white/50 dark:hover:bg-slate-700/50'"
                        class="px-4 py-2.5 text-sm font-semibold rounded-lg transition-all duration-200 whitespace-nowrap flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    {{ __('Manage') }}
                </button>
            @endif
        </div>

        {{-- ====================== MY TEAMS TAB ====================== --}}
        <div x-show="tab === 'my-teams'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            @if ($myTeams->isEmpty())
                <div class="card">
                    <x-empty-state
                        title="No teams yet"
                        description="You haven't joined any teams yet. Create your own team or discover existing ones to collaborate with others."
                        actionText="Create Team"
                        actionUrl="{{ route('teams.create') }}"
                    >
                        <x-slot name="icon">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"/>
                            </svg>
                        </x-slot>
                    </x-empty-state>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 stagger-children">
                    @foreach ($myTeams as $team)
                        <div class="card card-hover p-5 animate-fade-in">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($team->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-sm">{{ $team->name }}</h3>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 font-mono">{{ $team->code }}</p>
                                    </div>
                                </div>
                                <x-badge :variant="$team->pivot->role">{{ ucfirst((string) $team->pivot->role) }}</x-badge>
                            </div>

                            @if ($team->description)
                                <p class="text-xs text-slate-500 dark:text-slate-400 mb-3 line-clamp-2">{{ $team->description }}</p>
                            @endif

                            <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-700/50">
                                <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                    </svg>
                                    <span>{{ $team->users_count ?? $team->users->count() ?? '—' }} {{ __('members') }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 text-xs text-slate-400 dark:text-slate-500">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                    </svg>
                                    <span>{{ $team->pivot->joined_at ? \Illuminate\Support\Carbon::parse($team->pivot->joined_at)->format('d M Y') : '-' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ====================== DISCOVER TAB ====================== --}}
        <div x-show="tab === 'discover'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="card p-5 sm:p-6 mb-6">
                <h3 class="text-sm font-semibold text-slate-900 dark:text-slate-100 mb-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                    </svg>
                    {{ __('Search for Teams') }}
                </h3>
                <form method="GET" action="{{ route('teams.index') }}" class="flex flex-col sm:flex-row gap-3">
                    <input type="hidden" name="tab" value="discover">
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
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                            {{ __('Search') }}
                        </x-primary-button>
                        @if (!empty($search))
                            <a href="{{ route('teams.index') }}" class="btn-secondary inline-flex items-center">
                                {{ __('Clear') }}
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            @if (!empty($search))
                <div class="mb-4">
                    <p class="text-sm text-slate-500 dark:text-slate-400">
                        {{ __('Search results for') }} <span class="font-semibold text-slate-900 dark:text-slate-100">"{{ $search }}"</span>
                    </p>
                </div>

                @if ($joinableTeams->isEmpty())
                    <div class="card">
                        <x-empty-state
                            title="No teams found"
                            description="No teams found matching your search."
                        >
                            <x-slot name="icon">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                </svg>
                            </x-slot>
                        </x-empty-state>
                    </div>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 stagger-children">
                        @foreach ($joinableTeams as $team)
                            <div class="card card-hover p-5 animate-fade-in">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($team->name, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-sm truncate">{{ $team->name }}</h3>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 font-mono">{{ $team->code }}</p>
                                    </div>
                                </div>

                                @if ($team->description)
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mb-4 line-clamp-2">{{ $team->description }}</p>
                                @else
                                    <div class="mb-4"></div>
                                @endif

                                <form method="POST" action="{{ route('teams.join', $team) }}">
                                    @csrf
                                    @if (in_array($team->id, $pendingRequestTeamIds, true))
                                        <button type="button" disabled
                                                class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg border border-slate-200 dark:border-slate-700 text-xs font-semibold uppercase tracking-widest text-slate-400 dark:text-slate-500 bg-slate-50 dark:bg-slate-800/50 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Requested') }}
                                        </button>
                                    @else
                                        <x-primary-button class="w-full justify-center">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                                            </svg>
                                            {{ __('Request Join') }}
                                        </x-primary-button>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            @else
                <div class="card">
                    <x-empty-state
                        title="Discover Teams"
                        description="Enter a team name or exact Team Code (UID) above to find teams to join."
                    >
                        <x-slot name="icon">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>
                            </svg>
                        </x-slot>
                    </x-empty-state>
                </div>
            @endif
        </div>

        {{-- ====================== INVITATIONS TAB ====================== --}}
        <div x-show="tab === 'invitations'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
            @if (isset($receivedInvitations) && $receivedInvitations->isNotEmpty())
                <div class="space-y-3 stagger-children">
                    @foreach ($receivedInvitations as $invitation)
                        <div class="card card-hover p-5 animate-fade-in">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div class="flex items-start gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($invitation->team->name, 0, 2)) }}
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-sm">{{ $invitation->team->name }}</h3>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                            {{ __('Invited you to join as') }}
                                            <x-badge :variant="$invitation->role">{{ ucfirst($invitation->role) }}</x-badge>
                                        </p>
                                        @if ($invitation->team->description)
                                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1.5 italic">"{{ $invitation->team->description }}"</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 sm:shrink-0">
                                    <form method="POST" action="{{ route('teams.invitations.accept', $invitation) }}">
                                        @csrf
                                        <button type="submit" class="btn-primary inline-flex items-center gap-1.5 text-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                            {{ __('Accept') }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('teams.invitations.decline', $invitation) }}">
                                        @csrf
                                        <button type="submit" class="btn-danger inline-flex items-center gap-1.5 text-sm">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            {{ __('Decline') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <x-empty-state
                        title="No invitations"
                        description="You don't have any pending team invitations at the moment."
                    >
                        <x-slot name="icon">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                            </svg>
                        </x-slot>
                    </x-empty-state>
                </div>
            @endif
        </div>

        {{-- ====================== MANAGE TAB ====================== --}}
        @if ($ownedTeams->isNotEmpty())
            <div x-show="tab === 'manage'" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="space-y-4">
                    @foreach ($ownedTeams as $ownedTeam)
                        <div class="card overflow-hidden animate-fade-in" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                            {{-- Accordion Header --}}
                            <button @click="open = !open" class="w-full flex items-center justify-between p-5 sm:p-6 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors duration-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-500 flex items-center justify-center text-white font-bold text-sm shrink-0">
                                        {{ strtoupper(substr($ownedTeam->name, 0, 2)) }}
                                    </div>
                                    <div class="text-left">
                                        <h3 class="font-semibold text-slate-900 dark:text-slate-100 text-sm">{{ $ownedTeam->name }}</h3>
                                        <p class="text-xs text-slate-400 dark:text-slate-500 font-mono">{{ $ownedTeam->code }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="hidden sm:flex items-center gap-2">
                                        <span class="text-xs text-slate-400 dark:text-slate-500">{{ $ownedTeam->users->count() }} {{ __('members') }}</span>
                                        @if ($ownedTeam->pendingUsers->isNotEmpty())
                                            <x-badge variant="warning" size="xs">{{ $ownedTeam->pendingUsers->count() }} {{ __('pending') }}</x-badge>
                                        @endif
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                                    </svg>
                                </div>
                            </button>

                            {{-- Accordion Content --}}
                            <div x-show="open" x-collapse>
                                <div class="border-t border-slate-100 dark:border-slate-700/50">
                                    @if ($ownedTeam->description)
                                        <div class="px-5 sm:px-6 py-3 bg-slate-50/50 dark:bg-slate-800/30">
                                            <p class="text-sm text-slate-500 dark:text-slate-400">{{ $ownedTeam->description }}</p>
                                        </div>
                                    @endif

                                    {{-- Pending Requests Section --}}
                                    <div class="px-5 sm:px-6 py-5 border-b border-slate-100 dark:border-slate-700/50">
                                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Pending Requests') }}
                                        </h4>

                                        @if ($ownedTeam->pendingUsers->isEmpty())
                                            <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('No pending requests.') }}</p>
                                        @else
                                            <div class="space-y-2">
                                                @foreach ($ownedTeam->pendingUsers as $requestUser)
                                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3.5">
                                                        <div class="flex items-center gap-3">
                                                            <x-avatar :name="$requestUser->name" size="sm" />
                                                            <div>
                                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $requestUser->name }}</p>
                                                                <p class="text-xs text-slate-400 dark:text-slate-500">{{ $requestUser->email }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="flex items-center gap-2">
                                                            <form method="POST" action="{{ route('teams.approve', $ownedTeam) }}">
                                                                @csrf
                                                                <input type="hidden" name="user_id" value="{{ $requestUser->id }}">
                                                                <button type="submit" class="btn-primary inline-flex items-center gap-1.5 text-xs">
                                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                                    </svg>
                                                                    {{ __('Approve') }}
                                                                </button>
                                                            </form>
                                                            <form method="POST" action="{{ route('teams.reject', $ownedTeam) }}">
                                                                @csrf
                                                                <input type="hidden" name="user_id" value="{{ $requestUser->id }}">
                                                                <button type="submit" class="btn-danger inline-flex items-center gap-1.5 text-xs">
                                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                                    </svg>
                                                                    {{ __('Reject') }}
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Invite Member Section --}}
                                    <div class="px-5 sm:px-6 py-5 border-b border-slate-100 dark:border-slate-700/50">
                                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                                            </svg>
                                            {{ __('Invite Member') }}
                                        </h4>
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
                                                    class="w-full rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-rose-500 focus:ring-rose-500 text-sm"
                                                >
                                                    <option value="member">Member</option>
                                                    <option value="admin">Admin</option>
                                                </select>
                                            </div>
                                            <x-primary-button class="w-full sm:w-auto justify-center">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                                </svg>
                                                {{ __('Invite') }}
                                            </x-primary-button>
                                        </form>
                                        @if ($errors->any())
                                            <div class="mt-2 text-xs text-red-600 dark:text-red-400">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Pending Sent Invitations Section --}}
                                    <div class="px-5 sm:px-6 py-5 border-b border-slate-100 dark:border-slate-700/50">
                                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5"/>
                                            </svg>
                                            {{ __('Pending Sent Invitations') }}
                                        </h4>

                                        @if ($ownedTeam->invitations->isEmpty())
                                            <p class="text-sm text-slate-400 dark:text-slate-500">{{ __('No pending invitations.') }}</p>
                                        @else
                                            <div class="space-y-2">
                                                @foreach ($ownedTeam->invitations as $sentInvite)
                                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between rounded-xl bg-slate-50 dark:bg-slate-800/50 p-3.5">
                                                        <div class="flex items-center gap-3">
                                                            <x-avatar :name="$sentInvite->email" size="sm" />
                                                            <div>
                                                                <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $sentInvite->email }}</p>
                                                                <x-badge :variant="$sentInvite->role" size="xs">{{ ucfirst($sentInvite->role) }}</x-badge>
                                                            </div>
                                                        </div>
                                                        <form method="POST" action="{{ route('teams.invitations.destroy', $sentInvite) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-danger inline-flex items-center gap-1.5 text-xs">
                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                                </svg>
                                                                {{ __('Revoke') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Members Section --}}
                                    <div class="px-5 sm:px-6 py-5">
                                        <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400 dark:text-slate-500 mb-3 flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                            </svg>
                                            {{ __('Members') }}
                                            <span class="text-slate-300 dark:text-slate-600 font-normal">({{ $ownedTeam->users->count() }})</span>
                                        </h4>

                                        <div class="overflow-x-auto">
                                            <table class="w-full">
                                                <thead>
                                                    <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                                        <th class="text-left text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider pb-3 pr-4">{{ __('Member') }}</th>
                                                        <th class="text-left text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider pb-3 pr-4">{{ __('Role') }}</th>
                                                        <th class="text-right text-xs font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider pb-3">{{ __('Actions') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-50 dark:divide-slate-800/50">
                                                    @foreach ($ownedTeam->users as $member)
                                                        <tr>
                                                            <td class="py-3 pr-4">
                                                                <div class="flex items-center gap-3">
                                                                    <x-avatar :name="$member->name" size="sm" />
                                                                    <div>
                                                                        <p class="text-sm font-medium text-slate-900 dark:text-slate-100">{{ $member->name }}</p>
                                                                        <p class="text-xs text-slate-400 dark:text-slate-500">{{ $member->email }}</p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="py-3 pr-4">
                                                                <x-badge :variant="$member->pivot->role">{{ ucfirst($member->pivot->role) }}</x-badge>
                                                            </td>
                                                            <td class="py-3 text-right">
                                                                @if ($member->pivot->role !== 'owner')
                                                                    <div class="flex items-center justify-end gap-2">
                                                                        <form method="POST" action="{{ route('teams.role.update', $ownedTeam) }}" class="flex items-center gap-2">
                                                                            @csrf
                                                                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                                            <select
                                                                                name="role"
                                                                                class="rounded-lg border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 focus:border-rose-500 focus:ring-rose-500 text-xs py-1.5"
                                                                            >
                                                                                <option value="member" @selected($member->pivot->role === 'member')>Member</option>
                                                                                <option value="admin" @selected($member->pivot->role === 'admin')>Admin</option>
                                                                            </select>
                                                                            <button type="submit" class="btn-secondary text-xs px-3 py-1.5">
                                                                                {{ __('Update') }}
                                                                            </button>
                                                                        </form>

                                                                        <form method="POST" action="{{ route('teams.kick', $ownedTeam) }}">
                                                                            @csrf
                                                                            <input type="hidden" name="user_id" value="{{ $member->id }}">
                                                                            <button type="submit" class="btn-danger text-xs px-3 py-1.5 inline-flex items-center gap-1">
                                                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z"/>
                                                                                </svg>
                                                                                {{ __('Kick') }}
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @else
                                                                    <x-badge variant="owner">{{ __('Owner') }}</x-badge>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
