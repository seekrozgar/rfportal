@extends('admin.layouts.admin')

@section('title', 'Companies')
@section('page-title', 'Companies')
@section('page-subtitle', 'Manage employer companies and verification')

@section('content')

    <div class="container-fluid px-4">

        {{-- ============================================================
        PROFESSIONAL STATISTICS CARDS
        ============================================================ --}}
        <div class="row g-4 mb-4">

            {{-- Total Companies --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-primary h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['total'] }}</div>
                            <div class="stats-card-label">Total Companies</div>
                        </div>
                        <div class="stats-card-trend">
                            <span class="trend-up">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        <div class="progress-bar" style="width: 100%;"></div>
                    </div>
                </div>
            </div>

            {{-- Pending Verification --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-warning h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['pending'] }}</div>
                            <div class="stats-card-label">Pending Verification</div>
                        </div>
                        <div class="stats-card-trend">
                            @if($stats['pending'] > 0)
                                <span class="trend-warning">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            @else
                                <span class="trend-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $pendingPercent = $stats['total'] > 0 ? ($stats['pending'] / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $pendingPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Verified --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-success h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['verified'] }}</div>
                            <div class="stats-card-label">Verified</div>
                        </div>
                        <div class="stats-card-trend">
                            <span class="trend-success">
                                <i class="fas fa-arrow-up"></i>
                            </span>
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $verifiedPercent = $stats['total'] > 0 ? ($stats['verified'] / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $verifiedPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Rejected --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-danger h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['rejected'] ?? 0 }}</div>
                            <div class="stats-card-label">Rejected</div>
                        </div>
                        <div class="stats-card-trend">
                            @if(($stats['rejected'] ?? 0) > 0)
                                <span class="trend-danger">
                                    <i class="fas fa-arrow-down"></i>
                                </span>
                            @else
                                <span class="trend-success">
                                    <i class="fas fa-check"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $rejectedPercent = $stats['total'] > 0 ? (($stats['rejected'] ?? 0) / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $rejectedPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Fraud --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-fraud h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['fraud'] }}</div>
                            <div class="stats-card-label">Fraud</div>
                        </div>
                        <div class="stats-card-trend">
                            @if($stats['fraud'] > 0)
                                <span class="trend-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </span>
                            @else
                                <span class="trend-success">
                                    <i class="fas fa-shield-alt"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $fraudPercent = $stats['total'] > 0 ? ($stats['fraud'] / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $fraudPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Suspended --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-suspended h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['suspended'] }}</div>
                            <div class="stats-card-label">Suspended</div>
                        </div>
                        <div class="stats-card-trend">
                            @if($stats['suspended'] > 0)
                                <span class="trend-warning">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            @else
                                <span class="trend-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $suspendedPercent = $stats['total'] > 0 ? ($stats['suspended'] / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $suspendedPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Blocked --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-blocked h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['blocked'] ?? 0 }}</div>
                            <div class="stats-card-label">Blocked</div>
                        </div>
                        <div class="stats-card-trend">
                            @if(($stats['blocked'] ?? 0) > 0)
                                <span class="trend-danger">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            @else
                                <span class="trend-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $blockedPercent = $stats['total'] > 0 ? (($stats['blocked'] ?? 0) / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $blockedPercent }}%;"></div>
                    </div>
                </div>
            </div>

            {{-- Unverified --}}
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="stats-card stats-card-unverified h-100">
                    <div class="stats-card-body">
                        <div class="stats-card-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div class="stats-card-info">
                            <div class="stats-card-number">{{ $stats['unverified'] ?? 0 }}</div>
                            <div class="stats-card-label">Unverified</div>
                        </div>
                        <div class="stats-card-trend">
                            @if(($stats['unverified'] ?? 0) > 0)
                                <span class="trend-warning">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                            @else
                                <span class="trend-success">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="stats-card-progress">
                        @php
                            $unverifiedPercent = $stats['total'] > 0 ? (($stats['unverified'] ?? 0) / $stats['total']) * 100 : 0;
                        @endphp
                        <div class="progress-bar" style="width: {{ $unverifiedPercent }}%;"></div>
                    </div>
                </div>
            </div>

        </div>


        {{-- Filters --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-2">

                        <div class="col-md-4">

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

                                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>
                                    Suspended
                                </option>

                                <option value="blocked" {{ request('status') === 'blocked' ? 'selected' : '' }}>
                                    Blocked
                                </option>

                                <option value="fraud" {{ request('status') === 'fraud' ? 'selected' : '' }}>
                                    Fraud
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

            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">

                <h5 class="mb-0">
                    <i class="fas fa-building text-success me-2"></i>
                    Companies
                    <span class="badge bg-secondary ms-2">{{ $companies->total() }}</span>
                </h5>

                <div>
                    <small class="text-muted">
                        <i class="fas fa-history me-1"></i>
                        Latest actions tracked with ticket numbers
                    </small>
                </div>

            </div>

            <div class="table-responsive">

                <table class="table align-middle mb-0">

                    <thead class="table-light">

                        <tr>
                            <th>Company</th>
                            <th>Employer</th>
                            <th>Status</th>
                            <th>Account</th>
                            <th>Latest Ticket</th>
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

                                            {{-- Show rejection/suspension reason if exists --}}
                                            @if($company->verification_rejection_reason)
                                                <small class="d-block text-danger"
                                                    title="{{ $company->verification_rejection_reason }}">
                                                    <i class="fas fa-info-circle"></i>
                                                    {{ Str::limit($company->verification_rejection_reason, 30) }}
                                                </small>
                                            @endif

                                        </div>

                                    </div>

                                </td>

                                <td>

                                    {{ $company->user?->name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">{{ $company->user?->email ?? '' }}</small>

                                </td>

                                <td>

                                    @if($company->is_fraud)

                                        <span class="badge bg-danger">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Fraud
                                        </span>

                                    @elseif($company->is_suspended)

                                        <span class="badge bg-secondary">
                                            <i class="fas fa-ban me-1"></i>
                                            Suspended
                                        </span>

                                    @elseif($company->is_blocked)

                                        <span class="badge bg-dark">
                                            <i class="fas fa-lock me-1"></i>
                                            Blocked
                                        </span>

                                    @elseif($company->verification_status === 'verified')

                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Verified
                                        </span>

                                    @elseif($company->verification_status === 'pending')

                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>
                                            Pending
                                        </span>

                                    @elseif($company->verification_status === 'rejected')

                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Rejected
                                        </span>

                                    @else

                                        <span class="badge bg-light text-dark border">
                                            Unverified
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    @if($company->is_active && !$company->is_suspended && !$company->is_blocked)

                                        <span class="text-success">
                                            <i class="fas fa-circle" style="font-size:7px;"></i>
                                            Active
                                        </span>

                                    @elseif($company->is_blocked)

                                        <span class="text-danger">
                                            <i class="fas fa-circle" style="font-size:7px;"></i>
                                            Blocked
                                        </span>

                                    @else

                                        <span class="text-danger">
                                            <i class="fas fa-circle" style="font-size:7px;"></i>
                                            Inactive
                                        </span>

                                    @endif

                                    {{-- Show admin note if exists --}}
                                    @if($company->verification_admin_note)
                                        <br>
                                        <small class="text-muted" title="{{ $company->verification_admin_note }}">
                                            <i class="fas fa-sticky-note"></i>
                                            {{ Str::limit($company->verification_admin_note, 20) }}
                                        </small>
                                    @endif

                                </td>

                                <td>

                                    @php
                                        $latestLog = $company->latestAuditLog();
                                    @endphp

                                    @if($latestLog)
                                        <span class="badge bg-dark" title="Ticket: {{ $latestLog->ticket_number }}">
                                            {{ $latestLog->ticket_number }}
                                        </span>
                                        <br>
                                        <small class="text-muted">
                                            {{ $latestLog->created_at->diffForHumans() }}
                                            <br>
                                            <span class="text-muted">
                                                {!! $latestLog->action_badge !!}
                                            </span>
                                        </small>
                                    @else
                                        <span class="text-muted">No actions</span>
                                    @endif

                                </td>

                                <td>
                                    {{ $company->created_at->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $company->created_at->diffForHumans() }}</small>
                                </td>

                                <td class="text-end">

                                    <div class="d-flex gap-1 justify-content-end">

                                        <a href="{{ route('admin.companies.show', $company) }}"
                                            class="btn btn-sm btn-outline-success">
                                            <i class="fas fa-eye"></i>
                                            Review
                                        </a>

                                        @if($company->verification_status === 'pending' && !$company->is_fraud)
                                            <form method="POST" action="{{ route('admin.companies.approve', $company) }}"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5 text-muted">
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
