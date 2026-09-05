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
    5. EDUCATION & NEWS SECTION (Scholarships, Admissions, Results, News)
    ============================================================ --}}
<section class="education-section py-5">
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

        <div class="row g-4">
            {{-- Scholarships --}}
            <div class="col-lg-6 col-md-12">
                <div class="education-card-wrapper">
                    <div class="education-card-header">
                        <h4 class="education-card-title">
                            <i class="fas fa-award text-success me-2"></i> Scholarships
                        </h4>
                        <a href="{{ route('scholarships.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="education-card-body">
                        @if(isset($scholarships) && $scholarships->count() > 0)
                            @foreach($scholarships as $scholarship)
                                <a href="{{ route('scholarships.show', $scholarship->slug) }}" class="education-item">
                                    <div class="education-item-icon">
                                        <i class="fas fa-award text-success"></i>
                                    </div>
                                    <div class="education-item-content">
                                        <h6 class="education-item-title">{{ $scholarship->title }}</h6>
                                        <p class="education-item-meta">
                                            <span><i class="fas fa-building"></i> {{ $scholarship->provider ?? $scholarship->university ?? 'N/A' }}</span>
                                            @if($scholarship->amount)
                                                <span><i class="fas fa-money-bill-wave"></i> {{ $scholarship->amount }}</span>
                                            @endif
                                            @if($scholarship->deadline)
                                                <span><i class="fas fa-calendar-alt"></i> {{ $scholarship->deadline->format('d M Y') }}</span>
                                            @endif
                                            @if($scholarship->days_remaining > 0)
                                                <span class="text-success"><i class="fas fa-clock"></i> {{ $scholarship->days_remaining }} days left</span>
                                            @elseif($scholarship->days_remaining == 0 && $scholarship->deadline)
                                                <span class="text-danger"><i class="fas fa-clock"></i> Today</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="education-item-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </a>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-3">No scholarships available.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Admissions --}}
            <div class="col-lg-6 col-md-12">
                <div class="education-card-wrapper">
                    <div class="education-card-header">
                        <h4 class="education-card-title">
                            <i class="fas fa-university text-primary me-2"></i> Admissions
                        </h4>
                        <a href="{{ route('admissions.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="education-card-body">
                        @if(isset($admissions) && $admissions->count() > 0)
                            @foreach($admissions as $admission)
                                <a href="{{ route('admissions.show', $admission->slug) }}" class="education-item">
                                    <div class="education-item-icon">
                                        <i class="fas fa-university text-primary"></i>
                                    </div>
                                    <div class="education-item-content">
                                        <h6 class="education-item-title">{{ $admission->title }}</h6>
                                        <p class="education-item-meta">
                                            <span><i class="fas fa-building"></i> {{ $admission->institution ?? 'N/A' }}</span>
                                            <span><i class="fas fa-graduation-cap"></i> {{ Str::limit($admission->programs_offered ?? 'N/A', 30) }}</span>
                                            @if($admission->last_date)
                                                <span><i class="fas fa-calendar-alt"></i> {{ $admission->last_date->format('d M Y') }}</span>
                                            @endif
                                            @if($admission->days_remaining > 0)
                                                <span class="text-success"><i class="fas fa-clock"></i> {{ $admission->days_remaining }} days left</span>
                                            @elseif($admission->days_remaining == 0 && $admission->last_date)
                                                <span class="text-danger"><i class="fas fa-clock"></i> Today</span>
                                            @endif
                                        </p>
                                    </div>
                                    <span class="education-item-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </a>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-3">No admissions available.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Results --}}
            <div class="col-lg-6 col-md-12">
                <div class="education-card-wrapper">
                    <div class="education-card-header">
                        <h4 class="education-card-title">
                            <i class="fas fa-file-alt text-warning me-2"></i> Results
                        </h4>
                        <a href="{{ route('results.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="education-card-body">
                        @if(isset($results) && $results->count() > 0)
                            @foreach($results as $result)
                                <a href="{{ route('results.show', $result->slug) }}" class="education-item">
                                    <div class="education-item-icon">
                                        <i class="fas fa-file-alt text-warning"></i>
                                    </div>
                                    <div class="education-item-content">
                                        <h6 class="education-item-title">{{ $result->title }}</h6>
                                        <p class="education-item-meta">
                                            <span><i class="fas fa-building"></i> {{ $result->institution ?? 'N/A' }}</span>
                                            @if($result->exam_type)
                                                <span><i class="fas fa-tag"></i> {{ $result->exam_type }}</span>
                                            @endif
                                            <span><i class="fas fa-calendar-alt"></i> {{ $result->formatted_date }}</span>
                                        </p>
                                    </div>
                                    <span class="education-item-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </a>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-3">No results available.</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- News --}}
            <div class="col-lg-6 col-md-12">
                <div class="education-card-wrapper">
                    <div class="education-card-header">
                        <h4 class="education-card-title">
                            <i class="fas fa-newspaper text-danger me-2"></i> News
                        </h4>
                        <a href="{{ route('news.index') }}" class="view-all-link">
                            View All <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    <div class="education-card-body">
                        @if(isset($news) && $news->count() > 0)
                            @foreach($news as $newsItem)
                                <a href="{{ route('news.show', $newsItem->slug) }}" class="education-item">
                                    <div class="education-item-icon">
                                        <i class="fas fa-newspaper text-danger"></i>
                                    </div>
                                    <div class="education-item-content">
                                        <h6 class="education-item-title">{{ $newsItem->title }}</h6>
                                        <p class="education-item-meta">
                                            @if($newsItem->source)
                                                <span><i class="fas fa-link"></i> {{ $newsItem->source }}</span>
                                            @endif
                                            <span><i class="fas fa-user"></i> {{ $newsItem->author?->name ?? 'Admin' }}</span>
                                            <span><i class="fas fa-calendar-alt"></i> {{ $newsItem->formatted_date }}</span>
                                        </p>
                                    </div>
                                    <span class="education-item-arrow">
                                        <i class="fas fa-chevron-right"></i>
                                    </span>
                                </a>
                            @endforeach
                        @else
                            <p class="text-muted text-center py-3">No news available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
