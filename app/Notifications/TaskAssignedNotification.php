<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Task $task)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Task Assigned: \"{$this->task->title}\"")
            ->view('emails.task_assigned', [
                'task' => $this->task,
                'notifiable' => $notifiable,
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'task_assigned',
            'task_id'     => $this->task->id,
            'task_title'  => $this->task->title,
            'message'     => "You have been assigned a new task: \"{$this->task->title}\".",
            'assigned_by' => $this->task->created_by,
            'url'         => route('tasks.edit', $this->task->id, false),
        ];
    }
}
