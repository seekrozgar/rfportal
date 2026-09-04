<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Models\JobPosting;
use App\Helpers\SiteHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // ✅ Categories with jobs count using correct relationship
        $categories = JobCategory::where('is_active', true)
            ->withCount('jobs')  // ✅ 'jobs' not 'job_postings'
            ->orderBy('order', 'asc')
            ->limit(8)
            ->get();

        // ✅ Jobs with is_active column (exists in your model)
        $jobs = JobPosting::where('is_active', true)
            ->with('company')
            ->latest()
            ->limit(6)
            ->get();

        return view('frontend.home', compact('categories', 'jobs'));
    }
}
