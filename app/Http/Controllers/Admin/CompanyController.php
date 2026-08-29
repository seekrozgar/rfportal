<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * All companies.
     */
    public function index(Request $request)
    {
        $query = Company::with('user')
            ->latest();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");

            });
        }

        if ($request->filled('status')) {

            $query->where(
                'verification_status',
                $request->status
            );
        }

        if ($request->status === 'fraud') {
            $query->where('is_fraud', true);
        }

        if ($request->status === 'suspended') {
            $query->where('is_suspended', true);
        }

        $companies = $query
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Company::count(),

            'pending' => Company::where(
                'verification_status',
                'pending'
            )->count(),

            'verified' => Company::where(
                'verification_status',
                'verified'
            )->count(),

            'fraud' => Company::where(
                'is_fraud',
                true
            )->count(),

            'suspended' => Company::where(
                'is_suspended',
                true
            )->count(),
        ];

        return view(
            'admin.companies.index',
            compact('companies', 'stats')
        );
    }

    /**
     * Company details.
     */
    public function show(Company $company)
    {
        $company->load('user');

        return view(
            'admin.companies.show',
            compact('company')
        );
    }

    /**
     * Approve verification.
     */
    public function approve(Request $request, Company $company)
    {
        if ($company->is_fraud) {

            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Fraud company cannot be approved.',
            ]);
        }

        $company->update([
            'verification_status' => 'verified',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'verification_rejection_reason' => null,
        ]);

        if ($company->user) {

            $this->notificationService->send(
                $company->user,
                'verification_approved',
                'Company Verification Approved',
                'Congratulations! Your company "' .
                $company->name .
                '" has been verified successfully.',
                route('employer.company-profile.edit'),
                'check-circle'
            );
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Company verification approved successfully.',
        ]);
    }

    /**
     * Reject verification.
     */
    public function reject(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $company->update([
            'verification_status' => 'rejected',
            'verification_rejection_reason' => $validated['reason'],
            'verified_at' => null,
            'verified_by' => null,
        ]);

        if ($company->user) {

            $this->notificationService->send(
                $company->user,
                'verification_rejected',
                'Company Verification Rejected',
                'Your company verification request was rejected. Reason: ' .
                $validated['reason'],
                route('employer.company-profile.edit'),
                'times-circle'
            );
        }

        return back()->with('toast', [
            'type' => 'warning',
            'message' => 'Company verification rejected.',
        ]);
    }

    /**
     * Suspend company.
     */
    public function suspend(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $company->update([
            'is_suspended' => true,
            'is_active' => false,
        ]);

        if ($company->user) {

            $this->notificationService->send(
                $company->user,
                'suspended',
                'Company Account Suspended',
                'Your company account has been suspended. Reason: ' .
                $validated['reason'],
                route('employer.company-profile.edit'),
                'ban'
            );
        }

        return back()->with('toast', [
            'type' => 'warning',
            'message' => 'Company suspended successfully.',
        ]);
    }

    /**
     * Restore company.
     */
    public function restore(Company $company)
    {
        $company->update([
            'is_suspended' => false,
            'is_active' => true,
        ]);

        if ($company->user) {

            $this->notificationService->send(
                $company->user,
                'success',
                'Company Account Restored',
                'Your company account has been restored and is active again.',
                route('employer.company-profile.edit'),
                'check'
            );
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Company restored successfully.',
        ]);
    }

    /**
     * Mark company as fraud.
     */
    public function markFraud(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $company->update([
            'is_fraud' => true,
            'fraud_reason' => $validated['reason'],
            'fraud_marked_at' => now(),
            'fraud_marked_by' => auth()->id(),
            'is_suspended' => true,
            'is_active' => false,
        ]);

        if ($company->user) {

            $this->notificationService->send(
                $company->user,
                'fraud',
                'Company Account Flagged',
                'Your company account has been flagged and suspended due to a policy/fraud concern. Reason: ' .
                $validated['reason'],
                route('employer.company-profile.edit'),
                'exclamation-triangle'
            );
        }

        return back()->with('toast', [
            'type' => 'error',
            'message' => 'Company has been marked as fraud and suspended.',
        ]);
    }

    /**
     * Remove fraud flag.
     */
    public function removeFraud(Company $company)
    {
        $company->update([
            'is_fraud' => false,
            'fraud_reason' => null,
            'fraud_marked_at' => null,
            'fraud_marked_by' => null,
        ]);

        return back()->with('toast', [
            'type' => 'success',
            'message' => 'Fraud flag removed.',
        ]);
    }

    /**
     * Unverify company.
     */
    public function unverify(Company $company)
    {
        $company->update([
            'verification_status' => 'unverified',
            'verified_at' => null,
            'verified_by' => null,
        ]);

        if ($company->user) {

            $this->notificationService->send(
                $company->user,
                'warning',
                'Company Verification Revoked',
                'Your company verification has been revoked by the administration.',
                route('employer.company-profile.edit'),
                'shield-alt'
            );
        }

        return back()->with('toast', [
            'type' => 'warning',
            'message' => 'Company verification has been revoked.',
        ]);
    }
}
