<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rozgar Finder - Confirm Password">
    <meta name="author" content="Rozgar Finder">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Confirm Password - {{ siteName() }}</title>

    <!-- ✅ Vite - Bootstrap 5 + Custom CSS -->
    @vite(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js'])

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <img src="{{ sitelogo() }}" alt="{{ siteName() }}" class="auth-logo">
                    <hr>
                    <h2>Confirm Password</h2>
                    <p>Security verification required</p>
                </div>
                <div class="auth-card-body">

                    <!-- ✅ Icon -->
                    <div class="auth-icon">
                        <i class="fa fa-shield-alt fa-icon"></i>
                    </div>

                    <!-- ✅ Description -->
                    <p style="color: #666; font-size: 14px; text-align: center; margin-bottom: 25px; line-height: 1.6;">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>

                    <!-- ✅ Flash Messages -->
                    @if(session('error'))
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <!-- ✅ Confirm Password Form -->
                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <!-- ✅ PASSWORD WITH TOGGLE -->
                        <div class="form-group">
                            <div class="name">Your Password</div>
                            <div class="password-toggle-wrapper">
                                <input class="input--style-5 @error('password') is-invalid @enderror" type="password"
                                    name="password" id="password" placeholder="Enter your current password" required
                                    autofocus>
                                <button type="button" class="password-toggle-btn" id="togglePassword"
                                    aria-label="Toggle password visibility">
                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ SUBMIT BUTTON -->
                        <button class="btn-gradient" type="submit">
                            <i class="fa fa-check-circle me-2"></i> Confirm Password
                        </button>

                        <!-- ✅ BACK LINK -->
                        <div class="auth-link mt-3">
                            <a href="{{ url()->previous() }}">
                                <i class="fa fa-arrow-left me-1"></i> Go Back
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== PASSWORD TOGGLE ==========
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

            // Auto-hide alerts
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