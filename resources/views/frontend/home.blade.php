@extends('layouts.app')

@section('content')

{{-- ============================================================
    HERO SECTION
============================================================ --}}
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <span class="hero-badge">
                    <i class="fas fa-trophy me-2"></i> Pakistan's #1 Job Portal
                </span>
                <h1 class="hero-title">
                    Find Your <span class="text-gradient">Dream Job</span> Today
                </h1>
                <p class="hero-subtitle">
                    Join thousands of job seekers and find your perfect career with
                    <strong>{{ siteName() }}</strong>. Browse jobs from top companies across Pakistan.
                </p>

                {{-- ✅ Search Form - Fully Functional --}}
                <form action="{{ route('jobs.index') }}" method="GET" class="hero-search">
                    <div class="search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input
                            type="text"
                            name="search"
                            class="search-input"
                            placeholder="Search jobs, companies, keywords..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                        >
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search me-2"></i>
                            Find Jobs
                        </button>
                    </div>

                    {{-- Quick Search Suggestions --}}
                    <div class="search-suggestions">
                        <span class="suggestion-label">Popular:</span>
                        <a href="{{ route('jobs.index', ['search' => 'Software Engineer']) }}" class="suggestion-tag">
                            Software Engineer
                        </a>
                        <a href="{{ route('jobs.index', ['search' => 'Lahore']) }}" class="suggestion-tag">
                            Lahore
                        </a>
                        <a href="{{ route('jobs.index', ['search' => 'Remote']) }}" class="suggestion-tag">
                            Remote
                        </a>
                        <a href="{{ route('jobs.index', ['search' => 'Finance']) }}" class="suggestion-tag">
                            Finance
                        </a>
                    </div>
                </form>

                {{-- ✅ Live Stats from Database --}}
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($stats['active_jobs'] ?? 0) }}+</span>
                        <span class="stat-label">Active Jobs</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($stats['companies'] ?? 0) }}+</span>
                        <span class="stat-label">Companies</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($stats['seekers'] ?? 0) }}+</span>
                        <span class="stat-label">Job Seekers</span>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hero-image">
                <div class="hero-illustration">
                    <img src="{{ asset('images/hero-illustration.svg') }}" alt="Find Job" class="img-fluid">

                    {{-- Floating Elements --}}
                    <div class="floating-card card-1">
                        <i class="fas fa-briefcase"></i>
                        <span>{{ number_format($stats['active_jobs'] ?? 0) }}+ Jobs</span>
                    </div>
                    <div class="floating-card card-2">
                        <i class="fas fa-building"></i>
                        <span>{{ number_format($stats['companies'] ?? 0) }}+ Companies</span>
                    </div>
                    <div class="floating-card card-3">
                        <i class="fas fa-users"></i>
                        <span>{{ number_format($stats['seekers'] ?? 0) }}+ Candidates</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
    2. GENERAL ADS JOBS (Before Categories)
============================================================ --}}
@if(isset($generalJobs) && $generalJobs->count() > 0)
<section class="jobs-section py-5">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <span class="section-tag">
                    <i class="fas fa-bullhorn me-2"></i> Featured Ads
                </span>
                <h2 class="section-title">General <span class="text-gradient">Opportunities</span></h2>
                <p class="section-subtitle">Sponsored and general job advertisements</p>
            </div>
            <a href="{{ route('jobs.index', ['source' => 'admin']) }}" class="view-all-btn">
                View All Ads <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="jobs-grid">
            @foreach($generalJobs as $job)
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
                        @if($job->is_featured)
                            <span class="job-badge sponsored">Sponsored</span>
                        @endif
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
                        @if($job->is_featured)
                            <span class="job-tag featured">Featured</span>
                        @endif
                        @if($job->is_urgent)
                            <span class="job-tag urgent">Urgent</span>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', $job->slug) }}" class="job-apply-btn">
                        Apply Now <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ============================================================
    CATEGORIES SECTION - PROFESSIONAL V2
