<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <div class="brand">
        <div>
            <h3><i class="fa fa-briefcase me-2"></i> RF Portal</h3>
            <small>Admin Panel v1.0</small>
        </div>
        <button class="sidebar-toggle" id="closeSidebar">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- Menu -->
    <nav class="menu">
        @php
            $user = auth()->user();
            $isSuperAdmin = $user->hasRole('superadmin');
            $isAdmin = $user->hasRole('admin');
            $isAuthor = $user->hasRole('author');
            $currentRoute = request()->route()->getName();
        @endphp

        <!-- 🏠 Main -->
        <div class="menu-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ $currentRoute == 'admin.dashboard' ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt"></i> Dashboard
        </a>

        @if($isSuperAdmin || $isAdmin)
            <a href="{{ route('admin.users.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.users.') ? 'active' : '' }}">
                <i class="fa fa-users-cog"></i> Admin Users
                <span
                    class="badge">{{ \App\Models\User::whereIn('role', ['superadmin', 'admin', 'author'])->count() }}</span>
            </a>
        @endif

        <!-- 📦 Job Management -->
        <div class="menu-label">Job Management</div>

        <a href="{{ route('admin.jobs.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.jobs.') ? 'active' : '' }}">
            <i class="fa fa-briefcase"></i> General Jobs (PPSC/FPSC)
            <span class="badge">{{ \App\Models\Job::where('source', 'admin')->count() }}</span>
        </a>

        <a href="{{ route('admin.company-jobs.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.company-jobs.') ? 'active' : '' }}">
            <i class="fa fa-building"></i> Company Jobs
            <span class="badge">{{ \App\Models\Job::where('source', 'employer')->count() }}</span>
        </a>

        <!-- 📝 Education -->
        <div class="menu-label">Education</div>

        <a href="{{ route('admin.scholarships.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.scholarships.') ? 'active' : '' }}">
            <i class="fa fa-graduation-cap"></i> Scholarships
        </a>

        <a href="{{ route('admin.admissions.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.admissions.') ? 'active' : '' }}">
            <i class="fa fa-university"></i> Admissions
        </a>

        <a href="{{ route('admin.results.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.results.') ? 'active' : '' }}">
            <i class="fa fa-percent"></i> Results
        </a>

        <a href="{{ route('admin.news.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.news.') ? 'active' : '' }}">
            <i class="fa fa-newspaper"></i> News / Announcements
        </a>

        <!-- 👥 Users -->
        <div class="menu-label">Users</div>

        <a href="{{ route('admin.users.profiles') }}"
            class="menu-item {{ $currentRoute == 'admin.users.profiles' ? 'active' : '' }}">
            <i class="fa fa-user-tie"></i> User Profiles
            <span class="badge">{{ \App\Models\User::whereIn('role', ['employer', 'seeker'])->count() }}</span>
        </a>

        <!-- 📝 Content Management -->
        <div class="menu-label">Content</div>

        <a href="{{ route('admin.seo.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.seo.') ? 'active' : '' }}">
            <i class="fa fa-search"></i> SEO
        </a>

        <a href="{{ route('admin.faq.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.faq.') ? 'active' : '' }}">
            <i class="fa fa-question-circle"></i> FAQs
        </a>

        <!-- 🌍 Translation -->
        <div class="menu-label">Translation</div>

        <a href="{{ route('admin.languages.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.languages.') ? 'active' : '' }}">
            <i class="fa fa-language"></i> Languages
        </a>

        @if($isSuperAdmin)
            <!-- 🌍 Location -->
            <div class="menu-label">Location</div>

            <a href="{{ route('admin.locations.countries.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.locations.countries.') ? 'active' : '' }}">
                <i class="fa fa-flag"></i> Countries
            </a>

            <a href="{{ route('admin.locations.states.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.locations.states.') ? 'active' : '' }}">
                <i class="fa fa-map-marker-alt"></i> States
            </a>

            <a href="{{ route('admin.locations.cities.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.locations.cities.') ? 'active' : '' }}">
                <i class="fa fa-city"></i> Cities
            </a>
        @endif

        <!-- 💰 Packages & Payments -->
        <div class="menu-label">Packages</div>

        <a href="{{ route('admin.packages.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.packages.') ? 'active' : '' }}">
            <i class="fa fa-box"></i> Packages
        </a>

        <a href="{{ route('admin.payments.company') }}"
            class="menu-item {{ $currentRoute == 'admin.payments.company' ? 'active' : '' }}">
            <i class="fa fa-building"></i> Company Payments
        </a>

        <a href="{{ route('admin.payments.seeker') }}"
            class="menu-item {{ $currentRoute == 'admin.payments.seeker' ? 'active' : '' }}">
            <i class="fa fa-user"></i> Seeker Payments
        </a>

        <!-- 🏷️ Job Attributes -->
        <div class="menu-label">Job Attributes</div>

        @php
            $attributes = [
                'language-levels' => ['icon' => 'fa-language', 'label' => 'Language Levels'],
                'career-levels' => ['icon' => 'fa-chart-line', 'label' => 'Career Levels'],
                'functional-areas' => ['icon' => 'fa-layer-group', 'label' => 'Functional Areas'],
                'genders' => ['icon' => 'fa-venus-mars', 'label' => 'Genders'],
                'industries' => ['icon' => 'fa-industry', 'label' => 'Industries'],
                'job-experience' => ['icon' => 'fa-clock', 'label' => 'Job Experience'],
                'job-skills' => ['icon' => 'fa-tools', 'label' => 'Job Skills'],
                'job-types' => ['icon' => 'fa-tag', 'label' => 'Job Types'],
                'job-shifts' => ['icon' => 'fa-clock', 'label' => 'Job Shifts'],
                'degree-levels' => ['icon' => 'fa-graduation-cap', 'label' => 'Degree Levels'],
                'degree-types' => ['icon' => 'fa-graduation-cap', 'label' => 'Degree Types'],
                'major-subjects' => ['icon' => 'fa-book', 'label' => 'Major Subjects'],
                'result-types' => ['icon' => 'fa-percent', 'label' => 'Result Types'],
                'marital-status' => ['icon' => 'fa-ring', 'label' => 'Marital Status'],
                'ownership-types' => ['icon' => 'fa-user-tie', 'label' => 'Ownership Types'],
                'salary-periods' => ['icon' => 'fa-money-bill-wave', 'label' => 'Salary Periods'],
            ];
        @endphp

        @foreach($attributes as $route => $attr)
            <a href="{{ route('admin.attributes.' . $route) }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.attributes.' . $route) ? 'active' : '' }}">
                <i class="{{ $attr['icon'] }}"></i> {{ $attr['label'] }}
            </a>
        @endforeach

        <!-- ⚙️ Settings -->
        @if($isSuperAdmin || $isAdmin)
            <div class="menu-label">System</div>

            <a href="{{ route('admin.settings.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.settings.') ? 'active' : '' }}">
                <i class="fa fa-cog"></i> Site Settings
            </a>

            <a href="{{ route('admin.profile') }}" class="menu-item {{ $currentRoute == 'admin.profile' ? 'active' : '' }}">
                <i class="fa fa-user"></i> My Profile
            </a>

            <a href="{{ route('admin.change-password') }}"
                class="menu-item {{ $currentRoute == 'admin.change-password' ? 'active' : '' }}">
                <i class="fa fa-key"></i> Change Password
            </a>

            <a href="{{ route('admin.notifications') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.notifications') ? 'active' : '' }}">
                <i class="fa fa-bell"></i> Notifications
                <span class="badge">{{ \App\Models\ActivityLog::whereNull('read_at')->count() }}</span>
            </a>
        @endif
    </nav>

    <!-- ✅ Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}</div>
            <div>
                <div class="name">{{ Auth::user()->name ?? 'Admin' }}</div>
                <div class="role">{{ ucfirst(Auth::user()->role ?? 'admin') }}</div>
            </div>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ✅ Toggle sidebar
        const sidebar = document.getElementById('adminSidebar');
        const overlay = document.getElementById('adminOverlay');
        const closeBtn = document.getElementById('closeSidebar');

        function toggleSidebar() {
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }

        document.getElementById('toggleSidebar')?.addEventListener('click', toggleSidebar);
        closeBtn?.addEventListener('click', toggleSidebar);
        overlay?.addEventListener('click', toggleSidebar);

        // ✅ Sub-menu toggle
        document.querySelectorAll('.menu-item.has-sub').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                var sub = this.nextElementSibling;
                if (sub && sub.classList.contains('sub-menu')) {
                    sub.classList.toggle('open');
                    this.querySelector('.arrow')?.classList.toggle('open');
                }
            });
        });
    });
</script>
