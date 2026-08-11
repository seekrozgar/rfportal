<!-- ✅ Debug: Check if view is rendering -->
@php
    Log::info('📄 VERIFY EMAIL VIEW RENDERED', [
        'unverifiedEmail' => $unverifiedEmail ?? 'not set',
        'userId' => $userId ?? 'not set',
        'session_unverified_id' => session('unverified_user_id'),
        'is_logged_in' => auth()->check(),
    ]);
@endphp
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

    <style>
        .auth-wrapper .auth-container {
            max-width: 600px;
        }

        .resend-btn {
            background: var(--gradient-main);
            color: #fff;
            border: none;
            padding: 12px 30px;
            border-radius: var(--radius-button);
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-family);
        }

        .resend-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(56, 239, 125, 0.4);
            color: #fff;
        }

        .resend-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .logout-btn {
            background: transparent;
            color: #666;
            border: 1px solid #ddd;
            padding: 10px 25px;
            border-radius: var(--radius-button);
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-family);
        }

        .logout-btn:hover {
            background: #f5f5f5;
            border-color: #bbb;
        }

        .status-message {
            padding: 12px 20px;
            border-radius: var(--radius-input);
            margin-bottom: 15px;
            text-align: center;
            font-size: 14px;
        }

        .status-message.success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-message.error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-message.info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .check-inbox-text {
            color: #666;
            font-size: 14px;
            text-align: center;
            margin: 15px 0;
            line-height: 1.6;
        }

        .check-inbox-text strong {
            color: #333;
        }

        .verify-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .resend-info {
            font-size: 13px;
            color: #999;
            margin-top: 15px;
            text-align: center;
        }

        .email-icon-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-icon-wrapper .fa-envelope {
            font-size: 64px;
            background: var(--gradient-main);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: var(--primary-color);
        }

        /* ✅ Login link for unverified users */
        .back-to-login {
            text-align: center;
            margin-top: 15px;
        }

        .back-to-login a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }
    </style>
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

                    <!-- Icon -->
                    <div class="email-icon-wrapper">
                        <i class="fa fa-envelope"></i>
                    </div>

                    <!-- Status Messages -->
                    @if(session('error'))
                        <div class="status-message error">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('status') == 'verification-link-sent')
                        <div class="status-message success">
                            <i class="fa fa-check-circle me-2"></i>
                            {{ __('A new verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    <!-- ✅ Check Your Inbox Message -->
                    <div class="check-inbox-text">
                        <i class="fa fa-inbox me-2" style="color: var(--primary-color);"></i>
                        We've sent a verification link to <strong>{{ $unverifiedEmail ?? 'your email' }}</strong>.<br>
                        Please check your inbox and click the link to verify your account.
                    </div>

                    <!-- ✅ Action Buttons -->
                    <div class="verify-actions">
                        <!-- Resend Verification Email -->
                        <form method="POST" action="{{ route('verification.send') }}" id="resendForm">
                            @csrf
                            <button type="submit" class="resend-btn" id="resendBtn">
                                <i class="fa fa-paper-plane"></i>
                                {{ __('Resend Verification Email') }}
                            </button>
                        </form>
                    </div>

                    <!-- ✅ Resend Info -->
                    <div class="resend-info">
                        <i class="fa fa-clock me-1"></i>
                        Didn't receive the email? Check your spam folder or click the button above to resend.
                    </div>

                    <!-- ✅ Back to Login -->
                    <div class="back-to-login">
                        <a href="{{ route('login') }}">
                            <i class="fa fa-arrow-left me-1"></i> Back to Login
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Custom JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const resendBtn = document.getElementById('resendBtn');
            const resendForm = document.getElementById('resendForm');

            if (resendBtn && resendForm) {
                resendForm.addEventListener('submit', function (e) {
                    resendBtn.disabled = true;
                    resendBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

                    setTimeout(function () {
                        resendBtn.disabled = false;
                        resendBtn.innerHTML = '<i class="fa fa-paper-plane"></i> Resend Verification Email';
                    }, 30000);
                });
            }

            document.querySelectorAll('.status-message').forEach(function (alert) {
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
