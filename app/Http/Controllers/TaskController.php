<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryId = $request->query('category_id');
        $status = $request->query('status');
        $search = $request->query('search');

        $tasks = Task::query()
            ->where('user_id', auth()->id())
            ->filter($categoryId, $status, $search)
            ->with('category')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $categories = TaskCategory::query()->orderBy('name')->get();

        return view('dashboard', compact('tasks', 'categories', 'categoryId', 'status', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = TaskCategory::query()->orderBy('name')->get();

        return view('tasks.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,done'],
            'deadline' => ['nullable', 'date'],
            'category_id' => ['nullable', 'exists:task_categories,id'],
        ]);

        $validated['user_id'] = auth()->id();

        Task::create($validated);

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
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $categories = TaskCategory::query()->orderBy('name')->get();

        return view('tasks.edit', compact('task', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'in:pending,in_progress,done'],
            'deadline' => ['nullable', 'date'],
            'category_id' => ['nullable', 'exists:task_categories,id'],
        ]);

        $task->update($validated);

        return redirect()->route('dashboard')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::where('user_id', auth()->id())->findOrFail($id);
        $task->delete();

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully.');
    }
}
