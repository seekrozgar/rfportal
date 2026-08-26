<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rozgar Finder - Login">
    <meta name="author" content="Rozgar Finder">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @section('title', 'Login')
    @section('page-title', 'Login')
    @section('page-subtitle', 'Login your account')

    <!-- ✅ Vite - Single CSS file -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <img src="{{ sitelogo() }}" alt="{{ siteName() }}" class="auth-logo">
                    <hr>
                    <h2>Welcome Back</h2>
                    <p>Login to your account to continue</p>
                </div>
                <div class="auth-card-body">

                    <!-- Icon -->
                    <div class="auth-icon">
                        <i class="fa fa-user-circle fa-icon"></i>
                    </div>

                    <!-- Flash Messages -->
                    @if(session('error'))
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if(session('success'))
                        <div class="alert-custom alert-success">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="form-group">
                            <div class="name">Email Address</div>
                            <input class="input--style-5 @error('email') is-invalid @enderror" type="email" name="email"
                                value="{{ old('email') }}" placeholder="Enter your email address" required autofocus>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password with Toggle -->
                        <div class="form-group">
                            <div class="name">Password</div>
                            <div class="password-toggle-wrapper">
                                <input class="input--style-5 @error('password') is-invalid @enderror" type="password"
                                    name="password" id="password" placeholder="Enter your password" required>
                                <button type="button" class="password-toggle-btn" id="togglePassword">
                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="forgot-link" href="{{ route('password.request') }}">
                                    <i class="fa fa-key me-1"></i> Forgot Password?
                                </a>
                            @endif
                        </div>

                        <!-- Submit -->
                        <button class="btn-gradient" type="submit">
                            <i class="fa fa-sign-in-alt me-2"></i> Login
                        </button>

                        <!-- Social Login -->
                        <div class="social-login">
                            <div class="divider"><span>Or</span></div>
                            <div class="social-buttons">
                                <a href="{{ route('social.redirect', 'google') }}" class="social-btn google">
                                    <i class="fab fa-google"></i> Google
                                </a>
                                <a href="{{ route('social.redirect', 'github') }}" class="social-btn github">
                                    <i class="fab fa-github"></i> GitHub
                                </a>
                            </div>
                        </div>

                        <div class="auth-link">
                            Don't have an account? <a href="{{ route('register') }}">Create one now</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Password Toggle JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggleBtn = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    eyeIcon.classList.toggle('fa-eye');
                    eyeIcon.classList.toggle('fa-eye-slash');
                });
            }

            // Auto-hide alerts after 5 seconds
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