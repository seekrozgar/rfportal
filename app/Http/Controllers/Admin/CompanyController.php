<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use App\Models\CompanyAuditLog;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    /**
     * All companies.
     */
    public function index(Request $request)
    {
        $query = Company::with('user')->latest();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {

            switch ($request->status) {

                case 'verified':
                    $query->where('verification_status', 'verified');
                    break;

                case 'pending':
                    $query->where('verification_status', 'pending');
                    break;

                case 'rejected':
                    $query->where('verification_status', 'rejected');
                    break;

                case 'unverified':
                    $query->where('verification_status', 'unverified');
                    break;

                case 'suspended':
                    $query->where('is_suspended', true);
                    break;

                case 'blocked':
                    $query->where('is_blocked', true);
                    break;

                case 'fraud':
                    $query->where('is_fraud', true);
                    break;
            }
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

            'unverified' => Company::where(
                'verification_status',
                'unverified'
            )->count(),

            'rejected' => Company::where(
                'verification_status',
                'rejected'
            )->count(),

            'fraud' => Company::where(
                'is_fraud',
                true
            )->count(),

            'suspended' => Company::where(
                'is_suspended',
                true
            )->count(),

            'blocked' => Company::where(
                'is_blocked',
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
     * Approve company verification.
     */
    public function approve(Request $request, Company $company)
    {
        if ($company->is_fraud) {
            return back()->with('toast', [
                'type' => 'error',
                'message' => 'Fraud company cannot be approved.',
            ]);
        }

        // Get status before
        $statusBefore = $company->verification_status;

        $company->update([
            'is_verified' => true,
            'verification_status' => 'verified',

            'verified_at' => now(),
            'verified_by' => auth()->id(),

            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),

            'verification_admin_note' => null,

            'is_active' => true,
            'is_suspended' => false,
            'is_blocked' => false,
        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'approve',
            reason: 'Company verification approved',
            adminNote: 'Verified by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A'),
            ticketNumber: $ticketNumber,
            metadata: [
                'verified_by' => auth()->user()->name,
                'verified_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'verification_approved',
                'Company Verification Approved',
                "Congratulations! Your company {$company->name} has been verified successfully!\n\nReference: {$ticketNumber}\n\nYour company is now verified and can post jobs.",
                route('employer.company-profile.edit'),
                'check-circle'
            );
        }

        Log::info('Company verification approved', [
            'company_id' => $company->id,
            'admin_id' => auth()->id(),
        ]);

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('toast', [
                'type' => 'success',
                'message' => "Company verification approved successfully. Ticket: {$ticketNumber}",
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

        $statusBefore = $company->verification_status;

        $company->update([
            'is_verified' => false,
            'verification_status' => 'rejected',

            // ✅ Save rejection reason
            'verification_rejection_reason' => $validated['reason'],

            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),

            'verified_at' => null,
            'verified_by' => null,
        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'reject',
            reason: $validated['reason'],
            adminNote: 'Rejected by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A') . '. Reason: ' . $validated['reason'],
            ticketNumber: $ticketNumber,
            metadata: [
                'rejected_by' => auth()->user()->name,
                'rejected_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'verification_rejected',
                'Company Verification Rejected',
                "❌ Your company verification was rejected.\n\nReference: {$ticketNumber}\nReason: {$validated['reason']}\n\nPlease correct the issues and submit again.",
                route('employer.company-profile.edit'),
                'times-circle'
            );
        }

        Log::warning('Company verification rejected', [
            'company_id' => $company->id,
            'admin_id' => auth()->id(),
            'reason' => $validated['reason'],
        ]);

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('toast', [
                'type' => 'warning',
                'message' => "Company verification rejected successfully. Ticket: {$ticketNumber}",
            ]);
    }

    /**
     * Suspend company.
     *
     * IMPORTANT:
     * Suspension also removes verification.
     */
    public function suspend(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $statusBefore = $company->verification_status;

        $company->update([
            'is_suspended' => true,
            'is_active' => false,

            // ✅ Save suspension reason in BOTH fields
            'verification_rejection_reason' => $validated['reason'],

            // Suspend => verification revoked
            'is_verified' => false,
            'verification_status' => 'unverified',

            'verified_at' => null,
            'verified_by' => null,

        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'suspend',
            reason: $validated['reason'],
            adminNote: 'Suspended by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A') . '. Reason: ' . $validated['reason'],
            ticketNumber: $ticketNumber,
            metadata: [
                'suspended_by' => auth()->user()->name,
                'suspended_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'suspended',
                'Company Account Suspended',
                "⚠️ Your company account has been suspended.\n\nReference: {$ticketNumber}\nReason: {$validated['reason']}\n\nPlease contact support for further assistance.",
                route('employer.company-profile.edit'),
                'ban'
            );
        }

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('toast', [
                'type' => 'warning',
                'message' => "Company suspended successfully and verification has been revoked. Ticket: {$ticketNumber}",
            ]);
    }

    /**
     * Restore company.
     *
     * Restore does NOT automatically verify company.
     */
    public function restore(Company $company)
    {
        $statusBefore = $company->verification_status;

        $company->update([
            'is_suspended' => false,
            'is_active' => true,

            'is_verified' => false,
            'verification_status' => 'unverified',

            'verified_at' => null,
            'verified_by' => null,
        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'restore',
            reason: 'Company restored',
            adminNote: 'Restored by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A') . '. Company is now active but unverified.',
            ticketNumber: $ticketNumber,
            metadata: [
                'restored_by' => auth()->user()->name,
                'restored_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'restored',
                'Company Account Restored',
                "✅ Your company account has been restored.\n\nReference: {$ticketNumber}\n\nYour account is active but remains unverified. Please complete the verification process again.",
                route('employer.company-profile.edit'),
                'check'
            );
        }

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('toast', [
                'type' => 'success',
                'message' => "Company restored successfully. Ticket: {$ticketNumber} Verification remains unverified.",
            ]);
    }

    /**
     * Block company.
     *
     * Blocking also removes verification.
     */
    public function block(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $statusBefore = $company->verification_status;

        $company->update([
            'is_blocked' => true,
            'is_active' => false,

            // ✅ Save block reason
            'verification_rejection_reason' => $validated['reason'],

            'is_verified' => false,
            'verification_status' => 'unverified',

            'verified_at' => null,
            'verified_by' => null,
        ]);

        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'restore',
            reason: 'Company restored',
            adminNote: 'Restored by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A') . '. Company is now active but unverified.',
            ticketNumber: $ticketNumber,
            metadata: [
                'restored_by' => auth()->user()->name,
                'restored_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'blocked',
                'Company Account Blocked',
                "Your company account has been blocked by Support Team.\n\nReference: {$ticketNumber}\n\n Your verification has been revoked. Reason: {$validated['reason']}",
                route('employer.company-profile.edit'),
                'ban'
            );
        }

        Log::warning('Company blocked', [
            'company_id' => $company->id,
            'admin_id' => auth()->id(),
            'reason' => $validated['reason'],
        ]);

        return back()
            ->route('admin.companies.show', $company)
            ->with('toast', [
                'type' => 'error',
                'message' => "Company blocked and verification revoked. Ticket: {$ticketNumber}",
            ]);
    }

    /**
     * Mark company as fraud.
     *
     * Fraud automatically suspends and unverifies company.
     */
    public function markFraud(Request $request, Company $company)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
        ]);

        $statusBefore = $company->verification_status;

        $company->update([
            'is_fraud' => true,
            'fraud_reason' => $validated['reason'],
            'fraud_marked_at' => now(),
            'fraud_marked_by' => auth()->id(),

            // ✅ Save fraud reason in admin note as well
            'verification_rejection_reason' => $validated['reason'],

            'is_suspended' => true,
            'is_active' => false,

            // Fraud => verification revoked
            'is_verified' => false,
            'verification_status' => 'unverified',

            'verified_at' => null,
            'verified_by' => null,

        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'mark_fraud',
            reason: $validated['reason'],
            adminNote: 'Marked as fraud by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A') . '. Reason: ' . $validated['reason'],
            ticketNumber: $ticketNumber,
            metadata: [
                'fraud_marked_by' => auth()->user()->name,
                'fraud_marked_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'fraud',
                'Company Account Flagged',
                "⚠️ Your company account has been flagged.\n\nReference: {$ticketNumber}\nReason: {$validated['reason']}\n\nPlease contact support for further assistance.",
                route('employer.company-profile.edit'),
                'exclamation-triangle'
            );
        }

        return redirect()
            ->route('admin.companies.show', $company)
            ->with('toast', [
                'type' => 'error',
                'message' => "Company has been marked as fraud, suspended, and verification has been revoked. Ticket: {$ticketNumber}",
            ]);
    }

    /**
     * Remove fraud flag.
     *
     * Does NOT automatically verify company.
     */
    public function removeFraud(Company $company)
    {

        $statusBefore = $company->verification_status;

        $company->update([
            'is_fraud' => false,
            'fraud_reason' => null,
            'fraud_marked_at' => null,
            'fraud_marked_by' => null,

            'is_verified' => false,
            'verification_status' => 'unverified',
        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'remove_fraud',
            reason: 'Fraud flag removed',
            adminNote: 'Fraud flag removed by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A') . '. Company is now unverified.',
            ticketNumber: $ticketNumber,
            metadata: [
                'removed_by' => auth()->user()->name,
                'removed_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'fraud_removed',
                'Fraud Flag Removed',
                "✅ The fraud flag on your company has been removed.\n\nReference: {$ticketNumber}\n\nYour company is still unverified and needs to complete verification again.",
                route('employer.company-profile.edit'),
                'shield-alt'
            );
        }

        return back()->with('toast', [
            'type' => 'success',
            'message' => "Fraud flag removed. Company remains unverified. Ticket: {$ticketNumber}",
        ]);
    }

    /**
     * Unverify company manually.
     */
    public function unverify(Company $company)
    {
        $statusBefore = $company->verification_status;

        $company->update([
            'is_verified' => false,
            'verification_status' => 'unverified',

            'verified_at' => null,
            'verified_by' => null,

            'verification_reviewed_at' => now(),
            'verification_reviewed_by' => auth()->id(),
        ]);

        // ✅ LOG: Audit Trail
        $ticketNumber = CompanyAuditLog::generateTicketNumber();

        $auditLog = $company->logAdminAction(
            action: 'unverify',
            reason: 'Verification revoked',
            adminNote: 'Verification revoked by ' . auth()->user()->name . ' on ' . now()->format('d M Y, h:i A'),
            ticketNumber: $ticketNumber,
            metadata: [
                'unverified_by' => auth()->user()->name,
                'unverified_at' => now()->toDateTimeString(),
                'status_before' => $statusBefore,
            ]
        );

        if ($company->user) {

            NotificationService::send(
                $company->user,
                'verification_revoked',
                'Company Verification Revoked',
                "⚠️ Your company verification has been revoked.\n\nReference: {$ticketNumber}\n\nYou will need to complete the verification process again.",
                route('employer.company-profile.edit'),
                'shield-alt'
            );
        }

        return back()->with('toast', [
            'type' => 'warning',
            'message' => "Company verification has been revoked! Ticket: {$ticketNumber}",
        ]);
    }
}
