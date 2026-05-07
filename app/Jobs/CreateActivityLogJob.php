<?php

namespace App\Jobs;

use App\Models\ActivityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateActivityLogJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public int $userId,
        public string $action,
        public string $description,
        public ?string $subjectType = null,
        public ?int $subjectId = null,
        public ?array $properties = null,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        ActivityLog::query()->create([
            'user_id' => $this->userId,
            'action' => $this->action,
            'description' => $this->description,
            'subject_type' => $this->subjectType,
            'subject_id' => $this->subjectId,
            'properties' => $this->properties,
        ]);
    }
}
