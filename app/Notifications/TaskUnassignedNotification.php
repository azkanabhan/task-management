<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUnassignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $taskTitle,
        public readonly int $updaterId,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $updater = User::find($this->updaterId);
        $updaterName = $updater ? $updater->name : 'Someone';

        return (new MailMessage)
            ->subject("Unassigned from Task: \"{$this->taskTitle}\"")
            ->view('emails.task_unassigned', [
                'taskTitle' => $this->taskTitle,
                'notifiable' => $notifiable,
                'updaterName' => $updaterName,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        $updater = User::find($this->updaterId);
        $updaterName = $updater ? $updater->name : 'Someone';

        return [
            'type'        => 'task_unassigned',
            'task_title'  => $this->taskTitle,
            'message'     => "You have been unassigned from task \"{$this->taskTitle}\" by {$updaterName}.",
            'url'         => route('tasks.index', [], false),
        ];
    }
}
