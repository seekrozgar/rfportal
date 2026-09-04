@extends('layouts.app')

@section('title', siteName() . ' - Find Your Dream Job')
@section('meta')
    <meta name="description" content="{{ siteName() }} - Pakistan's leading job portal. Find jobs, post jobs, and build your career.">
@endsection

@section('content')

{{-- ============================================================
    HERO SECTION
============================================================ --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center min-vh-75">
            <div class="col-lg-6 hero-content">
                <span class="hero-badge">🏆 Pakistan's #1 Job Portal</span>
                <h1 class="hero-title">
                    Find Your <span class="text-gradient">Dream Job</span> Today
                </h1>
                <p class="hero-subtitle">
                    Thousands of jobs from top companies across Pakistan.
                    Start your career journey with {{ siteName() }}.
                </p>

                {{-- Search Form --}}
                <form action="{{ route('jobs.index') }}" method="GET" class="hero-search">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input
                            type="text"
                            name="search"
                            class="search-input"
                            placeholder="Search jobs, companies, keywords..."
                            value="{{ request('search') }}"
                        >
                        <button type="submit" class="search-btn">
                            Find Jobs
                            <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </form>

                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">10K+</span>
                        <span class="stat-label">Active Jobs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">5K+</span>
                        <span class="stat-label">Companies</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">50K+</span>
                        <span class="stat-label">Candidates</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hero-image">
                <img src="{{ asset('images/hero-illustration.svg') }}" alt="Find Job" class="img-fluid">
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
    CATEGORIES SECTION
============================================================ --}}
<section class="categories-section py-5">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">Categories</span>
            <h2 class="section-title">Browse by <span class="text-gradient">Category</span></h2>
            <p class="section-subtitle">Find jobs in your preferred field</p>
        </div>

        <div class="categories-grid">
            @foreach($categories ?? [] as $category)
                <a href="{{ route('jobs.index', ['category' => $category->slug]) }}" class="category-card">
                    <div class="category-icon">
                        <i class="fas {{ $category->icon ?? 'fa-briefcase' }}"></i>
                    </div>
                    <h5 class="category-name">{{ $category->name }}</h5>
                    <span class="category-count">{{ $category->jobs_count ?? 0 }} Jobs</span>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ============================================================
    FEATURED JOBS
============================================================ --}}
<section class="jobs-section py-5 bg-light">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <span class="section-tag">Recent Jobs</span>
                <h2 class="section-title">Featured <span class="text-gradient">Opportunities</span></h2>
            </div>
            <a href="{{ route('jobs.index') }}" class="view-all-btn">
                View All Jobs <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="jobs-grid">
            @forelse($jobs ?? [] as $job)
                <div class="job-card">
                    <div class="job-card-header">
                        <div class="company-logo">
                            @if($job->company?->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" alt="{{ $job->company->name }}">
                            @else
                                <i class="fas fa-building"></i>
                            @endif
                        </div>
                        <span class="job-type">{{ $job->job_type ?? 'Full Time' }}</span>
                    </div>
                    <h5 class="job-title">
                        <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                    </h5>
                    <p class="job-company">{{ $job->company?->name ?? 'Confidential' }}</p>
                    <div class="job-meta">
                        <span><i class="fas fa-map-marker-alt"></i> {{ $job->location ?? 'Remote' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $job->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="job-tags">
                        @foreach($job->tags ?? [] as $tag)
                            <span class="job-tag">{{ $tag }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('jobs.show', $job->slug) }}" class="job-apply-btn">Apply Now</a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">No jobs available at the moment.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================================================
    WHY CHOOSE US
============================================================ --}}
<section class="features-section py-5">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">Why Choose Us</span>
            <h2 class="section-title">Why <span class="text-gradient">{{ siteName() }}</span>?</h2>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h5 class="feature-title">Thousands of Jobs</h5>
                <p class="feature-desc">Find jobs from top companies across all industries.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-check"></i>
                </div>
                <h5 class="feature-title">Verified Employers</h5>
                <p class="feature-desc">All employers are verified for your safety.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h5 class="feature-title">Easy Apply</h5>
                <p class="feature-desc">Apply to jobs with just one click.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h5 class="feature-title">Career Growth</h5>
                <p class="feature-desc">Get insights and tips for your career journey.</p>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
    CALL TO ACTION
============================================================ --}}
<section class="cta-section">
    <div class="container">
        <div class="cta-wrapper">
            <div class="cta-content">
                <span class="cta-tag">Ready to Start?</span>
                <h2 class="cta-title">Join Thousands of <span class="text-gradient">Job Seekers</span></h2>
                <p class="cta-desc">Create your profile and start applying to jobs today.</p>
                <a href="{{ route('register') }}" class="cta-btn">
                    Get Started Now
                    <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
            <div class="cta-image">
                <img src="{{ asset('images/cta-illustration.svg') }}" alt="Get Started">
            </div>
        </div>
    </div>
</section>

@endsection
