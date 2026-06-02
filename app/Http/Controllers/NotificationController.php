<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    /**
     * Display a paginated list of the user's notifications.
     */
    public function index(Request $request): View
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(15);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Return unread notifications count and latest 5 for the bell dropdown (JSON).
     */
    public function unread(Request $request): JsonResponse
    {
        $user = $request->user();

        $unreadCount = $user->unreadNotifications()->count();

        $latest = $user->unreadNotifications()
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($n) => [
                'id'         => $n->id,
                'message'    => $n->data['message'] ?? 'New notification',
                'url'        => $n->data['url'] ?? '#',
                'created_at' => $n->created_at->diffForHumans(),
            ]);

        return response()->json([
            'count'         => $unreadCount,
            'notifications' => $latest,
        ]);
    }

    /**
     * Mark a single notification as read and redirect to its URL.
     */
    public function markAsRead(Request $request, string $id): RedirectResponse
    {
        $notification = $request->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('notifications.index');

        return redirect($url);
    }

    /**
     * Mark all unread notifications as read.
     */
    public function markAllRead(Request $request): RedirectResponse
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return back()->with('success','All notifications marked as read.');
    }
}
