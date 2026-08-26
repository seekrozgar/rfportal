{{-- resources/views/admin/location/countries/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Countries')
@section('page-title', 'Countries')
@section('page-subtitle', 'Manage countries worldwide')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-globe me-2" style="color: var(--primary-color);"></i> Countries</h5>
            <div class="card-actions">
                <button onclick="openAddModal()" class="btn-admin-primary">
                    <i class="fas fa-plus"></i> Add Country
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="admin-table" id="countriesTable">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Name</th>
                        <th style="width: 80px;">Code</th>
                        <th style="width: 100px;">Phone Code</th>
                        <th style="width: 80px;">States</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 160px; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($countries as $country)
                        <tr id="row-{{ $country->id }}">
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $country->name }}</strong></td>
                            <td><span class="badge bg-secondary">{{ $country->code }}</span></td>
                            <td>{{ $country->phone_code ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.location.states.index', ['country_id' => $country->id]) }}"
                                    class="text-primary fw-bold">
                                    {{ $country->states->count() }}
                                </a>
                            </td>
                            <td>
                                <span class="badge-{{ $country->is_active ? 'active' : 'inactive' }}">
                                    {{ $country->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td style="text-align: right;">
                                <div class="action-buttons">
                                    <button
                                        onclick="editItem({{ $country->id }}, '{{ addslashes($country->name) }}', '{{ $country->code }}', '{{ $country->phone_code }}')"
                                        class="btn btn-sm btn-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="toggleStatus({{ $country->id }})"
                                        class="btn btn-sm {{ $country->is_active ? 'btn-warning' : 'btn-success' }}"
                                        title="{{ $country->is_active ? 'Disable' : 'Enable' }}">
                                        <i class="fas fa-{{ $country->is_active ? 'ban' : 'check-circle' }}"></i>
                                    </button>
                                    <button
                                        onclick="deleteItem({{ $country->id }}, '{{ addslashes($country->name) }}', {{ $country->states->count() }})"
                                        class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="fas fa-globe fa-2x d-block mb-2"></i>
                                No countries found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ✅ Improved Pagination --}}
        <div class="pagination-wrapper mt-3">
            @if ($countries->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($countries->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $countries->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($countries->links()->elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <li class="page-item disabled">
                                    <span class="page-link">{{ $element }}</span>
                                </li>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $countries->currentPage())
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
                        @if ($countries->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $countries->nextPageUrl() }}" rel="next">
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

                {{-- ✅ Info Text --}}
                <div class="pagination-info text-center text-muted small">
                    Showing {{ $countries->firstItem() ?? 0 }} to {{ $countries->lastItem() ?? 0 }} of {{ $countries->total() }}
                    entries
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal fade" id="countryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Country</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="countryForm">
                        @csrf
                        <input type="hidden" id="itemId" value="">
                        <div class="form-group">
                            <label>Country Name <span class="text-danger">*</span></label>
                            <input type="text" id="itemName" class="form-control" placeholder="Enter country name" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Country Code <span class="text-danger">*</span></label>
                            <input type="text" id="itemCode" class="form-control" placeholder="e.g., PK, US, IN"
                                maxlength="3" required>
                            <small class="text-muted">Maximum 3 characters</small>
                        </div>
                        <div class="form-group mt-3">
                            <label>Phone Code</label>
                            <input type="text" id="itemPhoneCode" class="form-control" placeholder="e.g., +92, +1"
                                maxlength="10">
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
            const modalElement = document.getElementById('countryModal');
            let modal = new bootstrap.Modal(modalElement);
            window.countryModal = modal;

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
            document.getElementById('modalTitle').textContent = 'Add Country';
            document.getElementById('itemId').value = '';
            document.getElementById('itemName').value = '';
            document.getElementById('itemCode').value = '';
            document.getElementById('itemPhoneCode').value = '';
            document.getElementById('saveBtn').textContent = 'Add';
            window.countryModal.show();
        }

        function editItem(id, name, code, phoneCode) {
            document.getElementById('modalTitle').textContent = 'Edit Country';
            document.getElementById('itemId').value = id;
            document.getElementById('itemName').value = name;
            document.getElementById('itemCode').value = code;
            document.getElementById('itemPhoneCode').value = phoneCode || '';
            document.getElementById('saveBtn').textContent = 'Update';
            window.countryModal.show();
        }

        function saveItem() {
            const id = document.getElementById('itemId').value;
            const name = document.getElementById('itemName').value.trim();
            const code = document.getElementById('itemCode').value.trim().toUpperCase();
            const phoneCode = document.getElementById('itemPhoneCode').value.trim();

            if (!name || !code) {
                showToast('error', 'Please fill all required fields');
                return;
            }

            const url = id ? `/admin/location/countries/${id}` : `/admin/location/countries`;
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
                body: JSON.stringify({ name, code, phone_code: phoneCode })
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        showToast('success', response.message);
                        window.countryModal.hide();
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

            fetch(`/admin/location/countries/${id}/toggle`, {
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

        function deleteItem(id, name, statesCount) {
            let message = `Are you sure you want to delete "<strong>${name}</strong>"?`;
            if (statesCount > 0) {
                message += `<br><small style="color: #ffc107;">⚠️ This country has ${statesCount} state(s). They will not be deleted.</small>`;
            }
            message += `<br><small style="color: #999;">This action cannot be undone.</small>`;

            showDeleteConfirm(message, function () {
                const row = document.getElementById(`row-${id}`);
                if (row) row.style.opacity = '0.5';

                fetch(`/admin/location/countries/${id}`, {
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