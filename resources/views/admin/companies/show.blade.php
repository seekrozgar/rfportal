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

                        @else

                            <span class="badge bg-light text-dark border fs-6">
                                UNVERIFIED
                            </span>

                        @endif

                    </div>

                </div>

            </div>

        </div>


        {{-- Fraud Warning --}}
        @if($company->is_fraud)

            <div class="alert alert-danger border-0 shadow-sm">

                <h6 class="fw-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Fraud Alert
                </h6>

                <div>
                    {{ $company->fraud_reason }}
                </div>

                @if($company->fraud_marked_at)

                    <small class="d-block mt-2">
                        Marked {{ $company->fraud_marked_at->diffForHumans() }}
                    </small>

                @endif

            </div>

        @endif


        <div class="row g-4">

            {{-- Company Information --}}
            <div class="col-lg-8">

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
                <div class="card border-0 shadow-sm">

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
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm mb-4">

                    <div class="card-header bg-white">

                        <strong>
                            <i class="fas fa-gavel text-danger me-2"></i>
                            Admin Actions
                        </strong>

                    </div>

                    <div class="card-body">

                        {{-- Approve --}}
                        @if($company->verification_status === 'pending' && !$company->is_fraud)

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
                        @if(!$company->is_suspended && !$company->is_fraud)

                            <button type="button" class="btn btn-outline-secondary w-100 mb-2" data-bs-toggle="modal"
                                data-bs-target="#suspendModal">
                                <i class="fas fa-ban me-1"></i>
                                Suspend Company
                            </button>

                        @endif


                        {{-- Restore --}}
                        @if($company->is_suspended && !$company->is_fraud)

                            <form method="POST" action="{{ route('admin.companies.restore', $company) }}" class="mb-2">

                                @csrf

                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-undo me-1"></i>
                                    Restore Company
                                </button>

                            </form>

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
                <div class="card border-0 shadow-sm">

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

                    </div>

                </div>

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
                        Reject Verification
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Rejection Reason
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Explain why the verification request is rejected..."></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
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
                        Suspend Company
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                </div>

                <div class="modal-body">

                    <label class="form-label">
                        Suspension Reason
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Why is this company being suspended?"></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-secondary">
                        Suspend Company
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

                        Marking this company as fraud will also
                        suspend its account.

                    </div>

                    <label class="form-label">
                        Fraud Reason
                    </label>

                    <textarea name="reason" class="form-control" rows="5" required
                        placeholder="Enter detailed reason/evidence..."></textarea>

                </div>

                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-danger">
                        Mark as Fraud
                    </button>

                </div>

            </form>

        </div>
    </div>

@endsection
