<header class="site-header">
    <div class="container-fluid px-4">
        <nav class="navbar navbar-expand-lg">
            {{-- Logo / Brand --}}
            <a class="navbar-brand" href="{{ route('home') }}">
                @if(siteLogo())
                    <img src="{{ siteLogo() }}" alt="{{ siteName() }}" class="site-logo">
                @else
                    <span class="brand-text">{{ siteName() }}</span>
                @endif
            </a>

            {{-- ✅ Mobile Toggle with proper attributes --}}
            <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbarNav"
                    aria-controls="navbarNav"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Nav Links --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('jobs.*') ? 'active' : '' }}" href="{{ route('jobs.index') }}">
                            <i class="fas fa-briefcase me-1"></i> Jobs
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                            <i class="fas fa-building me-1"></i> Companies
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                            <i class="fas fa-info-circle me-1"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </li>
                </ul>

                {{-- Auth Actions --}}
                <div class="nav-actions">
                    @auth
                        <a href="{{ route('dashboard') }}" class="btn btn-dashboard">
                            <i class="fas fa-user-circle me-1"></i> Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-register">
                            <i class="fas fa-user-plus me-1"></i> Register
                        </a>
                    @endauth

                    {{-- Post Job Button --}}
                    <a href="{{ route('login') }}" class="btn btn-post-job">
                        <i class="fas fa-plus-circle me-1"></i> Post A Job
                    </a>
                </div>
            </div>
        </nav>
    </div>
</header>
