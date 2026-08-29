{{-- resources/views/components/notification-bell.blade.php --}}

<div class="dropdown notification-dropdown">

    <button class="topbar-icon" type="button" id="notificationToggle" title="Notifications" aria-expanded="false">
        <i class="fa fa-bell"></i>

        <span class="badge-dot" id="notifDot"
            style="{{ ($unreadNotifications ?? 0) > 0 ? '' : 'display:none;' }}"></span>
    </button>

    <div class="dropdown-menu dropdown-menu-end" id="notificationDropdown">

        <div class="notif-header">
            <span>
                <i class="fa fa-bell me-1"></i>
                Notifications
            </span>

            <button type="button" id="markAllRead" class="notification-mark-all">
                Mark all as read
            </button>
        </div>

        <div id="notificationList">

            @forelse($notifications ?? [] as $notif)

                <div class="notif-item {{ empty($notif['read_at']) ? 'unread' : '' }}"
                    data-notification-id="{{ $notif['id'] }}">

                    <div class="notif-icon {{ $notif['type'] ?? 'info' }}">
                        <i class="fa fa-{{ $notif['icon'] ?? 'bell' }}"></i>
                    </div>

                    <div class="notif-text">

                        <strong>
                            {{ $notif['title'] ?? 'Notification' }}
                        </strong>

                        <span>
                            {{ $notif['message'] ?? 'New notification' }}
                        </span>

                        <small>
                            {{ $notif['time'] ?? '' }}
                        </small>

                    </div>

                </div>

            @empty

                <div class="notif-empty">
                    <i class="fa fa-bell-slash fa-2x"></i>

                    <span>No notifications yet</span>
                </div>

            @endforelse

        </div>

        <div class="notif-footer">

            @if(request()->is('admin/*'))
                <a href="{{ route('admin.notifications.index') }}">
                    View all notifications
                </a>
            @else
                <a href="{{ route('employer.notifications.index') }}">
                    View all notifications
                </a>
            @endif

        </div>

    </div>
</div>

@once



    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const toggle = document.getElementById('notificationToggle');
            const dropdown = document.getElementById('notificationDropdown');
            const list = document.getElementById('notificationList');
            const dot = document.getElementById('notifDot');
            const markAll = document.getElementById('markAllRead');

            if (!toggle || !dropdown) {
                return;
            }

            /*
             * Toggle notification dropdown.
             */
            toggle.addEventListener('click', function (event) {

                event.preventDefault();
                event.stopPropagation();

                dropdown.classList.toggle('show');

            });

            /*
             * Close when clicking outside.
             */
            document.addEventListener('click', function (event) {

                if (
                    !dropdown.contains(event.target) &&
                    !toggle.contains(event.target)
                ) {
                    dropdown.classList.remove('show');
                }

            });

            /*
             * Load notifications from server.
             */
            async function loadNotifications() {

                try {

                    const response = await fetch(
                        '{{ route("notifications.latest") }}',
                        {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();

                    if (!data.success) {
                        return;
                    }

                    updateBell(data.unread || 0);

                    renderNotifications(data.notifications || []);

                } catch (error) {

                    console.error(
                        'Notification loading failed:',
                        error
                    );

                }
            }

            /*
             * Update red notification dot.
             */
            function updateBell(count) {

                if (!dot) {
                    return;
                }

                dot.style.display =
                    count > 0 ? 'block' : 'none';
            }

            /*
             * Render notifications.
             */
            function renderNotifications(notifications) {

                if (!list) {
                    return;
                }

                if (!notifications.length) {

                    list.innerHTML = `
                        <div class="notif-empty">
                            <i class="fa fa-bell-slash fa-2x"></i>
                            <span>No notifications yet</span>
                        </div>
                    `;

                    return;
                }

                list.innerHTML = notifications.map(function (notification) {

                    const unreadClass =
                        notification.read ? '' : 'unread';

                    return `
                        <div
                            class="notif-item ${unreadClass}"
                            data-notification-id="${notification.id}"
                            data-url="${notification.url || ''}"
                        >

                            <div class="notif-icon ${notification.type || 'info'}">
                                <i class="fa fa-${notification.icon || 'bell'}"></i>
                            </div>

                            <div class="notif-text">

                                <strong>
                                    ${escapeHtml(notification.title || 'Notification')}
                                </strong>

                                <span>
                                    ${escapeHtml(notification.message || '')}
                                </span>

                                <small>
                                    ${escapeHtml(notification.time || '')}
                                </small>

                            </div>

                        </div>
                    `;

                }).join('');

                bindNotificationClicks();
            }

            /*
             * Notification click.
             */
            function bindNotificationClicks() {

                document
                    .querySelectorAll('.notif-item[data-notification-id]')
                    .forEach(function (item) {

                        item.addEventListener('click', async function () {

                            const id =
                                item.dataset.notificationId;

                            const url =
                                item.dataset.url;

                            try {

                                await fetch(
                                    '{{ url("/notifications") }}/' + id + '/read',
                                    {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Accept': 'application/json',
                                            'X-CSRF-TOKEN':
                                                '{{ csrf_token() }}'
                                        }
                                    }
                                );

                            } catch (error) {
                                console.error(error);
                            }

                            item.classList.remove('unread');

                            if (url) {
                                window.location.href = url;
                            }

                            loadNotifications();

                        });

                    });
            }

            /*
             * Mark all read.
             */
            if (markAll) {

                markAll.addEventListener('click', async function (event) {

                    event.preventDefault();
                    event.stopPropagation();

                    try {

                        const response = await fetch(
                            '{{ route("notifications.mark-all-read") }}',
                            {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN':
                                        '{{ csrf_token() }}'
                                }
                            }
                        );

                        if (response.ok) {
                            updateBell(0);
                            loadNotifications();
                        }

                    } catch (error) {

                        console.error(
                            'Mark all read failed:',
                            error
                        );

                    }

                });

            }

            /*
             * Basic HTML escaping.
             */
            function escapeHtml(value) {

                const div =
                    document.createElement('div');

                div.textContent =
                    value ?? '';

                return div.innerHTML;
            }

            /*
             * Initial load.
             */
            loadNotifications();

            /*
             * Automatically refresh every 30 seconds.
             */
            setInterval(
                loadNotifications,
                30000
            );

        });
    </script>

@endonce
