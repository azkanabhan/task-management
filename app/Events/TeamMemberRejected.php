<?php

namespace App\Events;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TeamMemberRejected
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Team $team,
        public readonly User $member,
    ) {
    }
}
