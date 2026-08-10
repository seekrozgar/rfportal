<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rozgar Finder - Forgot Password">
    <meta name="author" content="Rozgar Finder">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Forgot Password - Rozgar Finder</title>

    <!-- ✅ Vite - Bootstrap 5 + Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h2>Forgot Password</h2>
                    <p>We'll send you a reset link</p>
                </div>
                <div class="auth-card-body">

                    <!-- ✅ Icon -->
                    <div class="auth-icon">
                        <i class="fa fa-key fa-icon"></i>
                    </div>

                    <!-- ✅ Description -->
                    <p style="color: #666; font-size: 14px; text-align: center; margin-bottom: 25px; line-height: 1.6;">
                        Enter your email address and we'll send you a link to reset your password.
                    </p>

                    <!-- ✅ Flash Messages -->
                    @if(session('status'))
                        <div class="alert-custom alert-success">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert-custom alert-danger">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- ✅ Forgot Password Form -->
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- ✅ EMAIL -->
                        <div class="form-group">
                            <div class="name">Email Address</div>
                            <input class="input--style-5 @error('email') is-invalid @enderror" type="email" name="email"
                                value="{{ old('email') }}" placeholder="Enter your registered email address" required
                                autofocus>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- ✅ SUBMIT BUTTON -->
                        <button class="btn-gradient" type="submit">
                            <i class="fa fa-paper-plane me-2"></i> Send Reset Link
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
