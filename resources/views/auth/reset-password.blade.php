<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rozgar Finder - Reset Password">
    <meta name="author" content="Rozgar Finder">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Reset Password - {{ siteName() }}</title>

    <!-- ✅ Vite - Bootstrap 5 + Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <img src="{{ sitelogo() }}" alt="{{ siteName() }}" class="auth-logo">
                    <hr>
                    <h2>Reset Password</h2>
                    <p>Create a new password for your account</p>
                </div>
                <div class="auth-card-body">

                    <!-- ✅ Icon -->
                    <div class="auth-icon">
                        <i class="fa fa-lock fa-icon"></i>
                    </div>

                    <!-- ✅ Flash Messages -->
                    @if($errors->any())
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ $errors->first() }}
                        </div>
                    @endif
                    @if(session('status'))
                        <div class="alert-custom alert-success">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- ✅ Reset Password Form -->
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <!-- ✅ Token (hidden) -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- ✅ EMAIL -->
                        <div class="form-group">
                            <div class="name">Email Address</div>
                            <input class="input--style-5 @error('email') is-invalid @enderror" type="email" name="email"
                                value="{{ old('email', $request->email) }}" placeholder="Enter your email address"
                                required readonly>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ PASSWORD WITH TOGGLE -->
                        <div class="form-group">
                            <div class="name">New Password</div>
                            <div class="password-toggle-wrapper">
                                <input class="input--style-5 @error('password') is-invalid @enderror" type="password"
                                    name="password" id="password" placeholder="Enter new password" required>
                                <button type="button" class="password-toggle-btn" id="togglePassword"
                                    aria-label="Toggle password visibility">
                                    <i class="fa fa-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                            <div class="password-hint">Must be at least 8 characters with letters, numbers &amp; symbols
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ CONFIRM PASSWORD WITH TOGGLE -->
                        <div class="form-group">
                            <div class="name">Confirm Password</div>
                            <div class="password-toggle-wrapper">
                                <input class="input--style-5" type="password" name="password_confirmation"
                                    id="password_confirmation" placeholder="Confirm your new password" required>
                                <button type="button" class="password-toggle-btn" id="toggleConfirmPassword"
                                    aria-label="Toggle password visibility">
                                    <i class="fa fa-eye" id="eyeIconConfirm"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ✅ SUBMIT BUTTON -->
                        <button class="btn-gradient" type="submit">
                            <i class="fa fa-save me-2"></i> Reset Password
                        </button>

                        <!-- ✅ BACK TO LOGIN -->
                        <div class="auth-link mt-3">
                            <a href="{{ route('login') }}">
                                <i class="fa fa-arrow-left me-1"></i> Back to Login
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== PASSWORD TOGGLE (Main Password) ==========
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

            // ========== PASSWORD TOGGLE (Confirm Password) ==========
            const toggleConfirmBtn = document.getElementById('toggleConfirmPassword');
            const confirmInput = document.getElementById('password_confirmation');
            const eyeIconConfirm = document.getElementById('eyeIconConfirm');

            if (toggleConfirmBtn && confirmInput) {
                toggleConfirmBtn.addEventListener('click', function () {
                    const type = confirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    confirmInput.setAttribute('type', type);
                    eyeIconConfirm.classList.toggle('fa-eye');
                    eyeIconConfirm.classList.toggle('fa-eye-slash');
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