<?php

namespace App\Listeners;

use App\Events\TaskUpdated;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use App\Notifications\TaskUnassignedNotification;
use App\Notifications\TaskUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTaskUpdatedNotification implements ShouldQueue
{
    public function handle(TaskUpdated $event): void
    {
        $task = $event->task;
        $updaterId = $event->updatedByUserId;
        $prevAssigneeId = $event->previousAssigneeId;
        $currentAssigneeId = $task->assign_to !== null ? (int) $task->assign_to : null;

        // Case 1: Assignee changed
        if ($prevAssigneeId !== $currentAssigneeId) {
            // Notify the previous assignee (if it wasn't the updater themselves)
            if ($prevAssigneeId !== null && $prevAssigneeId !== $updaterId) {
                $prevAssignee = User::find($prevAssigneeId);
                $prevAssignee?->notify(new TaskUnassignedNotification($task->title, $updaterId));
            }

            // Notify the new assignee (if there is one, and it's not the updater/creator)
            if ($currentAssigneeId !== null && $currentAssigneeId !== (int) $task->created_by) {
                $newAssignee = User::find($currentAssigneeId);
                $newAssignee?->notify(new TaskAssignedNotification($task));
            }
        } 
        // Case 2: Assignee remained the same
        else {
            // Only notify if there is an assignee and they are not the updater
            if ($currentAssigneeId !== null && $currentAssigneeId !== $updaterId) {
                $assignee = User::find($currentAssigneeId);
                $assignee?->notify(new TaskUpdatedNotification($task, $updaterId));
            }
        }
    }
}
