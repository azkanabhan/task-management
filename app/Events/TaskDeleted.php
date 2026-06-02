<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskDeleted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly string $taskTitle,
        public readonly int $deletedByUserId,
        public readonly ?int $assigneeId,
        public readonly int $creatorId,
    ) {
    }
}
