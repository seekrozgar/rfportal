<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Application;
use App\Http\Controllers\Employer\EmployerJobController;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ✅ Get company ID (if employer has company)
        $companyId = $user->company_id ?? null;

        // ✅ If employer doesn't have company yet, set defaults
        if (!$companyId) {
            return view('employer.dashboard', [
                'user' => $user,
                'totalJobs' => 0,
                'totalApplications' => 0,
                'totalViews' => 0,
                'featuredJobs' => 0,
                'recentApplications' => collect([]),
                'companyMissing' => true, // ✅ Flag to show "Create Company" message
            ]);
        }

        // ✅ Get stats
        $totalJobs = JobPosting::where('company_id', $companyId)->count();
        $totalApplications = Application::whereHas('job', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->count();
        $totalViews = JobPosting::where('company_id', $companyId)->sum('views_count');
        $featuredJobs = JobPosting::where('company_id', $companyId)->where('is_featured', true)->count();

        // ✅ Get recent applications
        $recentApplications = Application::whereHas('job', function ($query) use ($companyId) {
            $query->where('company_id', $companyId);
        })->with(['seeker', 'job'])
            ->latest()
            ->limit(5)
            ->get();

        return view('employer.dashboard.index', [
            'user' => $user,
            'totalJobs' => $totalJobs,
            'totalApplications' => $totalApplications,
            'totalViews' => $totalViews,
            'featuredJobs' => $featuredJobs,
            'recentApplications' => $recentApplications,
            'companyMissing' => false,
        ]);
    }
}
