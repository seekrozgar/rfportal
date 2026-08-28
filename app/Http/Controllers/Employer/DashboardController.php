<?php
// app/Http/Controllers/Employer/DashboardController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $company = $user->company;

        // ✅ Check if company exists and profile is complete
        $companyMissing = false;
        $isProfileComplete = false;

        if (!$company) {
            $companyMissing = true;
            $isProfileComplete = false;
        } else {
            $isProfileComplete = $company->is_complete ?? false;
        }

        // ✅ Statistics
        $totalJobs = $company ? $company->jobs()->count() : 0;
        $activeJobs = $company ? $company->jobs()->where('is_active', true)->count() : 0;
        $expiredJobs = $company ? $company->jobs()->where('deadline', '<', now())->count() : 0;
        $pendingJobs = $company ? $company->jobs()->where('is_verified', false)->count() : 0;
        $featuredJobs = $company ? $company->jobs()->where('is_featured', true)->count() : 0;

        // ✅ Total Applications
        $totalApplications = 0;
        if ($company) {
            $jobIds = $company->jobs()->pluck('id');
            $totalApplications = JobApplication::whereIn('job_id', $jobIds)->count();
        }

        // ✅ Total Views
        $totalViews = $company ? $company->jobs()->sum('views_count') : 0;

        // ✅ Recent Applications
        $recentApplications = collect();
        if ($company) {
            $jobIds = $company->jobs()->pluck('id');
            $recentApplications = JobApplication::whereIn('job_id', $jobIds)
                ->with(['job', 'user'])
                ->latest()
                ->take(5)
                ->get();
        }

        // ✅ Recent Jobs
        $recentJobs = $company ? $company->jobs()->latest()->take(5)->get() : collect();

        return view('employer.dashboard.index', compact(
            'user',
            'company',
            'companyMissing',
            'isProfileComplete',
            'totalJobs',
            'activeJobs',
            'expiredJobs',
            'pendingJobs',
            'featuredJobs',
            'totalApplications',
            'totalViews',
            'recentApplications',
            'recentJobs'
        ));
    }

    public function checkProfileComplete()
    {
        $user = Auth::user();
        $company = $user->company;

        if (!$company || !$company->is_complete) {
            return response()->json([
                'complete' => false,
                'message' => 'Please complete your company profile first.'
            ]);
        }

        return response()->json([
            'complete' => true,
            'message' => 'Profile complete!'
        ]);
    }
}
