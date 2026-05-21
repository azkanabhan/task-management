<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\Team;
use App\Models\User;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model at all.
     */
    public function update(User $user, Task $task): bool
    {
        return $this->updateFull($user, $task) || $this->updateStatus($user, $task);
    }

    /**
     * Determine whether the user can update all task fields (creator only).
     */
    public function updateFull(User $user, Task $task): bool
    {
        return (int) $user->id === (int) $task->created_by;
    }

    /**
     * Determine whether the user can update only the task status (assignee, not creator).
     */
    public function updateStatus(User $user, Task $task): bool
    {
        return (int) $user->id === (int) $task->assign_to
            && (int) $user->id !== (int) $task->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->created_by;
    }

    /**
     * Determine whether the assignee is valid for the selected team.
     */
    public function assign(User $user, ?int $teamId, ?int $assignToId): bool
    {
        if ($teamId === null) {
            return $assignToId === null || (int) $assignToId === (int) $user->id;
        }

        if ($assignToId === null) {
            return false;
        }

        $team = Team::query()->whereKey($teamId)->first();

        if ($team === null) {
            return false;
        }

        $assigneeOnTeam = $team->acceptedUsers()
            ->where('users.id', $assignToId)
            ->exists();

        $userOnTeam = $team->acceptedUsers()
            ->where('users.id', $user->id)
            ->exists();

        return $assigneeOnTeam && $userOnTeam;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
