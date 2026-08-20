@extends('admin.layouts.admin')

@section('title', $title . ' - Rozgar Finder')
@section('page-title', $title)
@section('page-subtitle', 'Manage ' . $title)

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-tag me-2" style="color: var(--primary-color);"></i> {{ $title }}</h5>
            <div class="card-actions">
                <button onclick="openAddModal()" class="btn-admin-primary">
                    <i class="fas fa-plus"></i> Add New
                </button>
                <button onclick="openImportModal()" class="btn-admin-outline">
                    <i class="fas fa-file-import"></i> Import
                </button>
            </div>
        </div>

        <div class="table-container">
            <div class="table-scroll-wrapper">
                <table class="admin-table" id="attributesTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th style="text-align: right;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $item)
                            <tr id="row-{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>
                                    <span class="badge-{{ $item->is_active ? 'active' : 'inactive' }}">
                                        {{ $item->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td style="text-align: right;">
                                    <div class="action-buttons">
                                        <button onclick="editItem({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                            class="btn btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button onclick="toggleStatus({{ $item->id }})"
                                            class="btn btn-sm {{ $item->is_active ? 'btn-warning' : 'btn-success' }}"
                                            title="{{ $item->is_active ? 'Disable' : 'Enable' }}">
                                            <i class="fas fa-{{ $item->is_active ? 'ban' : 'check-circle' }}"></i>
                                        </button>
                                        <button onclick="deleteItem({{ $item->id }}, '{{ addslashes($item->name) }}')"
                                            class="btn btn-sm btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <!-- 🟢 DataTables khud "No data available" dikhayega -->
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ✅ Custom Pagination (Only One) --}}
        <div class="pagination-wrapper mt-3">
            @if ($items->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($items->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $items->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($items->links()->elements as $element)
                            @if (is_string($element))
                                <li class="page-item disabled">
                                    <span class="page-link">{{ $element }}</span>
                                </li>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $items->currentPage())
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
                        @if ($items->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $items->nextPageUrl() }}" rel="next">
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
                    Showing {{ $items->firstItem() ?? 0 }} to {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} entries
                </div>
            @endif
        </div>
    </div>

    <!-- ✅ Add/Edit Modal -->
    <div class="modal fade" id="attributeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add New</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="attributeForm">
                        @csrf
                        <input type="hidden" id="itemId" value="">
                        <div class="form-group">
                            <label for="itemName">Name <span class="text-danger">*</span></label>
                            <input type="text" id="itemName" class="form-control" placeholder="Enter name" required>
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

    <!-- ✅ Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import {{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="importForm">
                        @csrf
                        <div class="form-group">
                            <label>Enter names (one per line)</label>
                            <textarea id="importNames" class="form-control" rows="5"
                                placeholder="Name 1&#10;Name 2&#10;Name 3"></textarea>
                            <small class="text-muted">Each name will be added as a separate entry.</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-admin-outline" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn-admin-primary" id="importBtn">Import</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // ✅ Wait for DOM to load
        document.addEventListener('DOMContentLoaded', function () {
            const type = '{{ $type }}';

            // ✅ Initialize Modals
            const modalElement = document.getElementById('attributeModal');
            const importModalElement = document.getElementById('importModal');

            let modal = null;
            let importModal = null;

            if (modalElement) {
                try {
                    modal = new bootstrap.Modal(modalElement);
                    console.log('✅ Attribute modal initialized');
                } catch (e) {
                    console.warn('⚠️ Bootstrap modal error:', e);
                }
            }
            if (importModalElement) {
                try {
                    importModal = new bootstrap.Modal(importModalElement);
                    console.log('✅ Import modal initialized');
                } catch (e) {
                    console.warn('⚠️ Bootstrap import modal error:', e);
                }
            }

            // ✅ Store modals globally
            window.attributeModal = modal;
            window.importModal = importModal;
            window.attributeType = type;

            // ✅ Save button handler
            document.getElementById('saveBtn')?.addEventListener('click', function () {
                saveItem(type);
            });

            // ✅ Import button handler
            document.getElementById('importBtn')?.addEventListener('click', function () {
                importItems(type);
            });

            // ✅ Enter key handler for name input
            document.getElementById('itemName')?.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveItem(type);
                }
            });
        });

        // ✅ Toastr helper
        function showToast(type, message) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                alert(message);
            }
        }

        // ✅ Toastr Confirmation for Delete
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

        // ✅ Open Add Modal
        function openAddModal() {
            const modal = window.attributeModal;
            if (!modal) {
                alert('Modal not initialized. Please refresh the page.');
                return;
            }

            try {
                document.getElementById('modalTitle').textContent = 'Add New {{ $title }}';
                document.getElementById('itemId').value = '';
                document.getElementById('itemName').value = '';
                document.getElementById('saveBtn').textContent = 'Add';
                modal.show();
            } catch (e) {
                console.error('Modal error:', e);
                alert('Error opening modal. Please refresh the page.');
            }
        }

        // ✅ Open Import Modal
        function openImportModal() {
            const modal = window.importModal;
            if (!modal) {
                alert('Import modal not initialized. Please refresh the page.');
                return;
            }

            try {
                document.getElementById('importNames').value = '';
                modal.show();
            } catch (e) {
                console.error('Import modal error:', e);
                alert('Error opening import modal. Please refresh the page.');
            }
        }

        // ✅ Edit Item
        function editItem(id, name) {
            const modal = window.attributeModal;
            if (!modal) {
                alert('Modal not initialized. Please refresh the page.');
                return;
            }

            try {
                document.getElementById('modalTitle').textContent = 'Edit {{ $title }}';
                document.getElementById('itemId').value = id;
                document.getElementById('itemName').value = name;
                document.getElementById('saveBtn').textContent = 'Update';
                modal.show();
            } catch (e) {
                console.error('Edit modal error:', e);
                alert('Error opening edit modal. Please refresh the page.');
            }
        }

        // ✅ Save Item (Add/Edit)
        function saveItem(type) {
            const id = document.getElementById('itemId').value;
            const name = document.getElementById('itemName').value.trim();

            if (!name) {
                showToast('error', 'Please enter a name');
                return;
            }

            const url = id ? `/admin/attributes/${type}/${id}` : `/admin/attributes/${type}`;
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
                body: JSON.stringify({ name: name })
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        showToast('success', response.message);
                        const modal = window.attributeModal;
                        if (modal) {
                            try { modal.hide(); } catch (e) { }
                        }
                        setTimeout(() => location.reload(), 500);
                    } else {
                        showToast('error', response.message || 'Error saving item');
                    }
                })
                .catch(() => {
                    showToast('error', 'An error occurred');
                })
                .finally(() => {
                    saveBtn.disabled = false;
                    saveBtn.textContent = originalText;
                });
        }

        // ✅ Import Items
        function importItems(type) {
            const names = document.getElementById('importNames').value.trim();

            if (!names) {
                showToast('error', 'Please enter at least one name');
                return;
            }

            const importBtn = document.getElementById('importBtn');
            const originalText = importBtn.textContent;

            importBtn.disabled = true;
            importBtn.textContent = 'Importing...';

            fetch(`/admin/attributes/${type}/import`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                },
                body: JSON.stringify({ names: names })
            })
                .then(response => response.json())
                .then(response => {
                    if (response.success) {
                        showToast('success', response.message);
                        const modal = window.importModal;
                        if (modal) {
                            try { modal.hide(); } catch (e) { }
                        }
                        setTimeout(() => location.reload(), 500);
                    } else {
                        showToast('error', response.message || 'Error importing items');
                    }
                })
                .catch(() => {
                    showToast('error', 'An error occurred');
                })
                .finally(() => {
                    importBtn.disabled = false;
                    importBtn.textContent = originalText;
                });
        }

        // ✅ Delete Item - WITH TOASTR CONFIRMATION
        function deleteItem(id, name) {
            const type = window.attributeType || '{{ $type }}';
            const message = `
                    Are you sure you want to <strong style="color: #e74c3c;">delete</strong> "<strong>${name}</strong>"?<br>
                    <small style="color: #999;">This action cannot be undone.</small>
                `;

            showDeleteConfirm(message, function () {
                const row = document.getElementById(`row-${id}`);
                if (row) row.style.opacity = '0.5';

                fetch(`/admin/attributes/${type}/${id}`, {
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
                            showToast('error', response.message || 'Error deleting item');
                            if (row) row.style.opacity = '1';
                        }
                    })
                    .catch(() => {
                        showToast('error', 'An error occurred');
                        if (row) row.style.opacity = '1';
                    });
            });
        }

        // ✅ Toggle Status
        function toggleStatus(id) {
            const type = window.attributeType || '{{ $type }}';

            fetch(`/admin/attributes/${type}/${id}/toggle`, {
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
                .catch(() => {
                    showToast('error', 'An error occurred');
                });
        }

        // ✅ DataTables - With Pagination Disabled
        document.addEventListener('DOMContentLoaded', function () {
            function initDataTable() {
                try {
                    if (typeof $ === 'undefined' || typeof $.fn === 'undefined' || typeof $.fn.DataTable === 'undefined') {
                        console.warn('DataTable not available, retrying...');
                        setTimeout(initDataTable, 500);
                        return;
                    }

                    var table = document.getElementById('attributesTable');
                    if (!table) {
                        console.warn('Table not found');
                        return;
                    }

                    if ($.fn.DataTable.isDataTable && $.fn.DataTable.isDataTable('#attributesTable')) {
                        console.log('DataTable already initialized');
                        return;
                    }

                    $('#attributesTable').DataTable({
                        responsive: true,
                        paging: false,        // ✅ Disable DataTable pagination
                        searching: true,       // ✅ Keep search
                        ordering: true,        // ✅ Keep sorting
                        info: false,           // ✅ Hide "Showing X to Y" info
                        lengthChange: false,   // ✅ Hide "Show entries" dropdown
                        language: {
                            search: "Search:",
                            emptyTable: "No data available"
                        },
                        columnDefs: [
                            { orderable: false, targets: [4] }
                        ]
                    });
                    console.log('✅ DataTable initialized (pagination disabled)');
                } catch (e) {
                    console.warn('DataTable init error:', e);
                }
            }

            setTimeout(initDataTable, 800);
        });
    </script>
@endpush