<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Job;
use App\Models\Company;
use App\Models\User;
use App\Models\Application;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Get all stats
        $data = [
            'totalJobs' => Job::count(),
            'totalCompanies' => Company::count(),
            'totalUsers' => User::count(),
            'totalApplications' => Application::count(),
            'totalRevenue' => Payment::where('status', 'completed')->sum('amount') ?? 0,
            'totalAdmins' => User::whereIn('role', ['superadmin', 'admin'])->count(),
            'totalEmployers' => User::where('role', 'employer')->count(),
            'totalSeekers' => User::where('role', 'seeker')->count(),
            'recentJobs' => Job::with('company')->latest()->limit(10)->get(),
            'recentUsers' => User::latest()->limit(10)->get(),
            'recentPayments' => Payment::with('user')->latest()->limit(10)->get(),
            'activeJobs' => Job::where('is_active', true)->count(),
            'inactiveJobs' => Job::where('is_active', false)->count(),
        ];

        return view('admin.dashboard.index', $data);
    }
}
