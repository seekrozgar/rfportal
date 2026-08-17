<div class="admin-card">
    <div class="card-header">
        <h5><i class="fas fa-tag me-2" style="color: var(--primary-color);"></i> {{ $title }}</h5>
        <div class="card-actions">
            <button onclick="openAddModal()" class="btn-admin-primary">
                <i class="fas fa-plus"></i> Add New
            </button>
        </div>
    </div>

    <div class="table-container">
        <table class="admin-table datatable" id="{{ $type }}-table">
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
                    <tr id="{{ $type }}-row-{{ $item->id }}">
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
                                <button onclick="editItem('{{ $type }}', {{ $item->id }}, '{{ $item->name }}')"
                                    class="btn btn-sm btn-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="toggleStatus('{{ $type }}', {{ $item->id }})"
                                    class="btn btn-sm {{ $item->is_active ? 'btn-warning' : 'btn-success' }}"
                                    title="{{ $item->is_active ? 'Disable' : 'Enable' }}">
                                    <i class="fas fa-{{ $item->is_active ? 'ban' : 'check-circle' }}"></i>
                                </button>
                                <button onclick="deleteItem('{{ $type }}', {{ $item->id }}, '{{ $item->name }}')"
                                    class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty-state">No items found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $items->links() }}
    </div>
</div>
