{{-- Without Stats Chips --}}
<div class="dashboard-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h1><i class="fa fa-tachometer-alt me-2"></i> @yield('page-title', 'Employer Dashboard')</h1>
            <p>@yield('page-subtitle', 'Welcome back, ' . Auth::user()->name . '! Here\'s what\'s happening with your jobs.')
            </p>
        </div>
        <div>
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-light">
                <i class="fa fa-plus-circle me-1"></i> Post New Job
            </a>
        </div>
    </div>
</div>
