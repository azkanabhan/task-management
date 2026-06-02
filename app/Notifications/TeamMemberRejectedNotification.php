<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TeamMemberRejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Team $team)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'      => 'team_member_rejected',
            'team_id'   => $this->team->id,
            'team_name' => $this->team->name,
            'message'   => "Your request to join \"{$this->team->name}\" was declined.",
            'url'       => route('teams.index', [], false),
        ];
    }
}
