<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Resend\Laravel\Facades\Resend;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $html = view('emails.login_notification', [
            'user' => $request->user(),
            'ip' => $request->ip(),
            'userAgent' => $request->userAgent(),
            'time' => now()->toDayDateTimeString(),
        ])->render();

        Resend::emails()->send([
            'from' => 'Azka Nabhan <admin@azkanabhan.space>',
            'to' => [$request->user()->email],
            'subject' => 'Successful Login Notification',
            'html' => $html,
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
