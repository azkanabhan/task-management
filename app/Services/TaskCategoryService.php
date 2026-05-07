<?php

namespace App\Services;

use App\Models\TaskCategory;
use Illuminate\Http\Request;

class TaskCategoryService
{
    public function validateCategoryData(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
    }

    public function createCategory(array $data): TaskCategory
    {
        return TaskCategory::create($data);
    }
}

