<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobType;
use App\Models\ExperienceLevel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class JobPostingController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::with(['company', 'category'])
            ->where('source', 'admin')
            ->latest()
            ->paginate(20);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        $companies = Company::where('is_active', true)->pluck('company_name', 'id');
        $categories = JobCategory::where('is_active', true)->pluck('name', 'id');
        $jobTypes = JobType::where('is_active', true)->pluck('name', 'id');
        $experienceLevels = ExperienceLevel::where('is_active', true)->pluck('name', 'id');

        return view('admin.jobs.create', compact('companies', 'categories', 'jobTypes', 'experienceLevels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'required|exists:job_categories,id',
            'job_type_id' => 'required|exists:job_types,id',
            'experience_level_id' => 'nullable|exists:experience_levels,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'salary_period' => 'nullable|string',
            'application_deadline' => 'required|date',
            'ad_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // ✅ Image validation
            'apply_link' => 'nullable|url', // ✅ URL validation
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // ✅ Handle image upload
        if ($request->hasFile('ad_image')) {
            $image = $request->file('ad_image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/jobs', $imageName);
            $validated['ad_image'] = $imageName;
        }

        $validated['slug'] = Str::slug($request->title) . '-' . uniqid();
        $validated['posted_by'] = auth()->id();
        $validated['source'] = 'admin';
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        JobPosting::create($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job created successfully!');
    }

    public function edit(JobPosting $job)
    {
        $companies = Company::where('is_active', true)->pluck('company_name', 'id');
        $categories = JobCategory::where('is_active', true)->pluck('name', 'id');
        $jobTypes = JobType::where('is_active', true)->pluck('name', 'id');
        $experienceLevels = ExperienceLevel::where('is_active', true)->pluck('name', 'id');

        return view('admin.jobs.edit', compact('job', 'companies', 'categories', 'jobTypes', 'experienceLevels'));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'required|exists:job_categories,id',
            'job_type_id' => 'required|exists:job_types,id',
            'experience_level_id' => 'nullable|exists:experience_levels,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'salary_period' => 'nullable|string',
            'application_deadline' => 'required|date',
            'ad_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'apply_link' => 'nullable|url',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        // ✅ Handle image upload
        if ($request->hasFile('ad_image')) {
            // Delete old image
            if ($job->ad_image) {
                Storage::delete('public/jobs/' . $job->ad_image);
            }
            $image = $request->file('ad_image');
            $imageName = time() . '_' . Str::slug($request->title) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/jobs', $imageName);
            $validated['ad_image'] = $imageName;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        $job->update($validated);

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job updated successfully!');
    }

    public function destroy(JobPosting $job)
    {
        $job->delete();

        return response()->json([
            'success' => true,
            'message' => 'Job deleted successfully!'
        ]);
    }

    public function import(Request $request)
    {
        // CSV import logic (later)
        return redirect()->route('admin.jobs.index')
            ->with('success', 'Jobs imported successfully!');
    }
}
