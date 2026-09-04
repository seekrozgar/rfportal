@extends('admin.layouts.admin')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'System notifications and activity')

@section('content')
<div class="container-fluid px-4">

    <div class="card border-0 shadow-sm">

        {{-- =========================================================
             HEADER - ALWAYS VISIBLE BUTTONS
        ========================================================== --}}
        <div class="card-header bg-white py-3">

            <div class="d-flex justify-content-between align-items-center gap-3 flex-wrap">

                <div>
                    <h5 class="mb-1">
                        <i class="fas fa-bell text-success me-2"></i>
                        Notifications
                        @if(isset($unreadCount) && $unreadCount > 0)
                            <span class="badge bg-danger ms-2">{{ $unreadCount }}</span>
                        @endif
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
                        id="adminMarkAllRead"
                        {{ !isset($unreadCount) || $unreadCount == 0 ? 'disabled' : '' }}
                    >
                        <i class="fas fa-check-double me-1"></i>
                        Mark All Read
                    </button>

                    {{-- DELETE ALL --}}
                    <button
                        type="button"
                        class="btn btn-sm btn-outline-danger admin-delete-all-btn"
                        id="adminDeleteAll"
                        {{ $notifications->count() == 0 ? 'disabled' : '' }}
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
                    {{ !$notification->read_at ? 'bg-light unread' : '' }}"
                    data-notification-id="{{ $notification->id }}"
                >

                    {{-- ICON --}}
                    <div
                        class="rounded-circle d-flex align-items-center justify-content-center notification-icon
                        {{ !$notification->read_at ? 'unread-icon' : '' }}"
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

                            <strong class="notification-title {{ !$notification->read_at ? 'unread-title' : '' }}">
                                {{ $notification->title }}
                            </strong>

                            <small class="text-muted text-nowrap notification-time">
                                {{ $notification->created_at->diffForHumans() }}
                            </small>

                        </div>


                        <div class="text-muted small mt-1 notification-message">
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
                    id="adminEmptyNotifications"
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
        @if($notifications->hasPages())

            <div class="card-footer bg-white">
                {{ $notifications->links() }}
            </div>

        @endif

    </div>

</div>


{{-- =============================================================
     DELETE ALL CONFIRMATION MODAL
============================================================= --}}
<div
    class="modal fade"
    id="adminDeleteAllModal"
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
                    id="adminConfirmDeleteAll"
                >
                    <i class="fas fa-trash-alt me-1"></i>
                    Delete All
                </button>

            </div>

        </div>

    </div>
</div>


