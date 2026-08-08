<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rozgar Finder</title>
    <!-- Vite standard assets load karne parenge warna page kharab dikhega -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <section class="bg-primary p-3 p-md-4 p-xl-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-9 col-lg-7 col-xl-6 col-xxl-5">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-body p-3 p-md-4 p-xl-5">

                            <!-- Session Status (Laravel Messages) -->
                            @if (session('status'))
                                <div class="alert alert-success mb-4 rounded-3 shadow-sm" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-5">
                                        <h3>Log in</h3>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Login Form -->
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="row gy-3 overflow-hidden">

                                    <!-- Email Field -->
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                id="email" placeholder="name@example.com" value="{{ old('email') }}"
                                                required autofocus autocomplete="username">
                                            <label for="email" class="form-label">Email</label>

                                            @error('email')
                                                <div class="invalid-feedback mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Password Field -->
                                    <div class="col-12">
                                        <div class="form-floating mb-3">
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" id="password" placeholder="Password" required
                                                autocomplete="current-password">
                                            <label for="password" class="form-label">Password</label>

                                            @error('password')
                                                <div class="invalid-feedback mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Remember Me Checkbox -->
                                    <div class="col-12">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember"
                                                id="remember_me">
                                            <label class="form-check-label text-secondary" for="remember_me">
                                                Keep me logged in
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button class="btn bsb-btn-2xl btn-success" type="submit"
                                                style="background-color: var(--primary-color, #4CAF50); border: none;">Log
                                                in now</button>
                                        </div>
                                    </div>

                                </div>
                            </form>

                            <!-- Navigation Links -->
                            <div class="row">
                                <div class="col-12">
                                    <hr class="mt-5 mb-4 border-secondary-subtle">
                                    <div class="d-flex gap-2 gap-md-4 flex-column flex-md-row justify-content-md-end">
                                        <a href="{{ route('register') }}"
                                            class="link-secondary text-decoration-none hover-underline">Create new
                                            account</a>

                                        @if (Route::has('password.request'))
                                            <a href="{{ route('password.request') }}"
                                                class="link-secondary text-decoration-none hover-underline">Forgot
                                                password</a>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Social Login Buttons -->
                            <div class="row">
                                <div class="col-12">
                                    <p class="mt-5 mb-4 text-muted text-uppercase small font-semibold">Or continue with
                                    </p>
                                    <div class="d-flex gap-3 flex-column">

                                        <!-- Google Login (Dynamic URL) -->
                                        <a href="{{ route('social.redirect', 'google') }}"
                                            class="btn bsb-btn-xl btn-danger d-flex align-items-center justify-content-center py-2.5 social-click-btn">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-google me-2" viewBox="0 0 16 16">
                                                <path
                                                    d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z" />
                                            </svg>
                                            <span class="fs-6 text-uppercase font-medium">Sign in With Google</span>
                                        </a>

                                        <!-- GitHub Login (Dynamic URL) -->
                                        <a href="{{ route('social.redirect', 'github') }}"
                                            class="btn bsb-btn-xl btn-dark d-flex align-items-center justify-content-center py-2.5 social-click-btn"
                                            style="background-color: #24292e; border: none;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-github me-2" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.446-3.582-8.05-8-8z" />
                                            </svg>
                                            <span class="fs-6 text-uppercase font-medium">Sign in With GitHub</span>
                                        </a>

                                        <!-- Note: Facebook aur Twitter hata diye hain kyunki aap Google/GitHub use kar rahe hain -->

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

</body>

</html>