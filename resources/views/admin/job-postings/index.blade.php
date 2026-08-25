{{-- resources/views/admin/job-postings/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Job Postings - Rozgar Finder')
@section('page-title', 'Job Postings')
@section('page-subtitle', 'Manage all job postings')

@push('styles')
    <style>
        .stats-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            border: 1px solid #f1f5f9;
        }

        .stats-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
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

        .bg-primary {
            background: #6366f1;
        }

        .bg-success {
            background: #22c55e;
        }

        .bg-danger {
            background: #ef4444;
        }

        .bg-warning {
            background: #f59e0b;
        }

        .bg-info {
            background: #3b82f6;
        }

        .bg-purple {
            background: #8b5cf6;
        }

        .bg-orange {
            background: #f97316;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            gap: 4px;
        }

        .status-active {
            background: #dcfce7;
            color: #166534;
        }

        .status-inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-expired {
            background: #fef3c7;
            color: #92400e;
        }

        .status-featured {
            background: #fef9c3;
            color: #854d0e;
        }

        .status-urgent {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-fresh {
            background: #dbeafe;
            color: #1e40af;
        }

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
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        }

        .job-title {
            font-weight: 600;
            color: #0f172a;
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

        .select-all-wrapper {
            display: flex;
            align-items: center;
        }

        .select-all-wrapper .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            margin-top: 0;
        }

        .select-all-wrapper .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .job-checkbox {
            width: 17px;
            height: 17px;
            cursor: pointer;
        }

        .job-checkbox:checked {
            background-color: #6366f1;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table tbody tr.selected {
            background: #eff6ff;
        }

        .empty-state i {
            opacity: 0.3;
        }

        @media (max-width: 992px) {
            .bulk-actions-wrapper.show {
                max-height: 200px;
                padding: 12px 16px;
            }
        }

        @media (max-width: 768px) {
            .bulk-actions-wrapper.show {
                max-height: 250px;
                padding: 12px 16px;
            }

            .bulk-actions-wrapper .row>[class*="col-"] {
                margin-bottom: 6px;
            }
        }

        @media (max-width: 576px) {
            .bulk-actions-wrapper.show {
                max-height: 300px;
                padding: 10px 12px;
            }

            .bulk-actions-wrapper .btn-sm {
                font-size: 12px;
            }

            .bulk-actions-wrapper .form-select-sm {
                font-size: 12px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary"><i class="fas fa-briefcase"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalJobs }}</div>
                                    <div class="stats-label">Total Jobs</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:100%; background:#6366f1;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-success"><i class="fas fa-check-circle"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $activeJobs }}</div>
                                    <div class="stats-label">Active</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:70%; background:#22c55e;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning"><i class="fas fa-clock"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $expiredJobs }}</div>
                                    <div class="stats-label">Expired</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:30%; background:#f59e0b;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-purple"><i class="fas fa-star"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $featuredJobs }}</div>
                                    <div class="stats-label">Featured</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:40%; background:#8b5cf6;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger"><i class="fas fa-fire"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $urgentJobs }}</div>
                                    <div class="stats-label">Urgent</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:20%; background:#ef4444;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-info"><i class="fas fa-globe"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $remoteJobs }}</div>
                                    <div class="stats-label">Remote</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:25%; background:#3b82f6;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Jobs Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-briefcase me-2 text-primary"></i> Job Postings
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.job-postings.scrape.form') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-rss"></i> Scrape Jobs
                            </a>
                            <a href="{{ route('admin.job-postings.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Job
                            </a>
                        </div>
                    </div>

                    {{-- Bulk Actions Bar --}}
                    <div class="bulk-actions-wrapper mt-20" id="bulkActionsWrapper">
                        <div class="container-fluid p-0">
                            <div class="row align-items-center g-2">
                                <div class="col-lg-3 col-md-12 text-center text-lg-start">
                                    <span class="badge bg-primary me-2" id="selectedCountBadge">0</span>
                                    <span class="fw-medium">
                                        <span id="selectedCountText" class="fw-bold text-primary">0</span>
                                        job<span id="selectedPlural">s</span> selected
                                    </span>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="row g-2 align-items-center">
                                        <div class="col-md-5 col-sm-6">
                                            <select class="form-select form-select-sm" id="bulkActionSelect">
                                                <option value="">Bulk Actions</option>
                                                <option value="delete">🗑️ Delete Permanently</option>
                                                <option value="activate">✅ Activate</option>
                                                <option value="deactivate">⛔ Deactivate</option>
                                                <option value="featured">⭐ Make Featured</option>
                                                <option value="unfeatured">⭐ Remove Featured</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3 col-sm-3">
                                            <button class="btn btn-primary btn-sm w-100" id="bulkApplyBtn"
                                                onclick="applyBulkAction()">
                                                <i class="fas fa-check me-1"></i> Apply
                                            </button>
                                        </div>
                                        <div class="col-md-4 col-sm-3">
                                            <button class="btn btn-outline-secondary btn-sm w-100"
                                                onclick="clearSelection()">
                                                <i class="fas fa-times me-1"></i> Clear
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-12 text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Selected jobs will be permanently deleted
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
                                            <div class="select-all-wrapper">
                                                <input type="checkbox" class="form-check-input" id="selectAllHeader">
                                            </div>
                                        </th>
                                        <th style="width:40px;">#</th>
                                        <th>Title</th>
                                        <th style="width:120px;">Category</th>
                                        <th style="width:120px;">Location</th>
                                        <th style="width:110px;">Deadline</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:160px;" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="jobsTableBody">
                                    @forelse($jobs as $job)
                                        <tr id="row-{{ $job->id }}" class="job-row">
                                            <td>
                                                <input type="checkbox" class="form-check-input job-checkbox"
                                                    data-id="{{ $job->id }}">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div>
                                                    <div class="job-title">{{ Str::limit($job->title, 40) }}</div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i> {{ $job->author->name ?? 'N/A' }}
                                                        <span class="mx-1">|</span>
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $job->created_at->format('d M, Y') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($job->category)
                                                    <span class="badge bg-light text-dark">{{ $job->category->name }}</span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($job->location)
                                                    <span class="badge bg-light text-dark">
                                                        <i class="fas fa-map-marker-alt me-1"></i>
                                                        {{ Str::limit($job->location, 20) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                                @if($job->is_remote)
                                                    <span class="badge bg-info text-white ms-1">Remote</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($job->deadline)
                                                    <div>
                                                        <strong>{{ $job->deadline->format('d M, Y') }}</strong>
                                                        @if($job->days_remaining > 0)
                                                            <br><small class="text-success">{{ $job->days_remaining }} days left</small>
                                                        @elseif($job->is_expired)
                                                            <br><small class="text-danger">Expired</small>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-muted">No deadline</span>
                                                @endif
                                            </td>
                                            <td>{!! $job->status_badge !!}</td>
                                            <td class="text-end">
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.job-postings.edit', $job) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    <button onclick="toggleStatus({{ $job->id }})"
                                                        class="btn btn-sm {{ $job->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $job->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $job->is_active ? 'ban' : 'check-circle' }}"></i>
                                                    </button>
                                                    <button
                                                        onclick="deleteItem({{ $job->id }}, '{{ addslashes($job->title) }}')"
                                                        class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-briefcase fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No Jobs Found</h5>
                                                    <p class="text-muted small">Click "Add Job" or "Scrape Jobs" to get started.
                                                    </p>
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
                                Showing {{ $jobs->firstItem() ?? 0 }} to {{ $jobs->lastItem() ?? 0 }} of
                                {{ $jobs->total() }} entries
                            </div>
                            <div>{{ $jobs->links() }}</div>
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

            // ============================================================
            // TOAST FUNCTIONS
            // ============================================================

            function showToast(type, message) {
                if (toastShown) return;
                if (typeof toastr !== 'undefined') {
                    const titles = { success: '✅ Success!', error: '❌ Error!', warning: '⚠️ Warning!', info: 'ℹ️ Info' };
                    toastr[type](message, titles[type] || 'Notification', {
                        timeOut: 5000,
                        progressBar: true,
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        preventDuplicates: true,
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                    });
                    toastShown = true;
                    setTimeout(() => { toastShown = false; }, 6000);
                } else { alert(message); }
            }

            function showConfirmToast(message, callback) {
                if (typeof toastr === 'undefined') {
                    if (confirm(message)) callback();
                    return;
                }

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

                window[key] = function (result) {
                    toastr.clear();
                    if (result) callback();
                    else showToast('info', 'Action cancelled');
                    delete window[key];
                };

                toastr.warning(html, 'Confirm Action', {
                    closeButton: false,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    positionClass: 'toast-top-center',
                    progressBar: false,
                    escapeHtml: false
                });
            }

            // ============================================================
            // BULK ACTIONS - FIXED COUNT
            // ============================================================

            function updateBulkActions() {
                const checkboxes = document.querySelectorAll('.job-checkbox');
                const checkedBoxes = document.querySelectorAll('.job-checkbox:checked');
                const count = checkedBoxes.length;

                const wrapper = document.getElementById('bulkActionsWrapper');
                const countBadge = document.getElementById('selectedCountBadge');
                const countText = document.getElementById('selectedCountText');
                const plural = document.getElementById('selectedPlural');
                const applyBtn = document.getElementById('bulkApplyBtn');

                // Update row selection styles
                checkboxes.forEach(cb => {
                    const row = cb.closest('.job-row');
                    if (row) {
                        if (cb.checked) {
                            row.classList.add('selected');
                        } else {
                            row.classList.remove('selected');
                        }
                    }
                });

                // Update count and visibility
                if (count > 0) {
                    wrapper.classList.add('show');
                    countBadge.textContent = count;
                    countText.textContent = count;
                    plural.textContent = count > 1 ? 's' : '';
                    if (applyBtn) applyBtn.disabled = false;
                } else {
                    wrapper.classList.remove('show');
                    if (applyBtn) applyBtn.disabled = true;
                }

                // Update Select All header
                const selectAllHeader = document.getElementById('selectAllHeader');
                if (selectAllHeader) {
                    selectAllHeader.checked = checkboxes.length > 0 && checkboxes.length === checkedBoxes.length;
                }
            }

            function toggleAllCheckboxes(headerCheckbox) {
                const checkboxes = document.querySelectorAll('.job-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = headerCheckbox.checked;
                });
                updateBulkActions();
            }

            function clearSelection() {
                const checkboxes = document.querySelectorAll('.job-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = false;
                });
                updateBulkActions();
            }

            function getSelectedIds() {
                return Array.from(document.querySelectorAll('.job-checkbox:checked')).map(cb => cb.dataset.id);
            }

            function applyBulkAction() {
                const select = document.getElementById('bulkActionSelect');
                const action = select.value;
                const ids = getSelectedIds();

                if (ids.length === 0) {
                    showToast('warning', 'Please select at least one job.');
                    return;
                }
                if (!action) {
                    showToast('warning', 'Please select a bulk action.');
                    return;
                }

                switch (action) {
                    case 'delete': confirmBulkDelete(ids); break;
                    case 'activate': confirmBulkStatus(ids, 'activate'); break;
                    case 'deactivate': confirmBulkStatus(ids, 'deactivate'); break;
                    case 'featured': confirmBulkFeatured(ids, true); break;
                    case 'unfeatured': confirmBulkFeatured(ids, false); break;
                    default: showToast('error', 'Unknown action.');
                }
            }

            function confirmBulkDelete(ids) {
                const message = `
                <strong>Delete ${ids.length} job(s)?</strong><br>
                <small style="color: #999;">This action cannot be undone. All selected jobs will be permanently deleted.</small>
            `;
                showConfirmToast(message, function () {
                    processBulkAction('delete', ids, 'job(s) deleted successfully!');
                });
            }

            function confirmBulkStatus(ids, action) {
                const statusText = action === 'activate' ? 'Activate' : 'Deactivate';
                const done = action === 'activate' ? 'activated' : 'deactivated';
                const message = `
                <strong>${statusText} ${ids.length} job(s)?</strong><br>
                <small style="color: #999;">Selected jobs will be ${done}.</small>
            `;
                showConfirmToast(message, function () {
                    processBulkAction('status', ids, `${ids.length} job(s) ${done} successfully!`, action);
                });
            }

            function confirmBulkFeatured(ids, featured) {
                const label = featured ? 'Make Featured' : 'Remove Featured';
                const done = featured ? 'marked as featured' : 'removed from featured';
                const message = `
                <strong>${label} ${ids.length} job(s)?</strong><br>
                <small style="color: #999;">Selected jobs will be ${done}.</small>
            `;
                showConfirmToast(message, function () {
                    processBulkAction('featured', ids, `${ids.length} job(s) ${done} successfully!`, featured);
                });
            }

            function processBulkAction(action, ids, successMessage, extra = null) {
                if (isProcessing) return;
                isProcessing = true;

                const btn = document.getElementById('bulkApplyBtn');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';
                }

                const payload = { ids: ids, action: action };
                if (extra !== null) {
                    payload.extra = extra;
                }

                fetch('{{ route("admin.job-postings.bulk-action") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify(payload)
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            showToast('success', data.message || successMessage);
                            if (action === 'delete') {
                                ids.forEach(id => {
                                    const row = document.getElementById('row-' + id);
                                    if (row) {
                                        row.style.transition = 'all 0.5s ease';
                                        row.style.opacity = '0';
                                        row.style.transform = 'translateX(50px)';
                                        setTimeout(() => {
                                            if (row.parentNode) row.remove();
                                        }, 500);
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
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check me-1"></i> Apply';
                        }
                    });
            }

            // ============================================================
            // SINGLE DELETE
            // ============================================================

            function deleteItem(id, name) {
                const msg = `Are you sure you want to delete "<strong>${name}</strong>"?<br><small style="color:#999;">This action cannot be undone.</small>`;
                showConfirmToast(msg, function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';

                    fetch('/admin/job-postings/' + id, {
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
                            showToast('error', 'An error occurred.');
                            if (row) row.style.opacity = '1';
                        });
                });
            }

            // ============================================================
            // TOGGLE STATUS
            // ============================================================

            function toggleStatus(id) {
                showConfirmToast('Are you sure you want to change the status of this job?', function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';

                    fetch('/admin/job-postings/' + id + '/toggle-status', {
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
                                showToast('error', data.message || 'Error');
                                if (row) row.style.opacity = '1';
                            }
                        })
                        .catch(() => {
                            showToast('error', 'An error occurred.');
                            if (row) row.style.opacity = '1';
                        });
                });
            }

            // ============================================================
            // DOCUMENT READY
            // ============================================================

            document.addEventListener('DOMContentLoaded', function () {
                // Toastr config
                if (typeof toastr !== 'undefined') {
                    toastr.options = {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 5000,
                        extendedTimeOut: 1000,
                        positionClass: 'toast-top-right',
                        preventDuplicates: true,
                        newestOnTop: true
                    };
                }

                // Session messages
                @if(session('toast'))
                    const toast = @json(session('toast'));
                    showToast(toast.type, toast.message);
                @endif

            // ✅ Attach change event to ALL checkboxes
            const allCheckboxes = document.querySelectorAll('.job-checkbox');
                allCheckboxes.forEach(cb => {
                    cb.addEventListener('change', updateBulkActions);
                });

                // ✅ Select All header
                const selectAllHeader = document.getElementById('selectAllHeader');
                if (selectAllHeader) {
                    selectAllHeader.addEventListener('change', function () {
                        toggleAllCheckboxes(this);
                    });
                }

                // ✅ Initialize bulk actions
                updateBulkActions();
            });
        </script>
    @endpush
@endsection