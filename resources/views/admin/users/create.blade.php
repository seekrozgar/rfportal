@extends('admin.layouts.admin')

@section('title', 'Add User - Rozgar Finder')
@section('page-title', 'Add User')
@section('page-subtitle', 'Create a new administrator or author')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-user-plus me-2" style="color: var(--primary-color);"></i> Add New User</h5>
            <div class="card-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}" class="admin-form">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name') }}" required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email Address <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select id="role" name="role" class="form-control @error('role') is-invalid @enderror" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                    {{ ucfirst($role->name) }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password</label>
                        <p class="text-muted small">A random password will be generated and sent via email.</p>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Menu Permissions</label>
                <p class="text-muted small">Select which menu items this user can access. Leave empty for full access
                    (except SuperAdmin).</p>

                <div class="row">
                    @php
                        $permissions = [
                            'dashboard' => 'Dashboard',
                            'users' => 'Admin Users',
                            'jobs' => 'General Jobs',
                            'company-jobs' => 'Company Jobs',
                            'scholarships' => 'Scholarships',
                            'admissions' => 'Admissions',
                            'results' => 'Results',
                            'news' => 'News',
                            'profiles' => 'User Profiles',
                            'seo' => 'SEO',
                            'faq' => 'FAQs',
                            'languages' => 'Languages',
                            'countries' => 'Countries',
                            'states' => 'States',
                            'cities' => 'Cities',
                            'packages' => 'Packages',
                            'payments-company' => 'Company Payments',
                            'payments-seeker' => 'Seeker Payments',
                            'attributes' => 'Job Attributes',
                            'settings' => 'Site Settings',
                        ];
                    @endphp
                    @foreach($permissions as $key => $label)
                        <div class="col-md-3 col-sm-4 col-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}"
                                    id="perm_{{ $key }}" {{ old('permissions') && in_array($key, old('permissions')) ? 'checked' : '' }}>
                                <label class="form-check-label" for="perm_{{ $key }}">
                                    {{ $label }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                @error('permissions')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-3">
                <button type="submit" class="btn-admin-primary">
                    <i class="fas fa-save"></i> Create User
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