============================================================ --}}
<section class="categories-section">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">
                <i class="fas fa-th-large me-2"></i> Categories
            </span>
            <h2 class="section-title">
                Browse by <span class="text-gradient">Category</span>
            </h2>
            <p class="section-subtitle">
                Explore thousands of jobs in your preferred field
            </p>
        </div>

        <div class="categories-grid">
            @if(isset($categories) && $categories->count() > 0)
                @foreach($categories as $category)
                    @php
                        // ✅ Use database icon first, fallback to mapping
                        $dbIcon = $category->icon ?? null;

                        // ✅ If no database icon, use mapping
                        if (!$dbIcon) {
                            $dbIcon = match(strtolower($category->name)) {
                                'software development', 'it', 'programming', 'web development', 'app development' => 'fa-code',
                                'data science', 'ai', 'machine learning' => 'fa-brain',
                                'cybersecurity' => 'fa-shield-alt',
                                'cloud computing', 'devops' => 'fa-cloud',
                                'graphic design', 'ui/ux', 'web design' => 'fa-paint-brush',
                                'digital marketing', 'seo', 'social media' => 'fa-bullhorn',
                                'content writing', 'copywriting' => 'fa-pen-fancy',
                                'accounting', 'finance', 'audit' => 'fa-calculator',
                                'banking' => 'fa-university',
                                'human resources', 'hr' => 'fa-users-cog',
                                'administration', 'office' => 'fa-clipboard-list',
                                'sales', 'business development' => 'fa-handshake',
                                'customer support', 'help desk' => 'fa-headset',
                                'project management' => 'fa-tasks',
                                'quality assurance', 'testing' => 'fa-check-double',
                                'healthcare', 'medical' => 'fa-heartbeat',
                                'nursing' => 'fa-user-md',
                                'pharmaceutical' => 'fa-pills',
                                'education', 'teaching' => 'fa-graduation-cap',
                                'engineering' => 'fa-microchip',
                                'civil engineering' => 'fa-hard-hat',
                                'mechanical engineering' => 'fa-cogs',
                                'electrical engineering' => 'fa-bolt',
                                'construction' => 'fa-building',
                                'architecture' => 'fa-drafting-compass',
                                'legal', 'law' => 'fa-gavel',
                                'marketing' => 'fa-chart-line',
                                'retail' => 'fa-shopping-bag',
                                'logistics', 'supply chain' => 'fa-truck',
                                'transport' => 'fa-bus',
                                'hospitality' => 'fa-hotel',
                                'tourism' => 'fa-umbrella-beach',
                                'food', 'restaurant' => 'fa-utensils',
                                'sports', 'fitness' => 'fa-running',
                                'media', 'journalism' => 'fa-newspaper',
                                'entertainment' => 'fa-film',
                                'real estate' => 'fa-home',
                                'telecommunication' => 'fa-phone-alt',
                                'automobile' => 'fa-car',
                                'manufacturing' => 'fa-industry',
                                'agriculture' => 'fa-seedling',
                                'energy', 'oil' => 'fa-bolt',
                                'consulting' => 'fa-comments',
                                'armed forces', 'paf', 'pakistan air force' => 'fa-shield-halved',
                                'wapda' => 'fa-bolt',
                                'government' => 'fa-landmark',
                                'private jobs' => 'fa-building',
                                'testing services' => 'fa-file-signature',
                                'foreign jobs' => 'fa-globe-asia',
                                'jobs by education' => 'fa-graduation-cap',
                                default => 'fa-briefcase'
                            };
                        }

                        // ✅ Color based on category
                        $colors = ['#11998e', '#38ef7d', '#f59e0b', '#3b82f6', '#8b5cf6', '#ec4899', '#ef4444', '#14b8a6', '#f97316', '#6366f1'];
                        $colorIndex = $loop->index % count($colors);
                        $color = $colors[$colorIndex];

                        // ✅ Jobs count with fallback
                        $jobsCount = $category->jobs_count ?? 0;
                    @endphp

                    <a href="{{ route('jobs.index', ['category' => $category->slug ?? '#']) }}" class="category-card">
                        <div class="category-card-inner">
                            <div class="category-icon-wrapper" style="background: {{ $color }}15;">
                                <i class="fas {{ $dbIcon }}" style="color: {{ $color }};"></i>
                            </div>
                            <h5 class="category-name">{{ $category->name ?? 'Uncategorized' }}</h5>
                            <div class="category-stats">
                                <span class="category-count">{{ $jobsCount }}</span>
                                <span class="category-label">Jobs</span>
                            </div>
                            <div class="category-hover-effect">
                                <span class="category-explore">Explore <i class="fas fa-arrow-right"></i></span>
                            </div>
                        </div>
                    </a>
                @endforeach
            @else
                <div class="col-12 text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No categories available</h5>
                    <p class="text-muted">Check back later for new categories.</p>
                </div>
            @endif
        </div>
    </div>
