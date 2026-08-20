<header class="admin-top-header">
    <div class="page-title">
        <button class="btn-toggle-sidebar" id="toggleSidebar">
            <i class="fa fa-bars"></i>
        </button>
        <div>
            <h4>@yield('page-title', 'Dashboard')</h4>
            <small>@yield('page-subtitle', 'Manage your portal')</small>
        </div>
    </div>

    <div class="header-actions">
        <!-- ✅ Language Switcher - UNCOMMENT THIS -->
        @include('partials.language-switcher')

        <!-- Theme Toggle -->
        <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
            <i class="fa fa-moon" id="themeIcon"></i>
        </button>

        <!-- Notification Bell -->
        <div class="dropdown notification-dropdown">
            <button class="topbar-icon" type="button" id="notificationToggle" title="Notifications">
                <i class="fa fa-bell"></i>
                <span class="badge-dot" id="notifDot"
                    style="{{ ($unreadNotifications ?? 0) > 0 ? '' : 'display:none;' }}"></span>
            </button>
            <div class="dropdown-menu dropdown-menu-end" id="notificationDropdown">
                <div class="notif-header">
                    <span>Notifications</span>
                    <a href="#" id="markAllRead">Mark all as read</a>
                </div>
                <div id="notificationList">
                    @forelse($notifications ?? [] as $notif)
                        <div class="notif-item">
                            <div class="notif-icon {{ $notif['type'] ?? 'info' }}">
                                <i class="fa fa-{{ $notif['icon'] ?? 'bell' }}"></i>
                            </div>
                            <div class="notif-text">
                                {{ $notif['message'] ?? 'New notification' }}
                                <small>{{ $notif['time'] ?? now()->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="notif-item">
                            <div class="notif-text text-center text-muted py-3" style="width:100%;">
                                <i class="fa fa-bell-slash fa-2x d-block mb-2"></i>
                                No notifications yet
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="notif-footer">
                    <a href="{{ route('admin.notifications.index') }}">View all notifications</a>
                </div>
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown user-dropdown">
            <button class="dropdown-toggle" type="button" id="userDropdownToggle">
                <span class="avatar-sm">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" id="userDropdownMenu">
                <li><a class="dropdown-item" href="{{ route('admin.profile.index') }}">
                        <i class="fa fa-user"></i> My Profile
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.change-password.index') }}">
                        <i class="fa fa-key"></i> Change Password
                    </a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="fa fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ============================================================
        // ✅ THEME TOGGLE
        // ============================================================
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const html = document.documentElement;

        const savedTheme = localStorage.getItem('theme') || 'light';
        html.setAttribute('data-bs-theme', savedTheme);
        updateThemeIcon(savedTheme);

        themeToggle?.addEventListener('click', function () {
            const currentTheme = html.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            html.setAttribute('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });

        function updateThemeIcon(theme) {
            themeIcon.className = theme === 'dark' ? 'fa fa-sun' : 'fa fa-moon';
        }

        // ============================================================
        // ✅ NOTIFICATION DROPDOWN (Manual Toggle)
        // ============================================================
        const notifToggle = document.getElementById('notificationToggle');
        const notifDropdown = document.getElementById('notificationDropdown');

        if (notifToggle && notifDropdown) {
            notifToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('show');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!notifToggle.contains(e.target) && !notifDropdown.contains(e.target)) {
                    notifDropdown.classList.remove('show');
                }
            });
        }

        // ============================================================
        // ✅ USER DROPDOWN (Manual Toggle)
        // ============================================================
        const userToggle = document.getElementById('userDropdownToggle');
        const userDropdown = document.getElementById('userDropdownMenu');

        if (userToggle && userDropdown) {
            userToggle.addEventListener('click', function (e) {
                e.stopPropagation();
                userDropdown.classList.toggle('show');
            });

            document.addEventListener('click', function (e) {
                if (!userToggle.contains(e.target) && !userDropdown.contains(e.target)) {
                    userDropdown.classList.remove('show');
                }
            });
        }

        // ============================================================
        // ✅ MARK ALL NOTIFICATIONS AS READ
        // ============================================================
        document.getElementById('markAllRead')?.addEventListener('click', function (e) {
            e.preventDefault();
            fetch('{{ route('admin.notifications.mark-read') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                }
            }).then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('notifDot').style.display = 'none';
                        if (typeof toastr !== 'undefined') {
                            toastr.success('All notifications marked as read');
                        }
                    }
                });
        });
    });
</script>