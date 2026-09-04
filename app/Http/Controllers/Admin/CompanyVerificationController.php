<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyVerificationController extends Controller
{
    /**
     * Pending verification requests.
     */
    public function index()
    {
        $companies = Company::with([
            'user',
            'verificationReviewer'
        ])
            ->where('verification_status', 'pending')
            ->latest('verification_requested_at')
            ->paginate(15);

        return view(
            'admin.company-verifications.index',
            compact('companies')
        );
    }

    /**
     * Verification details.
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
     * Approve verification.
     *
     * Actual business logic is delegated to CompanyController
     * so there is only ONE source of truth.
     */
    public function approve(Request $request, Company $company)
    {
        return app(CompanyController::class)
            ->approve($request, $company);
    }

    /**
     * Reject verification.
     *
     * Actual business logic is delegated to CompanyController.
     */
    public function reject(Request $request, Company $company)
    {
        return app(CompanyController::class)
            ->reject($request, $company);
    }
}
