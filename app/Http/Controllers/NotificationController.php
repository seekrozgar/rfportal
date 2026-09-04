<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display notifications.
     */
    public function index(Request $request)
    {
        $notifications = Notification::where(
            'user_id',
            $request->user()->id
        )
            ->latest()
            ->paginate(20);

        return view(
            'notifications.index',
            compact('notifications')
        );
    }


    /**
     * Return latest notifications for notification bell.
     */
    public function latest(Request $request): JsonResponse
    {
        try {

            $userId =
                $request->user()->id;


            $notifications =
                Notification::where(
                    'user_id',
                    $userId
                )
                    ->latest()
                    ->take(10)
                    ->get()
                    ->map(function (Notification $notification) {

                        return [
                            'id' =>
                                $notification->getKey(),

                            'type' =>
                                $notification->type ?? 'info',

                            'title' =>
                                $notification->title ??
                                'Notification',

                            'message' =>
                                $notification->message ??
                                '',

                            'icon' =>
                                $notification->icon ??
                                'bell',

                            'url' =>
                                $notification->action_url,

                            'time' =>
                                $notification->created_at
                                    ? $notification
                                        ->created_at
                                        ->diffForHumans()
                                    : '',

                            'read' =>
                                !is_null(
                                    $notification->read_at
                                ),

                            'read_at' =>
                                $notification->read_at,
                        ];

                    })
                    ->values();


            $unreadCount =
                Notification::where(
                    'user_id',
                    $userId
                )
                    ->whereNull('read_at')
                    ->count();


            return response()->json([
                'success' =>
                    true,

                'notifications' =>
                    $notifications,

                'unread_count' =>
                    $unreadCount,
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'Notification latest API error.',
                [
                    'user_id' =>
                        $request->user()->id ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' =>
                    false,

                'message' =>
                    'Unable to load notifications.',

                'notifications' =>
                    [],

                'unread_count' =>
                    0,
            ], 500);
        }
    }


    /**
     * Mark one notification as read.
     */
    public function markRead(
        Request $request,
        $notification
    ): JsonResponse {

        try {

            $userId =
                $request->user()->id;


            $notificationRecord =
                Notification::whereKey(
                    $notification
                )
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->first();


            if (!$notificationRecord) {

                return response()->json([
                    'success' =>
                        false,

                    'message' =>
                        'Notification not found.',
                ], 404);
            }


            $notificationRecord->markAsRead();


            $unreadCount =
                Notification::where(
                    'user_id',
                    $userId
                )
                    ->whereNull('read_at')
                    ->count();


            return response()->json([
                'success' =>
                    true,

                'message' =>
                    'Notification marked as read.',

                'unread_count' =>
                    $unreadCount,
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'Notification mark-read error.',
                [
                    'notification_id' =>
                        $notification,

                    'user_id' =>
                        $request->user()->id ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' =>
                    false,

                'message' =>
                    'Unable to mark notification as read.',
            ], 500);
        }
    }


    /**
     * Mark all notifications as read.
     */
    public function markAllRead(
        Request $request
    ): JsonResponse {

        try {

            $userId =
                $request->user()->id;


            Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->update([
                    'read_at' =>
                        now(),
                ]);


            return response()->json([
                'success' =>
                    true,

                'message' =>
                    'All notifications marked as read.',

                'unread_count' =>
                    0,
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'Notification mark-all-read error.',
                [
                    'user_id' =>
                        $request->user()->id ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' =>
                    false,

                'message' =>
                    'Unable to mark all notifications as read.',
            ], 500);
        }
    }


    /**
     * Delete one notification.
     */
    public function destroy(
        Request $request,
        $notification
    ): JsonResponse {

        try {

            $userId =
                $request->user()->id;


            $notificationRecord =
                Notification::whereKey(
                    $notification
                )
                    ->where(
                        'user_id',
                        $userId
                    )
                    ->first();


            if (!$notificationRecord) {

                return response()->json([
                    'success' =>
                        false,

                    'message' =>
                        'Notification not found.',
                ], 404);
            }


            $notificationRecord->delete();


            $unreadCount =
                Notification::where(
                    'user_id',
                    $userId
                )
                    ->whereNull('read_at')
                    ->count();


            return response()->json([
                'success' =>
                    true,

                'message' =>
                    'Notification deleted successfully.',

                'unread_count' =>
                    $unreadCount,
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'Notification delete error.',
                [
                    'notification_id' =>
                        $notification,

                    'user_id' =>
                        $request->user()->id ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' =>
                    false,

                'message' =>
                    'Unable to delete notification.',
            ], 500);
        }
    }


    /**
     * Delete all notifications.
     */
    public function destroyAll(
        Request $request
    ): JsonResponse {

        try {

            $userId =
                $request->user()->id;


            $deletedCount =
                Notification::where(
                    'user_id',
                    $userId
                )->delete();


            return response()->json([
                'success' =>
                    true,

                'message' =>
                    $deletedCount > 0
                        ? 'All notifications deleted successfully.'
                        : 'There are no notifications to delete.',

                'deleted_count' =>
                    $deletedCount,

                'unread_count' =>
                    0,
            ]);


        } catch (\Throwable $e) {

            Log::error(
                'Notification delete-all error.',
                [
                    'user_id' =>
                        $request->user()->id ?? null,

                    'error' =>
                        $e->getMessage(),
                ]
            );


            return response()->json([
                'success' =>
                    false,

                'message' =>
                    'Unable to delete all notifications.',
            ], 500);
        }
    }
}
