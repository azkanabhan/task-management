<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\Team;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TaskService
{
    public function getDashboardTasks(
        int $userId,
        ?string $categoryId,
        ?string $status,
        ?string $search,
        int $perPage = 1
    ): LengthAwarePaginator {
        return Task::query()
            ->where('assign_to', $userId)
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->with('category')
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getAllCategories(): Collection
    {
        return TaskCategory::query()->orderBy('name')->get();
    }

    public function getAssignableUsers(): Collection
    {
        return User::query()->orderBy('name')->get();
    }

    public function getUserTeams(int $userId): Collection
    {
        return Team::query()
            ->whereHas('users', fn ($query) => $query
                ->where('users.id', $userId)
                ->where('team_users.status', 'accepted'))
            ->orderBy('name')
            ->get();
    }

    public function validateTaskData(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,done'],
            'deadline' => ['nullable', 'date'],
            'category_id' => ['nullable', 'exists:task_categories,id'],
            'assign_to' => ['nullable', 'exists:users,id'],
            'team_id' => ['nullable', 'exists:teams,id'],
        ]);

        return $validated;
    }

    public function createTaskForUser(int $userId, array $data): Task
    {
        $data['created_by'] = $userId;
        $data['assign_to'] = $data['assign_to'] ?? $userId;

        return Task::create($data);
    }

    public function getTaskForUser(int $userId, string $taskId): Task
    {
        return Task::query()
            ->where('id', $taskId)
            ->where(function ($query) use ($userId) {
                $query->where('created_by', $userId)
                    ->orWhere('assign_to', $userId);
            })
            ->firstOrFail();
    }

    public function updateTaskForUser(int $userId, string $taskId, array $data): Task
    {
        $task = $this->getTaskForUser($userId, $taskId);
        $task->update($data);

        return $task;
    }

    public function deleteTaskForUser(int $userId, string $taskId): void
    {
        $task = $this->getTaskForUser($userId, $taskId);
        $task->delete();
    }
}

