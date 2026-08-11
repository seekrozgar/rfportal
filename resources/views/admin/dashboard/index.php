@extends('admin.layouts.admin')

@section('title', 'Admin Dashboard - Rozgar Finder')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Overview of your portal')

@section('content')
<div class="row">
    <!-- Stats Cards -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="admin-stat-card">
            <div class="stat-icon"><i class="fa fa-briefcase"></i></div>
            <h3 class="stat-number">{{ $totalJobs ?? 0 }}</h3>
            <p class="stat-label">Total Jobs</p>
            <small class="text-muted">
                <span class="text-success">{{ $activeJobs ?? 0 }}</span> active
            </small>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="admin-stat-card warning">
            <div class="stat-icon"><i class="fa fa-building"></i></div>
            <h3 class="stat-number">{{ $totalCompanies ?? 0 }}</h3>
            <p class="stat-label">Companies</p>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="admin-stat-card info">
            <div class="stat-icon"><i class="fa fa-users"></i></div>
            <h3 class="stat-number">{{ $totalUsers ?? 0 }}</h3>
            <p class="stat-label">Total Users</p>
            <small class="text-muted">
                <span class="text-primary">{{ $totalEmployers ?? 0 }}</span> employers,
                <span class="text-info">{{ $totalSeekers ?? 0 }}</span> seekers
            </small>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="admin-stat-card success">
            <div class="stat-icon"><i class="fa fa-credit-card"></i></div>
            <h3 class="stat-number">${{ number_format($totalRevenue ?? 0, 2) }}</h3>
            <p class="stat-label">Revenue</p>
        </div>
    </div>
</div>

<!-- Recent Jobs -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="fa fa-clock me-2" style="color: #11998e;"></i> Recent Jobs</h5>
        <div class="card-actions">
            <a href="{{ route('admin.jobs.create') }}" class="btn-admin-primary">
                <i class="fa fa-plus"></i> Add New
            </a>
        </div>
    </div>
    <table class="admin-table datatable">
        <thead>
            <tr>
                <th>Title</th>
                <th>Company</th>
                <th>Location</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentJobs ?? [] as $job)
            <tr>
                <td>{{ $job->title }}</td>
                <td>{{ $job->company->company_name ?? 'N/A' }}</td>
                <td>{{ $job->location }}</td>
                <td>
                    <span class="badge bg-{{ $job->is_active ? 'success' : 'danger' }}">
                        {{ $job->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No jobs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Recent Users -->
<div class="admin-card">
    <div class="card-header">
        <h5><i class="fa fa-users me-2" style="color: #3498db;"></i> Recent Users</h5>
        <div class="card-actions">
            <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">
                <i class="fa fa-eye"></i> View All
            </a>
        </div>
    </div>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentUsers ?? [] as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <span
                        class="badge bg-{{ $user->role == 'superadmin' ? 'danger' : ($user->role == 'admin' ? 'warning' : ($user->role == 'author' ? 'info' : ($user->role == 'employer' ? 'primary' : 'secondary'))) }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td>
                    <span class="badge bg-{{ ($user->is_active ?? true) ? 'success' : 'danger' }}">
                        {{ ($user->is_active ?? true) ? 'Active' : 'Inactive' }}
                    </span>
                </td>
                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">
                        <i class="fa fa-edit"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
