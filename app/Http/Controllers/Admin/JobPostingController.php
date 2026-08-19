<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JobPosting;
use App\Models\Company;
use App\Models\JobCategory;
use App\Models\JobType;
use App\Models\JobShift;           // ✅ Add this
use App\Models\ExperienceLevel;
use App\Models\CareerLevel;
use App\Models\DegreeLevel;
use App\Models\Gender;
use App\Models\Industry;
use App\Models\FunctionalArea;
use App\Models\MaritalStatus;
use App\Models\SalaryPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class JobPostingController extends Controller
{
    public function index()
    {
        $jobs = JobPosting::with(['company', 'category', 'jobType', 'experienceLevel', 'salaryPeriod'])
            ->where('source', 'admin')
            ->latest()
            ->paginate(20);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        // ✅ All attributes data
        $companies = Company::where('is_active', true)->pluck('company_name', 'id');
        $categories = JobCategory::where('is_active', true)->pluck('name', 'id');
        $jobTypes = JobType::where('is_active', true)->pluck('name', 'id');
        $jobShifts = JobShift::where('is_active', true)->pluck('name', 'id');  // ✅ Added
        $experienceLevels = ExperienceLevel::where('is_active', true)->pluck('name', 'id');
        $careerLevels = CareerLevel::where('is_active', true)->pluck('name', 'id');
        $degreeLevels = DegreeLevel::where('is_active', true)->pluck('name', 'id');
        $genders = Gender::where('is_active', true)->pluck('name', 'id');
        $industries = Industry::where('is_active', true)->pluck('name', 'id');
        $functionalAreas = FunctionalArea::where('is_active', true)->pluck('name', 'id');
        $maritalStatuses = MaritalStatus::where('is_active', true)->pluck('name', 'id');
        $salaryPeriods = SalaryPeriod::where('is_active', true)->pluck('name', 'id');

        return view('admin.jobs.create', compact(
            'companies',
            'categories',
            'jobTypes',
            'jobShifts',           // ✅ Now defined
            'experienceLevels',
            'careerLevels',
            'degreeLevels',
            'genders',
            'industries',
            'functionalAreas',
            'maritalStatuses',
            'salaryPeriods'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'required|exists:job_categories,id',
            'job_type_id' => 'required|exists:job_types,id',
            'job_shift_id' => 'nullable|exists:job_shifts,id',
            'experience_level_id' => 'nullable|exists:experience_levels,id',
            'career_level_id' => 'nullable|exists:career_levels,id',
            'degree_level_id' => 'nullable|exists:degree_levels,id',
            'gender_id' => 'nullable|exists:genders,id',
            'industry_id' => 'nullable|exists:industries,id',
            'functional_area_id' => 'nullable|exists:functional_areas,id',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'salary_period_id' => 'nullable|exists:salary_periods,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
            'application_deadline' => 'required|date',
            'ad_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'apply_link' => 'nullable|url',
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
        // ✅ All attributes data for edit
        $companies = Company::where('is_active', true)->pluck('company_name', 'id');
        $categories = JobCategory::where('is_active', true)->pluck('name', 'id');
        $jobTypes = JobType::where('is_active', true)->pluck('name', 'id');
        $jobShifts = JobShift::where('is_active', true)->pluck('name', 'id');
        $experienceLevels = ExperienceLevel::where('is_active', true)->pluck('name', 'id');
        $careerLevels = CareerLevel::where('is_active', true)->pluck('name', 'id');
        $degreeLevels = DegreeLevel::where('is_active', true)->pluck('name', 'id');
        $genders = Gender::where('is_active', true)->pluck('name', 'id');
        $industries = Industry::where('is_active', true)->pluck('name', 'id');
        $functionalAreas = FunctionalArea::where('is_active', true)->pluck('name', 'id');
        $maritalStatuses = MaritalStatus::where('is_active', true)->pluck('name', 'id');
        $salaryPeriods = SalaryPeriod::where('is_active', true)->pluck('name', 'id');

        return view('admin.jobs.edit', compact(
            'job',
            'companies',
            'categories',
            'jobTypes',
            'jobShifts',
            'experienceLevels',
            'careerLevels',
            'degreeLevels',
            'genders',
            'industries',
            'functionalAreas',
            'maritalStatuses',
            'salaryPeriods'
        ));
    }

    public function update(Request $request, JobPosting $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'category_id' => 'required|exists:job_categories,id',
            'job_type_id' => 'required|exists:job_types,id',
            'job_shift_id' => 'nullable|exists:job_shifts,id',
            'experience_level_id' => 'nullable|exists:experience_levels,id',
            'career_level_id' => 'nullable|exists:career_levels,id',
            'degree_level_id' => 'nullable|exists:degree_levels,id',
            'gender_id' => 'nullable|exists:genders,id',
            'industry_id' => 'nullable|exists:industries,id',
            'functional_area_id' => 'nullable|exists:functional_areas,id',
            'marital_status_id' => 'nullable|exists:marital_statuses,id',
            'salary_period_id' => 'nullable|exists:salary_periods,id',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'benefits' => 'nullable|string',
            'salary_min' => 'nullable|string',
            'salary_max' => 'nullable|string',
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
        // ✅ Delete image if exists
        if ($job->ad_image) {
            Storage::delete('public/jobs/' . $job->ad_image);
        }

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
