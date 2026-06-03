<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'img_profile', 'description', 'code'])]
class Team extends Model
{
    protected static function booted(): void
    {
        static::creating(function (Team $team) {
            if (empty($team->code)) {
                do {
                    $code = 'T-' . strtoupper(\Illuminate\Support\Str::random(8));
                } while (static::where('code', $code)->exists());

                $team->code = $code;
            }
        });
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(TeamInvitation::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->withPivot(['role', 'joined_at', 'status'])
            ->withTimestamps();
    }

    public function acceptedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'team_users')
            ->wherePivot('status', 'accepted')
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
