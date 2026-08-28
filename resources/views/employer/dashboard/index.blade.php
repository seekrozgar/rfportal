{{-- resources/views/employer/dashboard/index.blade.php --}}

@extends('employer.layouts.employer')

@section('title', 'Employer Dashboard')
@section('page-title', 'Employer Dashboard')
@section('page-subtitle', 'Welcome back, ' . Auth::user()->name . '! Here\'s what\'s happening with your jobs.')

@push('styles')
    <style>
        /* ✅ Stats Cards - Your Existing Styles */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            border: 1px solid #eef2f6;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #fff;
            flex-shrink: 0;
        }

        .stat-icon.blue {
            background: #2563eb;
        }

        .stat-icon.green {
            background: #22c55e;
        }

        .stat-icon.yellow {
            background: #f59e0b;
        }

        .stat-icon.red {
            background: #ef4444;
        }

        .stat-icon.purple {
            background: #8b5cf6;
        }

        .stat-info {
            flex: 1;
        }

        .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }

        .stat-label {
            font-size: 13px;
            color: #94a3b8;
            font-weight: 500;
        }

        .stat-progress {
            height: 4px;
            background: #f1f5f9;
            border-radius: 4px;
            margin-top: 8px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            border-radius: 4px;
            transition: width 1s ease;
        }

        /* ✅ Company Missing Alert */
        .company-missing-alert {
            background: linear-gradient(135deg, #fef3c7, #f59e0b);
            border-radius: 12px;
            padding: 16px 20px;
            border-left: 4px solid #d97706;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .company-missing-alert a {
            color: #d97706;
            font-weight: 600;
            text-decoration: underline;
        }

        .company-missing-alert a:hover {
            color: #b45309;
        }

        /* ✅ Quick Actions - Your Existing Styles */
        .quick-actions {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 24px;
            border: 1px solid #eef2f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .quick-actions-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            color: #1e293b;
        }

        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
        }

        .quick-action {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 16px;
            border-radius: 10px;
            background: #f8fafc;
            border: 1px solid #eef2f6;
            transition: all 0.3s ease;
            text-decoration: none;
            color: #1e293b;
        }

        .quick-action:hover {
            background: #f1f5f9;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            text-decoration: none;
            color: #1e293b;
        }

        .quick-action.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            pointer-events: none;
        }

        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #fff;
            flex-shrink: 0;
        }

        .action-icon.primary {
            background: #2563eb;
        }

        .action-icon.success {
            background: #22c55e;
        }

        .action-icon.warning {
            background: #f59e0b;
        }

        .action-icon.info {
            background: #8b5cf6;
        }

        .action-icon.danger {
            background: #ef4444;
        }

        .action-text {
            flex: 1;
        }

        .action-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }

        .action-desc {
            font-size: 12px;
            color: #94a3b8;
        }

        /* ✅ Recent Card - Your Existing Styles */
        .recent-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #eef2f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            margin-bottom: 24px;
        }

        .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #eef2f6;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #fafbfc;
        }

        .card-header h5 {
            font-size: 15px;
            font-weight: 600;
            margin: 0;
            color: #1e293b;
        }

        .card-header a {
            font-size: 13px;
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
        }

        .card-header a:hover {
            text-decoration: underline;
        }

        .card-body {
            padding: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #94a3b8;
            border-bottom: 2px solid #eef2f6;
            padding: 12px 16px;
        }

        .table td {
            padding: 12px 16px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-badge.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.approved {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge.reviewing {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.shortlisted {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.hired {
            background: #dcfce7;
            color: #166534;
        }

        /* ✅ Empty State - Your Existing Styles */
        .empty-state {
            text-align: center;
            padding: 48px 20px;
        }

        .empty-state i {
            font-size: 48px;
            color: #cbd5e1;
            margin-bottom: 12px;
            display: block;
        }

        .empty-state h5 {
            color: #475569;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .empty-state p {
            color: #94a3b8;
            font-size: 14px;
            margin: 0;
        }

        /* ✅ Responsive */
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }

            .stat-card {
                padding: 16px;
            }

            .stat-number {
                font-size: 20px;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr 1fr;
            }

            .quick-action {
                padding: 12px;
            }

            .action-desc {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 10px;
            }

            .stat-card {
                padding: 12px;
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .quick-actions-grid {
                grid-template-columns: 1fr;
            }

            .company-missing-alert {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-container">
        {{-- ✅ Company Missing Alert --}}
        @if(isset($companyMissing) && $companyMissing)
            <div class="company-missing-alert">
                <i class="fa fa-info-circle me-2"></i>
                <span><strong>Complete Your Company Profile!</strong> Please <a
                        href="{{ route('employer.company-profile.edit') }}">update your company profile</a> to start posting
                    jobs.</span>
            </div>
        @endif

        {{-- ✅ Profile Complete Banner (New) --}}
        @if(!isset($companyMissing) && isset($isProfileComplete) && !$isProfileComplete)
            <div class="company-missing-alert"
                style="background: linear-gradient(135deg, #fef3c7, #f59e0b); border-left-color: #d97706;">
                <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                <span><strong>Complete Your Company Profile!</strong> You need to complete your company profile before posting
                    jobs.</span>
                <a href="{{ route('employer.company-profile.edit') }}" class="btn btn-warning btn-sm"
                    style="margin-left: auto;">
                    <i class="fas fa-edit me-1"></i> Complete Profile
                </a>
            </div>
        @endif

        {{-- ✅ Profile Complete Message (New) --}}
        @if(isset($isProfileComplete) && $isProfileComplete && isset($company) && !$company->is_verified)
            <div class="alert alert-info mb-4" style="border-radius: 12px; border-left: 4px solid #3b82f6;">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Your company is pending verification.</strong>
                You can still post jobs, but they will be reviewed by admin before publishing.
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
                        <div class="progress-fill"
                            style="width: {{ $totalJobs > 0 ? min(100, ($totalJobs / 10) * 100) : 0 }}%; background: #2563eb;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fa fa-users"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalApplications ?? 0 }}</div>
                    <div class="stat-label">Applications</div>
                    <div class="stat-progress">
                        <div class="progress-fill"
                            style="width: {{ $totalApplications > 0 ? min(100, ($totalApplications / 20) * 100) : 0 }}%; background: #22c55e;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon yellow"><i class="fa fa-eye"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $totalViews ?? 0 }}</div>
                    <div class="stat-label">Total Views</div>
                    <div class="stat-progress">
                        <div class="progress-fill"
                            style="width: {{ $totalViews > 0 ? min(100, ($totalViews / 100) * 100) : 0 }}%; background: #f59e0b;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fa fa-star"></i></div>
                <div class="stat-info">
                    <div class="stat-number">{{ $activeJobs ?? 0 }}</div>
                    <div class="stat-label">Active Jobs</div>
                    <div class="stat-progress">
                        <div class="progress-fill"
                            style="width: {{ $activeJobs > 0 ? min(100, ($activeJobs / 10) * 100) : 0 }}%; background: #8b5cf6;">
                        </div>
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
                <a href="{{ route('employer.jobs.create') }}"
                    class="quick-action {{ (isset($isProfileComplete) && !$isProfileComplete) ? 'disabled' : '' }}">
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
                <a href="{{ route('employer.company-profile.edit') }}" class="quick-action">
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
                                            <span class="status-badge {{ $application->status ?? 'pending' }}">
                                                {{ ucfirst($application->status ?? 'Pending') }}
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

        {{-- ✅ Recent Jobs (New Section) --}}
        @if(isset($recentJobs) && $recentJobs->count() > 0)
            <div class="recent-card">
                <div class="card-header">
                    <h5><i class="fa fa-briefcase" style="color: #11998e; margin-right: 8px;"></i> Recent Jobs</h5>
                    <a href="{{ route('employer.jobs.index') }}">View All <i class="fa fa-arrow-right ms-1"></i></a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Job Title</th>
                                    <th>Category</th>
                                    <th>Deadline</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentJobs as $job)
                                    <tr>
                                        <td>{{ Str::limit($job->title, 40) }}</td>
                                        <td>{{ $job->category->name ?? 'N/A' }}</td>
                                        <td>{{ $job->deadline?->format('d M, Y') ?? 'No deadline' }}</td>
                                        <td>
                                            @if(!$job->is_verified)
                                                <span class="status-badge pending">Pending</span>
                                            @elseif($job->is_active)
                                                <span class="status-badge approved">Active</span>
                                            @else
                                                <span class="status-badge rejected">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('employer.jobs.edit', $job) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection