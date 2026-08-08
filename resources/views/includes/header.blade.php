<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <span class="text-primary">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index') }}">Jobs</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('companies.index') }}">Companies</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ route('blog.index') }}">Blog</a></li>
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            @if(Auth::user()->role == 'superadmin' || Auth::user()->role == 'admin')
                                <a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Panel</a>
                            @elseif(Auth::user()->role == 'author')
                                <a class="dropdown-item" href="{{ route('author.dashboard') }}">Author Panel</a>
                            @elseif(Auth::user()->role == 'employer')
                                <a class="dropdown-item" href="{{ route('employer.dashboard') }}">Employer Panel</a>
                            @elseif(Auth::user()->role == 'seeker')
                                <a class="dropdown-item" href="{{ route('seeker.dashboard') }}">Seeker Panel</a>
                            @endif
                            <hr class="dropdown-divider">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>