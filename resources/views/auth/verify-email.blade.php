<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rozgar Finder - Verify Email">
    <meta name="author" content="Rozgar Finder">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email - Rozgar Finder</title>

    <!-- ✅ Vite - Bootstrap 5 + Custom CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <h2>Verify Your Email</h2>
                    <p>One last step to activate your account</p>
                </div>
                <div class="auth-card-body">

                    <!-- ✅ Icon -->
                    <div class="auth-icon">
                        <i class="fa fa-envelope fa-icon"></i>
                    </div>

                    <!-- ✅ Info Message -->
                    <div class="alert-custom alert-info">
                        <i class="fa fa-info-circle me-2"></i>
                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?') }}
                    </div>

                    <!-- ✅ Success Message (after resend) -->
                    @if (session('status') == 'verification-link-sent')
                        <div class="alert-custom alert-success">
                            <i class="fa fa-check-circle me-2"></i>
                                    {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <!-- ✅ Action Buttons -->
                    <div class="d-flex gap-3 justify-content-center flex-wrap mt-3">
                    <!-- Resend Verification Email -->
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn-gradient" style="width: auto; padding: 12px 30px;">
                            <i class="fa fa-paper-plane me-2"></i>
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>

                        <!-- Logout -->
                        <form method=" POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="btn-outline-gradient">
                                    <i class="fa fa-sign-out-alt me-2"></i>
                                {{ __('Log Out') }}
                            </button>
                        </form>
                    </div>

                    <!-- ✅ Resend Info Text -->
                    <div class=" auth-link mt-3" style="font-size: 13px; color: #999;">
                                        <i class="fa fa-clock me-1"></i>
                                        Didn't receive the email? Check your spam folder or
                                        <a href="#"
                                            onclick="event.preventDefault(); document.querySelector('form[action=\" {{ route('verification.send') }}\"]').submit();">
                                            click here to resend
                                        </a>
                </div>

            </div>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-hide success message after 5 seconds
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
