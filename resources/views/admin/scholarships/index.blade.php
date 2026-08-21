{{-- resources/views/admin/scholarships/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Scholarships - Rozgar Finder')
@section('page-title', 'Scholarships')
@section('page-subtitle', 'Manage scholarship opportunities')



@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $totalScholarships }}</div>
                                    <div class="stats-label">Total</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 100%; background: #6366f1;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-completed">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $publishedCount }}</div>
                                    <div class="stats-label">Published</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 70%; background: #22c55e;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $upcomingCount }}</div>
                                    <div class="stats-label">Upcoming</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 50%; background: #ef4444;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-warning">
                                    <i class="fas fa-exclamation-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $expiredCount }}</div>
                                    <div class="stats-label">Expired</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 30%; background: #f59e0b;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-info">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $draftCount }}</div>
                                    <div class="stats-label">Drafts</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 20%; background: #3b82f6;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-purple">
                                    <i class="fas fa-rss"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">
                                        {{ $scholarships->whereIn('source', ['propakistani', 'scholars4dev', 'opportunitydesk', 'scholarshipscorner'])->count() }}
                                    </div>
                                    <div class="stats-label">RSS Feed</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 40%; background: #8b5cf6;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Scholarships Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-graduation-cap me-2 text-primary"></i> Scholarships
                        </h5>
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            {{-- Scrape Button with Dropdown --}}
                            <div class="btn-group me-2">
                                <a href="{{ route('admin.scholarships.scrape.form') }}"
                                    class="btn btn-outline-warning btn-sm">
                                    <i class="fas fa-rss"></i> Fetch RSS
                                </a>
                                <button type="button"
                                    class="btn btn-outline-warning btn-sm dropdown-toggle dropdown-toggle-split"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.scholarships.scrape.form') }}">
                                            <i class="fas fa-rss me-2"></i> Select Source
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.scholarships.scrape.all') }}">
                                            <i class="fas fa-rss me-2"></i> Fetch All Sources
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li class="dropdown-header">Available Sources</li>
                                    @foreach($sources ?? [] as $key => $source)
                                        <li>
                                            <a class="dropdown-item {{ $source['enabled'] ? '' : 'text-muted' }}"
                                                href="{{ route('admin.scholarships.scrape.form') }}?source={{ $key }}">
                                                <i class="fas fa-circle me-2"
                                                    style="color: {{ $source['enabled'] ? '#22c55e' : '#94a3b8' }}; font-size: 8px;"></i>
                                                {{ $source['name'] }}
                                                @if(!$source['enabled'])
                                                    <span class="badge bg-secondary ms-2">Disabled</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            {{-- Import Button --}}
                            <button type="button" class="btn btn-outline-success btn-sm me-2" data-bs-toggle="modal"
                                data-bs-target="#importModal">
                                <i class="fas fa-file-import"></i> Import
                            </button>

                            {{-- Export Button --}}
                            <a href="{{ route('admin.scholarships.export') }}" class="btn btn-outline-info btn-sm me-2">
                                <i class="fas fa-file-export"></i> Export
                            </a>

                            {{-- Add Scholarship Button --}}
                            <a href="{{ route('admin.scholarships.create') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> Add Scholarship
                            </a>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Title</th>
                                        <th style="width: 130px;">Provider</th>
                                        <th style="width: 120px;">Deadline</th>
                                        <th style="width: 100px;">Status</th>
                                        <th style="width: 120px;">Source</th>
                                        <th style="width: 160px;" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($scholarships as $item)
                                        <tr id="row-{{ $item->id }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="scholarship-title">
                                                    <strong>{{ Str::limit($item->title, 60) }}</strong>
                                                    @if($item->is_draft)
                                                        <span class="badge bg-info ms-1">Draft</span>
                                                    @endif
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($item->university ?? $item->provider ?? 'N/A', 40) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="provider-badge">{{ $item->provider ?? 'N/A' }}</span>
                                            </td>
                                            <td>
                                                <div class="deadline-info">
                                                    <strong>{{ $item->formatted_deadline }}</strong>
                                                    @php
                                                        $daysRemaining = $item->days_remaining;
                                                    @endphp
                                                    @if($daysRemaining > 0)
                                                        <br>
                                                        <small class="text-success">{{ $daysRemaining }} days left</small>
                                                    @elseif($daysRemaining == 0 && $item->deadline)
                                                        <br>
                                                        <small class="text-warning">Last day today!</small>
                                                    @elseif($item->deadline && $item->is_deadline_passed)
                                                        <br>
                                                        <small class="text-danger">Expired</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->is_draft)
                                                    <span class="status-badge status-draft">
                                                        <i class="fas fa-file-alt"></i> Draft
                                                    </span>
                                                @elseif($item->is_published && !$item->is_deadline_passed)
                                                    <span class="status-badge status-completed">
                                                        <i class="fas fa-check-circle"></i> Active
                                                    </span>
                                                @elseif($item->is_published && $item->is_deadline_passed)
                                                    <span class="status-badge status-expired">
                                                        <i class="fas fa-clock"></i> Expired
                                                    </span>
                                                @else
                                                    <span class="status-badge status-pending">
                                                        <i class="fas fa-clock"></i> Pending
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $sourceBadgeClass = 'source-manual';
                                                    $sourceIcon = 'fa-pencil';
                                                    $sourceLabel = 'Manual';

                                                    if ($item->source === 'propakistani') {
                                                        $sourceBadgeClass = 'source-rss';
                                                        $sourceIcon = 'fa-rss';
                                                        $sourceLabel = 'Pro Pakistani';
                                                    } elseif ($item->source === 'scholars4dev') {
                                                        $sourceBadgeClass = 'source-scholars4dev';
                                                        $sourceIcon = 'fa-rss';
                                                        $sourceLabel = 'Scholars4Dev';
                                                    } elseif ($item->source === 'opportunitydesk') {
                                                        $sourceBadgeClass = 'source-opportunitydesk';
                                                        $sourceIcon = 'fa-rss';
                                                        $sourceLabel = 'Opp. Desk';
                                                    } elseif ($item->source === 'scholarshipscorner') {
                                                        $sourceBadgeClass = 'source-scholarshipscorner';
                                                        $sourceIcon = 'fa-rss';
                                                        $sourceLabel = 'Scholars Corner';
                                                    }
                                                @endphp
                                                <span class="source-badge {{ $sourceBadgeClass }}">
                                                    <i class="fas {{ $sourceIcon }}"></i> {{ $sourceLabel }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="action-buttons">
                                                    <a href="{{ route('admin.scholarships.edit', $item) }}"
                                                        class="btn btn-sm btn-primary" title="Edit">
                                                        <i class="fas fa-pencil"></i>
                                                    </a>
                                                    <button onclick="toggleStatus({{ $item->id }})"
                                                        class="btn btn-sm {{ $item->is_published ? 'btn-warning' : 'btn-success' }}"
                                                        title="{{ $item->is_published ? 'Unpublish' : 'Publish' }}">
                                                        <i
                                                            class="fas fa-{{ $item->is_published ? 'ban' : 'check-circle' }}"></i>
                                                    </button>
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
                                            <td colspan="7" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-graduation-cap fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No Scholarships Found</h5>
                                                    <p class="text-muted small">Click "Add Scholarship" or "Fetch RSS" to get
                                                        started.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {{-- ✅ Improved Pagination --}}
        <div class="pagination-wrapper mt-3">
            @if ($scholarships->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($scholarships->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $scholarships->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($scholarships->links()->elements as $element)
                            @if (is_string($element))
                                <li class="page-item disabled">
                                    <span class="page-link">{{ $element }}</span>
                                </li>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $scholarships->currentPage())
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
                        @if ($scholarships->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $scholarships->nextPageUrl() }}" rel="next">
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
                    Showing {{ $scholarships->firstItem() ?? 0 }} to {{ $scholarships->lastItem() ?? 0 }} of {{ $scholarships->total() }} entries
                </div>
            @endif
        </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ✅ Import Modal --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.scholarships.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-file-import me-2"></i> Import Scholarships</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Upload File (CSV, XLSX)</label>
                            <input type="file" name="file" class="form-control" accept=".csv,.xlsx,.xls" required>
                            <small class="text-muted">Max size: 10MB. <a href="{{ route('admin.scholarships.template') }}"
                                    class="text-primary">Download template</a></small>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Supported columns:</strong> Title, Description, Provider, University,
                            Country, Amount, Deadline, Degree Level, Scholarship Type, Apply Link, Status
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-upload"></i> Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showToast(type, message) {
                if (typeof toastr !== 'undefined') {
                    toastr[type](message);
                } else {
                    alert(message);
                }
            }

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

            function toggleStatus(id) {
                showStatusConfirm(
                    'Are you sure you want to change the status of this scholarship?',
                    function () {
                        fetch(`/admin/scholarships/${id}/toggle`, {
                            method: 'POST',
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

            function deleteItem(id, name) {
                let message = `Are you sure you want to delete "<strong>${name}</strong>"?`;
                message += `<br><small style="color: #999;">This action cannot be undone.</small>`;

                showDeleteConfirm(message, function () {
                    const row = document.getElementById(`row-${id}`);
                    if (row) row.style.opacity = '0.5';

                    fetch(`/admin/scholarships/${id}`, {
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

            // ✅ Auto-close toastr notifications after 5 seconds
            document.addEventListener('DOMContentLoaded', function () {
                if (typeof toastr !== 'undefined') {
                    toastr.options.timeOut = 5000;
                    toastr.options.progressBar = true;
                    toastr.options.positionClass = 'toast-top-right';
                }
            });
        </script>
    @endpush
@endsection
