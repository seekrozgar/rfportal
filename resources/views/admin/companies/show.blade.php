@extends('admin.layouts.admin')

@section('title', 'Company Review')
@section('page-title', 'Company Review')
@section('page-subtitle', 'Review company verification and account status')

@section('content')

    <div class="container-fluid px-4">

        {{-- Header --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                    <div class="d-flex align-items-center gap-3">

                        @if($company->logo)

                            <img src="{{ asset('storage/' . $company->logo) }}" style="
                                        width:80px;
                                        height:80px;
                                        object-fit:contain;
                                        border-radius:12px;
                                        border:1px solid #e2e8f0;
                                    ">

                        @else

                            <div style="
                                        width:80px;
                                        height:80px;
                                        border-radius:12px;
                                        background:#f1f5f9;
                                        display:flex;
                                        align-items:center;
                                        justify-content:center;
                                    ">
                                <i class="fas fa-building fa-2x text-muted"></i>
                            </div>

                        @endif

                        <div>

                            <h4 class="mb-1">
                                {{ $company->name }}
                            </h4>

                            <div class="text-muted">
                                {{ $company->industry ?: 'Industry not specified' }}
                            </div>

                            {{-- Show latest ticket --}}
                            @if($company->latestAuditLog())
                                <small class="text-muted">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    Latest Ticket: <strong>{{ $company->latestAuditLog()->ticket_number }}</strong>
                                </small>
                            @endif

                        </div>

                    </div>


                    <div>

                        @if($company->is_fraud)

                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                FRAUD
                            </span>

                        @elseif($company->is_suspended)

                            <span class="badge bg-secondary fs-6">
                                <i class="fas fa-ban me-1"></i>
                                SUSPENDED
                            </span>

                        @elseif($company->is_blocked)

                            <span class="badge bg-dark fs-6">
                                <i class="fas fa-lock me-1"></i>
                                BLOCKED
                            </span>

                        @elseif($company->verification_status === 'verified')

                            <span class="badge bg-success fs-6">
                                <i class="fas fa-check-circle me-1"></i>
                                VERIFIED
                            </span>

                        @elseif($company->verification_status === 'pending')

                            <span class="badge bg-warning text-dark fs-6">
                                <i class="fas fa-clock me-1"></i>
                                VERIFICATION PENDING
                            </span>

                        @elseif($company->verification_status === 'rejected')

                            <span class="badge bg-danger fs-6">
                                <i class="fas fa-times-circle me-1"></i>
                                REJECTED
                            </span>

                        @else

                            <span class="badge bg-light text-dark border fs-6">
                                UNVERIFIED
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Status Alerts --}}

        {{-- Fraud Warning --}}
        @if($company->is_fraud)

            <div class="alert alert-danger border-0 shadow-sm">

                <h6 class="fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Fraud Alert
                </h6>

                <div>
                    {{ $company->fraud_reason ?? 'No reason provided.' }}
                </div>

                @if($company->fraud_marked_at)

                    <small class="d-block mt-2">
                        Marked {{ $company->fraud_marked_at->diffForHumans() }}
                        @if($company->fraud_marked_by)
                            by {{ $company->fraudMarkedBy?->name ?? 'Unknown' }}
                        @endif
                    </small>

                @endif

            </div>

        @endif

        {{-- Suspension Warning --}}
        @if($company->is_suspended && !$company->is_fraud)

            <div class="alert alert-warning border-0 shadow-sm">

                <h6 class="fw-bold">
                    <i class="fas fa-ban me-2"></i>
                    Company Suspended
                </h6>

                <div>
                    @php
                        $reason = $company->verification_admin_note
                            ?? $company->verification_rejection_reason
                            ?? 'No reason provided.';
                    @endphp
                    {{ $reason }}
                </div>

                @if($company->latestAuditLog())
                    <small class="d-block mt-2 text-muted">
                        <i class="fas fa-ticket-alt me-1"></i>
                        Ticket: {{ $company->latestAuditLog()->ticket_number }}
                        <br>
                        Action: {!! $company->latestAuditLog()->action_badge !!}
                        <br>
                        By: {{ $company->latestAuditLog()->admin?->name ?? 'Unknown' }}
                        <br>
                        At: {{ $company->latestAuditLog()->created_at->format('d M Y, h:i A') }}
                    </small>
                @endif

            </div>

        @endif

        {{-- Rejection Warning --}}
        @if($company->verification_status === 'rejected' && !$company->is_suspended)

            <div class="alert alert-danger border-0 shadow-sm">

                <h6 class="fw-bold">
                    <i class="fas fa-times-circle me-2"></i>
                    Verification Rejected
                </h6>

                <div>
                    {{ $company->verification_rejection_reason ?? 'No reason provided.' }}
                </div>

                @if($company->verification_reviewed_at)
                    <small class="d-block mt-2 text-muted">
                        Reviewed {{ $company->verification_reviewed_at->diffForHumans() }}
                        @if($company->verification_reviewed_by)
                            by {{ $company->reviewedBy?->name ?? 'Unknown' }}
                        @endif
                    </small>
                @endif

            </div>

        @endif

        {{-- Block Warning --}}
        @if($company->is_blocked)

            <div class="alert alert-dark border-0 shadow-sm">

                <h6 class="fw-bold">
                    <i class="fas fa-lock me-2"></i>
                    Company Blocked
                </h6>

                <div>
                    @php
                        $reason = $company->verification_admin_note
                            ?? $company->verification_rejection_reason
                            ?? 'No reason provided.';
                    @endphp
                    {{ $reason }}
                </div>

            </div>

        @endif


        <div class="row g-4">

            {{-- Company Information --}}
            <div class="col-lg-7">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">
                        <strong>
                            <i class="fas fa-building text-success me-2"></i>
                            Company Information
                        </strong>
                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Company Name
                                </small>

                                <div class="fw-semibold">
                                    {{ $company->name }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Industry
                                </small>

                                <div>
                                    {{ $company->industry ?: 'N/A' }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Email
                                </small>

                                <div>
                                    {{ $company->email ?: 'N/A' }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Phone
                                </small>

                                <div>
                                    {{ $company->phone ?: 'N/A' }}
                                </div>

                            </div>

                            <div class="col-12">

                                <small class="text-muted">
                                    Website
                                </small>

                                <div>

                                    @if($company->website)

                                        <a href="{{ $company->website }}" target="_blank" rel="noopener">
                                            {{ $company->website }}
                                        </a>

                                    @else

                                        N/A

                                    @endif

                                </div>

                            </div>

                            <div class="col-12">

                                <small class="text-muted">
                                    Address
                                </small>

                                <div>
                                    {{ $company->address ?: 'N/A' }}
                                </div>

                            </div>

                            <div class="col-12">

                                <small class="text-muted">
                                    Description
                                </small>

                                <div class="mt-1">
                                    {{ $company->description ?: 'No description provided.' }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Verification Documents --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <strong>
                            <i class="fas fa-shield-alt text-success me-2"></i>
                            Verification Documents
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-6">

                                <small class="text-muted">
                                    NTN Number
                                </small>

                                <div class="fw-semibold">
                                    {{ $company->ntn_number ?: 'Not provided' }}
                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    SECP Number
                                </small>

                                <div class="fw-semibold">
                                    {{ $company->secp_number ?: 'Not provided' }}
                                </div>

                            </div>

                            <div class="col-12">

                                <small class="text-muted d-block mb-2">
                                    Business License
                                </small>

                                @if($company->business_license)

                                    <a href="{{ asset('storage/' . $company->business_license) }}" target="_blank"
                                        class="btn btn-outline-primary">
                                        <i class="fas fa-file me-1"></i>
                                        View License
                                    </a>

                                @else

                                    <span class="text-muted">
                                        No license uploaded.
                                    </span>

                                @endif

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Social Media --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <strong>
                            <i class="fas fa-share-alt text-success me-2"></i>
                            Social Media
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="row g-3">

                            @foreach([
                                    'facebook' => 'Facebook',
                                    'linkedin' => 'LinkedIn',
                                    'twitter' => 'Twitter',
                                    'instagram' => 'Instagram',
                                    'youtube' => 'YouTube'
                                ] as $field => $label)

                                <div class="col-md-6">

                                    <small class="text-muted">
                                        {{ $label }}
                                    </small>

                                    <div>

                                        @if($company->{$field})

                                            <a href="{{ $company->{$field} }}" target="_blank" rel="noopener">
                                                Visit {{ $label }}
                                            </a>

                                        @else

                                            <span class="text-muted">
                                                Not provided
                                            </span>

                                        @endif

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            </div>


            {{-- Admin Actions --}}
            <div class="col-lg-5">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <strong>
                            <i class="fas fa-gavel text-danger me-2"></i>
                            Admin Actions
                        </strong>

                    </div>

                    <div class="card-body">

                        {{-- Approve --}}
                        @if($company->verification_status === 'pending' && !$company->is_fraud && !$company->is_suspended)

                            <form method="POST" action="{{ route('admin.companies.approve', $company) }}" class="mb-2">

                                @csrf

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Approve Verification
                                </button>

                            </form>


                            {{-- Reject --}}
                            <button type="button" class="btn btn-outline-danger w-100 mb-3" data-bs-toggle="modal"
                                data-bs-target="#rejectModal">
                                <i class="fas fa-times-circle me-1"></i>
                                Reject Verification
                            </button>

                        @endif


                        {{-- Unverify --}}
                        @if($company->verification_status === 'verified')

                            <form method="POST" action="{{ route('admin.companies.unverify', $company) }}" class="mb-2">

                                @csrf

                                <button type="submit" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-shield-alt me-1"></i>
                                    Revoke Verification
                                </button>

                            </form>

                        @endif


                        {{-- Suspend --}}
                        @if(!$company->is_suspended && !$company->is_fraud && !$company->is_blocked)

                            <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#suspendModal">
                                <i class="fas fa-ban me-1"></i>
                                Suspend Company
                            </button>

                        @endif


                        {{-- Restore --}}
                        @if(($company->is_suspended || $company->is_blocked) && !$company->is_fraud)

                            <form method="POST" action="{{ route('admin.companies.restore', $company) }}" class="mb-2">

                                @csrf

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-undo me-1"></i>
                                    Restore Company
                                </button>

                            </form>

                        @endif


                        {{-- Block --}}
                        @if(!$company->is_blocked && !$company->is_fraud)

                            <button type="button" class="btn btn-outline-dark w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#blockModal">
                                <i class="fas fa-lock me-1"></i>
                                Block Company
                            </button>

                        @endif


                        {{-- Fraud --}}
                        @if(!$company->is_fraud)

                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#fraudModal">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                Mark as Fraud
                            </button>

                        @else

                            <form method="POST" action="{{ route('admin.companies.remove-fraud', $company) }}">

                                @csrf

                                <button type="submit" class="btn btn-outline-success w-100">
                                    <i class="fas fa-check me-1"></i>
                                    Remove Fraud Flag
                                </button>

                            </form>

                        @endif

                    </div>

                </div>


                {{-- Employer --}}
                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <strong>
                            <i class="fas fa-user text-success me-2"></i>
                            Employer
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="fw-semibold">
                            {{ $company->user?->name ?? 'N/A' }}
                        </div>

                        <div class="text-muted small mt-1">
                            {{ $company->user?->email ?? 'N/A' }}
                        </div>

                        <hr>

                        <small class="text-muted">
                            Company Created
                        </small>

                        <div>
                            {{ $company->created_at->format('d M Y, h:i A') }}
                        </div>

                        @if($company->verification_requested_at)

                            <hr>

                            <small class="text-muted">
                                Verification Requested
                            </small>

                            <div>
                                {{ $company->verification_requested_at->format('d M Y, h:i A') }}
                            </div>

                        @endif

                        @if($company->verified_at)

                            <hr>

                            <small class="text-muted">
                                Verified At
                            </small>

                            <div>
                                {{ $company->verified_at->format('d M Y, h:i A') }}
                                @if($company->verified_by)
                                    by {{ $company->verifiedBy?->name ?? 'Unknown' }}
                                @endif
                            </div>

                        @endif

                    </div>

                </div>

                {{-- Current Status Summary --}}
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <strong>
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Current Status Summary
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="row g-2">

                            <div class="col-6">
                                <small class="text-muted">Verification</small>
                                <div>
                                    @if($company->verification_status === 'verified')
                                        <span class="badge bg-success">Verified</span>
                                    @elseif($company->verification_status === 'pending')
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @elseif($company->verification_status === 'rejected')
                                        <span class="badge bg-danger">Rejected</span>
                                    @else
                                        <span class="badge bg-secondary">Unverified</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-6">
                                <small class="text-muted">Account</small>
                                <div>
                                    @if($company->is_fraud)
                                        <span class="badge bg-danger">Fraud</span>
                                    @elseif($company->is_suspended)
                                        <span class="badge bg-warning text-dark">Suspended</span>
                                    @elseif($company->is_blocked)
                                        <span class="badge bg-dark">Blocked</span>
                                    @else
                                        <span class="badge bg-success">Active</span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        @if($company->verification_rejection_reason)
                            <hr>
                            <small class="text-muted">Current Reason</small>
                            <div class="text-danger small">
                                {{ $company->verification_rejection_reason }}
                            </div>
                        @endif

                        @if($company->verification_admin_note)
                            <hr>
                            <small class="text-muted">Admin Note</small>
                            <div class="text-secondary small">
                                {{ $company->verification_admin_note }}
                            </div>
                        @endif

                        @if($company->latestAuditLog())
                            <hr>
                            <small class="text-muted">Latest Action</small>
                            <div>
                                {!! $company->latestAuditLog()->action_badge !!}
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-ticket-alt me-1"></i>
                                    {{ $company->latestAuditLog()->ticket_number }}
                                    <br>
                                    {{ $company->latestAuditLog()->created_at->diffForHumans() }}
                                    <br>
                                    by {{ $company->latestAuditLog()->admin?->name ?? 'Unknown' }}
                                </small>
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        {{-- ============================================================
            AUDIT TRAIL - FULL HISTORY
            ============================================================ --}}
        <div class="card border-0 shadow-sm mt-4">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">

                <strong>
                    <i class="fas fa-history text-primary me-2"></i>
                    Audit Trail & Activity Log
                </strong>

                <span class="badge bg-secondary">{{ $company->auditLogs->count() }} logs</span>

            </div>

            <div class="card-body">

                @if($company->auditLogs->count() > 0)

                    <div class="table-responsive">

                        <table class="table table-hover">

                            <thead>

                                <tr>
                                    <th>Ticket #</th>
                                    <th>Action</th>
                                    <th>Reason</th>
                                    <th>Admin Note</th>
                                    <th>Admin</th>
                                    <th>Date/Time</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($company->auditLogs as $log)

                                    <tr>
                                        <td>
                                            <span class="badge bg-dark">{{ $log->ticket_number }}</span>
                                        </td>
                                        <td>
                                            {!! $log->action_badge !!}
                                            <br>
                                            <small class="text-muted">
                                                Status: {{ $log->status_before ?? 'N/A' }}
                                                <i class="fas fa-arrow-right mx-1"></i>
                                                {{ $log->status_after ?? 'N/A' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($log->reason)
                                                <span title="{{ $log->reason }}" data-bs-toggle="tooltip">
                                                    {{ Str::limit($log->reason, 50) }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($log->admin_note)
                                                <span title="{{ $log->admin_note }}" data-bs-toggle="tooltip">
                                                    {{ Str::limit($log->admin_note, 40) }}
                                                </span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ $log->admin?->name ?? 'Unknown' }}
                                                <br>
                                                <span class="text-muted">{{ $log->admin?->email ?? '' }}</span>
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $log->created_at->format('d M Y, h:i A') }}
                                                <br>
                                                <span class="text-muted">{{ $log->created_at->diffForHumans() }}</span>
                                                @if($log->ip_address)
                                                    <br>
                                                    <span class="text-muted">IP: {{ $log->ip_address }}</span>
                                                @endif
                                            </small>
                                        </td>
                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                @else

                    <p class="text-muted mb-0">No audit logs available for this company.</p>

                @endif

            </div>

        </div>

    </div>


    {{-- Reject Modal --}}
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">

            <form method="POST" action="{{ route('admin.companies.reject', $company) }}" class="modal-content">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="fas fa-times-circle text-danger me-2"></i>
                        Reject Verification
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-1"></i>
                        A ticket number will be generated automatically for this action.
                    </div>

                    <label class="form-label fw-bold">
                        Rejection Reason <span class="text-danger">*</span>
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Explain why the verification request is rejected..."></textarea>

                    <small class="text-muted mt-2 d-block">
                        This reason will be shown to the employer.
                    </small>

                    <hr>

                    <label class="form-label fw-bold">
                        Internal Admin Note (Optional)
                    </label>

                    <textarea name="admin_note" class="form-control" rows="3"
                        placeholder="Add internal notes for admin reference..."></textarea>

                    <small class="text-muted mt-2 d-block">
                        This note will only be visible to admins.
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle me-1"></i>
                        Reject Verification
                    </button>

                </div>

            </form>

        </div>
    </div>


    {{-- Suspend Modal --}}
    <div class="modal fade" id="suspendModal" tabindex="-1">
        <div class="modal-dialog">

            <form method="POST" action="{{ route('admin.companies.suspend', $company) }}" class="modal-content">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="fas fa-ban text-warning me-2"></i>
                        Suspend Company
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Suspending will also revoke verification. A ticket number will be generated automatically.
                    </div>

                    <label class="form-label fw-bold">
                        Suspension Reason <span class="text-danger">*</span>
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Why is this company being suspended?"></textarea>

                    <small class="text-muted mt-2 d-block">
                        This reason will be shown to the employer.
                    </small>

                    <hr>

                    <label class="form-label fw-bold">
                        Internal Admin Note (Optional)
                    </label>

                    <textarea name="admin_note" class="form-control" rows="3"
                        placeholder="Add internal notes for admin reference..."></textarea>

                    <small class="text-muted mt-2 d-block">
                        This note will only be visible to admins.
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-ban me-1"></i>
                        Suspend Company
                    </button>

                </div>

            </form>

        </div>
    </div>


    {{-- Block Modal --}}
    <div class="modal fade" id="blockModal" tabindex="-1">
        <div class="modal-dialog">

            <form method="POST" action="{{ route('admin.companies.block', $company) }}" class="modal-content">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title">
                        <i class="fas fa-lock text-dark me-2"></i>
                        Block Company
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-dark">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Blocking will also revoke verification. A ticket number will be generated automatically.
                    </div>

                    <label class="form-label fw-bold">
                        Block Reason <span class="text-danger">*</span>
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Why is this company being blocked?"></textarea>

                    <small class="text-muted mt-2 d-block">
                        This reason will be shown to the employer.
                    </small>

                    <hr>

                    <label class="form-label fw-bold">
                        Internal Admin Note (Optional)
                    </label>

                    <textarea name="admin_note" class="form-control" rows="3"
                        placeholder="Add internal notes for admin reference..."></textarea>

                    <small class="text-muted mt-2 d-block">
                        This note will only be visible to admins.
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-dark">
                        <i class="fas fa-lock me-1"></i>
                        Block Company
                    </button>

                </div>

            </form>

        </div>
    </div>


    {{-- Fraud Modal --}}
    <div class="modal fade" id="fraudModal" tabindex="-1">
        <div class="modal-dialog">

            <form method="POST" action="{{ route('admin.companies.fraud', $company) }}" class="modal-content">

                @csrf

                <div class="modal-header">

                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Mark Company as Fraud
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <div class="alert alert-danger">

                        <strong>Warning:</strong>
                        Marking this company as fraud will also suspend its account and revoke verification.
                        A ticket number will be generated automatically.

                    </div>

                    <label class="form-label fw-bold">
                        Fraud Reason <span class="text-danger">*</span>
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Enter detailed reason/evidence..."></textarea>

                    <small class="text-muted mt-2 d-block">
                        This reason will be shown to the employer.
                    </small>

                    <hr>

                    <label class="form-label fw-bold">
                        Internal Admin Note (Optional)
                    </label>

                    <textarea name="admin_note" class="form-control" rows="3"
                        placeholder="Add internal notes for admin reference..."></textarea>

                    <small class="text-muted mt-2 d-block">
                        This note will only be visible to admins.
                    </small>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Mark as Fraud
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
