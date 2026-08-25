<?php
// app/Http/Controllers/Employer/EmployerJobController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobCategory;
use App\Models\JobType;
use App\Models\JobShift;
use App\Models\ExperienceLevel;
use App\Models\CareerLevel;
use App\Models\Industry;
use App\Models\FunctionalArea;
use App\Models\DegreeLevel;
use App\Models\DegreeType;
use App\Models\MajorSubject;
use App\Models\Gender;
use App\Models\MaritalStatus;
use App\Models\LanguageLevel;
use App\Models\SalaryPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmployerJobController extends Controller
{
    public function index()
    {
        $company = auth()->user()->company;

        $jobs = JobPosting::where('company_id', $company->id)
            ->with(['category', 'author'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $totalJobs = JobPosting::where('company_id', $company->id)->count();
        $activeJobs = JobPosting::where('company_id', $company->id)->where('is_active', true)->count();
        $pendingJobs = JobPosting::where('company_id', $company->id)->where('is_verified', false)->count();
        $expiredJobs = JobPosting::where('company_id', $company->id)->where('deadline', '<', now())->count();

        return view('employer.jobs.index', compact(
            'jobs',
            'totalJobs',
            'activeJobs',
            'pendingJobs',
            'expiredJobs'
        ));
    }

    public function create()
    {
        $categories = JobCategory::active()->ordered()->get();
        $jobTypes = JobType::active()->orderBy('name')->get();
        $jobShifts = JobShift::active()->orderBy('name')->get();
        $experienceLevels = ExperienceLevel::active()->orderBy('name')->get();
        $careerLevels = CareerLevel::active()->orderBy('name')->get();
        $industries = Industry::active()->orderBy('name')->get();
        $functionalAreas = FunctionalArea::active()->orderBy('name')->get();
        $degreeLevels = DegreeLevel::active()->orderBy('name')->get();
        $degreeTypes = DegreeType::active()->orderBy('name')->get();
        $majorSubjects = MajorSubject::active()->orderBy('name')->get();
        $genders = Gender::active()->orderBy('name')->get();
        $maritalStatuses = MaritalStatus::active()->orderBy('name')->get();
        $languageLevels = LanguageLevel::active()->orderBy('name')->get();
        $salaryPeriods = SalaryPeriod::active()->orderBy('name')->get();

        return view('employer.jobs.create', compact(
            'categories',
            'jobTypes',
            'jobShifts',
            'experienceLevels',
            'careerLevels',
            'industries',
            'functionalAreas',
            'degreeLevels',
            'degreeTypes',
            'majorSubjects',
            'genders',
            'maritalStatuses',
            'languageLevels',
            'salaryPeriods'
        ));
    }

    public function store(Request $request)
    {
        try {
            $company = auth()->user()->company;

            // ✅ Check package limits here if needed
            // $package = $company->activePackage;
            // if ($package && $package->job_limit <= $company->jobs()->count()) {
            //     return back()->with('error', 'You have reached your job posting limit.');
            // }

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'nullable|exists:job_categories,id',
                'job_type_id' => 'nullable|exists:job_types,id',
                'job_shift_id' => 'nullable|exists:job_shifts,id',
                'location' => 'nullable|string|max:255',
                'experience_level_id' => 'nullable|exists:experience_levels,id',
                'career_level_id' => 'nullable|exists:career_levels,id',
                'industry_id' => 'nullable|exists:industries,id',
                'functional_area_id' => 'nullable|exists:functional_areas,id',
                'degree_level_id' => 'nullable|exists:degree_levels,id',
                'degree_type_id' => 'nullable|exists:degree_types,id',
                'major_subject_id' => 'nullable|exists:major_subjects,id',
                'gender_id' => 'nullable|exists:genders,id',
                'marital_status_id' => 'nullable|exists:marital_statuses,id',
                'language_level_id' => 'nullable|exists:language_levels,id',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0|gte:salary_min',
                'salary_period_id' => 'nullable|exists:salary_periods,id',
                'advertisement_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'apply_link' => 'nullable|url|max:500',
                'description' => 'required|string',
                'requirements' => 'nullable|string',
                'benefits' => 'nullable|string',
                'skills_required' => 'nullable|string',
                'responsibilities' => 'nullable|string',
                'apply_email' => 'nullable|email|max:255',
                'apply_phone' => 'nullable|string|max:50',
                'application_instructions' => 'nullable|string',
                'deadline' => 'nullable|date|after:today',
                'vacancies' => 'nullable|integer|min:1',
                'is_remote' => 'nullable|boolean',
                'is_fresh' => 'nullable|boolean',
            ]);

            // ✅ Auto-set company fields
            $validated['company_id'] = $company->id;
            $validated['job_source'] = 'company';
            $validated['posted_by'] = auth()->id();
            $validated['slug'] = Str::slug($request->title) . '-' . uniqid();
            $validated['vacancies'] = $request->vacancies ?? 1;
            $validated['is_verified'] = false;
            $validated['is_active'] = false;
            $validated['is_featured'] = false;
            $validated['is_urgent'] = false;
            $validated['is_remote'] = $request->has('is_remote');
            $validated['is_fresh'] = $request->has('is_fresh');

            // ✅ Handle image
            if ($request->hasFile('advertisement_image')) {
                $file = $request->file('advertisement_image');
                $path = $file->store('job-postings/advertisements', 'public');
                $validated['advertisement_image'] = $path;
            }

            $job = JobPosting::create($validated);

            return redirect()->route('employer.jobs.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Job posted successfully! It will be published after admin verification.'
                ]);

        } catch (\Exception $e) {
            Log::error('❌ employer job posting failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }
}
