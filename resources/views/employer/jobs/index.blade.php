{{-- resources/views/employer/jobs/index.blade.php --}}

@extends('employer.layouts.employer')

@section('title', 'My Jobs')
@section('page-title', 'My Job Postings')
@section('page-subtitle', 'Manage your employer job postings')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary"><i class="fas fa-briefcase"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalJobs ?? 0 }}</div>
                                    <div class="stats-label">Total Jobs</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:100%; background:#6366f1;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-success"><i class="fas fa-check-circle"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $activeJobs ?? 0 }}</div>
                                    <div class="stats-label">Active</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:70%; background:#22c55e;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning"><i class="fas fa-clock"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $pendingJobs ?? 0 }}</div>
                                    <div class="stats-label">Pending Verification</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:40%; background:#f59e0b;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger"><i class="fas fa-fire"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $expiredJobs ?? 0 }}</div>
                                    <div class="stats-label">Expired</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:30%; background:#ef4444;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jobs Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-briefcase me-2 text-primary"></i> My Job Postings
                        </h5>
                        <div>
                            <a href="{{ route('employer.jobs.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Post New Job
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>Job Title</th>
                                        <th style="width:120px;">Location</th>
                                        <th style="width:110px;">Deadline</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:100px;">Verification</th>
                                        <th style="width:160px;" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jobs ?? [] as $job)
                                        <tr id="row-{{ $job->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div>
                                                    <div class="fw-bold">{{ Str::limit($job->title, 40) }}</div>
                                                    <small class="text-muted">Posted:
                                                        {{ $job->created_at->format('d M, Y') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($job->location)
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ Str::limit($job->location, 20) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($job->deadline)
                                                    <div>
                                                        <strong>{{ $job->deadline->format('d M, Y') }}</strong>
                                                        @if($job->days_remaining > 0)
                                                            <br><small class="text-success">{{ $job->days_remaining }} days left</small>
                                                        @elseif($job->is_expired)
                                                            <br><small class="text-danger">Expired</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No deadline</span>
                                                @endif
                                            </td>
                                            <td>{!! $job->status_badge() !!}</td>
                                            <td>
                                                @if($job->is_verified())
                                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i>
                                                        Verified</span>
                                                @else
                                                    <span class="badge bg-warning"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="action-buttons">
                                                    <a href="{{ route('employer.jobs.edit', $job) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    @if(!$job->is_verified())
                                                        <span class="text-muted small">Awaiting verification</span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-briefcase fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No Jobs Posted Yet</h5>
                                                    <p class="text-muted small">Click "Post New Job" to get started.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of
                                {{ $jobs->total() ?? 0 }} entries
                            </div>
                            <div>{{ $jobs->links() ?? '' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection