<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Rozgar Finder - Register">
    <meta name="author" content="Rozgar Finder">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Register - {{ siteName() }}</title>

    <!-- ✅ Vite - Bootstrap 5 + Custom CSS -->
    @vite(['resources/css/app.css', 'resources/css/frontend.css', 'resources/js/app.js'])

    <!-- ✅ Page-specific styles -->
    <style>
        .auth-wrapper .auth-container {
            max-width: 790px;
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-card-header">
                    <img src="{{ sitelogo() }}" alt="{{ siteName() }}" class="auth-logo">
                    <hr>
                    <h2>Create Your Account</h2>
                    <p>Start your journey to find the perfect job or talent</p>
                </div>
                @if(isRegistrationEnabled())
                    <div class="auth-card-body">

                        <!-- ✅ Flash Messages -->
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

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- ✅ ROLE SELECTION BUTTONS -->
                            <div class="form-group">
                                <div class="name">I want to register as</div>
                                <div class="role-buttons">
                                    <label class="role-btn {{ old('role') == 'seeker' ? 'active' : '' }}" id="seeker-btn">
                                        <input type="radio" name="role" value="seeker" {{ old('role') == 'seeker' ? 'checked' : '' }}>
                                        <span class="icon">👤</span>
                                        <span class="label">Job Seeker</span>
                                        <small>Find your dream job</small>
                                    </label>
                                    <label class="role-btn {{ old('role') == 'employer' ? 'active' : '' }}"
                                        id="employer-btn">
                                        <input type="radio" name="role" value="employer" {{ old('role') == 'employer' ? 'checked' : '' }}>
                                        <span class="icon">🏢</span>
                                        <span class="label">Employer</span>
                                        <small>Hire the best talent</small>
                                    </label>
                                </div>
                                @error('role')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ✅ NAME -->
                            <div class="form-group">
                                <div class="name">Full Name</div>
                                <input class="input--style-5 @error('name') is-invalid @enderror" type="text" name="name"
                                    value="{{ old('name') }}" placeholder="Enter your full name" required>
                                @error('name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ✅ EMAIL -->
                            <div class="form-group">
                                <div class="name">Email Address</div>
                                <input class="input--style-5 @error('email') is-invalid @enderror" type="email" name="email"
                                    value="{{ old('email') }}" placeholder="Enter your email address" required>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ✅ PASSWORD WITH TOGGLE -->
                            <div class="form-group">
                                <div class="name">Password</div>
                                <div class="password-toggle-wrapper">
                                    <input class="input--style-5 @error('password') is-invalid @enderror" type="password"
                                        name="password" id="password" placeholder="Create a strong password" required>
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
                                    <input class="input--style-5 @error('password_confirmation') is-invalid @enderror"
                                        type="password" name="password_confirmation" id="password_confirmation"
                                        placeholder="Confirm your password" required>
                                    <button type="button" class="password-toggle-btn" id="toggleConfirmPassword"
                                        aria-label="Toggle password visibility">
                                        <i class="fa fa-eye" id="eyeIconConfirm"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ✅ COMPANY NAME (Optional - for Employers) -->
                            <div class="form-group" id="company-field"
                                style="{{ old('role') == 'employer' ? 'display: block;' : 'display: none;' }}">
                                <div class="name">Company Name <span class="text-muted small">(Optional)</span></div>
                                <input class="input--style-5 @error('company_name') is-invalid @enderror" type="text"
                                    name="company_name" value="{{ old('company_name') }}"
                                    placeholder="Enter your company name">
                                @error('company_name')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- ✅ SUBMIT BUTTON -->
                            <div class="mt-3">
                                <button class="btn-gradient" type="submit">
                                    <i class="fa fa-user-plus me-2"></i> Create Account
                                </button>
                            </div>

                            <!-- ✅ SOCIAL LOGIN -->
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

                            <!-- ✅ LOGIN LINK -->
                            <div class="auth-link">
                                Already have an account? <a href="{{ route('login') }}">Login</a>
                            </div>

                        </form>
                    </div>
                @else
                    <div class="alert alert-warning" style="margin: 20px;">
                        <i class="fas fa-exclamation-circle"></i>
                        Registration is currently disabled.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ✅ Custom JS for Role Selection + Password Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // ========== ROLE SELECTION ==========
            document.querySelectorAll('.role-btn').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    document.querySelectorAll('.role-btn').forEach(function (b) {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');
                    this.querySelector('input[type="radio"]').checked = true;

                    var role = this.querySelector('input[type="radio"]').value;
                    var companyField = document.getElementById('company-field');
                    if (role === 'employer') {
                        companyField.style.display = 'block';
                    } else {
                        companyField.style.display = 'none';
                    }
                });
            });

            // If role is already selected from old input
            var selectedRadio = document.querySelector('input[name="role"]:checked');
            if (selectedRadio) {
                var role = selectedRadio.value;
                var companyField = document.getElementById('company-field');
                if (role === 'employer') {
                    companyField.style.display = 'block';
                } else {
                    companyField.style.display = 'none';
                }
            }

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

            // ========== AUTO-HIDE ALERTS ==========
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