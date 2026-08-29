@extends('admin.layouts.admin')

@section('title', 'Companies')
@section('page-title', 'Companies')
@section('page-subtitle', 'Manage employer companies and verification')

@section('content')

    <div class="container-fluid px-4">

        {{-- Statistics --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-muted">Total Companies</small>
                        <h3 class="mb-0 mt-1">{{ $stats['total'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-warning">Pending</small>
                        <h3 class="mb-0 mt-1">{{ $stats['pending'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-success">Verified</small>
                        <h3 class="mb-0 mt-1">{{ $stats['verified'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-danger">Fraud</small>
                        <h3 class="mb-0 mt-1">{{ $stats['fraud'] }}</h3>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <small class="text-secondary">Suspended</small>
                        <h3 class="mb-0 mt-1">{{ $stats['suspended'] }}</h3>
                    </div>
                </div>
            </div>

        </div>


        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-2">

                        <div class="col-md-5">

                            <input type="text" name="search" class="form-control"
                                placeholder="Search company name, email or phone..." value="{{ request('search') }}">

                        </div>

                        <div class="col-md-3">

                            <select name="status" class="form-select">

                                <option value="">All Status</option>

                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                                    Pending Verification
                                </option>

                                <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>
                                    Verified
                                </option>

                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>
                                    Rejected
                                </option>

                                <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>
                                    Unverified
                                </option>

                            </select>

                        </div>

                        <div class="col-md-2">

                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-search me-1"></i>
                                Search
                            </button>

                        </div>

                        <div class="col-md-2">

                            <a href="{{ route('admin.companies.index') }}" class="btn btn-light border w-100">
                                Reset
                            </a>

                        </div>

                    </div>

                </form>

            </div>

        </div>


        {{-- Companies --}}
        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <h5 class="mb-0">
                    <i class="fas fa-building text-success me-2"></i>
                    Companies
                </h5>

            </div>

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Company</th>
                            <th>Employer</th>
                            <th>Status</th>
                            <th>Account</th>
                            <th>Created</th>
                            <th class="text-end">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($companies as $company)

                            <tr>

                                <td>

                                    <div class="d-flex align-items-center gap-2">

                                        @if($company->logo)

                                            <img src="{{ asset('storage/' . $company->logo) }}" style="
                                                        width:42px;
                                                        height:42px;
                                                        object-fit:contain;
                                                        border-radius:8px;
                                                        border:1px solid #eee;
                                                    ">

                                        @else

                                            <div class="rounded" style="
                                                        width:42px;
                                                        height:42px;
                                                        background:#f1f5f9;
                                                        display:flex;
                                                        align-items:center;
                                                        justify-content:center;
                                                    ">
                                                <i class="fas fa-building text-muted"></i>
                                            </div>

                                        @endif

                                        <div>

                                            <strong>
                                                {{ $company->name }}
                                            </strong>

                                            <small class="d-block text-muted">
                                                {{ $company->email ?: 'No email' }}
                                            </small>

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    {{ $company->user?->name ?? 'N/A' }}

                                </td>

                                <td>

                                    @if($company->is_fraud)

                                        <span class="badge bg-danger">
                                            Fraud
                                        </span>

                                    @elseif($company->is_suspended)

                                        <span class="badge bg-secondary">
                                            Suspended
                                        </span>

                                    @elseif($company->verification_status === 'verified')

                                        <span class="badge bg-success">
                                            Verified
                                        </span>

                                    @elseif($company->verification_status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            Pending
                                        </span>

                                    @elseif($company->verification_status === 'rejected')

                                        <span class="badge bg-danger">
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark border">
                                            Unverified
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($company->is_active && !$company->is_suspended)

                                        <span class="text-success">
                                            <i class="fas fa-circle" style="font-size:7px;"></i>
                                            Active
                                        </span>

                                    @else

                                        <span class="text-danger">
                                            <i class="fas fa-circle" style="font-size:7px;"></i>
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $company->created_at->format('d M Y') }}
                                </td>

                                <td class="text-end">

                                    <a href="{{ route('admin.companies.show', $company) }}"
                                        class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-eye"></i>
                                        Review
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="fas fa-building fa-3x mb-3"></i>

                                    <div>
                                        No companies found.
                                    </div>

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            @if($companies->hasPages())

                <div class="card-footer bg-white">
                    {{ $companies->links() }}
                </div>

            @endif

        </div>

    </div>

@endsection
