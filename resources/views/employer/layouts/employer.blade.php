{{-- resources/views/employer/layouts/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @hasSection('title')
            @yield('title') - {{ siteName() }}
        @else
            {{ siteName() }} - Employer
        @endif
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body>
    <div class="dashboard-wrapper">
        <div class="container-fluid">
            <div class="row g-0">
                {{-- ✅ Sidebar --}}
                <div class="col-12 col-md-3 col-lg-2 sidebar-col">
                    @include('employer.layouts.partials.sidebar')
                </div>

                {{-- ✅ Main Content --}}
                <div class="col-12 col-md-9 col-lg-10 main-content-col">
                    {{-- ✅ Header --}}
                    @include('employer.layouts.partials.header')

                    {{-- ✅ Page Content --}}
                    <div class="page-content-wrapper">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>