<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <div class="brand">
        <h3><i class="fa fa-briefcase me-2"></i> RF Portal</h3>
        <small>Admin Panel v1.0</small>
    </div>

    <!-- Menu -->
    <nav class="menu">
        @php
            $user = auth()->user();
            $isSuperAdmin = $user->hasRole('superadmin');
            $isAdmin = $user->hasRole('admin');
            $isAuthor = $user->hasRole('author');
        @endphp

        <!-- 🏠 Main -->
        <div class="menu-label">Main</div>
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt"></i> Dashboard
        </a>

        @if($isSuperAdmin || $isAdmin)
            <a href="{{ route('admin.users.index') }}"
                class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fa fa-users-cog"></i> Admin Users
                <span
                    class="badge">{{ \App\Models\User::whereIn('role', ['superadmin', 'admin', 'author'])->count() }}</span>
            </a>
        @endif

        <!-- 📦 Job Management -->
        <div class="menu-label">Job Management</div>

        <a href="{{ route('admin.jobs.index') }}"
            class="menu-item {{ request()->routeIs('admin.jobs.*') ? 'active' : '' }}">
            <i class="fa fa-briefcase"></i> General Jobs (PPSC/FPSC)
            <span class="badge">{{ \App\Models\Job::where('source', 'admin')->count() }}</span>
        </a>

        <a href="{{ route('admin.company-jobs.index') }}"
            class="menu-item {{ request()->routeIs('admin.company-jobs.*') ? 'active' : '' }}">
            <i class="fa fa-building"></i> Company Jobs
            <span class="badge">{{ \App\Models\Job::where('source', 'employer')->count() }}</span>
        </a>

        <!-- 📝 Education -->
        <div class="menu-label">Education</div>

        <a href="{{ route('admin.scholarships.index') }}"
            class="menu-item {{ request()->routeIs('admin.scholarships.*') ? 'active' : '' }}">
            <i class="fa fa-graduation-cap"></i> Scholarships
        </a>

        <a href="{{ route('admin.admissions.index') }}"
            class="menu-item {{ request()->routeIs('admin.admissions.*') ? 'active' : '' }}">
            <i class="fa fa-university"></i> Admissions
        </a>

        <a href="{{ route('admin.results.index') }}"
            class="menu-item {{ request()->routeIs('admin.results.*') ? 'active' : '' }}">
            <i class="fa fa-percent"></i> Results
        </a>

        <a href="{{ route('admin.news.index') }}"
            class="menu-item {{ request()->routeIs('admin.news.*') ? 'active' : '' }}">
            <i class="fa fa-newspaper"></i> News / Announcements
        </a>

        <!-- 👥 Users -->
        <div class="menu-label">Users</div>

        <a href="{{ route('admin.users.profiles') }}"
            class="menu-item {{ request()->routeIs('admin.users.profiles') ? 'active' : '' }}">
            <i class="fa fa-user-tie"></i> User Profiles
            <span class="badge">{{ \App\Models\User::whereIn('role', ['employer', 'seeker'])->count() }}</span>
        </a>

        <!-- 📝 Content Management -->
        <div class="menu-label">Content Management</div>

        <a href="{{ route('admin.seo.index') }}"
            class="menu-item {{ request()->routeIs('admin.seo.*') ? 'active' : '' }}">
            <i class="fa fa-search"></i> SEO
        </a>

        <a href="{{ route('admin.faq.index') }}"
            class="menu-item {{ request()->routeIs('admin.faq.*') ? 'active' : '' }}">
            <i class="fa fa-question-circle"></i> FAQs
        </a>

        <!-- 🌍 Translation -->
        <div class="menu-label">Translation</div>

        <a href="{{ route('admin.languages.index') }}"
            class="menu-item {{ request()->routeIs('admin.languages.*') ? 'active' : '' }}">
            <i class="fa fa-language"></i> Languages
        </a>

        @if($isSuperAdmin)
            <!-- 🌍 Location -->
            <div class="menu-label">Location</div>

            <a href="{{ route('admin.countries.index') }}"
                class="menu-item {{ request()->routeIs('admin.countries.*') ? 'active' : '' }}">
                <i class="fa fa-flag"></i> Countries
            </a>

            <a href="{{ route('admin.states.index') }}"
                class="menu-item {{ request()->routeIs('admin.states.*') ? 'active' : '' }}">
                <i class="fa fa-map-marker-alt"></i> States
            </a>

            <a href="{{ route('admin.cities.index') }}"
                class="menu-item {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                <i class="fa fa-city"></i> Cities
            </a>
        @endif

        <!-- 💰 Packages & Payments -->
        <div class="menu-label">Packages</div>

        <a href="{{ route('admin.packages.index') }}"
            class="menu-item {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
            <i class="fa fa-box"></i> Packages
        </a>

        <a href="{{ route('admin.payments.company') }}"
            class="menu-item {{ request()->routeIs('admin.payments.company') ? 'active' : '' }}">
            <i class="fa fa-building"></i> Company Payments
        </a>

        <a href="{{ route('admin.payments.seeker') }}"
            class="menu-item {{ request()->routeIs('admin.payments.seeker') ? 'active' : '' }}">
            <i class="fa fa-user"></i> Seeker Payments
        </a>

        <!-- 🏷️ Job Attributes -->
        <div class="menu-label">Job Attributes</div>

        @php
            $attributes = [
                'language-levels' => 'Language Levels',
                'career-levels' => 'Career Levels',
                'functional-areas' => 'Functional Areas',
                'genders' => 'Genders',
                'industries' => 'Industries',
                'job-experience' => 'Job Experience',
                'job-skills' => 'Job Skills',
                'job-types' => 'Job Types',
                'job-shifts' => 'Job Shifts',
                'degree-levels' => 'Degree Levels',
                'degree-types' => 'Degree Types',
                'major-subjects' => 'Major Subjects',
                'result-types' => 'Result Types',
                'marital-status' => 'Marital Status',
                'ownership-types' => 'Ownership Types',
                'salary-periods' => 'Salary Periods',
            ];
        @endphp

        @foreach($attributes as $route => $label)
            <a href="{{ route('admin.attributes.' . $route) }}"
                class="menu-item {{ request()->routeIs('admin.attributes.' . $route) ? 'active' : '' }}">
                <i class="fa fa-tag"></i> {{ $label }}
            </a>
        @endforeach

        <!-- ⚙️ Settings -->
        @if($isSuperAdmin || $isAdmin)
            <div class="menu-label">System</div>

            <a href="{{ route('admin.settings.index') }}"
                class="menu-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fa fa-cog"></i> Site Settings
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
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn" style="background: none; border: none; cursor: pointer;">
                    <i class="fa fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // ✅ Toggle sub-menus
        document.querySelectorAll('.menu-item.has-sub').forEach(function (item) {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                var sub = this.nextElementSibling;
                if (sub && sub.classList.contains('sub-menu')) {
                    sub.classList.toggle('open');
                }
            });
        });
    });
</script>
