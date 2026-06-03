<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Resend\Laravel\Facades\Resend;

class SendLoginNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public User $user,
        public string $ip,
        public string $userAgent,
        public string $time,
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $html = view('emails.login_notification', [
            'user' => $this->user,
            'ip' => $this->ip,
            'userAgent' => $this->userAgent,
            'time' => $this->time,
        ])->render();

        Resend::emails()->send([
            'from' => 'Azka Nabhan <admin@azkanabhan.space>',
            'to' => [$this->user->email],
            'subject' => 'Successful Login Notification',
            'html' => $html,
        ]);
    }
}
