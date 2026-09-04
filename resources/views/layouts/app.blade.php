<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', siteName() . ' - Find Your Dream Job')</title>

    @if(siteFavicon())
        <link rel="icon" type="image/png" href="{{ siteFavicon() }}">
    @endif

    @vite(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js'])

    @stack('styles')

    {{-- Meta Tags for SEO --}}
    @yield('meta')
</head>
<body class="frontend-body">

    {{-- Header --}}
    @include('partials.header')

    {{-- Main Content --}}
    <main class="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')

    @stack('scripts')

    {{-- Toast Container --}}
    <div id="toastContainer"></div>

    <script>
        // Global toast function
        function showToast(type, message, title = null) {
            let container = document.getElementById('toastContainer');

            if (!container) {
                container = document.createElement('div');
                container.id = 'toastContainer';
                Object.assign(container.style, {
                    position: 'fixed',
                    top: '24px',
                    right: '24px',
                    zIndex: '999999',
                    width: 'min(390px, calc(100vw - 32px))',
                    pointerEvents: 'none'
                });
                document.body.appendChild(container);
            }

            const meta = {
                success: { title: title || 'Success', icon: 'fa-check', iconClass: 'success' },
                error: { title: title || 'Error', icon: 'fa-times', iconClass: 'error' },
                warning: { title: title || 'Warning', icon: 'fa-exclamation', iconClass: 'warning' },
                info: { title: title || 'Information', icon: 'fa-info', iconClass: 'info' }
            };

            const item = meta[type] || meta.info;
            const toast = document.createElement('div');
            // ... toast implementation
        }
        window.showToast = showToast;
    </script>
</body>
</html>
