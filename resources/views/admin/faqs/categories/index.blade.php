{{-- resources/views/admin/faqs/categories/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'FAQ Categories - Rozgar Finder')
@section('page-title', 'FAQ Categories')
@section('page-subtitle', 'Manage FAQ categories')

@push('styles')
    <style>
        .stats-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }
        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .stats-card .stats-card-body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 18px 12px;
        }
        .stats-card .stats-icon-wrapper {
            margin-bottom: 8px;
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
        }
        .stats-card .stats-number {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
        }
        .stats-card .stats-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            margin-top: 2px;
        }
        .stats-progress-bar {
            height: 3px;
            background: #f1f5f9;
            width: 100%;
        }
        .stats-progress-fill {
            height: 100%;
            border-radius: 0 2px 2px 0;
            transition: width 1s ease;
        }
        .bg-primary { background: #6366f1; }
        .bg-success { background: #22c55e; }
        .bg-danger { background: #ef4444; }
        .bg-warning { background: #f59e0b; }
        .bg-info { background: #3b82f6; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            gap: 4px;
        }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #991b1b; }

        .action-buttons {
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .action-buttons .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 6px;
            transition: all 0.2s ease;
        }
        .action-buttons .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }
        .category-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #f1f5f9;
            font-size: 16px;
            color: #6366f1;
            flex-shrink: 0;
        }
        .category-name {
            font-weight: 600;
            color: #0f172a;
        }
        .category-faq-count {
            font-size: 12px;
            color: #64748b;
        }

        .bulk-actions-wrapper {
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0 20px;
        }
        .bulk-actions-wrapper.show {
            max-height: 100px;
            opacity: 1;
            padding: 10px 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-4 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary"><i class="fas fa-folder"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalCategories }}</div>
                                    <div class="stats-label">Total Categories</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar"><div class="stats-progress-fill" style="width:100%; background:#6366f1;"></div></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-success"><i class="fas fa-check-circle"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $activeCategories }}</div>
                                    <div class="stats-label">Active</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar"><div class="stats-progress-fill" style="width:70%; background:#22c55e;"></div></div>
                        </div>
                    </div>
                    <div class="col-md-4 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger"><i class="fas fa-ban"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $inactiveCategories }}</div>
                                    <div class="stats-label">Inactive</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar"><div class="stats-progress-fill" style="width:30%; background:#ef4444;"></div></div>
                        </div>
                    </div>
                </div>

                {{-- Categories Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-folder me-2 text-primary"></i> FAQ Categories
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.faq-categories.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Category
                            </a>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-question-circle"></i> FAQs
                            </a>
                        </div>
                    </div>

                    {{-- Bulk Actions --}}
                    <div class="bulk-actions-wrapper" id="bulkActionsWrapper">
                        <div class="container-fluid p-0">
                            <div class="row align-items-center g-2">
                                <div class="col-lg-3 col-md-12 text-center text-lg-start">
                                    <span class="badge bg-primary me-2" id="selectedCountBadge">0</span>
                                    <span class="fw-medium">
                                        <span id="selectedCountText" class="fw-bold text-primary">0</span>
                                        category<span id="selectedPlural">s</span> selected
                                    </span>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-5 col-sm-6">
                                            <select class="form-select form-select-sm" id="bulkActionSelect">
                                                <option value="">Bulk Actions</option>
                                                <option value="delete">🗑️ Delete</option>
                                                <option value="activate">✅ Activate</option>
                                                <option value="deactivate">⛔ Deactivate</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button class="btn btn-primary btn-sm w-100" id="bulkApplyBtn" onclick="applyBulkAction()">
                                                <i class="fas fa-check me-1"></i> Apply
                                            </button>
                                        </div>
                                        <div class="col-md-4 col-sm-3">
                                            <button class="btn btn-outline-secondary btn-sm w-100" onclick="clearSelection()">
                                                <i class="fas fa-times me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Selected categories will be processed
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:45px;">
                                            <input type="checkbox" class="form-check-input" id="selectAllHeader" onchange="toggleAllCheckboxes(this)">
                                        </th>
                                        <th style="width:40px;">#</th>
                                        <th>Name</th>
                                        <th style="width:100px;">Icon</th>
                                        <th style="width:120px;">FAQs</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:160px;" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($categories as $category)
                                        <tr id="row-{{ $category->id }}" class="category-row">
                                            <td>
                                                <input type="checkbox" class="form-check-input category-checkbox" data-id="{{ $category->id }}" onchange="updateBulkActions()">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="category-icon">{!! $category->icon_html !!}</span>
                                                    <div>
                                                        <div class="category-name">{{ $category->name }}</div>
                                                        @if($category->description)
                                                            <small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <i class="{{ $category->icon ?? 'fas fa-folder' }}"></i>
                                                    {{ $category->icon ?? 'fas fa-folder' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info text-white">
                                                    <i class="fas fa-question-circle me-1"></i>
                                                    {{ $category->faqs()->where('is_active', true)->count() }}
                                                    / {{ $category->faqs->count() }}
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
                                                    <a href="{{ route('admin.faq-categories.edit', $category) }}" class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    <button onclick="toggleStatus({{ $category->id }})"
                                                        class="btn btn-sm {{ $category->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $category->is_active ? 'ban' : 'check-circle' }}"></i>
                                                    </button>
                                                    <button onclick="deleteItem({{ $category->id }}, '{{ addslashes($category->name) }}')"
                                                        class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
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
                                Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
                            </div>
                            <div>{{ $categories->links() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
        let toastShown = false;
        let isProcessing = false;

        function showToast(type, message) {
            if (toastShown) return;
            if (typeof toastr !== 'undefined') {
                const titles = { success: '✅ Success!', error: '❌ Error!', warning: '⚠️ Warning!', info: 'ℹ️ Info' };
                toastr[type](message, titles[type] || 'Notification', {
                    timeOut: 5000, progressBar: true, closeButton: true,
                    positionClass: 'toast-top-right', preventDuplicates: true,
                    showMethod: 'slideDown', hideMethod: 'slideUp',
                });
                toastShown = true;
                setTimeout(() => { toastShown = false; }, 6000);
            } else { alert(message); }
        }

        function showConfirmToast(message, callback) {
            if (typeof toastr === 'undefined') { if (confirm(message)) callback(); return; }
            toastr.clear();
            const key = '_confirm_' + Date.now();
            const html = `<div style="text-align:center;padding:10px 0;">
                <p style="font-size:15px;margin-bottom:15px;color:#fff;">${message}</p>
                <div style="display:flex;gap:10px;justify-content:center;">
                    <button onclick="window['${key}'](true)" style="background:#22c55e;color:#fff;border:none;padding:8px 25px;border-radius:5px;cursor:pointer;font-weight:600;">
                        <i class="fas fa-check"></i> Yes
                    </button>
                    <button onclick="window['${key}'](false)" style="background:#6b7280;color:#fff;border:none;padding:8px 25px;border-radius:5px;cursor:pointer;font-weight:600;">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                </div>
            </div>`;
            window[key] = function(result) { toastr.clear(); if (result) callback(); else showToast('info', 'Action cancelled'); delete window[key]; };
            toastr.warning(html, 'Confirm Action', { closeButton: false, timeOut: 0, extendedTimeOut: 0, positionClass: 'toast-top-center', progressBar: false, escapeHtml: false });
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.category-checkbox:checked');
            const count = checkboxes.length;
            const wrapper = document.getElementById('bulkActionsWrapper');
            const badge = document.getElementById('selectedCountBadge');
            const text = document.getElementById('selectedCountText');
            const plural = document.getElementById('selectedPlural');
            const applyBtn = document.getElementById('bulkApplyBtn');

            document.querySelectorAll('.category-row').forEach(row => {
                const cb = row.querySelector('.category-checkbox');
                row.classList.toggle('selected', cb && cb.checked);
            });

            if (count > 0) {
                wrapper.classList.add('show');
                badge.textContent = count;
                text.textContent = count;
                plural.textContent = count > 1 ? 's' : '';
                if (applyBtn) applyBtn.disabled = false;
            } else {
                wrapper.classList.remove('show');
                if (applyBtn) applyBtn.disabled = true;
            }

            const allCheckboxes = document.querySelectorAll('.category-checkbox');
            const allChecked = document.querySelectorAll('.category-checkbox:checked');
            const selectAllHeader = document.getElementById('selectAllHeader');
            if (selectAllHeader) {
                selectAllHeader.checked = allCheckboxes.length > 0 && allCheckboxes.length === allChecked.length;
            }
        }

        function toggleAllCheckboxes(headerCheckbox) {
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = headerCheckbox.checked);
            updateBulkActions();
        }

        function clearSelection() {
            document.querySelectorAll('.category-checkbox').forEach(cb => cb.checked = false);
            updateBulkActions();
        }

        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.category-checkbox:checked')).map(cb => cb.dataset.id);
        }

        function applyBulkAction() {
            const select = document.getElementById('bulkActionSelect');
            const action = select.value;
            const ids = getSelectedIds();

            if (ids.length === 0) { showToast('warning', 'Please select at least one category.'); return; }
            if (!action) { showToast('warning', 'Please select a bulk action.'); return; }

            if (isProcessing) return;
            isProcessing = true;

            const btn = document.getElementById('bulkApplyBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

            fetch('{{ route("admin.faq-categories.bulk-action") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ ids: ids, action: action })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                    if (action === 'delete') {
                        ids.forEach(id => {
                            const row = document.getElementById('row-' + id);
                            if (row) {
                                row.style.transition = 'all 0.5s ease';
                                row.style.opacity = '0';
                                row.style.transform = 'translateX(50px)';
                                setTimeout(() => { if (row.parentNode) row.remove(); }, 500);
                            }
                        });
                    }
                    clearSelection();
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('error', data.message || 'Error processing action');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('error', 'An error occurred. Please try again.');
            })
            .finally(() => {
                isProcessing = false;
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-check me-1"></i> Apply';
            });
        }

        function toggleStatus(id) {
            showConfirmToast('Are you sure you want to change the status of this category?', function() {
                const row = document.getElementById('row-' + id);
                if (row) row.style.opacity = '0.5';
                fetch('/admin/faq-categories/' + id + '/toggle-status', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) { showToast('success', data.message); setTimeout(() => location.reload(), 800); }
                    else { showToast('error', data.message || 'Error'); if (row) row.style.opacity = '1'; }
                })
                .catch(() => { showToast('error', 'An error occurred.'); if (row) row.style.opacity = '1'; });
            });
        }

        function deleteItem(id, name) {
            const msg = `Are you sure you want to delete "<strong>${name}</strong>"?<br><small style="color:#999;">This action cannot be undone.</small>`;
            showConfirmToast(msg, function() {
                const row = document.getElementById('row-' + id);
                if (row) row.style.opacity = '0.5';
                fetch('/admin/faq-categories/' + id, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '' }
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
                    } else { showToast('error', data.message || 'Error deleting'); if (row) row.style.opacity = '1'; }
                })
                .catch(() => { showToast('error', 'An error occurred.'); if (row) row.style.opacity = '1'; });
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof toastr !== 'undefined') {
                toastr.options = { closeButton: true, progressBar: true, timeOut: 5000, extendedTimeOut: 1000, positionClass: 'toast-top-right', preventDuplicates: true, newestOnTop: true };
            }
            @if(session('toast')) const toast = @json(session('toast')); showToast(toast.type, toast.message); @endif
            updateBulkActions();
            document.getElementById('selectAllHeader')?.addEventListener('change', function() { toggleAllCheckboxes(this); });
        });
        </script>
    @endpush
@endsection
