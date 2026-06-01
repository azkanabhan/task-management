<?php

namespace App\Providers;

use App\Events\TaskCreated;
use App\Events\TaskStatusUpdated;
use App\Events\TeamJoinRequested;
use App\Events\TeamMemberApproved;
use App\Events\TeamMemberRejected;
use App\Listeners\LogAuthActivity;
use App\Listeners\SendTaskNotification;
use App\Listeners\SendTaskStatusNotification;
use App\Listeners\SendTeamNotification;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auth activity logging
        Event::listen(Login::class, [LogAuthActivity::class, 'handleLogin']);
        Event::listen(Logout::class, [LogAuthActivity::class, 'handleLogout']);
        Event::listen(Registered::class, [LogAuthActivity::class, 'handleRegistered']);
        Event::listen(PasswordReset::class, [LogAuthActivity::class, 'handlePasswordReset']);
        Event::listen(Verified::class, [LogAuthActivity::class, 'handleVerified']);

        // Task notifications
        Event::listen(TaskCreated::class, SendTaskNotification::class);
        Event::listen(TaskStatusUpdated::class, SendTaskStatusNotification::class);

        // Team notifications
        Event::listen(TeamJoinRequested::class, [SendTeamNotification::class, 'handleJoinRequested']);
        Event::listen(TeamMemberApproved::class, [SendTeamNotification::class, 'handleMemberApproved']);
        Event::listen(TeamMemberRejected::class, [SendTeamNotification::class, 'handleMemberRejected']);
    }
}
