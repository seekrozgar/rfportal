@extends('admin.layouts.admin')

@section('title', 'Reset User Password - Rozgar Finder')
@section('page-title', 'Reset User Password')
@section('page-subtitle', 'Reset password for ' . $user->name)

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-key me-2" style="color: var(--primary-color);"></i> Reset Password</h5>
            <div class="card-actions">
                <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}" class="admin-form">
            @csrf

            <div class="form-group">
                <label>User</label>
                <p><strong>{{ $user->name }}</strong> ({{ $user->email }})</p>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">New Password <span class="text-danger">*</span></label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password <span class="text-danger">*</span></label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            required>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn-admin-primary">
                    <i class="fas fa-save"></i> Reset Password
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn-admin-outline">Cancel</a>
            </div>
        </form>
    </div>
@endsection