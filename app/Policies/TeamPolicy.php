<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeamPolicy
{
    /**
     * Determine whether the user can view the team.
     */
    public function view(User $user, Team $team): bool
    {
        return $team->acceptedUsers()
            ->where('users.id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can create teams.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the team.
     */
    public function update(User $user, Team $team): bool
    {
        return $this->isOwner($user->id, $team->id);
    }

    /**
     * Determine whether the user can delete the team.
     */
    public function delete(User $user, Team $team): bool
    {
        return $this->isOwner($user->id, $team->id);
    }

    /**
     * Determine whether the user can manage membership of the team.
     */
    public function manageMembership(User $user, Team $team): bool
    {
        return $this->isOwner($user->id, $team->id);
    }

    /**
     * Helper to check if the user is the owner of the team.
     */
    private function isOwner(int $userId, int $teamId): bool
    {
        return DB::table('team_users')
            ->where('team_id', $teamId)
            ->where('user_id', $userId)
            ->where('role', 'owner')
            ->where('status', 'accepted')
            ->exists();
    }
}
