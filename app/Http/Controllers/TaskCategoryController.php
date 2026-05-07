<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use App\Services\TaskCategoryService;
use Illuminate\Http\Request;

class TaskCategoryController extends Controller
{
    public function __construct(
        private readonly TaskCategoryService $taskCategoryService,
        private readonly ActivityLogService $activityLogService,
    ) {
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->taskCategoryService->validateCategoryData($request);
        $category = $this->taskCategoryService->createCategory($validated);
        $this->activityLogService->log(
            userId: (int) auth()->id(),
            action: 'category.created',
            description: "Created category: {$category->name}",
            subject: $category
        );

        return redirect()->route('dashboard')->with('success', 'Category created successfully.');
    }
}
