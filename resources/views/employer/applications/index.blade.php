{{-- resources/views/employer/applications/index.blade.php --}}

@extends('employer.layouts.employer')

@section('title', 'Applications')
@section('page-title', 'Job Applications')
@section('page-subtitle', 'Manage all job applications')

@push('styles')
    <style>
        .stats-card {
            background: #fff;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid #eef2f6;
            transition: all 0.3s ease;
            text-align: center;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stats-card .stats-number {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .stats-card .stats-label {
            font-size: 12px;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 2px;
        }

        .stats-card .stats-icon {
            font-size: 20px;
            margin-right: 8px;
        }

        .stats-card.pending .stats-icon {
            color: #f59e0b;
        }

        .stats-card.reviewing .stats-icon {
            color: #3b82f6;
        }

        .stats-card.shortlisted .stats-icon {
            color: #8b5cf6;
        }

        .stats-card.interview .stats-icon {
            color: #06b6d4;
        }

        .stats-card.hired .stats-icon {
            color: #22c55e;
        }

        .stats-card.rejected .stats-icon {
            color: #ef4444;
        }

        .stats-card.total .stats-icon {
            color: #6366f1;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.reviewing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.shortlisted {
            background: #ede9fe;
            color: #5b21b6;
        }

        .status-badge.interview {
            background: #cffafe;
            color: #0e7490;
        }

        .status-badge.offered {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.hired {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .filter-bar {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid #eef2f6;
            margin-bottom: 20px;
        }

        .filter-bar .form-select,
        .filter-bar .form-control {
            border-radius: 8px;
            border-color: #e2e8f0;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Stats Cards --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-2 col-6">
                        <div class="stats-card total">
                            <div>
                                <span class="stats-icon"><i class="fas fa-file-alt"></i></span>
                                <span class="stats-number">{{ $totalApplications }}</span>
                            </div>
                            <div class="stats-label">Total Applications</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card pending">
                            <div>
                                <span class="stats-icon"><i class="fas fa-clock"></i></span>
                                <span class="stats-number">{{ $pendingCount }}</span>
                            </div>
                            <div class="stats-label">Pending</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card reviewing">
                            <div>
                                <span class="stats-icon"><i class="fas fa-search"></i></span>
                                <span class="stats-number">{{ $reviewingCount }}</span>
                            </div>
                            <div class="stats-label">Reviewing</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card shortlisted">
                            <div>
                                <span class="stats-icon"><i class="fas fa-star"></i></span>
                                <span class="stats-number">{{ $shortlistedCount }}</span>
                            </div>
                            <div class="stats-label">Shortlisted</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card hired">
                            <div>
                                <span class="stats-icon"><i class="fas fa-check-circle"></i></span>
                                <span class="stats-number">{{ $hiredCount }}</span>
                            </div>
                            <div class="stats-label">Hired</div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card rejected">
                            <div>
                                <span class="stats-icon"><i class="fas fa-times-circle"></i></span>
                                <span class="stats-number">{{ $rejectedCount }}</span>
                            </div>
                            <div class="stats-label">Rejected</div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Filter Bar --}}
                <div class="filter-bar">
                    <form method="GET" action="{{ route('employer.applications.index') }}" class="row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status</label>
                            <select name="status" class="form-select" onchange="this.form.submit()">
                                @foreach($statuses as $key => $label)
                                    <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Job</label>
                            <select name="job_id" class="form-select" onchange="this.form.submit()">
                                <option value="">All Jobs</option>
                                @foreach($jobs as $job)
                                    <option value="{{ $job->id }}" {{ request('job_id') == $job->id ? 'selected' : '' }}>
                                        {{ Str::limit($job->title, 30) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Search by name, email, reference..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ✅ Applications Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-file-alt me-2 text-primary"></i> Applications
                            @if($unreadCount > 0)
                                <span class="badge bg-danger ms-2">{{ $unreadCount }} New</span>
                            @endif
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        @if($applications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width:40px;">
                                                <input type="checkbox" class="form-check-input" id="selectAll">
                                            </th>
                                            <th>Reference</th>
                                            <th>Candidate</th>
                                            <th>Job</th>
                                            <th>Applied Date</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($applications as $application)
                                            <tr class="{{ !$application->is_read ? 'fw-bold' : '' }}">
                                                <td>
                                                    <input type="checkbox" class="form-check-input application-checkbox"
                                                        data-id="{{ $application->id }}">
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        {{ $application->application_reference }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $application->user->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $application->user->email }}</small>
                                                    </div>
                                                </td>
                                                <td>{{ Str::limit($application->job->title, 25) }}</td>
                                                <td>{{ $application->submitted_at->format('d M, Y') }}</td>
                                                <td>{!! $application->status_badge !!}</td>
                                                <td class="text-end">
                                                    <div class="d-flex gap-2 justify-content-end">
                                                        <a href="{{ route('employer.applications.show', $application) }}"
                                                            class="btn btn-sm btn-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <button onclick="changeStatus({{ $application->id }})"
                                                            class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-exchange-alt"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Applications Found</h5>
                                <p class="text-muted">When candidates apply, they'll appear here.</p>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of
                                {{ $applications->total() }} entries
                            </div>
                            <div>{{ $applications->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function changeStatus(id) {
                // Will implement status change modal
                alert('Status change functionality will be implemented in the show view.');
            }

            // ✅ Select All
            document.getElementById('selectAll')?.addEventListener('change', function () {
                document.querySelectorAll('.application-checkbox').forEach(cb => {
                    cb.checked = this.checked;
                });
            });

            // ✅ Toast messages
            @if(session('toast'))
                const toast = @json(session('toast'));
                showToast(toast.type, toast.message);
            @endif
        </script>
    @endpush
@endsection