<?php

namespace App\Services;

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
    ): ActivityLog {
        return ActivityLog::query()->create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? $subject::class : null,
            'subject_id' => $subject?->getKey(),
            'properties' => empty($properties) ? null : $properties,
        ]);
    }

    public function getLogsForUser(int $userId, int $perPage = 20): LengthAwarePaginator
    {
        return ActivityLog::query()
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
}
