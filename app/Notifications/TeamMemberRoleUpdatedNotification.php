<?php

namespace App\Notifications;

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamMemberRoleUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Team $team,
        public readonly string $role
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Role Updated in Team: \"{$this->team->name}\"")
            ->view('emails.team_member_role_updated', [
                'team' => $this->team,
                'role' => $this->role,
                'notifiable' => $notifiable,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'      => 'team_member_role_updated',
            'team_id'   => $this->team->id,
            'team_name' => $this->team->name,
            'role'      => $this->role,
            'message'   => "Your role in \"{$this->team->name}\" has been updated to {$this->role}.",
            'url'       => route('teams.index', [], false),
        ];
    }
}
