<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Job;
use App\Models\Company;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Cache stats
        $stats = Cache::remember('admin_dashboard_stats', 120, function () {
            $userRoles = User::select('role', DB::raw('count(*) as total'))
                ->groupBy('role')
                ->pluck('total', 'role');

            $jobStatus = Job::select('is_active', DB::raw('count(*) as total'))
                ->groupBy('is_active')
                ->pluck('total', 'is_active');

            return [
                'totalJobs' => $jobStatus->sum(),
                'activeJobs' => $jobStatus->get(1, 0),
                'inactiveJobs' => $jobStatus->get(0, 0),
                'totalCompanies' => Company::count(),
                'totalUsers' => $userRoles->sum(),
                'totalApplications' => Application::count(),
                'totalRevenue' => Payment::where('status', 'completed')->sum('amount') ?? 0,
                'totalEmployers' => $userRoles->get('employer', 0),
                'totalSeekers' => $userRoles->get('seeker', 0),
                'totalAdmins' => $userRoles->get('admin', 0) + $userRoles->get('superadmin', 0),
            ];
        });

        // ✅ Recent data
        $recentData = [
            'recentJobs' => Job::with('company:id,company_name')->latest()->limit(10)->get(),
            'recentUsers' => User::select('id', 'name', 'email', 'role', 'created_at')->latest()->limit(10)->get(),
            'recentPayments' => Payment::with('user:id,name')->latest()->limit(10)->get(),
        ];

        // ✅ Notifications
        $notifications = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'message' => $log->description,
                    'icon' => $log->icon,
                    'type' => $log->type,
                    'time' => $log->created_at->diffForHumans(),
                    'user' => $log->user->name ?? 'System',
                ];
            });

        $unreadCount = ActivityLog::whereNull('read_at')->count();

        // ✅ Merge all data
        $data = array_merge($stats, $recentData, [
            'notifications' => $notifications,
            'unreadNotifications' => $unreadCount,
        ]);

        return view('admin.dashboard.index', $data);
    }
}
