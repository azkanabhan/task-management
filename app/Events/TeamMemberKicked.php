<?php

namespace App\Events;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamMemberKicked
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Team $team,
        public User $member,
    ) {
    }
}
