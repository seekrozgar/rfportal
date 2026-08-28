<aside class="admin-sidebar" id="adminSidebar">
    <!-- Brand -->
    <div class="brand">
        <div>
            @if(siteLogo())
                <img src="{{ siteLogo() }}" alt="{{ siteName() }}" class="admin-panel-img">
            @endif
            <!-- <h3><i class="fa fa-briefcase me-2"></i>{{ __t('RF Portal') }}</h3> -->
            <small>{{ __t('Admin Panel') }} v1.0</small>
        </div>
        <button class="sidebar-toggle" id="closeSidebar">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <!-- Menu -->
    <nav class="menu" id="adminMenu">
        @php
            $user = auth()->user();
            $isSuperAdmin = $user->hasRole('superadmin');
            $isAdmin = $user->hasRole('admin');
            $isAuthor = $user->hasRole('author');
            $currentRoute = request()->route()->getName();
            use \App\Models\ActivityLog;
        @endphp

        <!-- 🏠 Main -->
        <div class="menu-label">{{ __t('Main') }}</div>
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ $currentRoute == 'admin.dashboard' ? 'active' : '' }}">
            <i class="fa fa-tachometer-alt"></i>
            <span class="menu-text">{{ __t('Dashboard') }}</span>
        </a>

        @if($isSuperAdmin || $isAdmin)
            <a href="{{ route('admin.users.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.users.') ? 'active' : '' }}">
                <i class="fa fa-users-cog"></i>
                <span class="menu-text">{{ __t('Admin Users') }}</span>
            </a>
        @endif

        <!-- 📦 Job Management -->
        <div class="menu-label">{{ __t('Job Management') }}</div>

        <a href="{{ route('admin.job-postings.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.job-postings.') ? 'active' : '' }}">
            <i class="fa fa-briefcase"></i>
            <span class="menu-text">{{ __t('Jobs') }}</span>
        </a>

        <a href="{{ route('admin.job-categories.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.job-categories.') ? 'active' : '' }}">
            <i class="fa fa-file"></i>
            <span class="menu-text">{{ __t('Job Categories') }}</span>
        </a>
        <!-- Companies Management -->
        <div class="menu-label">{{ __t('Companies Management')}}</div>

        <a href="{{ route('admin.company-verifications.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.company-verifications.') ? 'active' : '' }}">
            <i class="fas fa-shield-alt"></i>
            <span class="menu-text">{{ __t('Company Verification') }}</span>
            @php
                $pendingVerificationCount =
                    \App\Models\Company::where(
                        'verification_status',
                        'pending'
                    )->count();
            @endphp

            @if($pendingVerificationCount > 0)

                <span class="badge bg-warning text-dark ms-auto">
                    {{ $pendingVerificationCount }}
                </span>

            @endif

        </a>

        <!-- 📝 Education -->
        <div class="menu-label">{{ __t('Education') }}</div>

        <a href="{{ route('admin.scholarships.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.scholarships.') ? 'active' : '' }}">
            <i class="fa fa-graduation-cap"></i>
            <span class="menu-text">{{ __t('Scholarships') }}</span>
        </a>

        <a href="{{ route('admin.admissions.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.admissions.') ? 'active' : '' }}">
            <i class="fa fa-university"></i>
            <span class="menu-text">{{ __t('Admissions') }}</span>
        </a>

        <a href="{{ route('admin.results.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.results.') ? 'active' : '' }}">
            <i class="fa fa-percent"></i>
            <span class="menu-text">{{ __t('Results') }}</span>
        </a>

        <a href="{{ route('admin.news.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.news.') ? 'active' : '' }}">
            <i class="fa fa-newspaper"></i>
            <span class="menu-text">{{ __t('News / Announcements') }}</span>
        </a>

        <!-- 👥 Users -->
        <div class="menu-label">{{ __t('Users') }}</div>

        <a href="{{ route('admin.users.profiles') }}"
            class="menu-item {{ $currentRoute == 'admin.users.profiles' ? 'active' : '' }}">
            <i class="fa fa-user-tie"></i>
            <span class="menu-text">{{ __t('User Profiles') }}</span>
        </a>

        <!-- 📝 Content Management -->
        <div class="menu-label">{{ __t('Content') }}</div>

        <a href="{{ route('admin.seo.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.seo.') ? 'active' : '' }}">
            <i class="fa fa-search"></i>
            <span class="menu-text">{{ __t('SEO') }}</span>
        </a>

        <a href="{{ route('admin.faqs.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.faqs.') ? 'active' : '' }}">
            <i class="fa fa-question-circle"></i>
            <span class="menu-text">{{ __t('FAQs') }}</span>
        </a>

        <!-- 🌍 Translation -->
        <div class="menu-label">{{ __t('Translation') }}</div>

        <a href="{{ route('admin.languages.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.languages.') ? 'active' : '' }}">
            <i class="fa fa-language"></i>
            <span class="menu-text">{{ __t('Languages') }}</span>
        </a>

        @if($isSuperAdmin)
            <!-- 🌍 Location -->
            <div class="menu-label">{{ __t('Location') }}</div>

            <a href="{{ route('admin.location.countries.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.location.countries.') ? 'active' : '' }}">
                <i class="fa fa-flag"></i>
                <span class="menu-text">{{ __t('Countries') }}</span>
            </a>

            <a href="{{ route('admin.location.states.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.location.states.') ? 'active' : '' }}">
                <i class="fa fa-map-marker-alt"></i>
                <span class="menu-text">{{ __t('States') }}</span>
            </a>

            <a href="{{ route('admin.location.cities.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.location.cities.') ? 'active' : '' }}">
                <i class="fa fa-city"></i>
                <span class="menu-text">{{ __t('Cities') }}</span>
            </a>
        @endif

        <!-- 💰 Packages & Payments -->
        <div class="menu-label">{{ __t('Packages') }}</div>

        <a href="{{ route('admin.packages.index') }}"
            class="menu-item {{ str_starts_with($currentRoute, 'admin.packages.') ? 'active' : '' }}">
            <i class="fa fa-box"></i>
            <span class="menu-text">{{ __t('Packages') }}</span>
        </a>

        <a href="{{ route('admin.payments.company') }}"
            class="menu-item {{ $currentRoute == 'admin.payments.company' ? 'active' : '' }}">
            <i class="fa fa-building"></i>
            <span class="menu-text">{{ __t('Company Payments') }}</span>
        </a>

        <a href="{{ route('admin.payments.seeker') }}"
            class="menu-item {{ $currentRoute == 'admin.payments.seeker' ? 'active' : '' }}">
            <i class="fa fa-user"></i>
            <span class="menu-text">{{ __t('Seeker Payments') }}</span>
        </a>

        <!-- 🏷️ Job Attributes - COLLAPSIBLE SUBMENU -->
        <div class="menu-label">{{ __t('Job Attributes') }}</div>

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

        <!-- ✅ Parent Menu Item with Attractive Arrow -->
        <a href="#" class="menu-item has-sub" onclick="event.preventDefault(); toggleSubMenu(this);">
            <i class="fa fa-tags"></i>
            <span class="menu-text">{{ __t('Job Attributes') }}</span>
            <span class="arrow" id="attributesArrow">▶</span>
        </a>

        <!-- ✅ Sub-menu Items -->
        <div class="sub-menu" id="attributesSubMenu">
            @foreach($attributes as $route => $attr)
                <a href="{{ route('admin.attributes.' . $route) }}"
                    class="sub-item {{ str_starts_with($currentRoute, 'admin.attributes.' . $route) ? 'active' : '' }}">
                    <i class="{{ $attr['icon'] }}"></i>
                    <span>{{ __t($attr['label']) }}</span>
                </a>
            @endforeach
        </div>

        <!-- ⚙️ Settings -->
        @if($isSuperAdmin || $isAdmin)
            <div class="menu-label">{{ __t('System') }}</div>

            <a href="{{ route('admin.settings.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.settings.') ? 'active' : '' }}">
                <i class="fa fa-cog"></i>
                <span class="menu-text">{{ __t('Site Settings') }}</span>
            </a>

            <a href="{{ route('admin.profile.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.profile') ? 'active' : '' }}">
                <i class="fa fa-user"></i>
                <span class="menu-text">{{ __t('My Profile') }}</span>
            </a>

            <a href="{{ route('admin.change-password.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.change-password') ? 'active' : '' }}">
                <i class="fa fa-key"></i>
                <span class="menu-text">{{ __t('Change Password') }}</span>
            </a>

            <a href="{{ route('admin.notifications.index') }}"
                class="menu-item {{ str_starts_with($currentRoute, 'admin.notifications.') ? 'active' : '' }}">
                <i class="fa fa-bell"></i>
                <span class="menu-text">{{ __t('Notifications') }}</span>
                @php
                    $unreadCount = ActivityLog::whereNull('read_at')->count();
                @endphp
                @if($unreadCount > 0)
                    <span class="badge">{{ $unreadCount }}</span>
                @endif
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
