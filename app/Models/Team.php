<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'img_profile', 'description'])]
class Team extends Model
{
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->withPivot(['role', 'joined_at', 'status'])
            ->withTimestamps();
    }

    public function pendingUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->wherePivot('status', 'pending')
            ->withPivot(['role', 'joined_at', 'status'])
            ->withTimestamps();
    }
}
