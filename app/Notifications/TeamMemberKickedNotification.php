<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamMemberKickedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Team $team)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Removed from Team: \"{$this->team->name}\"")
            ->view('emails.team_member_kicked', [
                'team' => $this->team,
                'notifiable' => $notifiable,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'      => 'team_member_kicked',
            'team_id'   => $this->team->id,
            'team_name' => $this->team->name,
            'message'   => "You have been removed from the team \"{$this->team->name}\".",
            'url'       => route('teams.index', [], false),
        ];
    }
}
