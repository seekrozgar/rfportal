{{-- resources/views/admin/news/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'News - Rozgar Finder')
@section('page-title', 'News')
@section('page-subtitle', 'Manage news and announcements')

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
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $news->total() }}</div>
                                    <div class="stats-label">Total News</div>
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
                                    <div class="stats-number">{{ $news->where('is_published', true)->count() }}</div>
                                    <div class="stats-label">Published</div>
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
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $news->sum('views_count') }}</div>
                                    <div class="stats-label">Total Views</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 40%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $news->where('is_published', false)->count() }}</div>
                                    <div class="stats-label">Drafts</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 20%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ News Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-newspaper me-2 text-primary"></i> News & Announcements
                        </h5>
                        <div>
                            <a href="{{ route('admin.news.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add News
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 80px;">Image</th>
                                        <th>Title</th>
                                        <th style="width: 130px;">Author</th>
                                        <th style="width: 130px;">Date</th>
                                        <th style="width: 80px;">Views</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 160px;" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($news as $item)
                                        <tr id="row-{{ $item->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($item->featured_image)
                                                    <img src="{{ asset('storage/' . $item->featured_image) }}"
                                                        alt="{{ $item->title }}" class="news-thumbnail">
                                                @else
                                                    <div class="news-thumbnail-placeholder">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="news-title">
                                                    <strong>{{ $item->title }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $item->excerpt }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="author-info">
                                                    <span
                                                        class="author-avatar">{{ substr($item->author->name ?? 'A', 0, 1) }}</span>
                                                    <span>{{ $item->author->name ?? 'Admin' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <small>{{ $item->formatted_date }}</small>
                                            </td>
                                            <td>
                                                <span class="views-badge">
                                                    <i class="fas fa-eye"></i> {{ $item->views_count }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->is_published)
                                                    <span class="status-badge status-completed">
                                                        <i class="fas fa-check-circle"></i> Published
                                                    </span>
                                                @else
                                                    <span class="status-badge status-pending">
                                                        <i class="fas fa-clock"></i> Draft
                                                    </span>
                                                @endif
                                            </td>
                                            {{-- Action Buttons --}}
                                            <td class="text-end">
                                                <div class="action-buttons">
                                                    {{-- Edit Button --}}
                                                    <a href="{{ route('admin.news.edit', $item) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>

                                                    {{-- Toggle Status Button --}}
                                                    <button onclick="toggleStatus({{ $item->id }})"
                                                        class="btn btn-sm {{ $item->is_published ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $item->is_published ? 'Unpublish' : 'Publish' }}">
                                                        <i
                                                            class="fas fa-{{ $item->is_published ? 'ban' : 'check-circle' }}"></i>
                                                    </button>

                                                    {{-- Delete Button --}}
                                                    <button
                                                        onclick="deleteItem({{ $item->id }}, '{{ addslashes($item->title) }}')"
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
                                                    <i class="fas fa-newspaper fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No News Found</h5>
                                                    <p class="text-muted small">Click "Add News" to create your first news post.
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
                                Showing {{ $news->firstItem() ?? 0 }} to {{ $news->lastItem() ?? 0 }} of
                                {{ $news->total() }} entries
                            </div>
                            <div>
                                {{ $news->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            // ✅ Toastr Helper
            function showToast(type, message) {
                if (typeof toastr !== 'undefined') {
                    toastr[type](message);
                } else {
                    alert(message);
                }
            }

            // ✅ Toastr Delete Confirmation
            function showDeleteConfirm(message, callback) {
                if (typeof toastr === 'undefined') {
                    if (confirm(message.replace(/<[^>]*>/g, ''))) {
                        callback();
                    }
                    return;
                }

                toastr.clear();

                const callbackKey = '_deleteConfirmCallback_' + Date.now();

                var confirmHtml = `
                <div style="text-align: center; padding: 10px 0;">
                    <p style="font-size: 15px; margin-bottom: 15px; color: #fff;">${message}</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button onclick="window['${callbackKey}'](true)"
                                style="background: #e74c3c; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <button onclick="window['${callbackKey}'](false)"
                                style="background: #28a745; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            `;

                window[callbackKey] = function (result) {
                    toastr.clear();
                    if (result) {
                        callback();
                    } else {
                        toastr.info('Action cancelled');
                    }
                    delete window[callbackKey];
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

            // ✅ Toastr Status Toggle Confirmation
            function showStatusConfirm(message, callback) {
                if (typeof toastr === 'undefined') {
                    if (confirm(message.replace(/<[^>]*>/g, ''))) {
                        callback();
                    }
                    return;
                }

                toastr.clear();

                const callbackKey = '_statusConfirmCallback_' + Date.now();

                var confirmHtml = `
                <div style="text-align: center; padding: 10px 0;">
                    <p style="font-size: 15px; margin-bottom: 15px; color: #fff;">${message}</p>
                    <div style="display: flex; gap: 10px; justify-content: center;">
                        <button onclick="window['${callbackKey}'](true)"
                                style="background: #2563eb; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-check"></i> Yes, Proceed
                        </button>
                        <button onclick="window['${callbackKey}'](false)"
                                style="background: #6b7280; color: #fff; border: none; padding: 8px 25px; border-radius: 5px; cursor: pointer; font-weight: 600;">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                    </div>
                </div>
            `;

                window[callbackKey] = function (result) {
                    toastr.clear();
                    if (result) {
                        callback();
                    } else {
                        toastr.info('Action cancelled');
                    }
                    delete window[callbackKey];
                };

                toastr.info(confirmHtml, 'Confirm Status Change', {
                    closeButton: false,
                    timeOut: 0,
                    extendedTimeOut: 0,
                    positionClass: 'toast-top-center',
                    progressBar: false,
                    escapeHtml: false,
                });
            }

            // ✅ Toggle Status - FIXED
            function toggleStatus(id) {
                showStatusConfirm(
                    'Are you sure you want to change the status of this news?',
                    function () {
                        // ✅ Use the correct route with ID
                        const url = `/admin/news/${id}/toggle`;

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                            }
                        })
                            .then(response => {
                                // ✅ Check if response is JSON
                                const contentType = response.headers.get('content-type');
                                if (!contentType || !contentType.includes('application/json')) {
                                    throw new Error('Server returned HTML instead of JSON');
                                }
                                return response.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    showToast('success', data.message);
                                    setTimeout(() => location.reload(), 800);
                                } else {
                                    showToast('error', data.message || 'Error toggling status');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showToast('error', 'An error occurred. Please try again.');
                            });
                    }
                );
            }

            // ✅ Delete Item - FIXED
            function deleteItem(id, name) {
                let message = `Are you sure you want to delete "<strong>${name}</strong>"?`;
                message += `<br><small style="color: #999;">This action cannot be undone.</small>`;

                showDeleteConfirm(message, function () {
                    const row = document.getElementById(`row-${id}`);
                    if (row) row.style.opacity = '0.5';

                    fetch(`/admin/news/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                        .then(response => {
                            const contentType = response.headers.get('content-type');
                            if (!contentType || !contentType.includes('application/json')) {
                                throw new Error('Server returned HTML instead of JSON');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                if (row) {
                                    row.style.transition = 'all 0.5s ease';
                                    row.style.opacity = '0';
                                    setTimeout(() => {
                                        if (row.parentNode) row.remove();
                                    }, 500);
                                }
                            } else {
                                showToast('error', data.message || 'Error deleting');
                                if (row) row.style.opacity = '1';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('error', 'An error occurred. Please try again.');
                            if (row) row.style.opacity = '1';
                        });
                });
            }
        </script>
    @endpush
@endsection
