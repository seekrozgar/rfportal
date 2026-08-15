@extends('admin.layouts.admin')

@section('title', 'Admin Users - Rozgar Finder')
@section('page-title', 'Admin Users')
@section('page-subtitle', 'Manage administrators and authors')

@section('content')
<div class="admin-card">
    <div class="card-header">
        <h5><i class="fas fa-users-cog me-2" style="color: var(--primary-color);"></i> All Admin Users</h5>
        <div class="card-actions">
            <a href="{{ route('admin.users.create') }}" class="btn-admin-primary" style="padding: 10px 24px; font-size: 14px;">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>
    </div>
    <div class="table-container">
        <table class="admin-table datatable" id="usersTable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Email Status</th>
                    <th>Account Status</th>
                    <th style="text-align: center; min-width: 280px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr id="user-row-{{ $user->id }}">
                        <td class="job-title">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge-{{ $user->role }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td id="email-status-{{ $user->id }}">
                            @if($user->email_verified_at)
                                <span class="badge-verified">Verified</span>
                            @else
                                <span class="badge-unverified">Unverified</span>
                            @endif
                        </td>
                        <td id="status-cell-{{ $user->id }}">
                            @if($user->is_fraud)
                                <span class="badge-fraud">Fraud</span>
                            @elseif($user->is_active)
                                <span class="badge-active">Active</span>
                            @else
                                <span class="badge-inactive">Disabled</span>
                            @endif
                        </td>
                        <td style="text-align: center; min-width: 280px;">
                            <div class="action-buttons" style="display: flex; gap: 6px; justify-content: center; flex-wrap: wrap;">
                                @if(!$user->isSuperAdmin())
                                    <!-- ✅ Edit Button (Page Load) -->
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary" title="Edit User" style="padding: 6px 14px; font-size: 13px; border-radius: 6px;">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>

                                    <!-- ✅ Resend Verification (AJAX) -->
                                    @if(!$user->email_verified_at)
                                        <button type="button" onclick="resendVerification({{ $user->id }})" class="btn btn-sm btn-warning" title="Resend verification email" style="padding: 6px 14px; font-size: 13px; border-radius: 6px; color: #fff;">
                                            <i class="fas fa-paper-plane"></i> Resend
                                        </button>
                                    @endif

                                    <!-- ✅ Toggle Status (AJAX) -->
                                    <button type="button" onclick="toggleStatus({{ $user->id }})" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}" title="{{ $user->is_active ? 'Disable Account' : 'Enable Account' }}" style="padding: 6px 14px; font-size: 13px; border-radius: 6px;">
                                        <i class="fas fa-{{ $user->is_active ? 'ban' : 'check-circle' }}"></i>
                                        {{ $user->is_active ? 'Disable' : 'Enable' }}
                                    </button>

                                    <!-- ✅ Mark Fraud (AJAX) -->
                                    <button type="button" onclick="markFraud({{ $user->id }})" class="btn btn-sm {{ $user->is_fraud ? 'btn-success' : 'btn-danger' }}" title="{{ $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud' }}" style="padding: 6px 14px; font-size: 13px; border-radius: 6px;">
                                        <i class="fas fa-{{ $user->is_fraud ? 'shield-alt' : 'exclamation-triangle' }}"></i>
                                        {{ $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud' }}
                                    </button>

                                    <!-- ✅ Delete (AJAX) -->
                                    <button type="button" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')" class="btn btn-sm btn-danger" title="Delete User" style="padding: 6px 14px; font-size: 13px; border-radius: 6px;">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                @else
                                    <span class="text-muted">Super Admin</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty-state">No admin users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $users->links() }}
    </div>
</div>
@endsection

@push('scripts')
<script>
    // ✅ Toastr Configuration
    toastr.options = {
        closeButton: true,
        progressBar: true,
        positionClass: 'toast-top-right',
        timeOut: 5000,
        extendedTimeOut: 1000,
        showMethod: 'fadeIn',
        hideMethod: 'fadeOut',
    };

    // ============================================================
    // ✅ AJAX: Toggle Status (Without Page Refresh)
    // ============================================================
    function toggleStatus(userId) {
        if (!confirm('Are you sure you want to change this user\'s status?')) return;

        const row = document.getElementById(`user-row-${userId}`);
        const statusCell = document.getElementById(`status-cell-${userId}`);
        const button = event.currentTarget;

        // ✅ Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

        $.ajax({
            url: `/admin/users/${userId}/toggle-status-ajax`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    // ✅ Update status badge without refresh
                    statusCell.innerHTML = `<span class="${response.badge_class}">${response.badge}</span>`;

                    // ✅ Update button without refresh
                    button.className = `btn btn-sm ${response.is_active ? 'btn-danger' : 'btn-success'}`;
                    button.innerHTML = `<i class="fas fa-${response.icon}"></i> ${response.button_title}`;
                    button.title = response.button_title;

                    // ✅ Show toastr success
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                button.disabled = false;
            },
            error: function(xhr) {
                toastr.error('An error occurred. Please try again.');
                button.disabled = false;
                button.innerHTML = `<i class="fas fa-${button.title === 'Disable' ? 'ban' : 'check-circle'}"></i> ${button.title}`;
            }
        });
    }

    // ============================================================
    // ✅ AJAX: Mark Fraud (Without Page Refresh)
    // ============================================================
    function markFraud(userId) {
        const button = event.currentTarget;
        const isFraud = button.title === 'Clear Fraud';

        if (!confirm(`Are you sure you want to ${isFraud ? 'clear fraud' : 'mark as fraud'} this user?`)) return;

        const row = document.getElementById(`user-row-${userId}`);
        const statusCell = document.getElementById(`status-cell-${userId}`);

        // ✅ Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';

        $.ajax({
            url: `/admin/users/${userId}/mark-fraud-ajax`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    // ✅ Update status badge without refresh
                    statusCell.innerHTML = `<span class="${response.badge_class}">${response.badge}</span>`;

                    // ✅ Update button without refresh
                    button.className = `btn btn-sm ${response.is_fraud ? 'btn-success' : 'btn-danger'}`;
                    button.innerHTML = `<i class="fas fa-${response.icon}"></i> ${response.button_title}`;
                    button.title = response.button_title;
                    button.style.color = '';

                    // ✅ Show toastr success
                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                button.disabled = false;
            },
            error: function(xhr) {
                toastr.error('An error occurred. Please try again.');
                button.disabled = false;
            }
        });
    }

    // ============================================================
    // ✅ AJAX: Resend Verification (Without Page Refresh)
    // ============================================================
    function resendVerification(userId) {
        const button = event.currentTarget;

        // ✅ Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        $.ajax({
            url: `/admin/users/${userId}/resend-verification-ajax`,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    // ✅ Update email status badge without refresh
                    const emailStatusCell = document.getElementById(`email-status-${userId}`);
                    emailStatusCell.innerHTML = `<span class="badge-verified">Verified</span>`;

                    // ✅ Remove resend button
                    const resendButton = button;
                    resendButton.remove();

                    toastr.success(response.message);
                } else {
                    toastr.error(response.message);
                }
                button.disabled = false;
            },
            error: function(xhr) {
                toastr.error('Failed to send verification email.');
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-paper-plane"></i> Resend';
            }
        });
    }

    // ============================================================
    // ✅ AJAX: Delete User (Without Page Refresh)
    // ============================================================
    function deleteUser(userId, userName) {
        if (!confirm(`Are you sure you want to delete user "${userName}"? This action cannot be undone.`)) return;

        const row = document.getElementById(`user-row-${userId}`);
        const button = event.currentTarget;

        // ✅ Show loading state
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
        row.style.opacity = '0.5';

        $.ajax({
            url: `/admin/users/${userId}/delete-ajax`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    // ✅ Animate and remove row without refresh
                    row.style.transition = 'all 0.5s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    setTimeout(function() {
                        row.remove();
                        toastr.success(response.message);
                    }, 500);
                } else {
                    toastr.error(response.message);
                    row.style.opacity = '1';
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-trash"></i> Delete';
                }
            },
            error: function(xhr) {
                toastr.error('Failed to delete user.');
                row.style.opacity = '1';
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-trash"></i> Delete';
            }
        });
    }

    // ✅ DataTables
    $(document).ready(function() {
        if (typeof $.fn.DataTable !== 'undefined') {
            $('#usersTable').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                },
                columnDefs: [
                    { orderable: false, targets: 5 } // Actions column no sorting
                ]
            });
        }
    });
</script>
@endpush
