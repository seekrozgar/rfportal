{{-- resources/views/components/notification-bell.blade.php --}}

<div class="dropdown notification-dropdown">

    <button
        class="topbar-icon"
        type="button"
        id="notificationToggle"
        title="Notifications"
        aria-expanded="false"
    >
        <i class="fa fa-bell"></i>

        <span
            class="badge-dot"
            id="notifDot"
            style="{{ ($unreadNotifications ?? 0) > 0 ? '' : 'display:none;' }}"
        ></span>
    </button>


    <div
        class="dropdown-menu dropdown-menu-end"
        id="notificationDropdown"
    >

        {{-- HEADER --}}
        <div class="notif-header">

            <span>
                <i class="fa fa-bell me-1"></i>
                Notifications
            </span>

            <button
                type="button"
                id="markAllRead"
                class="notification-mark-all"
            >
                Mark all as read
            </button>

        </div>


        {{-- NOTIFICATION LIST --}}
        <div id="notificationList">

            @forelse($notifications ?? [] as $notif)

                <div
                    class="notif-item {{ empty($notif['read_at']) ? 'unread' : '' }}"
                    data-notification-id="{{ $notif['id'] }}"
                >

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

                    <span>
                        No notifications yet
                    </span>

                </div>

            @endforelse

        </div>


        {{-- FOOTER --}}
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

    /*
     * =========================================================
     * ELEMENTS
     * =========================================================
     */

    const toggle =
        document.getElementById('notificationToggle');

    const dropdown =
        document.getElementById('notificationDropdown');

    const list =
        document.getElementById('notificationList');

    const badge =
        document.getElementById('notifDot');

    const markAll =
        document.getElementById('markAllRead');


    /*
     * =========================================================
     * STOP IF BELL IS NOT PRESENT
     * =========================================================
     */

    if (!toggle || !dropdown || !list) {
        return;
    }


    /*
     * =========================================================
     * CSRF
     * =========================================================
     */

    function csrfToken() {

        return document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content');

    }


    /*
     * =========================================================
     * ROUTES
     * =========================================================
     */

    const latestUrl =
        @json(route('notifications.latest'));

    const markAllReadUrl =
        @json(route('notifications.mark-all-read'));


    /*
     * Build single notification read URL
     *
     * Example:
     * /notifications/43/read
     */

    const markReadUrlTemplate =
        @json(
            route(
                'notifications.mark-read',
                ['notification' => '__NOTIFICATION_ID__']
            )
        );


    /*
     * =========================================================
     * BADGE
     * =========================================================
     */

    function showBadge(count) {

        if (!badge) {
            return;
        }

        count =
            Number(count) || 0;


        if (count > 0) {

            badge.style.display =
                'block';

        } else {

            badge.style.display =
                'none';

        }

    }


    /*
     * =========================================================
     * ICON
     * =========================================================
     */

    function iconName(icon) {

        if (!icon) {
            return 'bell';
        }

        return String(icon)
            .replace(/^fa-/, '');

    }


    /*
     * =========================================================
     * HTML ESCAPE
     * =========================================================
     */

    function escapeHtml(value) {

        const div =
            document.createElement('div');

        div.textContent =
            value ?? '';

        return div.innerHTML;

    }


    /*
     * =========================================================
     * BUILD MARK READ URL
     * =========================================================
     */

    function buildMarkReadUrl(id) {

        return markReadUrlTemplate.replace(
            '__NOTIFICATION_ID__',
            encodeURIComponent(String(id))
        );

    }


    /*
     * =========================================================
     * RENDER NOTIFICATIONS
     * =========================================================
     */

    function renderNotifications(notifications) {

        if (
            !notifications ||
            notifications.length === 0
        ) {

            list.innerHTML = `
                <div class="notif-empty">
                    <i class="fa fa-bell-slash fa-2x"></i>
                    <span>No notifications yet</span>
                </div>
            `;

            return;
        }


        list.innerHTML =
            notifications.map(function (notification) {

                const id =
                    notification.id;

                const unreadClass =
                    notification.read
                        ? ''
                        : 'unread';


                const title =
                    escapeHtml(
                        notification.title ??
                        'Notification'
                    );


                const message =
                    escapeHtml(
                        notification.message ??
                        ''
                    );


                const time =
                    escapeHtml(
                        notification.time ??
                        ''
                    );


                const icon =
                    iconName(
                        notification.icon
                    );


                const url =
                    notification.url
                        ? escapeHtml(
                            notification.url
                        )
                        : '';


                return `
                    <div
                        class="notification-item ${unreadClass}"
                        data-id="${escapeHtml(String(id))}"
                        data-url="${url}"
                    >

                        <div class="notification-item-content">

                            <div class="notification-item-header">

                                <div class="notification-item-icon">
                                    <i class="fa fa-${icon}"></i>
                                </div>

                                <div class="notification-item-title">
                                    ${title}
                                </div>

                                <span class="notification-item-time">
                                    ${time}
                                </span>

                            </div>


                            <div class="notification-item-message">
                                ${message}
                            </div>

                        </div>

                    </div>
                `;

            }).join('');


        bindNotificationClicks();

    }


    /*
     * =========================================================
     * LOAD NOTIFICATIONS
     * =========================================================
     */

    async function loadNotifications() {

        try {

            const response =
                await fetch(
                    latestUrl,
                    {
                        method: 'GET',

                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        credentials: 'same-origin'
                    }
                );


            const data =
                await response.json();


            if (
                !response.ok ||
                !data.success
            ) {

                console.error(
                    'Notification API error:',
                    data
                );

                return;
            }


            renderNotifications(
                data.notifications || []
            );


            showBadge(
                data.unread_count || 0
            );


        } catch (error) {

            console.error(
                'Failed to load notifications:',
                error
            );

        }

    }


    /*
     * =========================================================
     * MARK SINGLE NOTIFICATION READ
     * =========================================================
     */

    async function markNotificationRead(id) {

        if (!id) {

            console.error(
                'Notification ID is missing.'
            );

            return false;
        }


        try {

            const response =
                await fetch(
                    buildMarkReadUrl(id),
                    {
                        method: 'POST',

                        headers: {
                            'X-CSRF-TOKEN':
                                csrfToken(),

                            'Accept':
                                'application/json',

                            'X-Requested-With':
                                'XMLHttpRequest'
                        },

                        credentials:
                            'same-origin'
                    }
                );


            const data =
                await response.json();


            if (
                !response.ok ||
                !data.success
            ) {

                console.error(
                    'Mark notification read failed:',
                    data
                );

                return false;
            }


            showBadge(
                data.unread_count || 0
            );


            return true;


        } catch (error) {

            console.error(
                'Mark notification read error:',
                error
            );

            return false;

        }

    }


    /*
     * =========================================================
     * BIND NOTIFICATION CLICK
     * =========================================================
     */

    function bindNotificationClicks() {

        list
            .querySelectorAll(
                '.notification-item'
            )
            .forEach(function (item) {

                item.addEventListener(
                    'click',
                    async function () {

                        const id =
                            this.dataset.id;

                        const url =
                            this.dataset.url;


                        if (!id) {

                            console.error(
                                'Notification ID missing:',
                                this
                            );

                            return;
                        }


                        /*
                         * Mark as read first
                         */
                        await markNotificationRead(
                            id
                        );


                        /*
                         * Navigate if action URL exists
                         */
                        if (url) {

                            window.location.href =
                                url;

                            return;
                        }


                        /*
                         * Refresh list if no URL
                         */
                        await loadNotifications();

                    }
                );

            });

    }


    /*
     * =========================================================
     * TOGGLE DROPDOWN
     * =========================================================
     */

    toggle.addEventListener(
        'click',
        function (event) {

            event.stopPropagation();


            const isOpen =
                dropdown.classList.contains(
                    'show'
                );


            dropdown.classList.toggle(
                'show',
                !isOpen
            );


            toggle.setAttribute(
                'aria-expanded',
                !isOpen
                    ? 'true'
                    : 'false'
            );


            if (!isOpen) {

                loadNotifications();

            }

        }
    );


    /*
     * =========================================================
     * CLOSE DROPDOWN OUTSIDE CLICK
     * =========================================================
     */

    document.addEventListener(
        'click',
        function (event) {

            if (
                !dropdown.contains(
                    event.target
                ) &&
                !toggle.contains(
                    event.target
                )
            ) {

                dropdown.classList.remove(
                    'show'
                );


                toggle.setAttribute(
                    'aria-expanded',
                    'false'
                );

            }

        }
    );


    /*
     * =========================================================
     * MARK ALL AS READ
     * =========================================================
     */

    if (markAll) {

        markAll.addEventListener(
            'click',
            async function (event) {

                event.preventDefault();

                event.stopPropagation();


                const button =
                    this;


                button.disabled =
                    true;


                const originalText =
                    button.innerHTML;


                button.innerHTML =
                    '<i class="fa fa-spinner fa-spin me-1"></i> Updating...';


                try {

                    const response =
                        await fetch(
                            markAllReadUrl,
                            {
                                method: 'POST',

                                headers: {
                                    'X-CSRF-TOKEN':
                                        csrfToken(),

                                    'Accept':
                                        'application/json',

                                    'X-Requested-With':
                                        'XMLHttpRequest'
                                },

                                credentials:
                                    'same-origin'
                            }
                        );


                    const data =
                        await response.json();


                    if (
                        !response.ok ||
                        !data.success
                    ) {

                        console.error(
                            'Mark all read failed:',
                            data
                        );

                        return;
                    }


                    showBadge(0);


                    /*
                     * Reload latest state
                     */
                    await loadNotifications();


                } catch (error) {

                    console.error(
                        'Mark all read error:',
                        error
                    );

                } finally {

                    button.disabled =
                        false;

                    button.innerHTML =
                        originalText;

                }

            }
        );

    }


    /*
     * =========================================================
     * INITIAL LOAD
     * =========================================================
     */

    loadNotifications();


    /*
     * =========================================================
     * AUTO REFRESH
     * =========================================================
     */

    setInterval(
        loadNotifications,
        20000
    );

});

</script>

@endonce