{{-- =============================================================
     PAGE-SPECIFIC TOAST + ACTIONS
============================================================= --}}
@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
     * =========================================================
     * TOAST FUNCTION - Page Specific
     * =========================================================
     */

    function showToast(type, message, title = null) {
        let container = document.getElementById('adminToastContainer');

        if (!container) {
            container = document.createElement('div');
            container.id = 'adminToastContainer';
            Object.assign(container.style, {
                position: 'fixed',
                top: '24px',
                right: '24px',
                zIndex: '999999',
                width: 'min(390px, calc(100vw - 32px))',
                pointerEvents: 'none'
            });
            document.body.appendChild(container);
        }

        const meta = {
            success: { title: title || 'Success', icon: 'fa-check', iconClass: 'success' },
            error: { title: title || 'Error', icon: 'fa-times', iconClass: 'error' },
            warning: { title: title || 'Warning', icon: 'fa-exclamation', iconClass: 'warning' },
            info: { title: title || 'Information', icon: 'fa-info', iconClass: 'info' }
        };

        const item = meta[type] || meta.info;

        const toast = document.createElement('div');
        toast.className = `admin-toast ${item.iconClass}`;
        toast.style.cssText = `
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 14px 16px;
            margin-bottom: 10px;
            border-radius: 12px;
            background: #ffffff;
            color: #1e293b;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 35px rgba(15, 23, 42, 0.16);
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.25s ease;
            pointer-events: auto;
            max-width: 100%;
        `;

        const iconColors = {
            success: { bg: '#dcfce7', color: '#15803d' },
            error: { bg: '#fee2e2', color: '#dc2626' },
            warning: { bg: '#fef3c7', color: '#b45309' },
            info: { bg: '#dbeafe', color: '#2563eb' }
        };

        const colors = iconColors[item.iconClass] || iconColors.info;

        toast.innerHTML = `
            <div class="admin-toast-icon" style="
                width: 34px;
                height: 34px;
                flex: 0 0 34px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: ${colors.bg};
                color: ${colors.color};
            ">
                <i class="fas ${item.icon}"></i>
            </div>
            <div class="admin-toast-body" style="flex: 1; min-width: 0;">
                <div class="admin-toast-title" style="font-size: 13px; font-weight: 700; margin-bottom: 2px;">
                    ${escapeHtml(item.title)}
                </div>
                <div class="admin-toast-message" style="font-size: 12px; line-height: 1.45; color: #64748b;">
                    ${escapeHtml(message)}
                </div>
            </div>
            <button type="button" class="admin-toast-close" style="
                border: 0;
                background: transparent;
                color: #94a3b8;
                padding: 0 2px;
                cursor: pointer;
            " aria-label="Close">
                <i class="fas fa-times"></i>
            </button>
        `;

        container.appendChild(toast);

        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        function closeToast() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 250);
        }

        toast.querySelector('.admin-toast-close').addEventListener('click', closeToast);
        setTimeout(closeToast, 5000);

        toast.addEventListener('click', function(e) {
            if (e.target === toast || e.target.closest('.admin-toast-close')) {
                closeToast();
            }
        });
    }

    function escapeHtml(value) {
        if (!value) return '';
        const div = document.createElement('div');
        div.textContent = value;
        return div.innerHTML;
    }

    // ✅ Session toast
    @if(session('toast'))
        const sessionToast = @json(session('toast'));
        showToast(
            sessionToast.type || 'info',
            sessionToast.message || 'Notification',
            sessionToast.title || null
        );
    @endif

    @if(session('success'))
        showToast('success', @json(session('success')));
    @endif

    @if(session('error'))
        showToast('error', @json(session('error')));
    @endif

    @if(session('warning'))
        showToast('warning', @json(session('warning')));
    @endif

    @if(session('info'))
        showToast('info', @json(session('info')));
    @endif

    window.showToast = showToast;

    /*
     * =========================================================
     * CONFIGURATION - ADMIN ROUTES
     * =========================================================
     */

    const csrfToken = @json(csrf_token());

    const markReadUrlTemplate =
        @json(route('admin.notifications.mark-single-read', [
            'id' => '__NOTIFICATION_ID__'
        ]));

    const deleteUrlTemplate =
        @json(route('admin.notifications.destroy', [
            'id' => '__NOTIFICATION_ID__'
        ]));

    const markAllReadUrl =
        @json(route('admin.notifications.mark-read'));

    const deleteAllUrl =
        @json(route('admin.notifications.destroy-all'));

    /*
     * =========================================================
     * ELEMENTS
     * =========================================================
     */

    const deleteAllModalElement = document.getElementById('adminDeleteAllModal');
    const confirmDeleteAllButton = document.getElementById('adminConfirmDeleteAll');
    const deleteAllButton = document.getElementById('adminDeleteAll');

    /*
     * =========================================================
     * URL BUILDER
     * =========================================================
     */

    function buildUrl(template, id) {
        return template.replace('__NOTIFICATION_ID__', encodeURIComponent(id));
    }

    /*
     * =========================================================
     * EMPTY STATE
     * =========================================================
     */

    function showEmptyState() {
        const list = document.getElementById('notificationsList');
        if (!list) return;

        if (list.querySelector('.notification-item')) return;
        if (list.querySelector('#adminEmptyNotifications')) return;

        list.innerHTML = `
            <div class="text-center py-5 text-muted" id="adminEmptyNotifications">
                <i class="fas fa-bell-slash fa-3x mb-3"></i>
                <div>No notifications yet.</div>
            </div>
        `;
    }

    /*
     * =========================================================
     * UPDATE UNREAD BADGE
     * =========================================================
     */

    function updateUnreadBadge(count) {
        const badge = document.querySelector('.badge.bg-danger.ms-2');
        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline';
            } else {
                badge.style.display = 'none';
                const markAllBtn = document.getElementById('adminMarkAllRead');
                if (markAllBtn) markAllBtn.style.display = 'none';
            }
        }
    }

    /*
     * =========================================================
     * MARK SINGLE AS READ
     * =========================================================
     */

    async function markNotificationRead(notificationId, showMessage = true) {
        if (!notificationId) return false;

        try {
            const url = buildUrl(markReadUrlTemplate, notificationId);

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Unable to mark notification as read.');
            }

            const item = document.querySelector(
                `.notification-item[data-notification-id="${CSS.escape(String(notificationId))}"]`
            );

            if (item) {
                item.classList.remove('bg-light', 'unread');

                const readButton = item.querySelector('.notification-read-btn');
                if (readButton) {
                    readButton.outerHTML = `
                        <span class="badge bg-light text-success border align-self-center notification-read-badge">
                            <i class="fas fa-check me-1"></i>
                            Read
                        </span>
                    `;
                }
            }

            if (showMessage) {
                showToast('success', data.message || 'Notification marked as read.', 'Notification');
            }

            if (data.unread_count !== undefined) {
                updateUnreadBadge(data.unread_count);
            }

            return true;

        } catch (error) {
            showToast('error', error.message || 'Unable to mark notification as read.', 'Notification');
            return false;
        }
    }

    window.markNotificationRead = markNotificationRead;

    /*
     * =========================================================
     * MARK SINGLE BUTTON
     * =========================================================
     */

    document.querySelectorAll('.notification-read-btn').forEach(function (button) {
        button.addEventListener('click', async function () {
            const notificationId = this.dataset.notificationId;
            await markNotificationRead(notificationId, true);
        });
    });

    /*
     * =========================================================
     * VIEW BUTTON
     * =========================================================
     */

    document.querySelectorAll('.notification-view-btn').forEach(function (link) {
        link.addEventListener('click', async function (event) {
            const notificationId = this.dataset.notificationId;
            if (!notificationId) return;

            event.preventDefault();
            const destination = this.href;

            const success = await markNotificationRead(notificationId, false);
            if (success) {
                window.location.href = destination;
            }
        });
    });

    /*
     * =========================================================
     * DELETE SINGLE
     * =========================================================
     */

    document.querySelectorAll('.notification-delete-btn').forEach(function (button) {
        button.addEventListener('click', async function () {
            const buttonElement = this;
            const notificationId = buttonElement.dataset.notificationId;

            if (!notificationId) {
                showToast('error', 'Notification ID is missing.', 'Delete Failed');
                return;
            }

            const item = buttonElement.closest('.notification-item');

            buttonElement.disabled = true;
            buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Deleting...';

            try {
                const url = buildUrl(deleteUrlTemplate, notificationId);

                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to delete notification.');
                }

                if (item) {
                    item.style.opacity = '0';
                    item.style.transform = 'translateX(25px)';
                    item.style.transition = 'all .25s ease';

                    setTimeout(function () {
                        item.remove();
                        showEmptyState();
                    }, 250);
                }

                showToast('success', data.message || 'Notification deleted successfully.', 'Notification Deleted');

                if (data.unread_count !== undefined) {
                    updateUnreadBadge(data.unread_count);
                }

            } catch (error) {
                showToast('error', error.message || 'Unable to delete notification.', 'Delete Failed');
                buttonElement.disabled = false;
                buttonElement.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Delete';
            }
        });
    });

    /*
     * =========================================================
     * MARK ALL AS READ
     * =========================================================
     */

    const markAllButton = document.getElementById('adminMarkAllRead');

    if (markAllButton) {
        markAllButton.addEventListener('click', async function () {
            // If button is disabled, do nothing
            if (this.disabled) return;

            const button = this;
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Updating...';

            try {
                const response = await fetch(markAllReadUrl, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Unable to mark all notifications as read.');
                }

                document.querySelectorAll('.notification-item').forEach(function (item) {
                    item.classList.remove('bg-light', 'unread');
                });

                document.querySelectorAll('.notification-read-btn').forEach(function (readButton) {
                    readButton.outerHTML = `
                        <span class="badge bg-light text-success border align-self-center notification-read-badge">
                            <i class="fas fa-check me-1"></i>
                            Read
                        </span>
                    `;
                });

                showToast('success', data.message || 'All notifications marked as read.', 'Notifications');
                updateUnreadBadge(0);

            } catch (error) {
                showToast('error', error.message || 'Unable to update notifications.', 'Notification');
            } finally {
                button.disabled = false;
                button.innerHTML = '<i class="fas fa-check-double me-1"></i> Mark All Read';
                // If no unread, re-disable
                if (document.querySelectorAll('.notification-item.unread').length === 0) {
                    button.disabled = true;
                }
            }
        });
    }

    /*
     * =========================================================
     * DELETE ALL
     * =========================================================
     */

    if (deleteAllButton) {
        deleteAllButton.addEventListener('click', function(event) {
            if (this.disabled) return;

            event.preventDefault();
            event.stopPropagation();

            if (this.dataset.deleteAllProcessing === 'true') return;

            if (deleteAllModalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                try {
                    const modal = bootstrap.Modal.getOrCreateInstance(deleteAllModalElement);
                    modal.show();
                } catch (modalError) {
                    if (window.confirm('Are you sure you want to delete all your notifications?')) {
                        performDeleteAll();
                    }
                }
            } else {
                if (window.confirm('Are you sure you want to delete all your notifications?')) {
                    performDeleteAll();
                }
            }
        });
    }

    /*
     * =========================================================
     * PERFORM DELETE ALL
     * =========================================================
     */

    async function performDeleteAll() {
        const button = document.getElementById('adminDeleteAll');

        if (!button) {
            showToast('error', 'Delete All button could not be found.', 'Delete Failed');
            return;
        }

        if (button.dataset.deleteAllProcessing === 'true') return;

        button.dataset.deleteAllProcessing = 'true';
        button.disabled = true;
        button.style.opacity = '0.6';
        button.style.cursor = 'not-allowed';
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Deleting...';

        if (confirmDeleteAllButton) {
            confirmDeleteAllButton.disabled = true;
            confirmDeleteAllButton.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Deleting...';
        }

        try {
            const response = await fetch(deleteAllUrl, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Unable to delete all notifications.');
            }

            document.querySelectorAll('.notification-item').forEach(function (item) {
                item.remove();
            });

            showEmptyState();

            if (deleteAllModalElement && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(deleteAllModalElement);
                if (modal) {
                    modal.hide();
                }
            }

            showToast('success', data.message || 'All notifications deleted successfully.', 'Notifications');

            // Disable delete all button
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';

            updateUnreadBadge(0);

        } catch (error) {
            showToast('error', error.message || 'Unable to delete all notifications.', 'Delete Failed');
        } finally {
            button.dataset.deleteAllProcessing = 'false';
            // Re-enable only if there are notifications
            if (document.querySelectorAll('.notification-item').length > 0) {
                button.disabled = false;
                button.style.opacity = '1';
                button.style.cursor = 'pointer';
            }
            button.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Delete All';

            if (confirmDeleteAllButton) {
                confirmDeleteAllButton.disabled = false;
                confirmDeleteAllButton.innerHTML = '<i class="fas fa-trash-alt me-1"></i> Delete All';
            }
        }
    }

    /*
     * =========================================================
     * CONFIRM DELETE ALL
     * =========================================================
     */

    if (confirmDeleteAllButton) {
        confirmDeleteAllButton.addEventListener('click', function () {
            performDeleteAll();
        });
    }

});
</script>

@endpush

@endsection
