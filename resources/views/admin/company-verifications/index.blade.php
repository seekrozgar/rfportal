@extends('admin.layouts.admin')

@section('title', 'Company Verification')
@section('page-title', 'Company Verification')
@section('page-subtitle', 'Review company verification requests')

@section('content')

    <div class="container-fluid px-4">

        <div class="card shadow-sm border-0">

            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-shield-alt text-warning me-2"></i>
                        Pending Verification Requests
                    </h5>

                    <small class="text-muted">
                        Companies waiting for admin verification
                    </small>
                </div>

                <span class="badge bg-warning text-dark">
                    {{ $companies->total() }} Pending
                </span>
            </div>

            <div class="card-body p-0">

                @if($companies->count())

                    <div class="table-responsive">

                        <table class="table table-hover align-middle mb-0">

                            <thead class="table-light">

                                <tr>
                                    <th>Company</th>
                                    <th>Employer</th>
                                    <th>Industry</th>
                                    <th>Requested</th>
                                    <th>Status</th>
                                    <th class="text-end">Action</th>
                                </tr>

                            </thead>

                            <tbody>

                                @foreach($companies as $company)

                                    <tr>

                                        <td>
                                            <div class="d-flex align-items-center gap-2">

                                                @if($company->logo)

                                                    <img src="{{ asset('storage/' . $company->logo) }}"
                                                        style="width:45px;height:45px;object-fit:contain;border-radius:8px;border:1px solid #eee;"
                                                        alt="{{ $company->name }}">

                                                @else

                                                    <div
                                                        style="width:45px;height:45px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                                                        <i class="fas fa-building text-muted"></i>
                                                    </div>

                                                @endif

                                                <div>
                                                    <strong>
                                                        {{ $company->name }}
                                                    </strong>

                                                    <small class="d-block text-muted">
                                                        #{{ $company->id }}
                                                    </small>
                                                </div>

                                            </div>
                                        </td>

                                        <td>
                                            {{ $company->user->name ?? 'N/A' }}
                                        </td>

                                        <td>
                                            {{ $company->industry ?? 'N/A' }}
                                        </td>

                                        <td>
                                            @if($company->verification_requested_at)
                                                {{ $company->verification_requested_at->format('d M Y, h:i A') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>

                                        <td>

                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-clock me-1"></i>
                                                Pending
                                            </span>

                                        </td>

                                        <td class="text-end">

                                            <a href="{{ route('admin.company-verifications.show', $company) }}"
                                                class="btn btn-sm btn-primary">

                                                <i class="fas fa-eye me-1"></i>
                                                Review

                                            </a>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>

                    <div class="p-3">
                        {{ $companies->links() }}
                    </div>

                @else

                    <div class="text-center py-5">

                        <i class="fas fa-shield-alt fa-3x text-muted mb-3"></i>

                        <h5>No Pending Requests</h5>

                        <p class="text-muted mb-0">
                            There are currently no company verification requests.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

@endsection
