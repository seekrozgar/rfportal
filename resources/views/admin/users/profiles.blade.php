@extends('admin.layouts.admin')

@section('title', 'User Profiles')
@section('page-title', 'User Profiles')
@section('page-subtitle', 'Manage employers and job seekers')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-user-tie me-2" style="color: var(--primary-color);"></i> All User Profiles</h5>
            <div class="card-actions">
                <span class="text-muted">Total: {{ $users->total() }} users</span>
            </div>
        </div>

        <div class="table-container">
            <div class="table-scroll-wrapper">
                <table class="admin-table datatable" id="profilesTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Company</th>
                            <th>Account Status</th>
                            <th>Joined</th>
                            <th style="text-align: center; min-width: 200px;">Actions</th>
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
                                <td>
                                    @if($user->role == 'employer' && $user->company)
                                        {{ $user->company->company_name ?? 'N/A' }}
                                    @else
                                        <span class="text-muted">N/A</span>
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
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td style="text-align: center; min-width: 200px;">
                                    <div class="action-buttons">
                                        <!-- ✅ Toggle Status -->
                                        <button type="button" onclick="toggleStatus({{ $user->id }})"
                                            id="status-btn-{{ $user->id }}"
                                            class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                                            title="{{ $user->is_active ? 'Disable Account' : 'Enable Account' }}">
                                            <i class="fas fa-{{ $user->is_active ? 'ban' : 'check-circle' }}"></i>
                                            {{ $user->is_active ? 'Disable' : 'Enable' }}
                                        </button>

                                        <!-- ✅ Mark Fraud -->
                                        <button type="button" onclick="markFraud({{ $user->id }})"
                                            id="fraud-btn-{{ $user->id }}"
                                            class="btn btn-sm {{ $user->is_fraud ? 'btn-success' : 'btn-danger' }}"
                                            title="{{ $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud' }}">
                                            <i class="fas fa-{{ $user->is_fraud ? 'shield-alt' : 'exclamation-triangle' }}"></i>
                                            {{ $user->is_fraud ? 'Clear Fraud' : 'Mark Fraud' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="empty-state">No users found.</td>
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
        function toggleStatus(userId) {
            if (!confirm('Are you sure you want to change this user\'s status?')) return;

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
                        statusCell.innerHTML = `<span class="${response.badge_class}">${response.badge}</span>`;
                        button.className = `btn btn-sm ${response.is_active ? 'btn-danger' : 'btn-success'}`;
                        button.innerHTML = `<i class="fas fa-${response.icon}"></i> ${response.button_title}`;
                        button.title = response.button_title;
                        button.onclick = function () { toggleStatus(userId); };
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
        }

        // ============================================================
        // ✅ AJAX: Mark Fraud (Without Page Refresh)
        // ============================================================
        function markFraud(userId) {
            const button = document.getElementById(`fraud-btn-${userId}`);
            const isFraud = button.title === 'Clear Fraud';

            if (!confirm(`Are you sure you want to ${isFraud ? 'clear fraud' : 'mark as fraud'} this user?`)) return;

            const statusCell = document.getElementById(`status-cell-${userId}`);

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
                        statusCell.innerHTML = `<span class="${response.badge_class}">${response.badge}</span>`;
                        button.className = `btn btn-sm ${response.is_fraud ? 'btn-success' : 'btn-danger'}`;
                        button.innerHTML = `<i class="fas fa-${response.icon}"></i> ${response.button_title}`;
                        button.title = response.button_title;
                        button.onclick = function () { markFraud(userId); };
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
        }

        // ✅ Toastr helper
        function showToast(type, message) {
            if (typeof toastr !== 'undefined') {
                if (type === 'success') toastr.success(message);
                else if (type === 'error') toastr.error(message);
                else if (type === 'warning') toastr.warning(message);
                else toastr.info(message);
            } else {
                alert(message);
            }
        }
        window.showToast = showToast;

        // ✅ DataTables
        $(document).ready(function () {
            if (typeof $.fn.DataTable !== 'undefined') {
                $('#profilesTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    },
                    columnDefs: [
                        { orderable: false, targets: 6 }
                    ]
                });
            }
        });
    </script>
@endpush