<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel - Rozgar Finder')</title>

</head>

<body class="admin-body">
    <!-- ✅ Overlay -->
    <div class="admin-overlay" id="adminOverlay"></div>

    <div class="admin-wrapper">
        <!-- ✅ Sidebar -->
        @include('admin.layouts.partials.sidebar')

        <!-- ✅ Main Content -->
        <div class="admin-content" id="adminContent">
            <!-- ✅ Top Header -->
            @include('admin.layouts.partials.topbar')

            <!-- ✅ Page Content -->
            <div class="admin-page-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- ✅ Scripts -->
    @include('admin.layouts.partials.scripts')
    @stack('scripts')
</body>

</html>