@extends('admin.layouts.admin')

@section('title', ucwords(str_replace('-', ' ', $type)) . ' - Rozgar Finder')
@section('page-title', ucwords(str_replace('-', ' ', $type)))
@section('page-subtitle', 'Manage ' . ucwords(str_replace('-', ' ', $type)))

@section('content')
    @include('admin.attributes.partials.attribute-list', [
        'title' => ucwords(str_replace('-', ' ', $type)),
        'type' => $type,
        'items' => $items
    ])
@endsection

@push('scripts')
    <script>
        // ✅ CRUD Functions for all attributes

        function openAddModal() {
            const name = prompt('Enter name:');
            if (name && name.trim()) {
                fetch(`/admin/attributes/${type}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ name: name.trim() })
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            window.showToast('success', response.message);
                            location.reload();
                        } else {
                            window.showToast('error', response.message || 'Error adding item');
                        }
                    })
                    .catch(() => {
                        window.showToast('error', 'An error occurred');
                    });
            }
        }

        function editItem(type, id, currentName) {
            const newName = prompt('Edit name:', currentName);
            if (newName && newName.trim() && newName !== currentName) {
                fetch(`/admin/attributes/${type}/${id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ name: newName.trim() })
                })
                    .then(response => response.json())
                    .then(response => {
                        if (response.success) {
                            window.showToast('success', response.message);
                            location.reload();
                        } else {
                            window.showToast('error', response.message || 'Error updating item');
                        }
                    })
                    .catch(() => {
                        window.showToast('error', 'An error occurred');
                    });
            }
        }

        function deleteItem(type, id, name) {
            if (confirm(`Are you sure you want to delete "${name}"?`)) {
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
                            window.showToast('success', response.message);
                            document.getElementById(`${type}-row-${id}`)?.remove();
                        } else {
                            window.showToast('error', response.message || 'Error deleting item');
                        }
                    })
                    .catch(() => {
                        window.showToast('error', 'An error occurred');
                    });
            }
        }

        function toggleStatus(type, id) {
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
                        window.showToast('success', response.message);
                        location.reload();
                    } else {
                        window.showToast('error', response.message || 'Error toggling status');
                    }
                })
                .catch(() => {
                    window.showToast('error', 'An error occurred');
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
                $(`#${type}-table`).DataTable({
                    responsive: true,
                    pageLength: 25,
                    language: {
                        search: "Search:",
                        lengthMenu: "Show _MENU_ entries",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    },
                    columnDefs: [
                        { orderable: false, targets: 4 }
                    ]
                });
            }
        });
    </script>
@endpush
