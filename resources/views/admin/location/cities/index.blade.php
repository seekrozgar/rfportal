{{-- resources/views/admin/location/cities/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Cities - Rozgar Finder')
@section('page-title', 'Cities')
@section('page-subtitle', 'Manage cities worldwide')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-city me-2" style="color: var(--primary-color);"></i> Cities</h5>
            <div class="card-actions">
                <button onclick="openAddModal()" class="btn-admin-primary">
                    <i class="fas fa-plus"></i> Add City
                </button>
            </div>
        </div>

        <!-- ✅ Filter - Inline with Label -->
        <div class="card-body border-bottom" style="padding: 12px 20px; background: #f8f9fa;">
            <form method="GET" class="row g-2 align-items-center" id="filterForm">
                <div class="col-auto">
                    <label class="fw-bold mb-0" style="font-size: 14px; white-space: nowrap;">
                        <i class="fas fa-filter me-1"></i> Filter by State:
                    </label>
                </div>
                <div class="col-md-3">
                    <select name="state_id" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="">All States</option>
                        @foreach($states as $s)
                            <option value="{{ $s->id }}" {{ request('state_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->name }} ({{ $s->country->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <a href="{{ route('admin.location.cities.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-times"></i> Clear
                    </a>
                </div>
                <div class="col-auto ms-auto">
                    <span class="text-muted small">Total: {{ $cities->total() }} cities</span>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table class="admin-table" id="citiesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Status</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cities as $city)
                        <tr id="row-{{ $city->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $city->name }}</strong></td>
                            <td>{{ $city->state->name ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-info">{{ $city->state->country->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge-{{ $city->is_active ? 'active' : 'inactive' }}">
                                    {{ $city->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons">
                                    <button
                                        onclick="editItem({{ $city->id }}, '{{ addslashes($city->name) }}', {{ $city->state_id }})"
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="toggleStatus({{ $city->id }})"
                                        class="btn btn-sm {{ $city->is_active ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $city->is_active ? 'Disable' : 'Enable' }}">
                                        <i class="fas fa-{{ $city->is_active ? 'ban' : 'check-circle' }}"></i>
                                    </button>
                                    <button onclick="deleteItem({{ $city->id }}, '{{ addslashes($city->name) }}')"
                                        class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-city fa-2x d-block mb-2"></i>
                                No cities found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $cities->links() }}
        </div>
    </div>

    <!-- ✅ Add/Edit Modal with Country → State Dropdown -->
    <div class="modal fade" id="cityModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add City</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="cityForm">
                        @csrf
                        <input type="hidden" id="itemId" value="">

                        <!-- ✅ Country Select - First -->
                        <div class="form-group">
                            <label>Country <span class="text-danger">*</span></label>
                            <select id="itemCountryId" class="form-select" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- ✅ State Select - Depends on Country -->
                        <div class="form-group mt-3">
                            <label>State <span class="text-danger">*</span></label>
                            <select id="itemStateId" class="form-select" required>
                                <option value="">First select a country</option>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label>City Name <span class="text-danger">*</span></label>
                            <input type="text" id="itemName" class="form-control" placeholder="Enter city name" required>
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
        const modalElement = document.getElementById('cityModal');
        let modal = new bootstrap.Modal(modalElement);
        window.cityModal = modal;

        document.getElementById('saveBtn').addEventListener('click', function () {
            saveItem();
        });

        document.getElementById('itemName').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                saveItem();
            }
        });

        // ✅ Country → State dropdown change
        document.getElementById('itemCountryId').addEventListener('change', function() {
            const countryId = this.value;
            const stateSelect = document.getElementById('itemStateId');

            if (!countryId) {
                stateSelect.innerHTML = '<option value="">First select a country</option>';
                return;
            }

            stateSelect.innerHTML = '<option value="">Loading states...</option>';

            fetch(`/admin/location/states-by-country/${countryId}`)
                .then(response => response.json())
                .then(data => {
                    stateSelect.innerHTML = '<option value="">Select State</option>';
                    data.forEach(state => {
                        stateSelect.innerHTML += `<option value="${state.id}">${state.name}</option>`;
                    });
                })
                .catch(() => {
                    stateSelect.innerHTML = '<option value="">Error loading states</option>';
                });
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
        document.getElementById('modalTitle').textContent = 'Add City';
        document.getElementById('itemId').value = '';
        document.getElementById('itemName').value = '';
        document.getElementById('itemCountryId').value = '';
        document.getElementById('itemStateId').innerHTML = '<option value="">First select a country</option>';
        document.getElementById('saveBtn').textContent = 'Add';
        window.cityModal.show();
    }

    function editItem(id, name, stateId) {
        document.getElementById('modalTitle').textContent = 'Edit City';
        document.getElementById('itemId').value = id;
        document.getElementById('itemName').value = name;

        // Get state info for edit
        fetch(`/admin/location/state-info/${stateId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('itemCountryId').value = data.country_id;

                // Load states for this country
                const stateSelect = document.getElementById('itemStateId');
                fetch(`/admin/location/states-by-country/${data.country_id}`)
                    .then(response => response.json())
                    .then(states => {
                        stateSelect.innerHTML = '<option value="">Select State</option>';
                        states.forEach(state => {
                            const selected = state.id == stateId ? 'selected' : '';
                            stateSelect.innerHTML += `<option value="${state.id}" ${selected}>${state.name}</option>`;
                        });
                    });
            });

        document.getElementById('saveBtn').textContent = 'Update';
        window.cityModal.show();
    }

    function saveItem() {
        const id = document.getElementById('itemId').value;
        const stateId = document.getElementById('itemStateId').value;
        const name = document.getElementById('itemName').value.trim();

        if (!stateId || !name) {
            showToast('error', 'Please select state and enter city name');
            return;
        }

        const url = id ? `/admin/location/cities/${id}` : `/admin/location/cities`;
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
            body: JSON.stringify({ state_id: stateId, name })
        })
        .then(response => response.json())
        .then(response => {
            if (response.success) {
                showToast('success', response.message);
                window.cityModal.hide();
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

        fetch(`/admin/location/cities/${id}/toggle`, {
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

            fetch(`/admin/location/cities/${id}`, {
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
