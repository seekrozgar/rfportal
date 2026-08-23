{{-- resources/views/admin/job-categories/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Job Categories - Rozgar Finder')
@section('page-title', 'Job Categories')
@section('page-subtitle', 'Manage job categories')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary">
                                    <i class="fas fa-folder"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalCategories }}</div>
                                    <div class="stats-label">Total Categories</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 100%; background: #6366f1;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-success">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $activeCategories }}</div>
                                    <div class="stats-label">Active</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 70%; background: #22c55e;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $inactiveCategories }}</div>
                                    <div class="stats-label">Inactive</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 30%; background: #ef4444;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $rootCategories }}</div>
                                    <div class="stats-label">Root Categories</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 50%; background: #f59e0b;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Categories Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-sitemap me-2 text-primary"></i> Job Categories
                        </h5>
                        <div>
                            <a href="{{ route('admin.job-categories.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Category
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Parent</th>
                                        <th>Sub-Categories</th>
                                        <th>Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr id="row-{{ $category->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="category-icon">{!! $category->icon_html !!}</span>
                                                    <div>
                                                        @if($category->parent_id)
                                                            <span class="text-muted" style="font-size: 12px;">
                                                                <i class="fas fa-arrow-right me-1"></i>
                                                            </span>
                                                        @endif
                                                        <strong>{{ $category->name }}</strong>
                                                        @if($category->children->count() > 0)
                                                            <br>
                                                            <small class="text-muted">
                                                                <i class="fas fa-folder-open"></i>
                                                                {{ $category->children->count() }} sub-categories
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($category->parent)
                                                    <span class="parent-badge">
                                                        <i class="fas fa-arrow-up"></i> {{ $category->parent->name }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="sub-count">
                                                    <i class="fas fa-folder-open"></i> {{ $category->children->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($category->is_active)
                                                    <span class="status-badge status-active">
                                                        <i class="fas fa-check-circle"></i> Active
                                                    </span>
                                                @else
                                                    <span class="status-badge status-inactive">
                                                        <i class="fas fa-times-circle"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="action-buttons">
                                                    {{-- Edit --}}
                                                    <a href="{{ route('admin.job-categories.edit', $category) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    {{-- Add Subcategory --}}
                                                    <a href="{{ route('admin.job-categories.create') }}?parent_id={{ $category->id }}"
                                                        class="btn btn-sm btn-info" title="Add Subcategory">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    {{-- Toggle Status --}}
                                                    <button onclick="toggleStatus({{ $category->id }})"
                                                        class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i
                                                            class="fas fa-{{ $category->is_active ? 'ban' : 'check-circle' }}"></i>
                                                    </button>
                                                    {{-- Delete --}}
                                                    <button
                                                        onclick="deleteItem({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                                        class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-folder-open fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No Categories Found</h5>
                                                    <p class="text-muted small">Click "Add Category" to get started.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of
                                {{ $categories->total() }} entries
                            </div>
                            <div>
                                {{ $categories->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // ✅ ============================================================
            // ✅ SINGLE TOAST HANDLER - NO DUPLICATES
            // ✅ ============================================================
            let toastShown = false;

            function showToast(type, message) {
                if (toastShown) return;

                if (typeof toastr !== 'undefined') {
                    const options = {
                        timeOut: 5000,
                        progressBar: true,
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        preventDuplicates: true,
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        showDuration: 300,
                        hideDuration: 300,
                    };

                    const titles = {
                        success: '✅ Success!',
                        error: '❌ Error!',
                        warning: '⚠️ Warning!',
                        info: 'ℹ️ Info'
                    };

                    toastr[type](message, titles[type] || 'Notification', options);
                    toastShown = true;

                    setTimeout(() => { toastShown = false; }, 6000);
                } else {
                    alert(message);
                }
            }

            // ✅ ============================================================
            // ✅ CONFIRMATION DIALOG WITH TOASTR
            // ✅ ============================================================
            function showConfirmToast(message, callback) {
                if (typeof toastr === 'undefined') {
                    if (confirm(message)) callback();
                    return;
                }

                toastr.clear();
                const callbackKey = '_confirm_' + Date.now();

                const html = `
                                <div style="text-align:center;padding:10px 0;">
                                    <p style="font-size:15px;margin-bottom:15px;color:#fff;">${message}</p>
                                    <div style="display:flex;gap:10px;justify-content:center;">
                                        <button onclick="window['${callbackKey}'](true)"
                                                style="background:#22c55e;color:#fff;border:none;padding:8px 25px;border-radius:5px;cursor:pointer;font-weight:600;">
                                            <i class="fas fa-check"></i> Yes
                                        </button>
                                        <button onclick="window['${callbackKey}'](false)"
                                                style="background:#6b7280;color:#fff;border:none;padding:8px 25px;border-radius:5px;cursor:pointer;font-weight:600;">
                                            <i class="fas fa-times"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            `;

                window[callbackKey] = function (result) {
                    toastr.clear();
                    if (result) {
                        callback();
                    } else {
                        showToast('info', 'Action cancelled');
                    }
                    delete window[callbackKey];
                };

                toastr.warning(html, 'Confirm Action', {
                    closeButton: false,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    positionClass: 'toast-top-center',
                    progressBar: false,
                    escapeHtml: false,
                });
            }

            // ✅ ============================================================
            // ✅ TOGGLE STATUS
            // ✅ ============================================================
            function toggleStatus(id) {
                showConfirmToast('Are you sure you want to change the status of this category?', function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';

                    fetch('/admin/job-categories/' + id + '/toggle', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                setTimeout(() => location.reload(), 800);
                            } else {
                                showToast('error', data.message || 'Error toggling status');
                                if (row) row.style.opacity = '1';
                            }
                        })
                        .catch(() => {
                            showToast('error', 'An error occurred. Please try again.');
                            if (row) row.style.opacity = '1';
                        });
                });
            }

            // ✅ ============================================================
            // ✅ DELETE ITEM
            // ✅ ============================================================
            function deleteItem(id, name) {
                const msg = `Are you sure you want to delete "<strong>${name}</strong>"?<br><small style="color:#999;">This action cannot be undone.</small>`;

                showConfirmToast(msg, function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';

                    fetch('/admin/job-categories/' + id, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                if (row) {
                                    row.style.transition = 'all 0.5s ease';
                                    row.style.opacity = '0';
                                    row.style.transform = 'translateX(50px)';
                                    setTimeout(() => { if (row.parentNode) row.remove(); }, 500);
                                }
                            } else {
                                showToast('error', data.message || 'Error deleting');
                                if (row) row.style.opacity = '1';
                            }
                        })
                        .catch(() => {
                            showToast('error', 'An error occurred. Please try again.');
                            if (row) row.style.opacity = '1';
                        });
                });
            }

            // ✅ ============================================================
            // ✅ SESSION TOAST MESSAGES - SINGLE HANDLER
            // ✅ ============================================================
            document.addEventListener('DOMContentLoaded', function () {
                // ✅ Configure Toastr globally
                if (typeof toastr !== 'undefined') {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                        positionClass: 'toast-top-right',
                        preventDuplicates: true,
                        newestOnTop: true,
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                        showDuration: 300,
                        hideDuration: 300,
                    };
                }

                // ✅ Show only ONE toast from session
                @if(session('toast'))
                    const toast = @json(session('toast'));
                    showToast(toast.type, toast.message);
                @endif

                // ✅ Fallback for old session format
                @if(session('success') && !session('toast'))
                    showToast('success', '{{ session('success') }}');
                @endif
                @if(session('error') && !session('toast'))
                    showToast('error', '{{ session('error') }}');
                @endif
                        });
        </script>
    @endpush
@endsection
