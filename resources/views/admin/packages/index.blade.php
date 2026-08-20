{{-- resources/views/admin/packages/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Packages - Admin')
@section('page-title', 'Packages')
@section('page-subtitle', 'Manage subscription packages')

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
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $packages->total() }}</div>
                                    <div class="stats-label">Total Packages</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-completed">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $packages->where('is_active', true)->count() }}</div>
                                    <div class="stats-label">Active</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 70%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger">
                                    <i class="fas fa-ban"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $packages->where('is_active', false)->count() }}</div>
                                    <div class="stats-label">Inactive</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 30%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $packages->where('is_featured', true)->count() }}</div>
                                    <div class="stats-label">Featured</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 40%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Packages Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-box me-2 text-primary"></i> Packages
                        </h5>
                        <div>
                            {{-- ✅ Attractive Filter Buttons --}}
                            <div class="filter-buttons-group" role="group">
                                <a href="{{ route('admin.packages.index') }}?type=all"
                                    class="filter-btn {{ request('type') == 'all' || !request('type') ? 'active' : '' }}">
                                    <i class="fas fa-th-list"></i> All
                                </a>
                                <a href="{{ route('admin.packages.index') }}?type=employer"
                                    class="filter-btn filter-btn-employer {{ request('type') == 'employer' ? 'active' : '' }}">
                                    <i class="fas fa-user-tie"></i> Employer
                                </a>
                                <a href="{{ route('admin.packages.index') }}?type=seeker"
                                    class="filter-btn filter-btn-seeker {{ request('type') == 'seeker' ? 'active' : '' }}">
                                    <i class="fas fa-user"></i> Seeker
                                </a>
                            </div>
                            <button onclick="openAddModal()" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Package
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="packagesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Name</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-end">Price</th>
                                        <th class="text-center">Duration</th>
                                        <th class="text-center">Features</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center" style="width: 130px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($packages as $package)
                                        <tr id="row-{{ $package->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="package-name">
                                                    <strong>{{ $package->name }}</strong>
                                                    @if($package->is_featured)
                                                        <span class="featured-badge">
                                                            <i class="fas fa-star"></i> Featured
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="type-badge {{ $package->type === 'employer' ? 'type-employer' : 'type-seeker' }}">
                                                    <i
                                                        class="fas {{ $package->type === 'employer' ? 'fa-user-tie' : 'fa-user' }}"></i>
                                                    {{ ucfirst($package->type) }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="price-text">PKR {{ number_format($package->price) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="duration-badge">{{ $package->duration_days }} days</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="features-count">{{ count($package->features) }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($package->is_active)
                                                    <span class="status-badge status-completed">
                                                        <i class="fas fa-check-circle"></i> Active
                                                    </span>
                                                @else
                                                    <span class="status-badge status-failed">
                                                        <i class="fas fa-times-circle"></i> Inactive
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <button onclick="editItem({{ $package->id }})" class="btn-action" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="toggleStatus({{ $package->id }})"
                                                    class="btn-action {{ $package->is_active ? 'text-warning' : 'text-success' }}"
                                                    title="{{ $package->is_active ? 'Disable' : 'Enable' }}">
                                                    <i class="fas fa-{{ $package->is_active ? 'ban' : 'check-circle' }}"></i>
                                                </button>
                                                <button
                                                    onclick="deleteItem({{ $package->id }}, '{{ addslashes($package->name) }}')"
                                                    class="btn-action text-danger" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-box fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No Packages Found</h5>
                                                    <p class="text-muted small">Click "Add Package" to create your first
                                                        package.</p>
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
                                Showing {{ $packages->firstItem() ?? 0 }} to {{ $packages->lastItem() ?? 0 }} of
                                {{ $packages->total() }} entries
                            </div>
                            <div>
                                {{ $packages->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div class="modal fade" id="packageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">
                        <i class="fas fa-box me-2 text-primary"></i> Add Package
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="packageForm">
                        @csrf
                        <input type="hidden" id="itemId" value="">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Package Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" id="itemName" class="form-control" placeholder="e.g. Pro Plan"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Type <span class="text-danger">*</span></label>
                                    <select id="itemType" class="form-select" required>
                                        <option value="employer">Employer</option>
                                        <option value="seeker">Seeker</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Price (PKR) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="itemPrice" class="form-control" min="0" placeholder="0"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Duration (Days) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" id="itemDuration" class="form-control" min="1" placeholder="30"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Display Order</label>
                                    <input type="number" id="itemOrder" class="form-control" min="0" placeholder="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Job Posts Limit (Employer)</label>
                                    <input type="number" id="itemJobLimit" class="form-control" min="0"
                                        placeholder="e.g. 10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Resume Views Limit (Seeker)</label>
                                    <input type="number" id="itemResumeLimit" class="form-control" min="0"
                                        placeholder="e.g. 100">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Badge Color</label>
                                    <input type="color" id="itemBadgeColor" class="form-control form-control-color"
                                        value="#6c757d">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Featured</label>
                                    <select id="itemFeatured" class="form-select">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Active</label>
                                    <select id="itemActive" class="form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea id="itemDescription" class="form-control" rows="2"
                                placeholder="Brief description of the package"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">Features (one per line)</label>
                            <textarea id="itemFeatures" class="form-control" rows="4"
                                placeholder="Feature 1&#10;Feature 2&#10;Feature 3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('packageModal');
            let modal = new bootstrap.Modal(modalElement);
            window.packageModal = modal;

            document.getElementById('saveBtn').addEventListener('click', function () {
                saveItem();
            });

            // Enter key support
            document.getElementById('itemName').addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveItem();
                }
            });
        });

        function showToast(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                alert(message);
            }
        }

        function openAddModal() {
            const modal = window.packageModal;
            if (!modal) {
                alert('Modal not initialized. Please refresh the page.');
                return;
            }

            try {
                document.getElementById('modalTitle').innerHTML = '<i class="fas fa-box me-2 text-primary"></i> Add Package';
                document.getElementById('itemId').value = '';
                document.getElementById('itemName').value = '';
                document.getElementById('itemType').value = 'employer';
                document.getElementById('itemPrice').value = '';
                document.getElementById('itemDuration').value = '30';
                document.getElementById('itemOrder').value = '0';
                document.getElementById('itemJobLimit').value = '';
                document.getElementById('itemResumeLimit').value = '';
                document.getElementById('itemBadgeColor').value = '#6c757d';
                document.getElementById('itemFeatured').value = '0';
                document.getElementById('itemActive').value = '1';
                document.getElementById('itemDescription').value = '';
                document.getElementById('itemFeatures').value = '';
                document.getElementById('saveBtn').textContent = 'Save';
                modal.show();
            } catch (e) {
                console.error('Modal error:', e);
                alert('Error opening modal. Please refresh the page.');
            }
        }

        function editItem(id) {
            const modal = window.packageModal;
            if (!modal) {
                alert('Modal not initialized. Please refresh the page.');
                return;
            }

            fetch(`/admin/packages/${id}/edit`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const pkg = data.data;
                        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit me-2 text-primary"></i> Edit Package';
                        document.getElementById('itemId').value = pkg.id;
                        document.getElementById('itemName').value = pkg.name;
                        document.getElementById('itemType').value = pkg.type;
                        document.getElementById('itemPrice').value = pkg.price;
                        document.getElementById('itemDuration').value = pkg.duration_days;
                        document.getElementById('itemOrder').value = pkg.display_order || 0;
                        document.getElementById('itemJobLimit').value = pkg.job_posts_limit || '';
                        document.getElementById('itemResumeLimit').value = pkg.resume_views_limit || '';
                        document.getElementById('itemBadgeColor').value = pkg.badge_color || '#6c757d';
                        document.getElementById('itemFeatured').value = pkg.is_featured ? '1' : '0';
                        document.getElementById('itemActive').value = pkg.is_active ? '1' : '0';
                        document.getElementById('itemDescription').value = pkg.description || '';
                        document.getElementById('itemFeatures').value = (pkg.features || []).join('\n');
                        document.getElementById('saveBtn').textContent = 'Update';
                        modal.show();
                    }
                })
                .catch(() => {
                    showToast('error', 'Failed to load package data');
                });
        }

        function saveItem() {
            const id = document.getElementById('itemId').value;
            const name = document.getElementById('itemName').value.trim();
            const type = document.getElementById('itemType').value;
            const price = document.getElementById('itemPrice').value;
            const duration = document.getElementById('itemDuration').value;
            const displayOrder = document.getElementById('itemOrder').value;
            const jobLimit = document.getElementById('itemJobLimit').value;
            const resumeLimit = document.getElementById('itemResumeLimit').value;
            const badgeColor = document.getElementById('itemBadgeColor').value;
            const isFeatured = document.getElementById('itemFeatured').value;
            const isActive = document.getElementById('itemActive').value;
            const description = document.getElementById('itemDescription').value.trim();
            const features = document.getElementById('itemFeatures').value.split('\n').filter(f => f.trim() !== '');

            if (!name) {
                showToast('error', 'Please enter package name');
                return;
            }

            if (!price || parseFloat(price) < 0) {
                showToast('error', 'Please enter a valid price');
                return;
            }

            if (!duration || parseInt(duration) < 1) {
                showToast('error', 'Please enter valid duration');
                return;
            }

            const url = id ? `/admin/packages/${id}` : `/admin/packages`;
            const method = id ? 'PUT' : 'POST';
            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.textContent;

            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({
                    name, type, price, duration_days: duration,
                    display_order: displayOrder || 0,
                    job_posts_limit: jobLimit || null,
                    resume_views_limit: resumeLimit || null,
                    badge_color: badgeColor,
                    is_featured: isFeatured === '1',
                    is_active: isActive === '1',
                    description,
                    features
                })
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        showToast('success', response.message);
                        const modal = window.packageModal;
                        if (modal) {
                            modal.hide();
                        }
                        setTimeout(() => location.reload(), 500);
                    } else {
                        showToast('error', response.message || 'Error saving');
                    }
                })
                .catch(() => showToast('error', 'An error occurred'))
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText;
                });
        }

        function toggleStatus(id) {
            if (!confirm('Are you sure you want to change status?')) return;

            fetch(`/admin/packages/${id}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        showToast('success', response.message);
                        location.reload();
                    } else {
                        showToast('error', response.message || 'Error toggling status');
                    }
                })
                .catch(() => showToast('error', 'An error occurred'));
        }

        function deleteItem(id, name) {
            let message = `Are you sure you want to delete "<strong>${name}</strong>"?`;
            message += `<br><small style="color: #999;">This action cannot be undone.</small>`;

            showDeleteConfirm(message, function () {
                const row = document.getElementById(`row-${id}`);
                if (row) row.style.opacity = '0.5';

                fetch(`/admin/packages/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            showToast('success', response.message);
                            if (row) {
                                row.style.transition = 'all 0.5s ease';
                                row.style.opacity = '0';
                                setTimeout(() => {
                                    if (row.parentNode) row.remove();
                                }, 500);
                            }
                        } else {
                            showToast('error', response.message || 'Error deleting');
                            if (row) row.style.opacity = '1';
                        }
                    })
                    .catch(() => {
                        showToast('error', 'An error occurred');
                        if (row) row.style.opacity = '1';
                    });
            });
        }

        function showDeleteConfirm(message, callback) {
            if (typeof toastr === 'undefined') {
                if (confirm(message.replace(/<[^>]*>/g, ''))) {
                    callback();
                }
                return;
            }

            toastr.clear();

            var confirmHtml = `
                        <div style="text-align: center; padding: 10px 0;">
                            <p style="font-size: 15px; margin-bottom: 15px; color: #fff;">${message}</p>
                            <div style="display: flex; gap: 10px; justify-content: center;">
                                <button onclick="window._deleteConfirmCallback(true)"
                                        style="background: #e74c3c; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                                <button onclick="window._deleteConfirmCallback(false)"
                                        style="background: #28a745; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                            </div>
                        </div>
                    `;

            window._deleteConfirmCallback = function (result) {
                toastr.clear();
                if (result) {
                    callback();
                } else {
                    toastr.info('Action cancelled');
                }
                delete window._deleteConfirmCallback;
            };

            toastr.warning(confirmHtml, 'Confirm Delete', {
                closeButton: false,
                timeOut: 0,
                extendedTimeOut: 0,
                positionClass: 'toast-top-center',
                progressBar: false,
                escapeHtml: false,
            });
        }
    </script>
@endpush