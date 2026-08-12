@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard - Rozgar Finder')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your portal')

@section('content')
    <!-- ✅ Stats Grid -->
    <div class="stats-grid">
        <!-- Total Jobs -->
        <div class="admin-stat-card">
            <i class="fa-solid fa-briefcase stat-icon"></i>
            <h3 class="stat-number">{{ $totalJobs ?? 0 }}</h3>
            <p class="stat-label">Total Jobs</p>
            <small class="stat-small text-success">{{ $activeJobs ?? 0 }} active</small>
        </div>

        <!-- Companies -->
        <div class="admin-stat-card info">
            <i class="fa-solid fa-building stat-icon"></i>
            <h3 class="stat-number">{{ $totalCompanies ?? 0 }}</h3>
            <p class="stat-label">Companies</p>
            <small class="stat-small">Registered partners</small>
        </div>

        <!-- Total Users -->
        <div class="admin-stat-card warning">
            <i class="fa-solid fa-users stat-icon"></i>
            <h3 class="stat-number">{{ $totalUsers ?? 0 }}</h3>
            <p class="stat-label">Total Users</p>
            <small class="stat-small">{{ $totalEmployers ?? 0 }} employers, {{ $totalSeekers ?? 0 }} seekers</small>
        </div>

        <!-- Revenue -->
        <div class="admin-stat-card success">
            <i class="fa-solid fa-wallet stat-icon"></i>
            <h3 class="stat-number">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
            <p class="stat-label">Revenue</p>
            <small class="stat-small text-success">Total earnings</small>
        </div>
    </div>

    <!-- ✅ Tables Grid -->
    <div class="tables-grid">

        <!-- Recent Jobs -->
        <div class="admin-card">
            <div class="card-header">
                <h5><i class="fa-solid fa-clock me-2" style="color: var(--primary-color);"></i> Recent Jobs</h5>
                <div class="card-actions">
                    <a href="{{ route('admin.jobs.create') }}" class="btn-admin-primary">
                        <i class="fa-solid fa-plus"></i> Add New
                    </a>
                </div>
            </div>
            <div class="table-container">
                <!-- 🛠️ Changed class to clear unknown parameter warnings -->
                <table class="admin-table" id="recentJobsTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentJobs ?? [] as $job)
                            <tr>
                                <td class="job-title">{{ $job->title }}</td>
                                <td>{{ $job->company->company_name ?? 'N/A' }}</td>
                                <td><i class="fa-solid fa-location-dot location-icon"></i>{{ $job->location }}</td>
                                <td>
                                    <span class="{{ $job->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.jobs.edit', $job) }}" class="btn-admin-outline btn-sm"
                                            title="Edit Job">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state" style="text-align: center; padding: 20px;">No jobs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Users -->
        <div class="admin-card">
            <div class="card-header">
                <h5><i class="fa-solid fa-users me-2" style="color: #3498db;"></i> Recent Users</h5>
                <div class="card-actions">
                    <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">
                        <i class="fa-solid fa-eye"></i> View All
                    </a>
                </div>
            </div>
            <div class="table-container">
                <!-- 🛠️ Changed class to clear unknown parameter warnings -->
                <table class="admin-table" id="recentUsersTable" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
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
                                    <span class="{{ ($user->is_active ?? true) ? 'badge-active' : 'badge-inactive' }}">
                                        {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin-outline btn-sm"
                                            title="Edit User">
                                            <i class="fa-solid fa-user-gear"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state" style="text-align: center; padding: 20px;">No users found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
