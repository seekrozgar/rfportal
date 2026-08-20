{{-- resources/views/employer/dashboard/index.blade.php --}}
@extends('employer.layouts.dashboard')

@section('title', 'Employer Dashboard - Rozgar Finder')
@section('page-title', 'Employer Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name . '! Here\'s what\'s happening with your jobs.')

@section('content')
    <div class="dashboard-container">
        {{-- ✅ Company Missing Alert --}}
        @if(isset($companyMissing) && $companyMissing)
            <div class="company-missing-alert">
                <i class="fa fa-info-circle me-2"></i>
                <strong>Complete Your Company Profile!</strong>
                Please <a href="{{ route('employer.profile.edit') }}">update your company profile</a> to start posting jobs.
            </div>
        @endif

        {{-- ✅ Stats Cards --}}
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fa fa-briefcase"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalJobs ?? 0 }}</div>
                    <div class="stat-label">Total Jobs</div>
                    <div class="stat-progress">
                        <div class="progress-fill" style="width: 75%; background: #2563eb;"></div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalApplications ?? 0 }}</div>
                    <div class="stat-label">Applications</div>
                    <div class="stat-progress">
                        <div class="progress-fill" style="width: 60%; background: #22c55e;"></div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fa fa-eye"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalViews ?? 0 }}</div>
                    <div class="stat-label">Total Views</div>
                    <div class="stat-progress">
                        <div class="progress-fill" style="width: 45%; background: #f59e0b;"></div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon red"><i class="fa fa-star"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $featuredJobs ?? 0 }}</div>
                    <div class="stat-label">Featured Jobs</div>
                    <div class="stat-progress">
                        <div class="progress-fill" style="width: 30%; background: #ef4444;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ✅ Quick Actions --}}
        <div class="quick-actions">
            <h5 class="quick-actions-title"><i class="fa fa-bolt" style="color: #11998e; margin-right: 8px;"></i> Quick
                Actions</h5>
            <div class="quick-actions-grid">
                <a href="{{ route('employer.jobs.index') }}" class="quick-action">
                    <div class="action-icon primary"><i class="fa fa-list"></i></div>
                    <div class="action-text">
                        <div class="action-title">My Jobs</div>
                        <div class="action-desc">View all your posted jobs</div>
                    </div>
                </a>
                <a href="{{ route('employer.jobs.create') }}" class="quick-action">
                    <div class="action-icon success"><i class="fa fa-plus-circle"></i></div>
                    <div class="action-text">
                        <div class="action-title">Post Job</div>
                        <div class="action-desc">Create a new job listing</div>
                    </div>
                </a>
                <a href="{{ route('employer.applications.index') }}" class="quick-action">
                    <div class="action-icon warning"><i class="fa fa-users"></i></div>
                    <div class="action-text">
                        <div class="action-title">Applications</div>
                        <div class="action-desc">Review candidate applications</div>
                    </div>
                </a>
                <a href="{{ route('employer.profile.edit') }}" class="quick-action">
                    <div class="action-icon info"><i class="fa fa-building"></i></div>
                    <div class="action-text">
                        <div class="action-title">Company Profile</div>
                        <div class="action-desc">Update your company details</div>
                    </div>
                </a>
            </div>
        </div>

        {{-- ✅ Recent Applications --}}
        <div class="recent-card">
            <div class="card-header">
                <h5><i class="fa fa-clock" style="color: #11998e; margin-right: 8px;"></i> Recent Applications</h5>
                <a href="{{ route('employer.applications.index') }}">View All <i class="fa fa-arrow-right ms-1"></i></a>
            </div>
            <div class="card-body">
                @if(isset($recentApplications) && $recentApplications->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Candidate</th>
                                    <th>Job Title</th>
                                    <th>Applied Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentApplications as $application)
                                    <tr>
                                        <td>
                                            <strong>{{ $application->seeker->name ?? 'N/A' }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $application->seeker->email ?? '' }}</small>
                                        </td>
                                        <td>{{ $application->job->title ?? 'N/A' }}</td>
                                        <td>{{ $application->created_at->format('d M, Y') }}</td>
                                        <td>
                                            <span class="status-badge {{ $application->status }}">
                                                {{ ucfirst($application->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('employer.applications.show', $application) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa fa-inbox"></i>
                        <h5>No applications yet</h5>
                        <p>When candidates apply, they'll appear here.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
