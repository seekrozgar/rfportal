<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Display admin notifications.
     */
    public function index(Request $request)
    {
        Log::info('🔔 ADMIN NOTIFICATIONS - INDEX STARTED', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'ip' => $request->ip(),
        ]);

        try {
            $notifications = Notification::where(
                'user_id',
                $request->user()->id
            )
                ->latest()
                ->paginate(20);

            $unreadCount = Notification::where(
                'user_id',
                $request->user()->id
            )
                ->whereNull('read_at')
                ->count();

            Log::info('✅ ADMIN NOTIFICATIONS - INDEX SUCCESS', [
                'user_id' => $request->user()->id,
                'total' => $notifications->total(),
                'unread_count' => $unreadCount,
            ]);

            return view(
                'admin.notifications.index',
                compact('notifications', 'unreadCount')
            );

        } catch (\Throwable $e) {
            Log::error('❌ ADMIN NOTIFICATIONS - INDEX ERROR', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }

    /**
     * Mark a single notification as read.
     */
    public function markSingleRead(Request $request, $id): JsonResponse
    {
        Log::info('🔔 ADMIN NOTIFICATIONS - MARK SINGLE READ STARTED', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'notification_id' => $id,
            'ip' => $request->ip(),
        ]);

        try {
            $userId = $request->user()->id;

            Log::debug('🔍 ADMIN - Finding notification', [
                'notification_id' => $id,
                'user_id' => $userId,
            ]);

            $notificationRecord = Notification::whereKey($id)
                ->where('user_id', $userId)
                ->first();

            if (!$notificationRecord) {
                Log::warning('⚠️ ADMIN - Notification not found', [
                    'notification_id' => $id,
                    'user_id' => $userId,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            Log::debug('🔍 ADMIN - Notification found', [
                'notification_id' => $id,
                'read_at' => $notificationRecord->read_at,
                'title' => $notificationRecord->title,
            ]);

            if (!$notificationRecord->read_at) {
                $notificationRecord->update([
                    'read_at' => now(),
                ]);

                Log::info('✅ ADMIN - Notification marked as read', [
                    'notification_id' => $id,
                    'user_id' => $userId,
                    'read_at' => now(),
                ]);
            } else {
                Log::info('ℹ️ ADMIN - Notification already read', [
                    'notification_id' => $id,
                    'read_at' => $notificationRecord->read_at,
                ]);
            }

            $unreadCount = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->count();

            Log::info('📊 ADMIN - Updated unread count', [
                'user_id' => $userId,
                'unread_count' => $unreadCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read.',
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ ADMIN - Mark single read error', [
                'notification_id' => $id,
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to mark notification as read.',
            ], 500);
        }
    }

    /**
     * Mark all admin notifications as read.
     */
    public function markRead(Request $request): JsonResponse
    {
        Log::info('🔔 ADMIN NOTIFICATIONS - MARK ALL READ STARTED', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'ip' => $request->ip(),
        ]);

        try {
            $userId = $request->user()->id;

            Log::debug('🔍 ADMIN - Checking unread notifications', [
                'user_id' => $userId,
            ]);

            $unreadBefore = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->count();

            Log::info('📊 ADMIN - Unread before update', [
                'user_id' => $userId,
                'unread_count' => $unreadBefore,
            ]);

            $updated = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->update([
                    'read_at' => now(),
                ]);

            Log::info('✅ ADMIN - Mark all read updated', [
                'user_id' => $userId,
                'updated_count' => $updated,
            ]);

            return response()->json([
                'success' => true,
                'message' => "All notifications marked as read. ({$updated} updated)",
                'unread_count' => 0,
                'updated_count' => $updated,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ ADMIN - Mark all read error', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to mark all notifications as read.',
            ], 500);
        }
    }

    /**
     * Delete a single notification.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        Log::info('🔔 ADMIN NOTIFICATIONS - DELETE SINGLE STARTED', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'notification_id' => $id,
            'ip' => $request->ip(),
        ]);

        try {
            $userId = $request->user()->id;

            Log::debug('🔍 ADMIN - Finding notification to delete', [
                'notification_id' => $id,
                'user_id' => $userId,
            ]);

            $notificationRecord = Notification::whereKey($id)
                ->where('user_id', $userId)
                ->first();

            if (!$notificationRecord) {
                Log::warning('⚠️ ADMIN - Notification not found for deletion', [
                    'notification_id' => $id,
                    'user_id' => $userId,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found.',
                ], 404);
            }

            Log::debug('🔍 ADMIN - Notification found for deletion', [
                'notification_id' => $id,
                'title' => $notificationRecord->title,
            ]);

            $notificationRecord->delete();

            Log::info('✅ ADMIN - Notification deleted', [
                'notification_id' => $id,
                'user_id' => $userId,
            ]);

            $unreadCount = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->count();

            Log::info('📊 ADMIN - Updated unread count after delete', [
                'user_id' => $userId,
                'unread_count' => $unreadCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully.',
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ ADMIN - Delete single error', [
                'notification_id' => $id,
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete notification.',
            ], 500);
        }
    }

    /**
     * Delete all admin notifications.
     */
    public function destroyAll(Request $request): JsonResponse
    {
        Log::info('🔔 ADMIN NOTIFICATIONS - DELETE ALL STARTED', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'ip' => $request->ip(),
        ]);

        try {
            $userId = $request->user()->id;

            Log::debug('🔍 ADMIN - Counting notifications to delete', [
                'user_id' => $userId,
            ]);

            $countBefore = Notification::where(
                'user_id',
                $userId
            )->count();

            Log::info('📊 ADMIN - Notifications before delete', [
                'user_id' => $userId,
                'count' => $countBefore,
            ]);

            $deletedCount = Notification::where(
                'user_id',
                $userId
            )->delete();

            Log::info('✅ ADMIN - All notifications deleted', [
                'user_id' => $userId,
                'deleted_count' => $deletedCount,
            ]);

            return response()->json([
                'success' => true,
                'message' => $deletedCount > 0
                    ? "All notifications deleted successfully. ({$deletedCount} deleted)"
                    : 'There are no notifications to delete.',
                'deleted_count' => $deletedCount,
                'unread_count' => 0,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ ADMIN - Delete all error', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to delete all notifications.',
            ], 500);
        }
    }

    /**
     * Get latest notifications for admin bell.
     */
    public function latest(Request $request): JsonResponse
    {
        Log::info('🔔 ADMIN NOTIFICATIONS - LATEST STARTED', [
            'user_id' => $request->user()->id,
            'user_email' => $request->user()->email,
            'ip' => $request->ip(),
        ]);

        try {
            $userId = $request->user()->id;

            $notifications = Notification::where(
                'user_id',
                $userId
            )
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $unreadCount = Notification::where(
                'user_id',
                $userId
            )
                ->whereNull('read_at')
                ->count();

            Log::info('✅ ADMIN - Latest notifications fetched', [
                'user_id' => $userId,
                'count' => $notifications->count(),
                'unread_count' => $unreadCount,
            ]);

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);

        } catch (\Throwable $e) {
            Log::error('❌ ADMIN - Latest notifications error', [
                'user_id' => $request->user()->id ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch notifications.',
            ], 500);
        }
    }
}
