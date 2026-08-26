{{-- resources/views/admin/faqs/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'FAQs')
@section('page-title', 'FAQs')
@section('page-subtitle', 'Manage frequently asked questions')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary"><i class="fas fa-question-circle"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalFaqs }}</div>
                                    <div class="stats-label">Total FAQs</div>
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
                                    <div class="stats-number">{{ $activeFaqs }}</div>
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
                                <div class="stats-icon-wrapper bg-danger"><i class="fas fa-ban"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $inactiveFaqs }}</div>
                                    <div class="stats-label">Inactive</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:30%; background:#ef4444;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning"><i class="fas fa-star"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $featuredFaqs }}</div>
                                    <div class="stats-label">Featured</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:40%; background:#f59e0b;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-info"><i class="fas fa-folder"></i></div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalCategories }}</div>
                                    <div class="stats-label">Categories</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width:50%; background:#3b82f6;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- FAQs Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-question-circle me-2 text-primary"></i> Frequently Asked Questions
                        </h5>
                        <div class="d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add FAQ
                            </a>
                            <a href="{{ route('admin.faq-categories.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="fas fa-folder"></i> Categories
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
                                        FAQ<span id="selectedPlural">s</span> selected
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
                                        Selected FAQs will be processed
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
                                            <input type="checkbox" class="form-check-input" id="selectAllHeader"
                                                onchange="toggleAllCheckboxes(this)">
                                        </th>
                                        <th style="width:40px;">#</th>
                                        <th>Question</th>
                                        <th style="width:150px;">Category</th>
                                        <th style="width:100px;">Status</th>
                                        <th style="width:160px;" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($faqs as $faq)
                                        <tr id="row-{{ $faq->id }}" class="faq-row">
                                            <td>
                                                <input type="checkbox" class="form-check-input faq-checkbox"
                                                    data-id="{{ $faq->id }}" onchange="updateBulkActions()">
                                            </td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div>
                                                    <div class="faq-question">{{ Str::limit($faq->question, 60) }}</div>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user me-1"></i> {{ $faq->creator->name ?? 'N/A' }}
                                                        <span class="mx-1">|</span>
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ $faq->created_at->format('d M, Y') }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($faq->category)
                                                    <span class="badge bg-light text-dark">{{ $faq->category->name }}</span>
                                                @else
                                                    <span class="text-muted">Uncategorized</span>
                                                @endif
                                            </td>
                                            <td>{!! $faq->status_badge !!}</td>
                                            <td class="text-end">
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    <button onclick="toggleStatus({{ $faq->id }})"
                                                        class="btn btn-sm {{ $faq->is_active ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $faq->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas fa-{{ $faq->is_active ? 'ban' : 'check-circle' }}"></i>
                                                    </button>
                                                    <button onclick="toggleFeatured({{ $faq->id }})"
                                                        class="btn btn-sm {{ $faq->is_featured ? 'btn-warning' : 'btn-info' }}"
                                                        title="{{ $faq->is_featured ? 'Remove Featured' : 'Make Featured' }}">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                    <button
                                                        onclick="deleteItem({{ $faq->id }}, '{{ addslashes($faq->question) }}')"
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
                                                    <i class="fas fa-question-circle fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No FAQs Found</h5>
                                                    <p class="text-muted small">Click "Add FAQ" to get started.</p>
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
                                Showing {{ $faqs->firstItem() ?? 0 }} to {{ $faqs->lastItem() ?? 0 }} of
                                {{ $faqs->total() }} entries
                            </div>
                            <div>{{ $faqs->links() }}</div>
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
                window[key] = function (result) { toastr.clear(); if (result) callback(); else showToast('info', 'Action cancelled'); delete window[key]; };
                toastr.warning(html, 'Confirm Action', { closeButton: false, timeOut: 0, extendedTimeOut: 0, positionClass: 'toast-top-center', progressBar: false, escapeHtml: false });
            }

            function updateBulkActions() {
                const checkboxes = document.querySelectorAll('.faq-checkbox:checked');
                const count = checkboxes.length;
                const wrapper = document.getElementById('bulkActionsWrapper');
                const badge = document.getElementById('selectedCountBadge');
                const text = document.getElementById('selectedCountText');
                const plural = document.getElementById('selectedPlural');
                const applyBtn = document.getElementById('bulkApplyBtn');

                document.querySelectorAll('.faq-row').forEach(row => {
                    const cb = row.querySelector('.faq-checkbox');
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

                const allCheckboxes = document.querySelectorAll('.faq-checkbox');
                const allChecked = document.querySelectorAll('.faq-checkbox:checked');
                const selectAllHeader = document.getElementById('selectAllHeader');
                if (selectAllHeader) {
                    selectAllHeader.checked = allCheckboxes.length > 0 && allCheckboxes.length === allChecked.length;
                }
            }

            function toggleAllCheckboxes(headerCheckbox) {
                document.querySelectorAll('.faq-checkbox').forEach(cb => cb.checked = headerCheckbox.checked);
                updateBulkActions();
            }

            function clearSelection() {
                document.querySelectorAll('.faq-checkbox').forEach(cb => cb.checked = false);
                updateBulkActions();
            }

            function getSelectedIds() {
                return Array.from(document.querySelectorAll('.faq-checkbox:checked')).map(cb => cb.dataset.id);
            }

            function applyBulkAction() {
                const select = document.getElementById('bulkActionSelect');
                const action = select.value;
                const ids = getSelectedIds();

                if (ids.length === 0) { showToast('warning', 'Please select at least one FAQ.'); return; }
                if (!action) { showToast('warning', 'Please select a bulk action.'); return; }

                if (isProcessing) return;
                isProcessing = true;

                const btn = document.getElementById('bulkApplyBtn');
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Processing...';

                fetch('{{ route("admin.faqs.bulk-action") }}', {
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
                showConfirmToast('Are you sure you want to change the status of this FAQ?', function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';
                    fetch('/admin/faqs/' + id + '/toggle-status', {
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

            function toggleFeatured(id) {
                showConfirmToast('Are you sure you want to change the featured status?', function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';
                    fetch('/admin/faqs/' + id + '/toggle-featured', {
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
                showConfirmToast(msg, function () {
                    const row = document.getElementById('row-' + id);
                    if (row) row.style.opacity = '0.5';
                    fetch('/admin/faqs/' + id, {
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

            document.addEventListener('DOMContentLoaded', function () {
                if (typeof toastr !== 'undefined') {
                    toastr.options = { closeButton: true, progressBar: true, timeOut: 5000, extendedTimeOut: 1000, positionClass: 'toast-top-right', preventDuplicates: true, newestOnTop: true };
                }
                            @if(session('toast')) const toast = @json(session('toast')); showToast(toast.type, toast.message); @endif
                updateBulkActions();
                document.getElementById('selectAllHeader')?.addEventListener('change', function () { toggleAllCheckboxes(this); });
            });
        </script>
    @endpush
@endsection