<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TeamService
{
    public function getUserTeams(int $userId): Collection
    {
        $user = User::query()->findOrFail($userId);

        return $user->teams()
            ->wherePivot('status', 'accepted')
            ->orderBy('name')
            ->get();
    }

    public function getJoinableTeams(int $userId): Collection
    {
        return Team::query()
            ->whereDoesntHave('users', fn ($query) => $query
                ->where('users.id', $userId)
                ->where('team_users.status', 'accepted'))
            ->orderBy('name')
            ->get();
    }

    public function getPendingRequestTeamIds(int $userId): array
    {
        return DB::table('team_users')
            ->where('user_id', $userId)
            ->where('status', 'pending')
            ->pluck('team_id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public function getOwnedTeamsWithMembersAndRequests(int $userId): Collection
    {
        return Team::query()
            ->whereHas('users', fn ($query) => $query
                ->where('users.id', $userId)
                ->where('team_users.role', 'owner')
                ->where('team_users.status', 'accepted'))
            ->with([
                'users' => fn ($query) => $query
                    ->where('team_users.status', 'accepted')
                    ->orderBy('team_users.role')
                    ->orderBy('users.name'),
                'pendingUsers' => fn ($query) => $query
                    ->orderBy('users.name'),
            ])
            ->orderBy('name')
            ->get();
    }

    public function validateTeamData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }

    public function createTeamForUser(User $user, array $data): Team
    {
        $team = Team::create($data);

        $team->users()->attach($user->id, [
            'role' => 'owner',
            'status' => 'accepted',
            'joined_at' => Carbon::now(),
        ]);

        return $team;
    }

    public function joinTeam(User $user, Team $team): void
    {
        $existing = DB::table('team_users')
            ->where('team_id', $team->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing && $existing->status === 'accepted') {
            return;
        }

        if ($existing && $existing->status === 'pending') {
            return;
        }

        $team->users()->syncWithoutDetaching([
            $user->id => [
                'role' => 'member',
                'status' => 'pending',
                'joined_at' => null,
            ],
        ]);
    }

    public function approveRequest(Team $team, int $memberId): void
    {
        $updated = DB::table('team_users')
            ->where('team_id', $team->id)
            ->where('user_id', $memberId)
            ->where('status', 'pending')
            ->update([
                'status' => 'accepted',
                'role' => 'member',
                'joined_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        if (! $updated) {
            throw new HttpException(404, 'Pending request not found.');
        }
    }

    public function rejectRequest(Team $team, int $memberId): void
    {
        $updated = DB::table('team_users')
            ->where('team_id', $team->id)
            ->where('user_id', $memberId)
            ->where('status', 'pending')
            ->update([
                'status' => 'rejected',
                'joined_at' => null,
                'updated_at' => Carbon::now(),
            ]);

        if (! $updated) {
            throw new HttpException(404, 'Pending request not found.');
        }
    }

    public function updateMemberRole(Team $team, int $memberId, string $role): void
    {
        if (! in_array($role, ['admin', 'member'], true)) {
            throw new HttpException(422, 'Invalid role.');
        }

        $updated = DB::table('team_users')
            ->where('team_id', $team->id)
            ->where('user_id', $memberId)
            ->where('status', 'accepted')
            ->where('role', '!=', 'owner')
            ->update([
                'role' => $role,
                'updated_at' => Carbon::now(),
            ]);

        if (! $updated) {
            throw new HttpException(404, 'Member not found or cannot change owner role.');
        }
    }

    public function kickMember(Team $team, int $memberId): void
    {
        $deleted = DB::table('team_users')
            ->where('team_id', $team->id)
            ->where('user_id', $memberId)
            ->where('status', 'accepted')
            ->where('role', '!=', 'owner')
            ->delete();

        if (! $deleted) {
            throw new HttpException(404, 'Member not found or cannot remove owner.');
        }
    }
}
