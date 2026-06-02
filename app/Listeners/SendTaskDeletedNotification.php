<?php

namespace App\Listeners;

use App\Events\TaskDeleted;
use App\Models\User;
use App\Notifications\TaskDeletedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskDeletedNotification implements ShouldQueue
{
    public function handle(TaskDeleted $event): void
    {
        $assigneeId = $event->assigneeId;
        $deleterId = $event->deletedByUserId;

        // If the task was assigned to someone other than the deleter, notify them
        if ($assigneeId !== null && $assigneeId !== $deleterId) {
            $assignee = User::find($assigneeId);
            $assignee?->notify(new TaskDeletedNotification($event->taskTitle, $deleterId));
        }
    }
}
