<?php

namespace App\Notifications;

use App\Models\TeamInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeamInvitationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public TeamInvitation $invitation,
        public string $senderName
    ) {
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $teamsUrl = route('teams.index');

        return (new MailMessage)
            ->subject("Invitation to join team: {$this->invitation->team->name}")
            ->view('emails.team_invitation', [
                'invitation' => $this->invitation,
                'senderName' => $this->senderName,
                'teamsUrl' => $teamsUrl,
            ]);
    }
}
