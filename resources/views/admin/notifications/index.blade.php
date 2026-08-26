{{-- resources/views/admin/notifications/index.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'View all system notifications')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Stats Cards --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-primary p-3 me-3 text-white">
                                        <i class="fas fa-bell"></i>
                                    </div>
                                    <div>
                                        <div class="h4 fw-bold mb-0">{{ $notifications->total() }}</div>
                                        <div class="text-muted small text-uppercase">Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-warning p-3 me-3 text-white">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <div class="h4 fw-bold mb-0">{{ $notifications->whereNull('read_at')->count() }}
                                        </div>
                                        <div class="text-muted small text-uppercase">Unread</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-success p-3 me-3 text-white">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <div>
                                        <div class="h4 fw-bold mb-0">{{ $notifications->whereNotNull('read_at')->count() }}
                                        </div>
                                        <div class="text-muted small text-uppercase">Read</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-danger p-3 me-3 text-white">
                                        <i class="fas fa-exclamation-triangle"></i>
                                    </div>
                                    <div>
                                        <div class="h4 fw-bold mb-0">{{ $notifications->where('module', 'error')->count() }}
                                        </div>
                                        <div class="text-muted small text-uppercase">Errors</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Notifications Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-bell me-2 text-primary"></i> Notifications
                        </h5>
                        <div>
                            <form action="{{ route('admin.notifications.mark-read') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-check-double"></i> Mark All Read
                                </button>
                            </form>
                            <button onclick="location.reload()" class="btn btn-outline-secondary btn-sm ms-2">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="notificationsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th style="width: 40px;"></th>
                                        <th>Message</th>
                                        <th style="width: 130px;">Module</th>
                                        <th style="width: 100px;">Action</th>
                                        <th style="width: 160px;">Date</th>
                                        <th style="width: 130px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($notifications as $notification)
                                        <tr class="{{ is_null($notification->read_at) ? 'table-primary' : '' }}">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if(is_null($notification->read_at))
                                                    <span class="badge-dot"></span>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $notification->action ?? 'System Notification' }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ Str::limit($notification->description ?? '', 150) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $notification->module === 'error' ? 'danger' : ($notification->module === 'success' ? 'success' : 'info') }}">
                                                    {{ ucfirst($notification->module ?? 'system') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($notification->action)
                                                    <span class="badge bg-secondary">{{ $notification->action }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>{{ $notification->created_at->format('d M, Y h:i A') }}</small>
                                                <br>
                                                <small
                                                    class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                @if(is_null($notification->read_at))
                                                    <span class="badge bg-warning text-dark">Unread</span>
                                                    <button onclick="markAsRead({{ $notification->id }})"
                                                        class="btn btn-sm btn-outline-primary mt-1 d-block" title="Mark as read">
                                                        <i class="fas fa-check"></i> Mark Read
                                                    </button>
                                                @else
                                                    <span class="badge bg-success">Read</span>
                                                    <small
                                                        class="text-muted d-block">{{ $notification->read_at->format('d M, Y') }}</small>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center py-5">
                                                <i class="fas fa-bell-slash fa-3x d-block mb-3 text-muted"></i>
                                                <h5 class="text-muted">No Notifications</h5>
                                                <p class="text-muted small">You're all caught up!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-center">
                            {{ $notifications->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function markAsRead(id) {
            const btn = event?.target?.closest('button') || document.querySelector(`button[onclick*="markAsRead(${id})"]`);
            if (btn) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            }

            fetch(`/admin/notifications/${id}/mark-read`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (typeof toastr !== 'undefined') {
                            toastr.success('Notification marked as read');
                        } else {
                            alert('Notification marked as read');
                        }
                        setTimeout(() => location.reload(), 500);
                    } else {
                        const msg = data.message || 'Failed to mark as read';
                        if (typeof toastr !== 'undefined') {
                            toastr.error(msg);
                        } else {
                            alert(msg);
                        }
                        if (btn) {
                            btn.disabled = false;
                            btn.innerHTML = '<i class="fas fa-check"></i> Mark Read';
                        }
                    }
                })
                .catch(() => {
                    if (typeof toastr !== 'undefined') {
                        toastr.error('Failed to mark as read');
                    } else {
                        alert('Failed to mark as read');
                    }
                    if (btn) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-check"></i> Mark Read';
                    }
                });
        }
    </script>
@endpush