<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;

class LogAuthActivity
{
    public function __construct(
        private readonly ActivityLogService $activityLogService
    ) {
    }

    public function handleLogin(Login $event): void
    {
        if ($event->user) {
            $this->activityLogService->log(
                userId: (int) $event->user->id,
                action: 'auth.login',
                description: 'User logged in.',
                subject: $event->user,
                properties: ['guard' => $event->guard]
            );
        }
    }

    public function handleLogout(Logout $event): void
    {
        if ($event->user) {
            $this->activityLogService->log(
                userId: (int) $event->user->id,
                action: 'auth.logout',
                description: 'User logged out.',
                subject: $event->user,
                properties: ['guard' => $event->guard]
            );
        }
    }

    public function handleRegistered(Registered $event): void
    {
        if ($event->user) {
            $this->activityLogService->log(
                userId: (int) $event->user->id,
                action: 'auth.registered',
                description: 'User registered a new account.',
                subject: $event->user
            );
        }
    }

    public function handlePasswordReset(PasswordReset $event): void
    {
        if ($event->user) {
            $this->activityLogService->log(
                userId: (int) $event->user->id,
                action: 'auth.password_reset',
                description: 'User reset their password.',
                subject: $event->user
            );
        }
    }

    public function handleVerified(Verified $event): void
    {
        if ($event->user) {
            $this->activityLogService->log(
                userId: (int) $event->user->id,
                action: 'auth.email_verified',
                description: 'User verified their email address.',
                subject: $event->user
            );
        }
    }
}
