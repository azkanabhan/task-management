<?php

namespace App\Services;

use App\Jobs\CreateActivityLogJob;
use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(
        int $userId,
        string $action,
        string $description,
        ?Model $subject = null,
        array $properties = []
    ): void {
        CreateActivityLogJob::dispatch(
            userId: $userId,
            action: $action,
            description: $description,
            subjectType: $subject ? $subject::class : null,
            subjectId: $subject ? (int) $subject->getKey() : null,
            properties: empty($properties) ? null : $properties,
        );
    }

    public function getLogsForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
}
