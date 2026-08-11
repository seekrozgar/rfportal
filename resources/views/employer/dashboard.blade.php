<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employer Dashboard - Rozgar Finder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .dashboard-wrapper {
            min-height: 100vh;
            background: #f4f6f9;
            padding: 20px;
        }

        .dashboard-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            padding: 25px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.12);
        }

        .dashboard-card .card-icon {
            font-size: 40px;
            color: #11998e;
            margin-bottom: 10px;
        }

        .dashboard-card .card-number {
            font-size: 32px;
            font-weight: 700;
            color: #2c3e50;
            margin: 0;
        }

        .dashboard-card .card-label {
            font-size: 14px;
            color: #7f8c8d;
            margin: 0;
        }

        .dashboard-header {
            background: linear-gradient(to left, #11998e, #38ef7d);
            padding: 20px 30px;
            border-radius: 10px;
            margin-bottom: 25px;
            color: #fff;
        }

        .dashboard-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }

        .dashboard-header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }

        .quick-link-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            color: #333;
            border: 1px solid #eee;
        }

        .quick-link-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            border-color: #11998e;
            text-decoration: none;
            color: #11998e;
        }

        .quick-link-card .icon {
            font-size: 32px;
            display: block;
            margin-bottom: 10px;
            color: #11998e;
        }

        .quick-link-card .label {
            font-size: 14px;
            font-weight: 600;
        }

        .quick-link-card .desc {
            font-size: 12px;
            color: #999;
            margin-top: 3px;
        }

        .logout-btn {
            background: #e74c3c;
            color: #fff;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: #c0392b;
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(231, 76, 60, 0.3);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li {
            margin-bottom: 5px;
        }

        .sidebar-menu li a {
            display: block;
            padding: 10px 15px;
            border-radius: 8px;
            color: #555;
            text-decoration: none;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: rgba(17, 153, 142, 0.1);
            color: #11998e;
        }

        .sidebar-menu li a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(to left, #11998e, #38ef7d);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 700;
            margin-right: 15px;
        }

        .badge-pending {
            background: #ffc107;
            color: #212529;
        }

        .badge-shortlisted {
            background: #17a2b8;
            color: #fff;
        }

        .badge-hired {
            background: #28a745;
            color: #fff;
        }

        .badge-rejected {
            background: #dc3545;
            color: #fff;
        }

        .badge-interview {
            background: #6f42c1;
            color: #fff;
        }

        /* ✅ Company Missing Alert */
        .company-missing-alert {
            background: #fff3cd;
            border: 1px solid #ffeeba;
            color: #856404;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .company-missing-alert a {
            color: #11998e;
            font-weight: 600;
            text-decoration: none;
        }

        .company-missing-alert a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="dashboard-wrapper">
        <div class="container-fluid">
            <div class="row">
                <!-- ✅ Sidebar -->
                <div class="col-md-3 col-lg-2 d-md-block sidebar">
                    <div class="dashboard-card" style="padding: 20px; text-align: center;">
                        <div class="user-avatar" style="margin: 0 auto 15px;">
                            {{ strtoupper(substr(Auth::user()->name ?? 'E', 0, 1)) }}
                        </div>
                        <h5 style="margin: 0; font-weight: 700;">{{ Auth::user()->name ?? 'Employer' }}</h5>
                        <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                        <hr>
                        <ul class="sidebar-menu">
                            <li><a href="{{ route('employer.dashboard') }}" class="active"><i
                                        class="fa fa-tachometer-alt"></i> Dashboard</a></li>
                            <li><a href="{{ route('employer.jobs.index') }}"><i class="fa fa-briefcase"></i> Manage
                                    Jobs</a></li>
                            <li><a href="{{ route('employer.applications.index') }}"><i class="fa fa-users"></i>
                                    Applications</a></li>
                            <li><a href="{{ route('employer.profile.edit') }}"><i class="fa fa-user-cog"></i>
                                    Profile</a></li>
                            <li><a href="{{ route('employer.packages.index') }}"><i class="fa fa-box"></i> Packages</a>
                            </li>
                        </ul>
                        <hr>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="logout-btn" style="width: 100%;">
                                <i class="fa fa-sign-out-alt"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ✅ Main Content -->
                <div class="col-md-9 col-lg-10">
                    <!-- Header -->
                    <div class="dashboard-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h1><i class="fa fa-tachometer-alt me-2"></i> Employer Dashboard</h1>
                                <p>Welcome back, {{ Auth::user()->name ?? 'Employer' }}! Here's what's happening with
                                    your jobs.</p>
                            </div>
                            <div>
                                <a href="{{ route('employer.jobs.create') }}" class="btn btn-light"
                                    style="border-radius: 25px; font-weight: 600;">
                                    <i class="fa fa-plus-circle me-1"></i> Post New Job
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Company Missing Alert -->
                    @if(isset($companyMissing) && $companyMissing)
                        <div class="company-missing-alert">
                            <i class="fa fa-info-circle me-2"></i>
                            <strong>Complete Your Company Profile!</strong>
                            Please <a href="{{ route('employer.profile.edit') }}">update your company profile</a> to start
                            posting jobs.
                        </div>
                    @endif

                    <!-- ✅ Stats Cards -->
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="dashboard-card text-center">
                                <div class="card-icon"><i class="fa fa-briefcase"></i></div>
                                <h3 class="card-number">{{ $totalJobs ?? 0 }}</h3>
                                <p class="card-label">Total Jobs</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="dashboard-card text-center">
                                <div class="card-icon"><i class="fa fa-users"></i></div>
                                <h3 class="card-number">{{ $totalApplications ?? 0 }}</h3>
                                <p class="card-label">Total Applications</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="dashboard-card text-center">
                                <div class="card-icon"><i class="fa fa-eye"></i></div>
                                <h3 class="card-number">{{ $totalViews ?? 0 }}</h3>
                                <p class="card-label">Total Views</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="dashboard-card text-center">
                                <div class="card-icon"><i class="fa fa-star"></i></div>
                                <h3 class="card-number">{{ $featuredJobs ?? 0 }}</h3>
                                <p class="card-label">Featured Jobs</p>
                            </div>
                        </div>
                    </div>

                    <!-- ✅ Quick Links -->
                    <div class="row">
                        <div class="col-12">
                            <h4 style="margin-bottom: 15px; font-weight: 600; color: #2c3e50;">
                                <i class="fa fa-link me-2" style="color: #11998e;"></i> Quick Actions
                            </h4>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('employer.jobs.index') }}" class="quick-link-card">
                                <span class="icon"><i class="fa fa-list"></i></span>
                                <span class="label">My Jobs</span>
                                <span class="desc">View all your posted jobs</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('employer.jobs.create') }}" class="quick-link-card">
                                <span class="icon"><i class="fa fa-plus-circle"></i></span>
                                <span class="label">Post Job</span>
                                <span class="desc">Create a new job listing</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('employer.applications.index') }}" class="quick-link-card">
                                <span class="icon"><i class="fa fa-users"></i></span>
                                <span class="label">Applications</span>
                                <span class="desc">Review candidate applications</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <a href="{{ route('employer.profile.edit') }}" class="quick-link-card">
                                <span class="icon"><i class="fa fa-building"></i></span>
                                <span class="label">Company Profile</span>
                                <span class="desc">Update your company details</span>
                            </a>
                        </div>
                    </div>

                    <!-- ✅ Recent Applications -->
                    <div class="dashboard-card mt-3">
                        <h5 style="font-weight: 600; margin-bottom: 15px;">
                            <i class="fa fa-clock me-2" style="color: #11998e;"></i> Recent Applications
                        </h5>
                        @if(isset($recentApplications) && $recentApplications->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Candidate</th>
                                            <th>Job</th>
                                            <th>Applied On</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentApplications as $application)
                                            <tr>
                                                <td>{{ $application->seeker->name ?? 'N/A' }}</td>
                                                <td>{{ $application->job->title ?? 'N/A' }}</td>
                                                <td>{{ $application->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $application->status }}">
                                                        {{ ucfirst($application->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <a href="{{ route('employer.applications.show', $application) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted">No applications received yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.alert-custom').forEach(function (alert) {
                setTimeout(function () {
                    alert.style.transition = 'opacity 0.5s ease';
                    alert.style.opacity = '0';
                    setTimeout(function () { alert.style.display = 'none'; }, 500);
                }, 5000);
            });
        });
    </script>
</body>

</html>
