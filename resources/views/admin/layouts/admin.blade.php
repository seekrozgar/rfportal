<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @hasSection('title')
            @yield('title') - {{ siteName() }}
        @else
            {{ siteName() }} - Admin
        @endif
    </title>

    @if(siteFavicon())
        <link rel="icon" type="image/png" href="{{ siteFavicon() }}">
    @endif

    @vite(['resources/css/app.css', 'resources/css/admin.css', 'resources/js/app.js'])

    @stack('head')
</head>

<body class="admin-body">

    <div class="admin-overlay" id="adminOverlay"></div>

    <div class="admin-wrapper">

        {{-- Sidebar --}}
        @include('admin.layouts.partials.sidebar')

        <div class="admin-content" id="adminContent">

            {{-- Topbar --}}
            @include('admin.layouts.partials.topbar')

            {{-- Page Content --}}
            <main class="admin-page-content">
                @yield('content')
            </main>

        </div>
    </div>

    {{-- Global Scripts --}}
    @include('admin.layouts.partials.scripts')

    @stack('scripts')

</body>

</html>