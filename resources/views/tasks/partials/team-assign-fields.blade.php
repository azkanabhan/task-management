@php
    $selectedTeamId = (string) old('team_id', $selectedTeamId ?? '');
    $selectedAssignTo = (string) old('assign_to', $selectedAssignTo ?? '');
    $teamMembers = $teams->mapWithKeys(fn ($team) => [
        (string) $team->id => $team->acceptedUsers->map(fn ($member) => [
            'id' => $member->id,
            'label' => $member->name.' ('.$member->email.')',
        ])->values()->all(),
    ]);
@endphp

<div>
    <x-input-label for="team_id" :value="__('Team (Optional)')" />
    <select
        id="team_id"
        name="team_id"
        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
    >
        <option value="">{{ __('No Team') }}</option>
        @foreach ($teams as $team)
            <option value="{{ $team->id }}" @selected($selectedTeamId === (string) $team->id)>
                {{ $team->name }}
            </option>
        @endforeach
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('team_id')" />
</div>

<div id="assign-to-self-notice" class="{{ $selectedTeamId !== '' ? 'hidden' : '' }} mt-2">
    <p class="text-sm text-gray-600 dark:text-gray-300">
        {{ __('No team selected. This task will be assigned to you.') }}
    </p>
</div>

<div id="assign-to-field" class="{{ $selectedTeamId === '' ? 'hidden' : '' }}">
    <x-input-label for="assign_to" :value="__('Assign To')" />
    <select
        id="assign_to"
        name="assign_to"
        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500"
        @disabled($selectedTeamId === '')
    >
        <option value="">{{ __('Select a team member') }}</option>
    </select>
    <x-input-error class="mt-2" :messages="$errors->get('assign_to')" />
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
