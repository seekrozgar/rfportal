<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Return latest notifications for bell dropdown.
     */
    public function latest(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(10)
            ->get()
            ->map(function (Notification $notification) {

                return [
                    'id' => $notification->id,
                    'type' => $notification->type,
                    'title' => $notification->title,
                    'message' => $notification->message,
                    'icon' => $notification->icon ?: 'bell',
                    'url' => $notification->action_url,
                    'read' => !is_null($notification->read_at),
                    'time' => $notification->created_at->diffForHumans(),
                ];
            });

        $unread = $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->count();

        return response()->json([
            'success' => true,
            'notifications' => $notifications,
            'unread' => $unread,
        ]);
    }

    /**
     * Mark one notification as read.
     */
    public function markRead(Request $request, Notification $notification)
    {
        abort_unless(
            $notification->user_id === $request->user()->id,
            403
        );

        $notification->markAsRead();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * Mark all current user's notifications as read.
     */
    public function markAllRead(Request $request)
    {
        $request->user()
            ->notifications()
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Notification page.
     */
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }
}
