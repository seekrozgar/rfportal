{{-- resources/views/admin/location/states/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'States - Rozgar Finder')
@section('page-title', 'States')
@section('page-subtitle', 'Manage states/provinces')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-map-marker-alt me-2" style="color: var(--primary-color);"></i> States / Provinces</h5>
            <div class="card-actions">
                <button onclick="openAddModal()" class="btn-admin-primary">
                    <i class="fas fa-plus"></i> Add State
                </button>
            </div>
        </div>

        <!-- ✅ Filter - Inline with Label -->
        <div class="card-body border-bottom" style="padding: 12px 20px; background: #f8f9fa;">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="fw-bold mb-0" style="font-size: 14px; white-space: nowrap;">
                        <i class="fas fa-filter me-1"></i> Filter by Country:
                    </label>
                </div>
                <div class="col-md-3">
                    <select name="country_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All Countries</option>
                        @foreach($countries as $c)
                            <option value="{{ $c->id }}" {{ request('country_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.location.states.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
                <div class="col-auto ms-auto">
                    <span class="text-muted small">Total: {{ $states->total() }} states</span>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="admin-table" id="statesTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Name</th>
                        <th style="width: 80px;">Code</th>
                        <th>Country</th>
                        <th style="width: 80px;">Cities</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 160px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($states as $state)
                        <tr id="row-{{ $state->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $state->name }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $state->code ?? '-' }}</span></td>
                            <td>
                                <span class="badge bg-info">{{ $state->country->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.location.cities.index', ['state_id' => $state->id]) }}"
                                    class="text-primary fw-bold">
                                    {{ $state->cities->count() }}
                                </a>
                            </td>
                            <td>
                                <span class="badge-{{ $state->is_active ? 'active' : 'inactive' }}">
                                    {{ $state->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons">
                                    <button
                                        onclick="editItem({{ $state->id }}, '{{ addslashes($state->name) }}', '{{ $state->code }}', {{ $state->country_id }})"
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="toggleStatus({{ $state->id }})"
                                        class="btn btn-sm {{ $state->is_active ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $state->is_active ? 'Disable' : 'Enable' }}">
                                        <i class="fas fa-{{ $state->is_active ? 'ban' : 'check-circle' }}"></i>
                                    </button>
                                    <button
                                        onclick="deleteItem({{ $state->id }}, '{{ addslashes($state->name) }}', {{ $state->cities->count() }})"
                                        class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-map-marker-alt fa-2x d-block mb-2"></i>
                                No states found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Improved Pagination --}}
        <div class="pagination-wrapper mt-3">
            @if ($states->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($states->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $states->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($states->links()->elements as $element)
                            @if (is_string($element))
                                <li class="page-item disabled">
                                    <span class="page-link">{{ $element }}</span>
                                </li>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $states->currentPage())
                                        <li class="page-item active" aria-current="page">
                                            <span class="page-link">{{ $page }}</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                        </li>
                                    @endif
                                @endforeach
                            @endif
                        @endforeach

                        {{-- Next Page Link --}}
                        @if ($states->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $states->nextPageUrl() }}" rel="next">
                                    <i class="fas fa-chevron-right" style="font-size: 11px;"></i>
                                </a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-right" style="font-size: 11px;"></i>
                                </span>
                            </li>
                        @endif
                    </ul>
                </nav>

                <div class="pagination-info text-center text-muted small">
                    Showing {{ $states->firstItem() ?? 0 }} to {{ $states->lastItem() ?? 0 }} of {{ $states->total() }} entries
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="stateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add State</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="stateForm">
                        @csrf
                        <input type="hidden" id="itemId" value="">
                        <div class="form-group">
                            <label>Country <span class="text-danger">*</span></label>
                            <select id="itemCountryId" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label>State Name <span class="text-danger">*</span></label>
                            <input type="text" id="itemName" class="form-control" placeholder="Enter state name" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>State Code</label>
                            <input type="text" id="itemCode" class="form-control" placeholder="e.g., PB, SD" maxlength="10">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-admin-primary" id="saveBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalElement = document.getElementById('stateModal');
            let modal = new bootstrap.Modal(modalElement);
            window.stateModal = modal;

            document.getElementById('saveBtn').addEventListener('click', function () {
                saveItem();
            });

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
            document.getElementById('modalTitle').textContent = 'Add State';
            document.getElementById('itemId').value = '';
            document.getElementById('itemName').value = '';
            document.getElementById('itemCode').value = '';
            document.getElementById('itemCountryId').value = '{{ request('country_id') }}' || '';
            document.getElementById('saveBtn').textContent = 'Add';
            window.stateModal.show();
        }

        function editItem(id, name, code, countryId) {
            document.getElementById('modalTitle').textContent = 'Edit State';
            document.getElementById('itemId').value = id;
            document.getElementById('itemName').value = name;
            document.getElementById('itemCode').value = code || '';
            document.getElementById('itemCountryId').value = countryId;
            document.getElementById('saveBtn').textContent = 'Update';
            window.stateModal.show();
        }

        function saveItem() {
            const id = document.getElementById('itemId').value;
            const countryId = document.getElementById('itemCountryId').value;
            const name = document.getElementById('itemName').value.trim();
            const code = document.getElementById('itemCode').value.trim();

            if (!countryId || !name) {
                showToast('error', 'Please fill all required fields');
                return;
            }

            const url = id ? `/admin/location/states/${id}` : `/admin/location/states`;
            const method = id ? 'PUT' : 'POST';
            const saveBtn = document.getElementById('saveBtn');
            const originalText = saveBtn.textContent;

            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ country_id: countryId, name, code })
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        showToast('success', response.message);
                        window.stateModal.hide();
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

            fetch(`/admin/location/states/${id}/toggle`, {
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

        function deleteItem(id, name, citiesCount) {
            let message = `Are you sure you want to delete "<strong>${name}</strong>"?`;
            if (citiesCount > 0) {
                message += `<br><small style="color: #ffc107;">⚠️ This state has ${citiesCount} city/cities. They will not be deleted.</small>`;
            }
            message += `<br><small style="color: #999;">This action cannot be undone.</small>`;

            showDeleteConfirm(message, function () {
                const row = document.getElementById(`row-${id}`);
                if (row) row.style.opacity = '0.5';

                fetch(`/admin/location/states/${id}`, {
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