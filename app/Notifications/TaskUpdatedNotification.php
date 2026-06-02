<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Task $task,
        public readonly int $updaterId,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $updater = User::find($this->updaterId);
        $updaterName = $updater ? $updater->name : 'Someone';

        return [
            'type'       => 'task_updated',
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
            'message'    => "Task \"{$this->task->title}\" was updated by {$updaterName}.",
            'url'        => route('tasks.edit', $this->task->id, false),
        ];
    }
}
