<header class="admin-top-header">
    <div class="page-title">
        <button class="btn-toggle-sidebar" id="toggleSidebar">
            <i class="fa fa-bars"></i>
        </button>
        <h4>@yield('page-title', 'Dashboard')</h4>
        <small>@yield('page-subtitle', 'Manage your portal')</small>
    </div>
    <div class="header-actions">
        <!-- Notifications -->
        <a href="#" class="text-muted" style="font-size: 18px; position: relative;">
            <i class="fa fa-bell"></i>
            <span class="badge"
                style="background: #e74c3c; font-size: 9px; padding: 2px 6px; position: absolute; top: -8px; right: -8px;">3</span>
        </a>

        <!-- User Dropdown -->
        <div class="dropdown">
            <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fa fa-user-circle me-1"></i>
                {{ Auth::user()->name ?? 'Admin' }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="fa fa-user me-2"></i> Profile</a></li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item" style="color: #e74c3c;">
                            <i class="fa fa-sign-out-alt me-2"></i> Logout
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
            });
        }

        if (overlay) {
            overlay.addEventListener('click', function () {
                sidebar.classList.remove('open');
                overlay.classList.remove('active');
            });
        }
    });
</script>
