<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    /**
     * Display the dashboard.
     */
    public function index()
    {
        $userId = (int) auth()->id();
        $user = auth()->user();

        // Task counts by status
        $taskCounts = [
            'total' => \App\Models\Task::where('created_by', $userId)->orWhere('assign_to', $userId)->count(),
            'pending' => \App\Models\Task::where(fn($q) => $q->where('created_by', $userId)->orWhere('assign_to', $userId))->where('status', 'pending')->count(),
            'in_progress' => \App\Models\Task::where(fn($q) => $q->where('created_by', $userId)->orWhere('assign_to', $userId))->where('status', 'in_progress')->count(),
            'done' => \App\Models\Task::where(fn($q) => $q->where('created_by', $userId)->orWhere('assign_to', $userId))->where('status', 'done')->count(),
        ];

        // Upcoming deadlines (next 7 days)
        $upcomingDeadlines = \App\Models\Task::where(fn($q) => $q->where('created_by', $userId)->orWhere('assign_to', $userId))
            ->where('status', '!=', 'done')
            ->whereNotNull('deadline')
            ->whereBetween('deadline', [now()->toDateString(), now()->addDays(7)->toDateString()])
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        // Recent activity logs
        $recentLogs = $this->activityLogService->getLogsForUser($userId, perPage: 5);

        // Team count
        $teamCount = $user->teams()->wherePivot('status', 'accepted')->count();

        return view('dashboard', compact('taskCounts', 'upcomingDeadlines', 'recentLogs', 'teamCount'));
    }

    /**
     * Display created and assigned tasks in separate sections.
     */
    public function tasks(Request $request)
    {
        $userId = (int) auth()->id();
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $search = $request->query('search');

        $createdTasks = $this->taskService->getCreatedTasks(
            userId: $userId,
            categoryId: $categoryId,
            status: $status,
            search: $search,
        );

        $assignedTasks = $this->taskService->getAssignedTasks(
            userId: $userId,
            categoryId: $categoryId,
            status: $status,
            search: $search,
        );

        $categories = $this->taskService->getAllCategories();

        return view('tasks.index', compact(
            'createdTasks',
            'assignedTasks',
            'categories',
            'categoryId',
            'status',
            'search',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->taskService->getAllCategories();
        $teams = $this->taskService->getUserTeamsWithMembers((int) auth()->id());
        $currentUserId = (int) auth()->id();

        return view('tasks.create', compact('categories', 'teams', 'currentUserId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->taskService->validateTaskData($request);
        $task = $this->taskService->createTaskForUser(auth()->id(), $validated);
        $this->activityLogService->log(
            userId: (int) auth()->id(),
            action: 'task.created',
            description: "Created task: {$task->title}",
            subject: $task,
            properties: [
                'assign_to' => $task->assign_to,
                'team_id' => $task->team_id,
            ]
        );

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = $this->taskService->getTaskForUser((int) auth()->id(), $id);
        Gate::authorize('update', $task);
        $task->load(['category', 'creator', 'assignee', 'team']);

        $categories = $this->taskService->getAllCategories();
        $teams = $this->taskService->getUserTeamsWithMembers((int) auth()->id());
        $currentUserId = (int) auth()->id();
        $canEditFull = Gate::allows('updateFull', $task);

        return view('tasks.edit', compact('task', 'categories', 'teams', 'currentUserId', 'canEditFull'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = $this->taskService->updateTaskForUser((int) auth()->id(), $id, $request);
        $this->activityLogService->log(
            userId: (int) auth()->id(),
            action: 'task.updated',
            description: "Updated task: {$task->title}",
            subject: $task,
            properties: [
                'assign_to' => $task->assign_to,
                'team_id' => $task->team_id,
                'status' => $task->status,
            ]
        );

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = $this->taskService->getTaskForUser((int) auth()->id(), $id);
        $this->taskService->deleteTaskForUser(auth()->id(), $id);
        $this->activityLogService->log(
            userId: (int) auth()->id(),
            action: 'task.deleted',
            description: "Deleted task: {$task->title}",
            subject: null,
            properties: [
                'task_id' => (int) $task->id,
                'title' => $task->title,
            ]
        );

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
