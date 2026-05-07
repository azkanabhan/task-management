<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Services\TaskService;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskService $taskService,
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $search = $request->query('search');

        $tasks = $this->taskService->getDashboardTasks(
            userId: auth()->id(),
            categoryId: $categoryId,
            status: $status,
            search: $search,
        );

        $categories = $this->taskService->getAllCategories();

        return view('dashboard', compact('tasks', 'categories', 'categoryId', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = $this->taskService->getAllCategories();
        $users = $this->taskService->getAssignableUsers();
        $teams = $this->taskService->getUserTeams((int) auth()->id());

        return view('tasks.create', compact('categories', 'users', 'teams'));
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

        return redirect()->route('dashboard')->with('success', 'Task created successfully.');
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
        $task = $this->taskService->getTaskForUser(auth()->id(), $id);
        $categories = $this->taskService->getAllCategories();
        $users = $this->taskService->getAssignableUsers();
        $teams = $this->taskService->getUserTeams((int) auth()->id());

        return view('tasks.edit', compact('task', 'categories', 'users', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $this->taskService->validateTaskData($request);
        $task = $this->taskService->updateTaskForUser(auth()->id(), $id, $validated);
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

        return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
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

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully.');
    }
}
