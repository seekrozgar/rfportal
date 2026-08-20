{{-- resources/views/employer/layouts/partials/sidebar.blade.php --}}
<div class="sidebar-wrapper">
    <div class="dashboard-card sidebar-card">
        {{-- ✅ User Info --}}
        <div class="sidebar-user">
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name ?? 'E', 0, 1)) }}
            </div>
            <div class="user-info">
                <h5 class="user-name">{{ Auth::user()->name ?? 'Employer' }}</h5>
                <small class="user-email">{{ Auth::user()->email ?? '' }}</small>
            </div>
        </div>

        <hr>

        {{-- ✅ Menu --}}
        <ul class="sidebar-menu">
            {{-- 🏠 Dashboard --}}
            <li>
                <a href="{{ route('employer.dashboard') }}"
                    class="{{ request()->routeIs('employer.dashboard') ? 'active' : '' }}">
                    <i class="fa fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            {{-- 📋 Jobs (With Submenu) --}}
            <li class="has-submenu {{ request()->routeIs('employer.jobs.*') ? 'open' : '' }}">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fa fa-briefcase"></i>
                    <span>Jobs</span>
                    <i class="fa fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('employer.jobs.index') }}"
                            class="{{ request()->routeIs('employer.jobs.index') ? 'active' : '' }}">
                            <i class="fa fa-list"></i> All Jobs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.jobs.create') }}"
                            class="{{ request()->routeIs('employer.jobs.create') ? 'active' : '' }}">
                            <i class="fa fa-plus-circle"></i> Post New Job
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.jobs.index') }}?status=active"
                            class="{{ request('status') == 'active' ? 'active' : '' }}">
                            <i class="fa fa-check-circle"></i> Active Jobs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.jobs.index') }}?status=expired"
                            class="{{ request('status') == 'expired' ? 'active' : '' }}">
                            <i class="fa fa-clock"></i> Expired Jobs
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.jobs.index') }}?status=draft"
                            class="{{ request('status') == 'draft' ? 'active' : '' }}">
                            <i class="fa fa-file"></i> Draft Jobs
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 📥 Applications (With Submenu) --}}
            <li class="has-submenu {{ request()->routeIs('employer.applications.*') ? 'open' : '' }}">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fa fa-file-alt"></i>
                    <span>Applications</span>
                    <i class="fa fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('employer.applications.index') }}"
                            class="{{ request()->routeIs('employer.applications.index') ? 'active' : '' }}">
                            <i class="fa fa-inbox"></i> All Applications
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.applications.index') }}?status=pending"
                            class="{{ request('status') == 'pending' ? 'active' : '' }}">
                            <i class="fa fa-clock"></i> Pending
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.applications.index') }}?status=shortlisted"
                            class="{{ request('status') == 'shortlisted' ? 'active' : '' }}">
                            <i class="fa fa-star"></i> Shortlisted
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.applications.index') }}?status=interview"
                            class="{{ request('status') == 'interview' ? 'active' : '' }}">
                            <i class="fa fa-calendar"></i> Interview
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.applications.index') }}?status=hired"
                            class="{{ request('status') == 'hired' ? 'active' : '' }}">
                            <i class="fa fa-check"></i> Hired
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.applications.index') }}?status=rejected"
                            class="{{ request('status') == 'rejected' ? 'active' : '' }}">
                            <i class="fa fa-times"></i> Rejected
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 🏷️ CV Search --}}
            <li>
                <a href="{{ route('employer.talent.search') }}"
                    class="{{ request()->routeIs('employer.talent.*') ? 'active' : '' }}">
                    <i class="fa fa-search"></i>
                    <span>CV Search</span>
                </a>
            </li>

            {{-- 👤 Profile --}}
            <li>
                <a href="{{ route('employer.profile.edit') }}"
                    class="{{ request()->routeIs('employer.profile.*') ? 'active' : '' }}">
                    <i class="fa fa-building"></i>
                    <span>Company Profile</span>
                </a>
            </li>

            {{-- 💰 Packages (With Submenu) --}}
            <li
                class="has-submenu {{ request()->routeIs('employer.packages.*') || request()->routeIs('employer.subscriptions.*') ? 'open' : '' }}">
                <a href="javascript:void(0)" class="submenu-toggle">
                    <i class="fa fa-box"></i>
                    <span>Packages</span>
                    <i class="fa fa-chevron-right arrow"></i>
                </a>
                <ul class="submenu">
                    <li>
                        <a href="{{ route('employer.packages.index') }}"
                            class="{{ request()->routeIs('employer.packages.index') ? 'active' : '' }}">
                            <i class="fa fa-th-list"></i> All Packages
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.subscriptions.index') }}"
                            class="{{ request()->routeIs('employer.subscriptions.index') ? 'active' : '' }}">
                            <i class="fa fa-history"></i> Subscription History
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employer.subscriptions.active') }}"
                            class="{{ request()->routeIs('employer.subscriptions.active') ? 'active' : '' }}">
                            <i class="fa fa-check-circle"></i> Active Subscription
                        </a>
                    </li>
                </ul>
            </li>

            {{-- 📊 Analytics --}}
            <li>
                <a href="{{ route('employer.analytics.index') }}"
                    class="{{ request()->routeIs('employer.analytics.*') ? 'active' : '' }}">
                    <i class="fa fa-chart-bar"></i>
                    <span>Analytics</span>
                </a>
            </li>

            {{-- ⚙️ Settings --}}
            <li>
                <a href="{{ route('employer.settings.index') }}"
                    class="{{ request()->routeIs('employer.settings.*') ? 'active' : '' }}">
                    <i class="fa fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>

            <hr>

            {{-- 🚪 Logout --}}
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fa fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

{{-- ============================================ --}}
{{-- JavaScript for Submenu --}}
{{-- ============================================ --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const submenuToggles = document.querySelectorAll('.submenu-toggle');

            submenuToggles.forEach(function (toggle) {
                toggle.addEventListener('click', function (e) {
                    e.preventDefault();
                    const parentLi = this.closest('.has-submenu');
                    if (parentLi) {
                        parentLi.classList.toggle('open');
                        const arrow = this.querySelector('.arrow');
                        if (arrow) {
                            arrow.style.transform = parentLi.classList.contains('open') ? 'rotate(90deg)' : 'rotate(0deg)';
                        }
                    }
                });
            });

            document.querySelectorAll('.has-submenu').forEach(function (li) {
                if (li.querySelector('ul.submenu .active')) {
                    li.classList.add('open');
                    const arrow = li.querySelector('.arrow');
                    if (arrow) {
                        arrow.style.transform = 'rotate(90deg)';
                    }
                }
            });
        });
    </script>
@endpush
