<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskNotification implements ShouldQueue
{
    public function handle(TaskCreated $event): void
    {
        $task = $event->task;

        // Only notify if the task is assigned to someone other than the creator
        if (
            $task->assign_to !== null
            && (int) $task->assign_to !== (int) $task->created_by
        ) {
            $assignee = User::find($task->assign_to);
            $assignee?->notify(new TaskAssignedNotification($task));
        }
    }
}
