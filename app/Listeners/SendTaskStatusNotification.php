<?php

namespace App\Listeners;

use App\Events\TaskStatusUpdated;
use App\Models\User;
use App\Notifications\TaskStatusUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskStatusNotification implements ShouldQueue
{
    public function handle(TaskStatusUpdated $event): void
    {
        $task = $event->task;

        // Notify the task creator only if the update was made by someone else
        if ((int) $task->created_by !== $event->updatedByUserId) {
            $creator = User::find($task->created_by);
            $creator?->notify(new TaskStatusUpdatedNotification(
                task: $task,
                newStatus: $task->status,
                updatedByUserId: $event->updatedByUserId,
            ));
        }
    }
}
