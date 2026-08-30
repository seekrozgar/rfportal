<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Return latest notifications for current user.
     */
    public function latest(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'notifications' => [],
                    'unread_count' => 0,
                ], 401);
            }

            /*
             * Laravel's built-in database notifications.
             *
             * Important:
             * This assumes users table has Laravel notifications
             * relationship available through Notifiable trait.
             */
            $notifications = $user->notifications()
                ->latest()
                ->take(10)
                ->get()
                ->map(function ($notification) {
                    $data = is_array($notification->data)
                        ? $notification->data
                        : [];

                    return [
                        'id' => $notification->id,

                        'type' => $data['type'] ?? 'info',

                        'icon' => $data['icon'] ?? 'bell',

                        'message' => $data['message']
                            ?? $data['title']
                            ?? 'You have a new notification.',

                        'title' => $data['title'] ?? 'Notification',

                        'url' => $data['url'] ?? null,

                        'time' => $notification->created_at
                            ? $notification->created_at->diffForHumans()
                            : '',

                        'read' => !is_null($notification->read_at),
                    ];
                });

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {

            \Log::error('Latest notifications failed', [
                'user_id' => optional($request->user())->id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to load notifications.',
                'notifications' => [],
                'unread_count' => 0,
            ], 500);
        }
    }


    /**
     * Mark all notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        try {

            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            $user->unreadNotifications->markAsRead();

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'unread_count' => 0,
            ]);

        } catch (\Throwable $e) {

            \Log::error('Mark notifications read failed', [
                'user_id' => optional($request->user())->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to update notifications.',
            ], 500);
        }
    }
}
