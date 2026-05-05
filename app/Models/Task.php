<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['title', 'description', 'status', 'deadline', 'category_id', 'user_id'])]
class Task extends Model
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class);
    }

    public function scopeFilter($query, $categoryId, $status, $search)
    {
        return $query
            ->when($categoryId, fn ($q) => $q->where('category_id', $categoryId))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where('title', 'like', "%{$search}%"));
    }

}