</section>
{{-- ============================================================
    4. COMPANY JOBS (After Categories)
============================================================ --}}
@if(isset($companyJobs) && $companyJobs->count() > 0)
<section class="company-jobs-section py-5 bg-light">
    <div class="container">
        <div class="section-header d-flex justify-content-between align-items-center">
            <div>
                <span class="section-tag">
                    <i class="fas fa-building me-2"></i> Companies
                </span>
                <h2 class="section-title">Top Company <span class="text-gradient">Jobs</span></h2>
                <p class="section-subtitle">Verified jobs from leading employers</p>
            </div>
            <a href="{{ route('jobs.index', ['source' => 'company']) }}" class="view-all-btn">
                View All Company Jobs <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

        <div class="jobs-grid">
            @foreach($companyJobs as $job)
                <div class="job-card company-job">
                    <div class="job-card-header">
                        <div class="company-logo">
                            @if($job->company?->logo)
                                <img src="{{ asset('storage/' . $job->company->logo) }}" alt="{{ $job->company->name }}">
                            @else
                                <i class="fas fa-building"></i>
                            @endif
                        </div>
                        <span class="job-type">{{ $job->job_type ?? 'Full Time' }}</span>
                        @if($job->company?->is_verified)
                            <span class="job-badge verified">
                                <i class="fas fa-check-circle"></i> Verified
                            </span>
                        @endif
                    </div>
                    <h5 class="job-title">
                        <a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a>
                    </h5>
                    <p class="job-company">
                        {{ $job->company?->name ?? 'Confidential' }}
                        @if($job->company?->industry)
                            <span class="company-industry">• {{ $job->company->industry }}</span>
                        @endif
                    </p>
                    <div class="job-meta">
                        <span><i class="fas fa-map-marker-alt"></i> {{ $job->location ?? 'Remote' }}</span>
                        <span><i class="fas fa-clock"></i> {{ $job->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="job-tags">
                        @if($job->is_featured)
                            <span class="job-tag featured">Featured</span>
                        @endif
                        @if($job->is_remote)
                            <span class="job-tag remote">Remote</span>
                        @endif
                    </div>
                    <a href="{{ route('jobs.show', $job->slug) }}" class="job-apply-btn">
                        Apply Now <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ============================================================
    EDUCATION SECTION - SCHOLARSHIPS, ADMISSIONS, RESULTS, NEWS
    All 4 Sections with Professional Cards
============================================================ --}}
<section class="education-main-section py-5">
    <div class="container">
        <div class="section-header text-center">
            <span class="section-tag">
                <i class="fas fa-graduation-cap me-2"></i> Education & Updates
            </span>
            <h2 class="section-title">
                <span class="text-gradient">Scholarships</span> & <span class="text-gradient">Admissions</span>
            </h2>
            <p class="section-subtitle">
                Latest scholarships, admissions, results and news
            </p>
        </div>

        {{-- ============================================================
            1. SCHOLARSHIPS SECTION
        ============================================================ --}}
        <div class="education-section-block">
            <div class="section-block-header">
                <div class="block-header-left">
                    <div class="block-icon success">
                        <i class="fas fa-award"></i>
                    </div>
                    <h3 class="block-title">Scholarships</h3>
                    <span class="block-count">{{ $scholarships->count() ?? 0 }}</span>
                </div>
                <a href="{{ route('scholarships.index') }}" class="block-view-all">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @if(isset($scholarships) && $scholarships->count() > 0)
                    @foreach($scholarships as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="education-card">
                                @if($item->scholarship_type)
                                    <span class="education-badge {{ strtolower($item->scholarship_type) == 'fully funded' ? 'badge-fully' : (strtolower($item->scholarship_type) == 'partially funded' ? 'badge-partial' : 'badge-other') }}">
                                        {{ $item->scholarship_type }}
                                    </span>
                                @endif
                                <h5 class="education-card-title">{{ Str::limit($item->title, 45) }}</h5>
                                <p class="education-card-subtitle">
                                    <i class="fas fa-university"></i> {{ $item->university ?? $item->provider ?? 'N/A' }}
                                </p>
                                @if($item->country)
                                    <p class="education-card-location">
                                        <i class="fas fa-map-marker-alt"></i> {{ $item->country }}
                                    </p>
                                @endif
                                <div class="education-card-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Application Type</span>
                                        <span class="detail-value">Online</span>
                                    </div>
                                    @if($item->deadline)
                                        <div class="detail-item">
                                            <span class="detail-label">Deadline</span>
                                            <span class="detail-value {{ $item->days_remaining > 0 ? 'text-success' : 'text-danger' }}">
                                                @if($item->days_remaining > 0)
                                                    {{ $item->deadline->format('d M, Y') }}
                                                @elseif($item->days_remaining == 0)
                                                    Today
                                                @else
                                                    Expired
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    @if($item->amount)
                                        <div class="detail-item">
                                            <span class="detail-label">Award Amount</span>
                                            <span class="detail-value">{{ $item->amount }}</span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('scholarships.show', $item->slug) }}" class="education-card-btn">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="education-empty">
                            <i class="fas fa-award"></i>
                            <p>No scholarships available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================
            2. ADMISSIONS SECTION
        ============================================================ --}}
        <div class="education-section-block">
            <div class="section-block-header">
                <div class="block-header-left">
                    <div class="block-icon primary">
                        <i class="fas fa-university"></i>
                    </div>
                    <h3 class="block-title">Admissions</h3>
                    <span class="block-count">{{ $admissions->count() ?? 0 }}</span>
                </div>
                <a href="{{ route('admissions.index') }}" class="block-view-all">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @if(isset($admissions) && $admissions->count() > 0)
                    @foreach($admissions as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="education-card">
                                @if($item->category)
                                    <span class="education-badge badge-other">{{ $item->category }}</span>
                                @endif
                                <h5 class="education-card-title">{{ Str::limit($item->title, 45) }}</h5>
                                <p class="education-card-subtitle">
                                    <i class="fas fa-building"></i> {{ $item->institution ?? 'N/A' }}
                                </p>
                                @if($item->programs_offered)
                                    <p class="education-card-location">
                                        <i class="fas fa-graduation-cap"></i> {{ Str::limit($item->programs_offered, 25) }}
                                    </p>
                                @endif
                                <div class="education-card-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Application Type</span>
                                        <span class="detail-value">Online</span>
                                    </div>
                                    @if($item->last_date)
                                        <div class="detail-item">
                                            <span class="detail-label">Deadline</span>
                                            <span class="detail-value {{ $item->days_remaining > 0 ? 'text-success' : 'text-danger' }}">
                                                @if($item->days_remaining > 0)
                                                    {{ $item->last_date->format('d M, Y') }}
                                                @elseif($item->days_remaining == 0)
                                                    Today
                                                @else
                                                    Expired
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    @if($item->fee)
                                        <div class="detail-item">
                                            <span class="detail-label">Fee</span>
                                            <span class="detail-value">{{ $item->fee }}</span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('admissions.show', $item->slug) }}" class="education-card-btn">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="education-empty">
                            <i class="fas fa-university"></i>
                            <p>No admissions available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================
            3. RESULTS SECTION
        ============================================================ --}}
        <div class="education-section-block">
            <div class="section-block-header">
                <div class="block-header-left">
                    <div class="block-icon warning">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h3 class="block-title">Results</h3>
                    <span class="block-count">{{ $results->count() ?? 0 }}</span>
                </div>
                <a href="{{ route('results.index') }}" class="block-view-all">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @if(isset($results) && $results->count() > 0)
                    @foreach($results as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="education-card">
                                @if($item->exam_type)
                                    <span class="education-badge badge-other">{{ $item->exam_type }}</span>
                                @endif
                                <h5 class="education-card-title">{{ Str::limit($item->title, 45) }}</h5>
                                <p class="education-card-subtitle">
                                    <i class="fas fa-building"></i> {{ $item->institution ?? 'N/A' }}
                                </p>
                                @if($item->category)
                                    <p class="education-card-location">
                                        <i class="fas fa-tag"></i> {{ $item->category }}
                                    </p>
                                @endif
                                <div class="education-card-details">
                                    @if($item->result_date)
                                        <div class="detail-item">
                                            <span class="detail-label">Result Date</span>
                                            <span class="detail-value">{{ $item->result_date->format('d M, Y') }}</span>
                                        </div>
                                    @endif
                                    <div class="detail-item">
                                        <span class="detail-label">Status</span>
                                        <span class="detail-value text-success">Published</span>
                                    </div>
                                    @if($item->file_path)
                                        <div class="detail-item">
                                            <span class="detail-label">Download</span>
                                            <span class="detail-value">
                                                <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" class="text-primary">
                                                    <i class="fas fa-file-pdf"></i> PDF
                                                </a>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('results.show', $item->slug) }}" class="education-card-btn">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="education-empty">
                            <i class="fas fa-file-alt"></i>
                            <p>No results available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ============================================================
            4. NEWS SECTION
        ============================================================ --}}
        <div class="education-section-block">
            <div class="section-block-header">
                <div class="block-header-left">
                    <div class="block-icon danger">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h3 class="block-title">News</h3>
                    <span class="block-count">{{ $news->count() ?? 0 }}</span>
                </div>
                <a href="{{ route('news.index') }}" class="block-view-all">
                    View All <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="row g-4">
                @if(isset($news) && $news->count() > 0)
                    @foreach($news as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="education-card">
                                @if($item->source)
                                    <span class="education-badge badge-other">{{ $item->source }}</span>
                                @endif
                                <h5 class="education-card-title">{{ Str::limit($item->title, 45) }}</h5>
                                <p class="education-card-subtitle">
                                    <i class="fas fa-user"></i> {{ $item->author?->name ?? 'Admin' }}
                                </p>
                                @if($item->excerpt)
                                    <p class="education-card-location">
                                        <i class="fas fa-quote-left"></i> {{ Str::limit($item->excerpt, 40) }}
                                    </p>
                                @endif
                                <div class="education-card-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Published</span>
                                        <span class="detail-value">{{ $item->formatted_date }}</span>
                                    </div>
                                    @if($item->source)
                                        <div class="detail-item">
                                            <span class="detail-label">Source</span>
                                            <span class="detail-value">{{ $item->source }}</span>
                                        </div>
                                    @endif
                                    @if($item->views_count)
                                        <div class="detail-item">
                                            <span class="detail-label">Views</span>
                                            <span class="detail-value">{{ $item->views_count }}</span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ route('news.show', $item->slug) }}" class="education-card-btn">
                                    Read More <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="education-empty">
                            <i class="fas fa-newspaper"></i>
                            <p>No news available</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</section>
@endsection
