<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskDeletedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly string $taskTitle,
        public readonly int $deleterId,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $deleter = User::find($this->deleterId);
        $deleterName = $deleter ? $deleter->name : 'Someone';

        return (new MailMessage)
            ->subject("Task Deleted: \"{$this->taskTitle}\"")
            ->view('emails.task_deleted', [
                'taskTitle' => $this->taskTitle,
                'notifiable' => $notifiable,
                'deleterName' => $deleterName,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        $deleter = User::find($this->deleterId);
        $deleterName = $deleter ? $deleter->name : 'Someone';

        return [
            'type'       => 'task_deleted',
            'task_title' => $this->taskTitle,
            'message'    => "Task \"{$this->taskTitle}\" was deleted by {$deleterName}.",
            'url'        => route('tasks.index', [], false),
        ];
    }
}
