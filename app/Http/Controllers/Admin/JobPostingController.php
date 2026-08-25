<?php
// app/Http/Controllers/Admin/JobPostingController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobCategory;
use App\Models\Company;
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
use App\Services\JobScrapingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class JobPostingController extends Controller
{
    /**
     * Permanent fallback image.
     * This file is stored once in storage/app/public/images/.
     */
    private const DEFAULT_ADVERTISEMENT_IMAGE = 'images/no-image-icon-15.png';
    public function index()
    {
        $jobs = JobPosting::with(['category', 'company', 'author', 'jobType'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $totalJobs = JobPosting::count();
        $activeJobs = JobPosting::where('is_active', true)->count();
        $expiredJobs = JobPosting::where('deadline', '<', now())->count();
        $featuredJobs = JobPosting::where('is_featured', true)->count();
        $urgentJobs = JobPosting::where('is_urgent', true)->count();
        $remoteJobs = JobPosting::where('is_remote', true)->count();

        return view('admin.job-postings.index', compact(
            'jobs',
            'totalJobs',
            'activeJobs',
            'expiredJobs',
            'featuredJobs',
            'urgentJobs',
            'remoteJobs'
        ));
    }

    public function create()
    {
        $categories = JobCategory::active()->orderBy('name', 'asc')->get();

        $jobTypes = JobType::active()->orderBy('name', 'asc')->get();
        $jobShifts = JobShift::active()->orderBy('name', 'asc')->get();
        $experienceLevels = ExperienceLevel::active()->orderBy('name', 'asc')->get();
        $careerLevels = CareerLevel::active()->orderBy('name', 'asc')->get();
        $industries = Industry::active()->orderBy('name', 'asc')->get();
        $functionalAreas = FunctionalArea::active()->orderBy('name', 'asc')->get();
        $degreeLevels = DegreeLevel::active()->orderBy('name', 'asc')->get();
        $degreeTypes = DegreeType::active()->orderBy('name', 'asc')->get();
        $majorSubjects = MajorSubject::active()->orderBy('name', 'asc')->get();
        $genders = Gender::active()->orderBy('name', 'asc')->get();
        $maritalStatuses = MaritalStatus::active()->orderBy('name', 'asc')->get();
        $languageLevels = LanguageLevel::active()->orderBy('name', 'asc')->get();
        $salaryPeriods = SalaryPeriod::active()->orderBy('name', 'asc')->get();

        return view('admin.job-postings.create', compact(
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
                'application_instructions' => 'nullable|string',
                'deadline' => 'nullable|date|after:today',
                'vacancies' => 'nullable|integer|min:1',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'is_urgent' => 'nullable|boolean',
                'is_remote' => 'nullable|boolean',
                'is_fresh' => 'nullable|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
            ]);

            // Auto-set fields
            $validated['job_source'] = 'admin';
            $validated['posted_by'] = auth()->id();
            $validated['slug'] = Str::slug($request->title) . '-' . uniqid();
            $validated['vacancies'] = $request->vacancies ?? 1;
            // Checkboxes are not submitted at all when they are OFF.
            // boolean() therefore gives us a reliable true/false value and
            // prevents database defaults from turning statuses ON.
            $validated['is_active'] = $request->boolean('is_active');
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_urgent'] = $request->boolean('is_urgent');
            $validated['is_remote'] = $request->boolean('is_remote');
            $validated['is_fresh'] = $request->boolean('is_fresh');

            $validated['is_verified'] = true;
            $validated['verified_at'] = now();
            $validated['verified_by'] = auth()->id();

            // Only publish immediately when Active was explicitly enabled.
            $validated['published_at'] = $validated['is_active'] ? now() : null;

            // Handle image.
            // The default image is already stored once; do not upload it again.
            $validated['advertisement_image'] = self::DEFAULT_ADVERTISEMENT_IMAGE;

            if ($request->hasFile('advertisement_image')) {
                $file = $request->file('advertisement_image');
                $path = $file->store('job-postings/advertisements', 'public');
                $validated['advertisement_image'] = $path;
            }

            $job = JobPosting::create($validated);

            Log::info('✅ Job posted', [
                'id' => $job->id,
                'title' => $job->title,
                'by' => auth()->user()->name
            ]);

            return redirect()->route('admin.job-postings.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Job "' . $job->title . '" posted successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Job posting failed', ['error' => $e->getMessage()]);
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function edit(JobPosting $jobPosting)
    {
        $categories = JobCategory::active()->orderBy('name', 'asc')->get();

        $jobTypes = JobType::active()->orderBy('name', 'asc')->get();
        $jobShifts = JobShift::active()->orderBy('name', 'asc')->get();
        $experienceLevels = ExperienceLevel::active()->orderBy('name', 'asc')->get();
        $careerLevels = CareerLevel::active()->orderBy('name', 'asc')->get();
        $industries = Industry::active()->orderBy('name', 'asc')->get();
        $functionalAreas = FunctionalArea::active()->orderBy('name', 'asc')->get();
        $degreeLevels = DegreeLevel::active()->orderBy('name', 'asc')->get();
        $degreeTypes = DegreeType::active()->orderBy('name', 'asc')->get();
        $majorSubjects = MajorSubject::active()->orderBy('name', 'asc')->get();
        $genders = Gender::active()->orderBy('name', 'asc')->get();
        $maritalStatuses = MaritalStatus::active()->orderBy('name', 'asc')->get();
        $languageLevels = LanguageLevel::active()->orderBy('name', 'asc')->get();
        $salaryPeriods = SalaryPeriod::active()->orderBy('name', 'asc')->get();

        return view('admin.job-postings.edit', compact(
            'jobPosting',
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

    public function update(Request $request, JobPosting $jobPosting)
    {
        try {
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
                'application_instructions' => 'nullable|string',
                'deadline' => 'nullable|date|after:today',
                'vacancies' => 'nullable|integer|min:1',
                'is_active' => 'nullable|boolean',
                'is_featured' => 'nullable|boolean',
                'is_urgent' => 'nullable|boolean',
                'is_remote' => 'nullable|boolean',
                'is_fresh' => 'nullable|boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'meta_keywords' => 'nullable|string|max:255',
            ]);

            // Explicitly write all five statuses on every update.
            // This is important because an unchecked checkbox is absent from
            // the HTTP request; boolean() converts that absence to false.
            $validated['is_active'] = $request->boolean('is_active');
            $validated['is_featured'] = $request->boolean('is_featured');
            $validated['is_urgent'] = $request->boolean('is_urgent');
            $validated['is_remote'] = $request->boolean('is_remote');
            $validated['is_fresh'] = $request->boolean('is_fresh');
            $validated['vacancies'] = $request->vacancies ?? 1;

            // If no new image is selected, keep the existing image exactly as-is.
            // This prevents unnecessary duplicate uploads.
            if ($request->hasFile('advertisement_image')) {
                $oldImage = $jobPosting->advertisement_image;

                // Never delete the permanent fallback image.
                if ($oldImage && $oldImage !== self::DEFAULT_ADVERTISEMENT_IMAGE) {
                    Storage::disk('public')->delete($oldImage);
                }

                $file = $request->file('advertisement_image');
                $validated['advertisement_image'] =
                    $file->store('job-postings/advertisements', 'public');
            } elseif (empty($jobPosting->advertisement_image)) {
                // Older records without an image get the permanent fallback.
                $validated['advertisement_image'] = self::DEFAULT_ADVERTISEMENT_IMAGE;
            }

            // Keep publication state consistent with Active.
            if ($validated['is_active']) {
                if (!$jobPosting->is_active || empty($jobPosting->published_at)) {
                    $validated['published_at'] = now();
                }
            } else {
                $validated['published_at'] = null;
            }

            $jobPosting->update($validated);

            return redirect()->route('admin.job-postings.index')
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Job "' . $jobPosting->title . '" updated successfully!'
                ]);

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withInput()
                ->with('toast', ['type' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        }
    }

    public function destroy(JobPosting $jobPosting)
    {
        try {
            if (
                $jobPosting->advertisement_image &&
                $jobPosting->advertisement_image !== self::DEFAULT_ADVERTISEMENT_IMAGE
            ) {
                Storage::disk('public')->delete($jobPosting->advertisement_image);
            }
            $jobPosting->delete();

            return response()->json([
                'success' => true,
                'message' => 'Job "' . $jobPosting->title . '" deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }

    public function toggleStatus(JobPosting $jobPosting)
    {
        try {
            $newStatus = !$jobPosting->is_active;
            $jobPosting->update([
                'is_active' => $newStatus,
                'published_at' => $newStatus ? now() : null
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Job "' . $jobPosting->title . '" is now ' . ($newStatus ? 'Active' : 'Inactive') . '!'
            ]);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 422);
        }
    }

    // ============================================================
    // SCRAPING METHODS
    // ============================================================

    public function showScrapeForm()
    {
        $categories = JobCategory::active()->orderBy('name', 'asc')->get();
        $sources = app(JobScrapingService::class)->getSources();
        return view('admin.job-postings.scrape', compact('categories', 'sources'));
    }

    public function scrape(Request $request, JobScrapingService $scraper)
    {
        try {
            $request->validate([
                'source' => 'required|string|in:' . implode(',', array_keys($scraper->getSources())),
                'category_id' => 'nullable|exists:job_categories,id',
                'keywords' => 'nullable|string|max:100',
                'limit' => 'nullable|integer|min:1|max:50',
                'auto_publish' => 'nullable|boolean',
            ]);

            $result = $scraper->scrapeJobs(
                $request->source,
                $request->keywords ?? '',
                $request->limit ?? 20,
                $request->category_id,
                $request->has('auto_publish')
            );

            $status = $result['added'] > 0 ? 'success' : 'warning';
            $message = "✅ {$result['added']} jobs scraped successfully!";

            if ($result['skipped'] > 0) {
                $message .= " ⏭️ {$result['skipped']} jobs already exist.";
            }
            if ($result['errors'] > 0) {
                $message .= " ⚠️ {$result['errors']} errors occurred.";
            }

            if ($request->has('auto_publish')) {
                $message .= " Jobs have been published automatically.";
            } else {
                $message .= " Jobs saved as drafts. Review and publish manually.";
            }

            return redirect()->route('admin.job-postings.index')
                ->with('toast', [
                    'type' => $status,
                    'message' => $message
                ]);

        } catch (\Exception $e) {
            Log::error('❌ Job scraping failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Scraping error: ' . $e->getMessage()
                ]);
        }
    }

    public function testConnection(Request $request)
    {
        try {
            $request->validate(['source' => 'required|string']);
            $result = app(JobScrapingService::class)->testConnection($request->source);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    // ============================================================
    // ✅ BULK ACTION - FIXED METHOD NAME
    // ============================================================

    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:job_postings,id',
                'action' => 'required|string|in:delete,activate,deactivate,featured,unfeatured',
            ]);

            $ids = $request->ids;
            $action = $request->action;
            $extra = $request->extra;
            $updated = 0;

            foreach ($ids as $id) {
                $job = JobPosting::find($id);
                if (!$job)
                    continue;

                switch ($action) {
                    case 'delete':
                        if (
                            $job->advertisement_image &&
                            $job->advertisement_image !== self::DEFAULT_ADVERTISEMENT_IMAGE
                        ) {
                            Storage::disk('public')->delete($job->advertisement_image);
                        }
                        $job->delete();
                        $updated++;
                        break;

                    case 'activate':
                        $job->is_active = true;
                        $job->published_at = now();
                        $job->save();
                        $updated++;
                        break;

                    case 'deactivate':
                        $job->is_active = false;
                        $job->published_at = null;
                        $job->save();
                        $updated++;
                        break;

                    case 'featured':
                        $job->is_featured = true;
                        $job->save();
                        $updated++;
                        break;

                    case 'unfeatured':
                        $job->is_featured = false;
                        $job->save();
                        $updated++;
                        break;
                }
            }

            $message = "{$updated} job(s) ";
            $message .= $action === 'delete' ? 'deleted' :
                ($action === 'activate' ? 'activated' :
                    ($action === 'deactivate' ? 'deactivated' :
                        ($action === 'featured' ? 'marked as featured' : 'removed from featured')));
            $message .= ' successfully!';

            Log::info('✅ Bulk action performed', [
                'action' => $action,
                'count' => $updated,
                'by' => auth()->user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'updated' => $updated
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Bulk action failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 422);
        }
    }
}
