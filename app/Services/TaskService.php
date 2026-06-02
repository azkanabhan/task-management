<?php

namespace App\Services;

use App\Events\TaskCreated;
use App\Events\TaskStatusUpdated;
use App\Events\TaskUpdated;
use App\Events\TaskDeleted;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\Team;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskService
{
    public function getDashboardTasks(
        int $userId,
        ?string $categoryId,
        ?string $status,
        ?string $search,
        int $perPage = 10
    ): LengthAwarePaginator {
        return $this->getTasksForUser($userId, 'assigned', $categoryId, $status, $search, $perPage);
    }

    public function getCreatedTasks(
        int $userId,
        ?string $categoryId,
        ?string $status,
        ?string $search,
        int $perPage = 10,
        string $pageName = 'created_page',
    ): LengthAwarePaginator {
        return $this->getTasksForUser($userId, 'created', $categoryId, $status, $search, $perPage, $pageName);
    }

    public function getAssignedTasks(
        int $userId,
        ?string $categoryId,
        ?string $status,
        ?string $search,
        int $perPage = 10,
        string $pageName = 'assigned_page',
    ): LengthAwarePaginator {
        return $this->getTasksForUser($userId, 'assigned', $categoryId, $status, $search, $perPage, $pageName);
    }

    public function getTasksForUser(
        int $userId,
        string $scope,
        ?string $categoryId,
        ?string $status,
        ?string $search,
        int $perPage = 10,
        string $pageName = 'page',
    ): LengthAwarePaginator {
        $relations = ['category'];

        if ($scope === 'created') {
            $relations[] = 'assignee';
        } else {
            $relations[] = 'creator';
        }

        return Task::query()
            ->when($scope === 'created', fn ($q) => $q->where('created_by', $userId))
            ->when($scope === 'assigned', fn ($q) => $q->where('assign_to', $userId))
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"))
            ->with($relations)
            ->latest()
            ->paginate($perPage, ['*'], $pageName)
            ->withQueryString();
    }

    public function getAllCategories(): Collection
    {
        return TaskCategory::query()->orderBy('name')->get();
    }

    public function getUserTeamsWithMembers(int $userId): Collection
    {
        return Team::query()
            ->whereHas('acceptedUsers', fn ($query) => $query->where('users.id', $userId))
            ->with(['acceptedUsers' => fn ($query) => $query->orderBy('name')])
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
        $data = $this->resolveTaskAssignment($userId, $data);
        $data['created_by'] = $userId;

        $task = Task::create($data);

        TaskCreated::dispatch($task);

        return $task;
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

    public function updateTaskForUser(int $userId, string $taskId, Request $request): Task
    {
        $task = $this->getTaskForUser($userId, $taskId);
        Gate::authorize('update', $task);

        $previousStatus = $task->status;
        $previousAssigneeId = $task->assign_to !== null ? (int) $task->assign_to : null;
        $wasFullUpdate = false;

        if (Gate::allows('updateFull', $task)) {
            $data = $this->validateTaskData($request);
            $data = $this->resolveTaskAssignment($userId, $data);
            $task->update($data);
            $wasFullUpdate = true;
        } elseif (Gate::allows('updateStatus', $task)) {
            $data = $request->validate([
                'status' => ['required', 'in:pending,in_progress,done'],
            ]);
            $task->update($data);
        } else {
            abort(403);
        }

        $task->refresh();

        if ($task->status !== $previousStatus) {
            TaskStatusUpdated::dispatch($task, $userId);
        }

        if ($wasFullUpdate) {
            TaskUpdated::dispatch($task, $userId, $previousAssigneeId);
        }

        return $task;
    }

    public function deleteTaskForUser(int $userId, string $taskId): void
    {
        $task = $this->getTaskForUser($userId, $taskId);
        Gate::authorize('delete', $task);

        $taskTitle = $task->title;
        $assigneeId = $task->assign_to !== null ? (int) $task->assign_to : null;
        $creatorId = (int) $task->created_by;

        $task->delete();

        TaskDeleted::dispatch($taskTitle, $userId, $assigneeId, $creatorId);
    }

    public function resolveTaskAssignment(int $userId, array $data): array
    {
        $teamId = ! empty($data['team_id']) ? (int) $data['team_id'] : null;

        if ($teamId === null) {
            $data['team_id'] = null;
            $data['assign_to'] = $userId;
            Gate::authorize('assign', [Task::class, null, $userId]);

            return $data;
        }

        $this->assertUserBelongsToTeam($userId, $teamId);

        $assignTo = ! empty($data['assign_to']) ? (int) $data['assign_to'] : $userId;

        Gate::authorize('assign', [Task::class, $teamId, $assignTo]);

        $data['team_id'] = $teamId;
        $data['assign_to'] = $assignTo;

        return $data;
    }

    private function assertUserBelongsToTeam(int $userId, int $teamId): void
    {
        $belongs = Team::query()
            ->whereKey($teamId)
            ->whereHas('acceptedUsers', fn ($query) => $query->where('users.id', $userId))
            ->exists();

        if (! $belongs) {
            abort(403);
        }
    }
}
