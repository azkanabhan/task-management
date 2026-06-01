<?php

namespace App\Listeners;

use App\Events\TeamJoinRequested;
use App\Events\TeamMemberApproved;
use App\Events\TeamMemberRejected;
use App\Notifications\TeamJoinRequestedNotification;
use App\Notifications\TeamMemberApprovedNotification;
use App\Notifications\TeamMemberRejectedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class SendTeamNotification implements ShouldQueue
{
    public function handleJoinRequested(TeamJoinRequested $event): void
    {
        // Notify all owners of the team
        $ownerIds = DB::table('team_users')
            ->where('team_id', $event->team->id)
            ->where('role', 'owner')
            ->where('status', 'accepted')
            ->pluck('user_id');

        $event->team->users()
            ->whereIn('users.id', $ownerIds)
            ->get()
            ->each(fn ($owner) => $owner->notify(
                new TeamJoinRequestedNotification($event->team, $event->requester)
            ));
    }

    public function handleMemberApproved(TeamMemberApproved $event): void
    {
        $event->member->notify(new TeamMemberApprovedNotification($event->team));
    }

    public function handleMemberRejected(TeamMemberRejected $event): void
    {
        $event->member->notify(new TeamMemberRejectedNotification($event->team));
    }
}
