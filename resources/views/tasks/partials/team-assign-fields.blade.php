@php
    $selectedTeamId = (string) old('team_id', $selectedTeamId ?? '');
    $selectedAssignTo = (string) old('assign_to', $selectedAssignTo ?? '');
    $currentUserId = auth()->id();

    $roleWeights = [
        'member' => 1,
        'admin'  => 2,
        'owner'  => 3,
    ];

    $teamMembers = $teams->mapWithKeys(function ($team) use ($currentUserId, $roleWeights) {
        $currentUserMember = $team->acceptedUsers->first(fn ($u) => (int) $u->id === (int) $currentUserId);
        $currentUserRole = $currentUserMember ? $currentUserMember->pivot->role : 'member';
        $currentUserWeight = $roleWeights[$currentUserRole] ?? 0;

        $filteredMembers = $team->acceptedUsers->filter(function ($member) use ($currentUserWeight, $roleWeights) {
            $memberRole = $member->pivot->role ?? 'member';
            $memberWeight = $roleWeights[$memberRole] ?? 0;
            return $currentUserWeight >= $memberWeight;
        });

        return [
            (string) $team->id => $filteredMembers->map(fn ($member) => [
                'id' => $member->id,
                'label' => $member->name . ' (' . $member->email . ')' . ((int) $member->id === (int) $currentUserId ? ' (You)' : ''),
            ])->values()->all(),
        ];
    });
@endphp

<div class="space-y-4">
    {{-- Team Selection --}}
    <div>
        <label for="team_id" class="input-label">{{ __('Team') }} <span class="text-slate-400 dark:text-slate-500 text-xs font-normal">({{ __('Optional') }})</span></label>
        <select id="team_id" name="team_id" class="input-field mt-1">
            <option value="">{{ __('No Team') }}</option>
            @foreach ($teams as $team)
                <option value="{{ $team->id }}" @selected($selectedTeamId === (string) $team->id)>
                    {{ $team->name }}
                </option>
            @endforeach
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('team_id')" />
    </div>

    {{-- Self-assign Notice --}}
    <div id="assign-to-self-notice" class="{{ $selectedTeamId !== '' ? 'hidden' : '' }}">
        <div class="flex items-center gap-2.5 px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700">
            <svg class="w-4 h-4 text-slate-400 dark:text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-sm text-slate-500 dark:text-slate-400">
                {{ __('No team selected. This task will be assigned to you.') }}
            </p>
        </div>
    </div>

    {{-- Assign To Member --}}
    <div id="assign-to-field" class="{{ $selectedTeamId === '' ? 'hidden' : '' }}">
        <label for="assign_to" class="input-label">{{ __('Assign To') }}</label>
        <select
            id="assign_to"
            name="assign_to"
            class="input-field mt-1"
            @disabled($selectedTeamId === '')
        >
            <option value="" disabled selected>{{ __('Select a team member') }}</option>
        </select>
        <x-input-error class="mt-2" :messages="$errors->get('assign_to')" />
    </div>
</div>

<script>
    (function () {
        const teamMembers = @json($teamMembers);
        const currentUserId = @json($currentUserId);
        const initialTeamId = @json($selectedTeamId);
        const initialAssignTo = @json($selectedAssignTo);
        const selectMemberLabel = @json(__('Select a team member'));

        const teamSelect = document.getElementById('team_id');
        const assignField = document.getElementById('assign-to-field');
        const assignSelect = document.getElementById('assign_to');
        const selfNotice = document.getElementById('assign-to-self-notice');

        function populateAssignees(teamId, selectedId) {
            assignSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = selectMemberLabel;
            placeholder.disabled = true;
            placeholder.selected = true;
            assignSelect.appendChild(placeholder);

            const members = teamMembers[teamId] ?? [];

            members.forEach((member) => {
                const option = document.createElement('option');
                option.value = String(member.id);
                option.textContent = member.label;

                if (String(selectedId) === String(member.id)) {
                    option.selected = true;
                }

                assignSelect.appendChild(option);
            });

            if (!assignSelect.value && members.some((member) => String(member.id) === String(currentUserId))) {
                assignSelect.value = String(currentUserId);
            }
        }

        function syncAssignFields() {
            const teamId = teamSelect.value;

            if (!teamId) {
                assignField.classList.add('hidden');
                selfNotice.classList.remove('hidden');
                assignSelect.disabled = true;
                assignSelect.innerHTML = '';
                assignSelect.removeAttribute('name');

                return;
            }

            assignField.classList.remove('hidden');
            selfNotice.classList.add('hidden');
            assignSelect.disabled = false;
            assignSelect.setAttribute('name', 'assign_to');
            populateAssignees(teamId, initialTeamId === teamId ? initialAssignTo : currentUserId);
        }

        teamSelect.addEventListener('change', syncAssignFields);
        syncAssignFields();
    })();
</script>
