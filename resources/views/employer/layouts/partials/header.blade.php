{{-- Without Stats Chips --}}
{{-- resources/views/employer/partials/dashboard-header.blade.php --}}

<div class="dashboard-header">
    <div class="dashboard-header-inner">

        {{-- Left: Page Title --}}
        <div class="dashboard-header-content">
            <h1>
                <i class="fa fa-tachometer-alt me-2"></i>
                @yield('page-title', 'Employer Dashboard')
            </h1>

            <p>
                @yield(
                    'page-subtitle',
                    'Welcome back, ' . Auth::user()->name . '! Here\'s what\'s happening with your jobs.'
                )
            </p>
        </div>

        {{-- Right: Actions --}}
        <div class="dashboard-header-actions">

            {{-- Notification Bell --}}
            <div class="employer-notification-wrapper">
                @include('components.notification-bell')
            </div>

            {{-- Post Job --}}
            <a href="{{ route('employer.jobs.create') }}" class="btn btn-light employer-post-job-btn">
                <i class="fa fa-plus-circle me-1"></i>
                Post New Job
            </a>

        </div>

    </div>
</div>