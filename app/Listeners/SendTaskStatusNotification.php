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
        $updaterId = $event->updatedByUserId;

        // Notify the task creator only if the update was made by someone else
        if ((int) $task->created_by !== $updaterId) {
            $creator = User::find($task->created_by);
            $creator?->notify(new TaskStatusUpdatedNotification(
                task: $task,
                newStatus: $task->status,
                updatedByUserId: $updaterId,
            ));
        } else {
            // If the update was made by the creator, notify the assignee (if there is one, and it's not the creator)
            $assigneeId = $task->assign_to !== null ? (int) $task->assign_to : null;
            if ($assigneeId !== null && $assigneeId !== (int) $task->created_by) {
                $assignee = User::find($assigneeId);
                $assignee?->notify(new TaskStatusUpdatedNotification(
                    task: $task,
                    newStatus: $task->status,
                    updatedByUserId: $updaterId,
                ));
            }
        }
    }
}
