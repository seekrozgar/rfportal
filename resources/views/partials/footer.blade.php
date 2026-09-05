<footer class="site-footer">
    <div class="container">
        {{-- Main Footer --}}
        <div class="row g-4">
            {{-- Brand --}}
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-title">{{ siteName() }}</h5>
                <p class="footer-desc">
                    Pakistan's leading job portal connecting talented professionals
                    with top employers across the country.
                </p>
                <div class="social-links">
                    @php
                        // ✅ Get social links from SiteHelper or helper function
                        $socialLinks = [];
                        if (function_exists('socialLinks')) {
                            $socialLinks = socialLinks();
                        } elseif (class_exists('App\Helpers\SiteHelper')) {
                            $socialLinks = \App\Helpers\SiteHelper::get('social_links', []);
                        }

                        // ✅ Decode if string
                        if (is_string($socialLinks)) {
                            $socialLinks = json_decode($socialLinks, true) ?? [];
                        }
                    @endphp

                    @if(!empty($socialLinks) && is_array($socialLinks))
                        @foreach($socialLinks as $platform => $url)
                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer"
                                   class="social-link" aria-label="{{ ucfirst($platform) }}">
                                    <i class="fab fa-{{ $platform }}"></i>
                                </a>
                            @endif
                        @endforeach
                    @else
                        {{-- Default social links fallback --}}
                        <a href="#" class="social-link" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-link" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-link" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="social-link" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-link" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    @endif
                </div>
            </div>

            {{-- Quick Links --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-subtitle">Quick Links</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('jobs.index') }}">Browse Jobs</a></li>
                    <li><a href="{{ route('companies.index') }}">Companies</a></li>
                    <li><a href="{{ route('about') }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}">Contact Us</a></li>
                </ul>
            </div>

            {{-- For Employers --}}
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-subtitle">For Employers</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('register') }}">Post a Job</a></li>
                    <li><a href="{{ route('register') }}">Find Talent</a></li>
                    <li><a href="#">Resources</a></li>
                </ul>
            </div>

            {{-- Newsletter --}}
            <div class="col-lg-4 col-md-6">
                <h6 class="footer-subtitle">Stay Updated</h6>
                <p class="footer-desc">
                    Get the latest job opportunities and career tips.
                </p>
                <form action="#" method="POST" class="newsletter-form">
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Your email address" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
                <p class="newsletter-note">
                    <i class="fas fa-lock text-muted me-1"></i>
                    We respect your privacy. No spam.
                </p>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="footer-bottom">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">
                        &copy; {{ date('Y') }}
                        <span class="footer-company-name">{{ siteName() }}</span>.
                        All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <ul class="footer-bottom-links">
                        <li><a href="{{ route('privacy') }}">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}">Terms of Service</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
