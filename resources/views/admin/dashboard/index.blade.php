@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page-title', __t('Dashboard'))
@section('page-subtitle', __t('Overview of your portal'))

@section('content')
    <div class="stats-grid">
        <div class="admin-stat-card">
            <i class="fas fa-briefcase stat-icon"></i>
            <h3 class="stat-number">{{ $totalJobs ?? 0 }}</h3>
            <p class="stat-label">{{ __t('Total Jobs') }}</p>
            <small class="stat-small text-success">{{ $activeJobs ?? 0 }} {{ __t('Active') }}</small>
        </div>

        <div class="admin-stat-card info">
            <i class="fas fa-building stat-icon"></i>
            <h3 class="stat-number">{{ $totalCompanies ?? 0 }}</h3>
            <p class="stat-label">{{ __t('Total Companies') }}</p>
            <small class="stat-small">{{ __t('Registered partners') }}</small>
        </div>

        <div class="admin-stat-card warning">
            <i class="fas fa-users stat-icon"></i>
            <h3 class="stat-number">{{ $totalUsers ?? 0 }}</h3>
            <p class="stat-label">{{ __t('Total Users') }}</p>
            <small class="stat-small">{{ $totalEmployers ?? 0 }} {{ __t('employers') }}, {{ $totalSeekers ?? 0 }}
                {{ __t('seekers') }}</small>
        </div>

        <div class="admin-stat-card success">
            <i class="fas fa-wallet stat-icon"></i>
            <h3 class="stat-number">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
            <p class="stat-label">{{ __t('Revenue') }}</p>
            <small class="stat-small text-success">{{ __t('Total earnings') }}</small>
        </div>
    </div>

    <div class="tables-grid">
        <!-- Recent Jobs -->
        <div class="admin-card">
            <div class="card-header">
                <h5><i class="fas fa-clock"
                        style="color: var(--primary-color); margin-right: 8px;"></i>{{ __t('Recent Jobs') }}</h5>
                <div class="card-actions">
                    <a href="{{ route('admin.job-postings.create') }}" class="btn-admin-primary">
                        <i class="fas fa-plus"></i> {{ __t('Add New') }}
                    </a>
                </div>
            </div>
            <div class="table-container">
                <table class="admin-table" id="jobsTable">
                    <thead>
                        <tr>
                            <th>{{ __t('Title') }}</th>
                            <th>{{ __t('Company') }}</th>
                            <th>{{ __t('Location') }}</th>
                            <th>{{ __t('Status') }}</th>
                            <th style="text-align: right;">{{ __t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentJobs ?? [] as $job)
                            <tr>
                                <td class="job-title">{{ $job->title }}</td>
                                <td>{{ $job->company->company_name ?? 'N/A' }}</td>
                                <td><i class="fas fa-location-dot location-icon"></i>{{ $job->location }}</td>
                                <td>
                                    <span class="badge-{{ $job->is_active ? 'active' : 'inactive' }}">
                                        {{ $job->is_active ? __t('Active') : __t('Inactive') }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.job-postings.edit', $job) }}" class="btn-admin-outline btn-sm"
                                            title="{{ __t('Edit Job') }}">
                                            <i class="fas fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">{{ __t('No jobs found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="admin-card">
            <div class="card-header">
                <h5><i class="fas fa-users" style="color: #3498db; margin-right: 8px;"></i>{{ __t('Recent Users') }}</h5>
                <div class="card-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">
                        <i class="fas fa-eye"></i> {{ __t('View All') }}
                    </a>
                </div>
            </div>
            <div class="table-container">
                <table class="admin-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>{{ __t('Name') }}</th>
                            <th>{{ __t('Email') }}</th>
                            <th>{{ __t('Role') }}</th>
                            <th>{{ __t('Status') }}</th>
                            <th style="text-align: right;">{{ __t('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentUsers ?? [] as $user)
                            <tr>
                                <td class="job-title">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <span class="badge-{{ $user->role ?? 'seeker' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-{{ ($user->is_active ?? true) ? 'active' : 'inactive' }}">
                                        {{ ($user->is_active ?? true) ? __t('Active') : __t('Inactive') }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin-outline btn-sm"
                                            title="{{ __t('Edit User') }}">
                                            <i class="fas fa-user-gear"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">{{ __t('No users found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
