<?php

namespace App\Notifications;

use App\Models\Team;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamJoinRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Team $team,
        public readonly User $requester,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Join Request for Team: \"{$this->team->name}\"")
            ->view('emails.team_join_requested', [
                'team' => $this->team,
                'requester' => $this->requester,
                'notifiable' => $notifiable,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'           => 'team_join_requested',
            'team_id'        => $this->team->id,
            'team_name'      => $this->team->name,
            'requester_id'   => $this->requester->id,
            'requester_name' => $this->requester->name,
            'message'        => "\"{$this->requester->name}\" requested to join your team \"{$this->team->name}\".",
            'url'            => route('teams.index', [], false),
        ];
    }
}
