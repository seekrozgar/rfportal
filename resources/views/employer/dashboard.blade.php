<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employer Dashboard - Rozgar Finder</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1>Employer Dashboard</h1>
                <p>Welcome, {{ Auth::user()->name ?? 'Employer' }}!</p>

                <div class="mt-4">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="{{ route('employer.jobs.index') }}">Manage Jobs</a></li>
                        <li><a href="{{ route('employer.applications.index') }}">View Applications</a></li>
                        <li><a href="{{ route('employer.profile.edit') }}">Edit Profile</a></li>
                        <li><a href="{{ route('employer.packages.index') }}">Packages</a></li>
                    </ul>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
