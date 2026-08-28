<?php
// app/Http/Controllers/Employer/ApplicationController.php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $company = auth()->user()->company;

        if (!$company) {
            return redirect()->route('employer.company-profile.edit')
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Please complete your company profile first.'
                ]);
        }

        // ✅ Get job IDs for this company
        $jobIds = $company->jobs()->pluck('id');

        // ✅ Build query
        $query = JobApplication::whereIn('job_id', $jobIds)
            ->with(['job', 'user']);

        // ✅ Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // ✅ Filter by job
        if ($request->has('job_id') && $request->job_id) {
            $query->where('job_id', $request->job_id);
        }

        // ✅ Search
        if ($request->has('search') && $request->search) {
            $query->search($request->search);
        }

        $applications = $query->orderBy('created_at', 'desc')->paginate(20);

        // ✅ Statistics
        $totalApplications = JobApplication::whereIn('job_id', $jobIds)->count();
        $pendingCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'pending')->count();
        $reviewingCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'reviewing')->count();
        $shortlistedCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'shortlisted')->count();
        $interviewCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'interview')->count();
        $hiredCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'hired')->count();
        $rejectedCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'rejected')->count();
        $unreadCount = JobApplication::whereIn('job_id', $jobIds)->where('is_read', false)->count();

        $jobs = $company->jobs()->where('is_active', true)->get();

        $statuses = [
            'all' => 'All Applications',
            'pending' => 'Pending',
            'reviewing' => 'Reviewing',
            'shortlisted' => 'Shortlisted',
            'interview' => 'Interview',
            'offered' => 'Offered',
            'hired' => 'Hired',
            'rejected' => 'Rejected',
        ];

        return view('employer.applications.index', compact(
            'applications',
            'totalApplications',
            'pendingCount',
            'reviewingCount',
            'shortlistedCount',
            'interviewCount',
            'hiredCount',
            'rejectedCount',
            'unreadCount',
            'jobs',
            'statuses'
        ));
    }

    public function show(JobApplication $application)
    {
        $company = auth()->user()->company;

        // ✅ Check if application belongs to this company
        if ($application->job->company_id !== $company->id) {
            abort(403, 'Unauthorized access.');
        }

        // ✅ Mark as read
        if (!$application->is_read) {
            $application->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }

        return view('employer.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        try {
            $request->validate([
                'status' => 'required|in:pending,reviewing,shortlisted,interview,offered,hired,rejected',
                'notes' => 'nullable|string',
            ]);

            $company = auth()->user()->company;

            if ($application->job->company_id !== $company->id) {
                abort(403, 'Unauthorized access.');
            }

            $oldStatus = $application->status;
            $newStatus = $request->status;

            // ✅ Update timestamps based on status
            $updateData = [
                'status' => $newStatus,
                'employer_notes' => $request->notes ?? $application->employer_notes,
            ];

            // ✅ Set timestamps
            $statusTimestamps = [
                'reviewing' => 'reviewed_at',
                'shortlisted' => 'shortlisted_at',
                'interview' => 'interview_at',
                'offered' => 'offered_at',
                'hired' => 'hired_at',
                'rejected' => 'rejected_at',
            ];

            if (isset($statusTimestamps[$newStatus])) {
                $updateData[$statusTimestamps[$newStatus]] = now();
            }

            // ✅ If hiring, set hired_at
            if ($newStatus === 'hired') {
                $updateData['hired_at'] = now();
            }

            // ✅ If rejecting, set rejected_at and add reason
            if ($newStatus === 'rejected') {
                $updateData['rejected_at'] = now();
                if ($request->has('rejection_reason')) {
                    $updateData['rejection_reason'] = $request->rejection_reason;
                }
            }

            // ✅ If reviewing, set reviewed_at
            if ($newStatus === 'reviewing' && !$application->reviewed_at) {
                $updateData['reviewed_at'] = now();
                $updateData['reviewed_by'] = auth()->id();
            }

            $application->update($updateData);

            Log::info('✅ Application status updated', [
                'application_id' => $application->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'by' => auth()->user()->name
            ]);

            return redirect()->route('employer.applications.show', $application)
                ->with('toast', [
                    'type' => 'success',
                    'message' => 'Application status updated to ' . ucfirst($newStatus) . '!'
                ]);

        } catch (\Exception $e) {
            Log::error('❌ Status update failed', ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'Error: ' . $e->getMessage()
                ]);
        }
    }

    public function bulkAction(Request $request)
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'required|integer|exists:job_applications,id',
                'action' => 'required|string|in:reviewing,shortlisted,interview,offered,hired,rejected,archive',
            ]);

            $company = auth()->user()->company;
            $action = $request->action;
            $updated = 0;

            foreach ($request->ids as $id) {
                $application = JobApplication::find($id);

                // ✅ Check if application belongs to this company
                if ($application->job->company_id !== $company->id) {
                    continue;
                }

                if ($action === 'archive') {
                    $application->update(['is_archived' => true]);
                } else {
                    $updateData = ['status' => $action];

                    // ✅ Set timestamps
                    $statusTimestamps = [
                        'reviewing' => 'reviewed_at',
                        'shortlisted' => 'shortlisted_at',
                        'interview' => 'interview_at',
                        'offered' => 'offered_at',
                        'hired' => 'hired_at',
                        'rejected' => 'rejected_at',
                    ];

                    if (isset($statusTimestamps[$action])) {
                        $updateData[$statusTimestamps[$action]] = now();
                    }

                    if ($action === 'reviewing' && !$application->reviewed_at) {
                        $updateData['reviewed_at'] = now();
                        $updateData['reviewed_by'] = auth()->id();
                    }

                    $application->update($updateData);
                }
                $updated++;
            }

            $message = "{$updated} application(s) ";
            $message .= $action === 'archive' ? 'archived' : 'updated to ' . ucfirst($action);
            $message .= ' successfully!';

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

    public function downloadResume(JobApplication $application)
    {
        $company = auth()->user()->company;

        if ($application->job->company_id !== $company->id) {
            abort(403, 'Unauthorized access.');
        }

        if (!$application->resume) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'No resume uploaded for this application.'
            ]);
        }

        $path = storage_path('app/public/' . $application->resume);

        if (!file_exists($path)) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Resume file not found.'
            ]);
        }

        return response()->download($path, 'Resume_' . $application->user->name . '.pdf');
    }
}
