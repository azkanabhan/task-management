<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskStatusUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Task $task,
        public readonly string $newStatus,
        public readonly int $updatedByUserId,
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $statusLabel = match ($this->newStatus) {
            'pending'     => 'Pending',
            'in_progress' => 'In Progress',
            'done'        => 'Done',
            default       => ucfirst($this->newStatus),
        };

        return [
            'type'       => 'task_status_updated',
            'task_id'    => $this->task->id,
            'task_title' => $this->task->title,
            'message'    => "Your task \"{$this->task->title}\" status was updated to \"{$statusLabel}\".",
            'new_status' => $this->newStatus,
            'url'        => route('tasks.edit', $this->task->id),
        ];
    }
}
