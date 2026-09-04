<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display employer notifications.
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
            'employer.notifications.index',
            compact('notifications')
        );
    }


    /**
     * Mark a single notification as read.
     */
    public function markRead(
        Request $request,
        $notification
    ): JsonResponse {
        try {

            $userId = $request->user()->id;

            $notificationRecord = Notification::whereKey($notification)
                ->where('user_id', $userId)
                ->first();

            if (!$notificationRecord) {

                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }


            if (!$notificationRecord->read_at) {

                $notificationRecord->update([
                    'read_at' => now(),
                ]);
            }


            $unreadCount = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->count();


            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Employer notification mark-read error.',
                [
                    'notification_id' => $notification,
                    'user_id' => $request->user()->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,
                'message' => 'Unable to mark notification as read.',
            ], 500);
        }
    }


    /**
     * Delete a single notification.
     */
    public function destroy(
        Request $request,
        $notification
    ): JsonResponse {
        try {

            $userId = $request->user()->id;

            $notificationRecord = Notification::whereKey($notification)
                ->where('user_id', $userId)
                ->first();

            if (!$notificationRecord) {

                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }


            $notificationRecord->delete();


            $unreadCount = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->count();


            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Employer notification delete error.',
                [
                    'notification_id' => $notification,
                    'user_id' => $request->user()->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,
                'message' => 'Unable to delete notification.',
            ], 500);
        }
    }


    /**
     * Mark all employer notifications as read.
     */
    public function markAllRead(Request $request): JsonResponse
    {
        try {

            $userId = $request->user()->id;

            Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                ]);


            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read.',
                'unread_count' => 0,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Employer mark-all-read error.',
                [
                    'user_id' => $request->user()->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,
                'message' => 'Unable to mark all notifications as read.',
            ], 500);
        }
    }


    /**
     * Delete all employer notifications.
     */
    public function destroyAll(Request $request): JsonResponse
    {
        try {

            $userId = $request->user()->id;

            $deletedCount = Notification::where(
                'user_id',
                $userId
            )->delete();


            return response()->json([
                'success' => true,
                'message' => $deletedCount > 0
                    ? 'All notifications deleted successfully.'
                    : 'There are no notifications to delete.',
                'deleted_count' => $deletedCount,
                'unread_count' => 0,
            ]);

        } catch (\Throwable $e) {

            Log::error(
                'Employer delete-all notifications error.',
                [
                    'user_id' => $request->user()->id ?? null,
                    'error' => $e->getMessage(),
                ]
            );


            return response()->json([
                'success' => false,
                'message' => 'Unable to delete all notifications.',
            ], 500);
        }
    }
}
