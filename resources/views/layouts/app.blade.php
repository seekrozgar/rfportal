{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      dir="{{ in_array(app()->getLocale(), ['ur', 'ar']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- ✅ SEO Meta Tags --}}
    <title>@yield('title', siteName())</title>
    <meta name="description" content="@yield('meta_description', metaDescription())">
    <meta name="keywords" content="@yield('meta_keywords', metaKeywords())">
    <meta name="author" content="@yield('meta_author', metaAuthor())">
    <meta name="robots" content="@yield('meta_robots', metaRobots())">

    {{-- ✅ Open Graph --}}
    <meta property="og:title" content="@yield('og_title', metaTitle())">
    <meta property="og:description" content="@yield('og_description', metaDescription())">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ siteName() }}">
    @if(siteLogo())
        <meta property="og:image" content="{{ siteLogo() }}">
    @endif

    {{-- ✅ Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', metaTitle())">
    <meta name="twitter:description" content="@yield('twitter_description', metaDescription())">
    @if(siteLogo())
        <meta name="twitter:image" content="{{ siteLogo() }}">
    @endif

    {{-- ✅ Favicon --}}
    @if(siteFavicon())
        <link rel="icon" href="{{ siteFavicon() }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ siteFavicon() }}" type="image/x-icon">
    @endif

    {{-- ✅ Canonical URL --}}
    <link rel="canonical" href="@yield('canonical', url()->current())">

    {{-- ✅ Google Analytics --}}
    @if(isAnalyticsEnabled() && analyticsMeasurementId())
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ analyticsMeasurementId() }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ analyticsMeasurementId() }}');
        </script>
    @endif

    {{-- ✅ Styles --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>
    {{-- ✅ Maintenance Mode Notice --}}
    @if(isMaintenanceMode())
        <div class="maintenance-banner bg-warning py-2">
            <div class="container text-center">
                <i class="fas fa-tools me-2"></i>
                <strong>Maintenance Mode:</strong> {{ siteSetting('maintenance_message', 'We are currently under maintenance.') }}
            </div>
        </div>
    @endif

    {{-- ✅ Header --}}
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    @if(siteLogo())
                        <img src="{{ siteLogo() }}" alt="{{ siteName() }}" height="40">
                    @else
                        <strong>{{ siteName() }}</strong>
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('jobs.index') }}">Jobs</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    {{-- ✅ Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- ✅ Footer --}}
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>{{ siteName() }}</h5>
                    <p class="text-muted">{{ siteTagline() }}</p>
                    <p class="text-muted small">
                        <i class="fas fa-map-marker-alt me-2"></i>{{ siteAddress() }}<br>
                        <i class="fas fa-phone me-2"></i>{{ sitePhone() }}<br>
                        <i class="fas fa-envelope me-2"></i>{{ siteEmail() }}
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="{{ route('jobs.index') }}" class="text-muted text-decoration-none">Jobs</a></li>
                        <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">About</a></li>
                        <li><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Follow Us</h5>
                    <div class="social-links">
                        @foreach(socialLinks() as $platform => $url)
                            <a href="{{ $url }}" target="_blank" class="text-white me-2">
                                <i class="fab fa-{{ $platform }} fa-lg"></i>
                            </a>
                        @endforeach
                    </div>
                    <p class="text-muted small mt-3">{{ copyrightText() }}</p>
                </div>
            </div>
        </div>
    </footer>

    {{-- ✅ Cookie Consent --}}
    @if(isCookieConsentEnabled())
        <div id="cookieConsent" class="cookie-consent fixed-bottom bg-dark text-white p-3" style="display: none;">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-9">
                        <p class="mb-0">{{ cookieConsentMessage() }}</p>
                    </div>
                    <div class="col-md-3 text-end">
                        <button class="btn btn-primary btn-sm" onclick="acceptCookies()">
                            <i class="fas fa-check me-1"></i> Accept
                        </button>
                        <button class="btn btn-outline-light btn-sm" onclick="rejectCookies()">
                            <i class="fas fa-times me-1"></i> Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ✅ Scripts --}}
    @stack('scripts')

    {{-- ✅ Cookie Consent Script --}}
    @if(isCookieConsentEnabled())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (!localStorage.getItem('cookie_consent')) {
                    document.getElementById('cookieConsent').style.display = 'block';
                }
            });

            function acceptCookies() {
                localStorage.setItem('cookie_consent', 'accepted');
                document.getElementById('cookieConsent').style.display = 'none';
            }

            function rejectCookies() {
                localStorage.setItem('cookie_consent', 'rejected');
                document.getElementById('cookieConsent').style.display = 'none';
            }
        </script>
    @endif

    {{-- ✅ Ads --}}
    @if(isAdsEnabled() && headerAdCode())
        <div class="ad-container text-center my-2">
            {!! headerAdCode() !!}
        </div>
    @endif
</body>
</html>
