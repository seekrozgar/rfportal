@extends('admin.layouts.admin')

@section('title', 'Review Company Verification')
@section('page-title', 'Review Company Verification')
@section('page-subtitle', 'Review company information and documents')

@section('content')

    <div class="container-fluid px-4">

        <div class="row g-4">

            {{-- Company Information --}}
            <div class="col-lg-8">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            <i class="fas fa-building text-primary me-2"></i>
                            Company Information
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="d-flex align-items-center gap-3 mb-4">

                            @if($company->logo)

                                <img src="{{ asset('storage/' . $company->logo) }}"
                                    style="width:90px;height:90px;object-fit:contain;border:1px solid #eee;border-radius:12px;"
                                    alt="{{ $company->name }}">

                            @else

                                <div
                                    style="width:90px;height:90px;border-radius:12px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">

                                    <i class="fas fa-building fa-2x text-muted"></i>

                                </div>

                            @endif

                            <div>

                                <h4 class="mb-1">
                                    {{ $company->name }}
                                </h4>

                                <span class="badge bg-warning text-dark">
                                    Verification Pending
                                </span>

                            </div>

                        </div>

                        <div class="row g-3">

                            <div class="col-md-6">
                                <strong>Industry</strong>
                                <div class="text-muted">
                                    {{ $company->industry ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <strong>Company Size</strong>
                                <div class="text-muted">
                                    {{ $company->company_size ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <strong>Email</strong>
                                <div class="text-muted">
                                    {{ $company->email ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <strong>Phone</strong>
                                <div class="text-muted">
                                    {{ $company->phone ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <strong>Website</strong>
                                <div>
                                    @if($company->website)
                                        <a href="{{ $company->website }}" target="_blank">
                                            {{ $company->website }}
                                        </a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <strong>Headquarters</strong>
                                <div class="text-muted">
                                    {{ $company->headquarters ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <strong>Address</strong>
                                <div class="text-muted">
                                    {{ $company->address ?? 'N/A' }}
                                </div>
                            </div>

                            <div class="col-12">
                                <strong>Description</strong>
                                <div class="text-muted">
                                    {{ $company->description ?? 'N/A' }}
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Verification Panel --}}
            <div class="col-lg-4">

                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            <i class="fas fa-shield-alt text-warning me-2"></i>
                            Verification
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted">
                                Requested At
                            </small>

                            <div class="fw-semibold">

                                {{ $company->verification_requested_at
        ? $company->verification_requested_at->format('d M Y, h:i A')
        : 'N/A'
                                }}

                            </div>

                        </div>


                        <hr>


                        {{-- Approve --}}
                        <form action="{{ route('admin.company-verifications.approve', $company) }}" method="POST"
                            class="mb-3">

                            @csrf

                            <button type="submit" class="btn btn-success w-100">

                                <i class="fas fa-check-circle me-2"></i>
                                Approve Verification

                            </button>

                        </form>


                        {{-- Reject --}}

                        <form action="{{ route('admin.company-verifications.reject', $company) }}" method="POST">

                            @csrf

                            <div class="mb-3">

                                <label class="form-label fw-semibold">
                                    Rejection Reason
                                </label>

                                <textarea name="reason" class="form-control" rows="4"
                                    placeholder="Explain why verification is being rejected..." required></textarea>

                            </div>

                            <button type="submit" class="btn btn-outline-danger w-100">

                                <i class="fas fa-times-circle me-2"></i>
                                Reject Verification

                            </button>

                        </form>

                    </div>

                </div>


                {{-- Documents --}}

                <div class="card border-0 shadow-sm mt-4">

                    <div class="card-header bg-white">

                        <h5 class="mb-0">
                            <i class="fas fa-file-alt text-primary me-2"></i>
                            Documents
                        </h5>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted">
                                NTN Number
                            </small>

                            <div class="fw-semibold">
                                {{ $company->ntn_number ?? 'Not provided' }}
                            </div>

                        </div>

                        <div class="mb-3">

                            <small class="text-muted">
                                SECP Number
                            </small>

                            <div class="fw-semibold">
                                {{ $company->secp_number ?? 'Not provided' }}
                            </div>

                        </div>

                        @if($company->business_license)

                            <a href="{{ asset('storage/' . $company->business_license) }}" target="_blank"
                                class="btn btn-outline-primary w-100">

                                <i class="fas fa-file-pdf me-2"></i>
                                View Business License

                            </a>

                        @else

                            <div class="alert alert-warning mb-0">
                                Business license not provided.
                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
