<?php
// app/Http/Controllers/Admin/NotificationController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display notifications
     */
    public function index()
    {
        $notifications = ActivityLog::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    /**
     * Mark all notifications as read
     */
    public function markRead(Request $request)
    {
        ActivityLog::whereNull('read_at')->update(['read_at' => now()]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'All notifications marked as read.']);
        }

        return back()->with('success', 'All notifications marked as read.');
    }

    /**
     * Mark single notification as read (AJAX)
     */
    public function markSingleRead($id)
    {
        $notification = ActivityLog::find($id);
        if ($notification) {
            $notification->update(['read_at' => now()]);
            return response()->json(['success' => true, 'message' => 'Notification marked as read.']);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found.'], 404);
    }
}
