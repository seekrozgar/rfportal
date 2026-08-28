<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyVerificationController extends Controller
{
    /**
     * Show pending verification requests.
     */
    public function index()
    {
        $companies = Company::with(['user', 'verificationReviewer'])
            ->where('verification_status', 'pending')
            ->latest('verification_requested_at')
            ->paginate(15);

        return view(
            'admin.company-verifications.index',
            compact('companies')
        );
    }

    /**
     * Show company verification details.
     */
    public function show(Company $company)
    {
        $company->load([
            'user',
            'verificationReviewer'
        ]);

        return view(
            'admin.company-verifications.show',
            compact('company')
        );
    }

    /**
     * Approve company verification.
     */
    public function approve(Request $request, Company $company)
    {
        if ($company->verification_status !== 'pending') {
            return redirect()
                ->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'This verification request is not pending.',
                ]);
        }

        $company->update([
            'verification_status' => 'approved',
            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
            'verification_rejection_reason' => null,
            'verified_at' => now(),
        ]);

        Log::info('Company verification approved', [
            'company_id' => $company->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.company-verifications.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Company verification approved successfully.',
            ]);
    }

    /**
     * Reject company verification.
     */
    public function reject(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        if ($company->verification_status !== 'pending') {
            return redirect()
                ->back()
                ->with('toast', [
                    'type' => 'error',
                    'message' => 'This verification request is not pending.',
                ]);
        }

        $company->update([
            'verification_status' => 'rejected',
            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
            'verification_rejection_reason' => $validated['reason'],
            'verified_at' => null,
        ]);

        Log::info('Company verification rejected', [
            'company_id' => $company->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.company-verifications.index')
            ->with('toast', [
                'type' => 'warning',
                'message' => 'Company verification rejected.',
            ]);
    }
}
