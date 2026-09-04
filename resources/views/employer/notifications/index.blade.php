@extends('employer.layouts.employer')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'System notifications and activity')

@section('content')
<div class="container-fluid px-4">

    <div class="card border-0 shadow-sm">

        {{-- =========================================================
             HEADER
        ========================================================== --}}
        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-bell text-success me-2"></i>
                        Notifications
                    </h5>

                    <small class="text-muted">
                        All your system notifications
                    </small>
                </div>

                <div class="d-flex gap-2 flex-wrap">

                    {{-- MARK ALL READ --}}
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-success"
                        id="employerMarkAllRead"
                    >
                        <i class="fas fa-check-double me-1"></i>
                        Mark All Read
                    </button>

                    {{-- DELETE ALL --}}
                    {{-- Class is intentionally used in addition to ID.
                         This makes the button resistant to other scripts
                         changing/removing the ID. --}}
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger employer-delete-all-btn"
                        id="employerDeleteAll"
                    >
                        <i class="fas fa-trash-alt me-1"></i>
                        Delete All
                    </button>

                </div>

            </div>

        </div>


        {{-- =========================================================
             NOTIFICATIONS LIST
        ========================================================== --}}
        <div class="card-body p-0" id="notificationsList">

            @forelse($notifications as $notification)

                <div
                    class="notification-item d-flex gap-3 p-3 border-bottom
                    {{ !$notification->read_at ? 'bg-light' : '' }}"
                    data-notification-id="{{ $notification->id }}"
                >

                    {{-- ICON --}}
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center"
                        style="
                            width:42px;
                            height:42px;
                            min-width:42px;
                            background:#ecfdf5;
                            color:#059669;
                        "
                    >
                        <i class="fas fa-{{ $notification->icon ?: 'bell' }}"></i>
                    </div>


                    {{-- CONTENT --}}
                    <div class="flex-grow-1">

                        <div class="d-flex justify-content-between gap-3 flex-wrap">

                            <strong>
                                {{ $notification->title }}
                            </strong>

                            <small class="text-muted text-nowrap">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>

                        </div>


                        <div class="text-muted small mt-1">
                            {{ $notification->message }}
                        </div>


                        {{-- ACTIONS --}}
                        <div class="d-flex flex-wrap gap-2 mt-2">

                            @if($notification->action_url)

                                <a
                                    href="{{ $notification->action_url }}"
                                    class="btn btn-sm btn-outline-success notification-view-btn"
                                    data-notification-id="{{ $notification->id }}"
                                >
                                    <i class="fas fa-eye me-1"></i>
                                    View
                                </a>

                            @endif


                            @if(!$notification->read_at)

                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-primary notification-read-btn"
                                    data-notification-id="{{ $notification->id }}"
                                >
                                    <i class="fas fa-check me-1"></i>
                                    Mark as Read
                                </button>

                            @else

                                <span class="badge bg-light text-success border align-self-center notification-read-badge">
                                    <i class="fas fa-check me-1"></i>
                                    Read
                                </span>

                            @endif


                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger notification-delete-btn"
                                data-notification-id="{{ $notification->id }}"
                            >
                                <i class="fas fa-trash-alt me-1"></i>
                                Delete
                            </button>

                        </div>

                    </div>

                </div>

            @empty

                <div
                    class="text-center py-5 text-muted"
                    id="employerEmptyNotifications"
                >
                    <i class="fas fa-bell-slash fa-3x mb-3"></i>

                    <div>
                        No notifications yet.
                    </div>
                </div>

            @endforelse

        </div>


        {{-- =========================================================
             PAGINATION
        ========================================================== --}}
        {{-- ✅ Improved Pagination --}}
        <div class="pagination-wrapper mt-3">
            @if ($notifications->hasPages())
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Previous Page Link --}}
                        @if ($notifications->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->previousPageUrl() }}" rel="prev">
                                    <i class="fas fa-chevron-left" style="font-size: 11px;"></i>
                                </a>
                            </li>
                        @endif

                        {{-- Pagination Elements --}}
                        @foreach ($notifications->links()->elements as $element)
                            @if (is_string($element))
                                <li class="page-item disabled">
                                    <span class="page-link">{{ $element }}</span>
                                </li>
                            @endif

                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    @if ($page == $notifications->currentPage())
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
                        @if ($notifications->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $notifications->nextPageUrl() }}" rel="next">
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
                    Showing {{ $notifications->firstItem() ?? 0 }} to {{ $notifications->lastItem() ?? 0 }} of {{ $notifications->total() }} entries
                </div>
            @endif
        </div>

    </div>

</div>


{{-- =============================================================
     DELETE ALL CONFIRMATION MODAL
============================================================= --}}
<div
    class="modal fade"
    id="employerDeleteAllModal"
    tabindex="-1"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0 shadow">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Delete All Notifications
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>

            </div>


            <div class="modal-body">

                <p class="mb-2">
                    Are you sure you want to delete all your notifications?
                </p>

                <small class="text-muted">
                    This action cannot be undone.
                </small>

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal"
                >
                    Cancel
                </button>

                <button
                    type="button"
                    class="btn btn-danger"
                    id="employerConfirmDeleteAll"
                >
                    <i class="fas fa-trash-alt me-1"></i>
                    Delete All
                </button>

            </div>

        </div>

    </div>
</div>


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    console.log('EMPLOYER NOTIFICATIONS JS LOADED');

    /*
     * =========================================================
     * CONFIGURATION - ✅ CORRECT EMPLOYER ROUTES
     * =========================================================
     */

    const csrfToken = @json(csrf_token());

    // ✅ employer.notifications.* routes
    const markReadUrlTemplate =
        @json(route('employer.notifications.mark-single-read', [
            'id' => '__NOTIFICATION_ID__'
        ]));

    const deleteUrlTemplate =
        @json(route('employer.notifications.destroy', [
            'id' => '__NOTIFICATION_ID__'
        ]));

    const markAllReadUrl =
        @json(route('employer.notifications.mark-read'));

    const deleteAllUrl =
        @json(route('employer.notifications.destroy-all'));

    console.log('Mark Read URL:', markReadUrlTemplate);
    console.log('Delete URL:', deleteUrlTemplate);
    console.log('Mark All Read URL:', markAllReadUrl);
    console.log('Delete All URL:', deleteAllUrl);

    // ... rest of the code ...
});
</script>
@endpush

@endsection
