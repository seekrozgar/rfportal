<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\User;
use App\Models\Scholarship;
use App\Models\Admission;
use App\Models\Result;
use App\Models\News;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ General Jobs (Admin posted)
        $generalJobs = JobPosting::where('is_active', true)
            ->where('job_source', 'admin')
            ->with('company')
            ->latest()
            ->limit(6)
            ->get();

        // ✅ Company Jobs (Employer posted)
        $companyJobs = JobPosting::where('is_active', true)
            ->where('job_source', 'company')
            ->where('is_verified', true)
            ->with('company')
            ->latest()
            ->limit(6)
            ->get();

        // ✅ Categories
        $categories = JobCategory::where('is_active', true)
            ->withCount('jobs')
            ->orderBy('order', 'asc')
            ->limit(12)
            ->get();

        // ✅ Scholarships (Published & Upcoming)
        $scholarships = Scholarship::where('is_published', true)
            ->where('is_draft', false)
            ->where(function($q) {
                $q->where('deadline', '>=', now())
                  ->orWhereNull('deadline');
            })
            ->latest()
            ->limit(4)
            ->get();

        // ✅ Admissions (Published & Upcoming)
        $admissions = Admission::where('is_published', true)
            ->where(function($q) {
                $q->where('last_date', '>=', now())
                  ->orWhereNull('last_date');
            })
            ->latest()
            ->limit(4)
            ->get();

        // ✅ Results (Published)
        $results = Result::where('is_published', true)
            ->latest()
            ->limit(4)
            ->get();

        // ✅ News (Published)
        $news = News::where('is_published', true)
            ->latest()
            ->limit(4)
            ->get();

        // ✅ Stats
        $stats = [
            'active_jobs' => JobPosting::where('is_active', true)->count(),
            'companies' => Company::count(),
            'seekers' => User::whereHas('roles', function($q) {
                $q->where('name', 'seeker');
            })->count(),
        ];

        return view('frontend.home', compact(
            'generalJobs',
            'companyJobs',
            'categories',
            'stats',
            'scholarships',
            'admissions',
            'results',
            'news'
        ));
    }
}
