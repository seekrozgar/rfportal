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
        <!-- Theme Toggle -->
        <button class="theme-toggle" id="themeToggle" title="Toggle Theme">
            <i class="fa fa-moon" id="themeIcon"></i>
        </button>

        <!-- Notification Bell -->
        <div class="dropdown notification-dropdown">
            <button class="topbar-icon" type="button" data-bs-toggle="dropdown" aria-expanded="false"
                title="Notifications">
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
                    <a href="{{ route('admin.notifications') }}">View all notifications</a>
                </div>
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="dropdown user-dropdown">
            <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="avatar-sm">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</span>
                <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fa fa-user"></i> My Profile
                    </a></li>
                <li><a class="dropdown-item" href="{{ route('admin.change-password') }}">
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
        // Theme Toggle
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

        // Mark all notifications as read
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
                        toastr.success('All notifications marked as read');
                    }
                });
        });
    });
</script>
