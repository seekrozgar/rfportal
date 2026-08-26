@extends('admin.layouts.admin')

@section('title', 'Admin Users')
@section('page-title', 'Admin Users')
@section('page-subtitle', 'Manage administrators and authors')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-users-cog me-2" style="color: var(--primary-color);"></i> All Admin Users</h5>
            <div class="card-actions">
                <a href="{{ route('admin.users.create') }}" class="btn-admin-primary"
                    style="padding: 10px 24px; font-size: 14px;">
                    <i class="fas fa-plus"></i> Add New
                </a>
            </div>
        </div>

        <div class="table-container">
            <div class="table-scroll-wrapper">
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
                                    <div class="action-buttons">
                                        @if(!$user->isSuperAdmin())
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary"
                                                title="Edit User">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>

                                            @if(!$user->email_verified_at)
                                                <button type="button" onclick="resendVerification({{ $user->id }})"
                                                    class="btn btn-sm btn-warning" title="Resend verification email"
                                                    style="color: #fff;">
                                                    <i class="fas fa-paper-plane"></i> Resend
                                                </button>
                                            @endif

                                            <button type="button"
                                                onclick="toggleStatus({{ $user->id }}, '{{ $user->name }}', {{ $user->is_active ? 'true' : 'false' }})"
                                                id="status-btn-{{ $user->id }}"
                                                class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                                                title="{{ $user->is_active ? 'Disable Account' : 'Enable Account' }}">
                                                <i class="fas fa-{{ $user->is_active ? 'ban' : 'check-circle' }}"></i>
                                                {{ $user->is_active ? 'Disable' : 'Enable' }}
                                            </button>

                                            <button type="button"
                                                onclick="markFraud({{ $user->id }}, '{{ $user->name }}', {{ $user->is_fraud ? 'true' : 'false' }})"
                                                id="fraud-btn-{{ $user->id }}"
                                                class="btn btn-sm {{ $user->is_fraud ? 'btn-success' : 'btn-danger' }}"
                                                title="{{ $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud' }}">
                                                <i class="fas fa-{{ $user->is_fraud ? 'shield-alt' : 'exclamation-triangle' }}"></i>
                                                {{ $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud' }}
                                            </button>

                                            <button type="button" onclick="deleteUser({{ $user->id }}, '{{ $user->name }}')"
                                                class="btn btn-sm btn-danger" title="Delete User">
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
            <div class="scroll-indicator">
                <i class="fas fa-arrow-left me-1"></i> Scroll to see more <i class="fas fa-arrow-right ms-1"></i>
            </div>
        </div>

        <div class="mt-3">
            {{ $users->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ============================================================
        // ✅ AJAX: Toggle Status (Without Page Refresh)
        // ============================================================
        function toggleStatus(userId, userName, isActive) {
            const actionText = isActive === 'true' ? 'disable' : 'enable';
            const message = `Are you sure you want to <strong>${actionText}</strong> user "<strong>${userName}</strong>"?`;

            window.showToastConfirm(message, function () {
                const statusCell = document.getElementById(`status-cell-${userId}`);
                const button = document.getElementById(`status-btn-${userId}`);

                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`/admin/users/${userId}/toggle-status-ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({})
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            // ✅ Update status badge
                            statusCell.innerHTML = `<span class="${response.badge_class}">${response.badge}</span>`;

                            // ✅ Update button
                            button.className = `btn btn-sm ${response.is_active ? 'btn-danger' : 'btn-success'}`;
                            button.innerHTML = `<i class="fas fa-${response.icon}"></i> ${response.button_title}`;
                            button.title = response.button_title;
                            button.onclick = function () {
                                toggleStatus(userId, userName, response.is_active);
                            };

                            window.showToast('success', response.message);
                        } else {
                            window.showToast('error', response.message);
                        }
                        button.disabled = false;
                    })
                    .catch(() => {
                        window.showToast('error', 'An error occurred. Please try again.');
                        button.disabled = false;
                    });
            });
        }

        // ============================================================
        // ✅ AJAX: Mark Fraud (Without Page Refresh)
        // ============================================================
        function markFraud(userId, userName, isFraud) {
            const actionText = isFraud === 'true' ? 'clear fraud from' : 'mark as fraud';
            const message = `Are you sure you want to <strong>${actionText}</strong> user "<strong>${userName}</strong>"?`;

            window.showToastConfirm(message, function () {
                const statusCell = document.getElementById(`status-cell-${userId}`);
                const button = document.getElementById(`fraud-btn-${userId}`);

                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`/admin/users/${userId}/mark-fraud-ajax`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({})
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            // ✅ Update status badge
                            statusCell.innerHTML = `<span class="${response.badge_class}">${response.badge}</span>`;

                            // ✅ Update button
                            button.className = `btn btn-sm ${response.is_fraud ? 'btn-success' : 'btn-danger'}`;
                            button.innerHTML = `<i class="fas fa-${response.icon}"></i> ${response.button_title}`;
                            button.title = response.button_title;
                            button.style.color = '';
                            button.onclick = function () {
                                markFraud(userId, userName, response.is_fraud);
                            };

                            window.showToast('success', response.message);
                        } else {
                            window.showToast('error', response.message);
                        }
                        button.disabled = false;
                    })
                    .catch(() => {
                        window.showToast('error', 'An error occurred. Please try again.');
                        button.disabled = false;
                    });
            });
        }

        // ============================================================
        // ✅ AJAX: Resend Verification (Without Page Refresh)
        // ============================================================
        function resendVerification(userId) {
            const button = event.currentTarget;

            button.disabled = true;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            fetch(`/admin/users/${userId}/resend-verification-ajax`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({})
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        document.getElementById(`email-status-${userId}`).innerHTML = `<span class="badge-verified">Verified</span>`;
                        button.remove();
                        window.showToast('success', response.message);
                    } else {
                        window.showToast('error', response.message);
                    }
                    button.disabled = false;
                })
                .catch(() => {
                    window.showToast('error', 'Failed to send verification email.');
                    button.disabled = false;
                    button.innerHTML = '<i class="fas fa-paper-plane"></i> Resend';
                });
        }

        // ============================================================
        // ✅ AJAX: Delete User (Without Page Refresh)
        // ============================================================
        function deleteUser(userId, userName) {
            const message = `Are you sure you want to <strong style="color: #e74c3c;">delete</strong> user "<strong>${userName}</strong>"?<br><small style="color: #999;">This action cannot be undone.</small>`;

            window.showToastConfirm(message, function () {
                const row = document.getElementById(`user-row-${userId}`);
                const button = event.currentTarget;

                button.disabled = true;
                button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Deleting...';
                row.style.opacity = '0.5';

                fetch(`/admin/users/${userId}/delete-ajax`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({})
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            row.style.transition = 'all 0.5s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            setTimeout(() => {
                                row.remove();
                                window.showToast('success', response.message);
                            }, 500);
                        } else {
                            window.showToast('error', response.message);
                            row.style.opacity = '1';
                            button.disabled = false;
                            button.innerHTML = '<i class="fas fa-trash"></i> Delete';
                        }
                    })
                    .catch(() => {
                        window.showToast('error', 'Failed to delete user.');
                        row.style.opacity = '1';
                        button.disabled = false;
                        button.innerHTML = '<i class="fas fa-trash"></i> Delete';
                    });
            });
        }
    </script>
@endpush