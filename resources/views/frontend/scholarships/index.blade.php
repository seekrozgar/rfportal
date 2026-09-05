@extends('layouts.app')

@section('title', 'Scholarships - ' . siteName())
@section('meta')
    <meta name="description" content="Find latest scholarships for Pakistani students. Fully funded, partially funded, and merit-based scholarships.">
@endsection

@section('content')

{{-- ============================================================
    HERO / PAGE HEADER - PROFESSIONAL
============================================================ --}}
<section class="scholarships-hero">
    <div class="container">
        <div class="scholarships-hero-content">
            <div class="scholarships-hero-text">
                <span class="hero-badge">
                    <i class="fas fa-graduation-cap me-2"></i> Education
                </span>
                <h1 class="hero-title">
                    Find Your Dream <span class="text-gradient">Scholarship</span>
                </h1>
                <p class="hero-subtitle">
                    Discover fully funded, partially funded, and merit-based scholarships
                    from top universities worldwide. Your future starts here.
                </p>
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $scholarships->total() }}</span>
                        <span class="stat-label">Total Scholarships</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $activeCount }}</span>
                        <span class="stat-label">Active Now</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $fullyFundedCount }}</span>
                        <span class="stat-label">Fully Funded</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $scholarships->where('scholarship_type', 'Partial Funded')->count() }}</span>
                        <span class="stat-label">Partially Funded</span>
                    </div>
                </div>
            </div>
            <div class="scholarships-hero-image">
                <div class="hero-illustration">
                    <i class="fas fa-award"></i>
                    <div class="floating-icon icon-1"><i class="fas fa-graduation-cap"></i></div>
                    <div class="floating-icon icon-2"><i class="fas fa-globe-asia"></i></div>
                    <div class="floating-icon icon-3"><i class="fas fa-university"></i></div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
    FILTERS - ENHANCED
============================================================ --}}
<section class="filters-section">
    <div class="container">
        <form method="GET" action="{{ route('scholarships.index') }}" class="filters-form">
            <div class="filters-wrapper">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" name="search" class="form-control"
                           placeholder="Search scholarships by title, university, country..."
                           value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <select name="type" class="form-select">
                        <option value="">All Types</option>
                        <option value="Fully Funded" {{ request('type') == 'Fully Funded' ? 'selected' : '' }}>Fully Funded</option>
                        <option value="Partial Funded" {{ request('type') == 'Partial Funded' ? 'selected' : '' }}>Partially Funded</option>
                        <option value="Tuition Waiver" {{ request('type') == 'Tuition Waiver' ? 'selected' : '' }}>Tuition Waiver</option>
                        <option value="Stipend" {{ request('type') == 'Stipend' ? 'selected' : '' }}>Stipend</option>
                    </select>
                </div>
                <div class="filter-group">
                    <select name="degree" class="form-select">
                        <option value="">All Degrees</option>
                        <option value="Bachelor" {{ request('degree') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                        <option value="Master" {{ request('degree') == 'Master' ? 'selected' : '' }}>Master</option>
                        <option value="M.Phil" {{ request('degree') == 'M.Phil' ? 'selected' : '' }}>M.Phil</option>
                        <option value="PhD" {{ request('degree') == 'PhD' ? 'selected' : '' }}>PhD</option>
                        <option value="Post Doc" {{ request('degree') == 'Post Doc' ? 'selected' : '' }}>Post Doc</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-sliders-h me-2"></i> Apply Filters
                </button>
            </div>

            @if(request()->anyFilled(['search', 'type', 'degree']))
                <div class="active-filters mt-3">
                    <span class="filter-label">Active Filters:</span>
                    @if(request('search'))
                        <span class="filter-tag">
                            Search: {{ request('search') }}
                            <a href="{{ route('scholarships.index', array_merge(request()->except('search'))) }}" class="remove-filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('type'))
                        <span class="filter-tag">
                            Type: {{ request('type') }}
                            <a href="{{ route('scholarships.index', array_merge(request()->except('type'))) }}" class="remove-filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    @if(request('degree'))
                        <span class="filter-tag">
                            Degree: {{ request('degree') }}
                            <a href="{{ route('scholarships.index', array_merge(request()->except('degree'))) }}" class="remove-filter">
                                <i class="fas fa-times"></i>
                            </a>
                        </span>
                    @endif
                    <a href="{{ route('scholarships.index') }}" class="clear-all">
                        <i class="fas fa-undo me-1"></i> Clear All
                    </a>
                </div>
            @endif
        </form>
    </div>
</section>

{{-- ============================================================
    SCHOLARSHIPS GRID - ENHANCED
============================================================ --}}
<section class="scholarships-grid-section">
    <div class="container">
        @if($scholarships->count() > 0)
            <div class="scholarships-grid-header">
                <div>
                    <span class="results-count">{{ $scholarships->total() }} Scholarships Found</span>
                    <span class="results-sub">Showing {{ $scholarships->firstItem() ?? 0 }} to {{ $scholarships->lastItem() ?? 0 }}</span>
                </div>
                <div class="view-options">
                    <button class="view-btn active" data-view="grid" title="Grid View">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="view-btn" data-view="list" title="List View">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            {{-- Grid View --}}
            <div class="row g-4 scholarships-grid" id="scholarshipsGrid">
                @foreach($scholarships as $scholarship)
                    <div class="col-lg-3 col-md-6 scholarship-item">
                        <div class="scholarship-card">
                            @if($scholarship->scholarship_type)
                                <span class="scholarship-badge
                                    @if(strtolower($scholarship->scholarship_type) == 'fully funded') badge-fully
                                    @elseif(strtolower($scholarship->scholarship_type) == 'partially funded') badge-partial
                                    @else badge-other @endif">
                                    {{ $scholarship->scholarship_type }}
                                </span>
                            @endif

                            <div class="scholarship-card-body">
                                <h5 class="scholarship-title">{{ Str::limit($scholarship->title, 45) }}</h5>

                                <p class="scholarship-university">
                                    <i class="fas fa-university"></i>
                                    {{ $scholarship->university ?? $scholarship->provider ?? 'N/A' }}
                                </p>

                                @if($scholarship->country)
                                    <p class="scholarship-country">
                                        <i class="fas fa-map-marker-alt"></i> {{ $scholarship->country }}
                                    </p>
                                @endif

                                <div class="scholarship-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Application</span>
                                        <span class="detail-value">Online</span>
                                    </div>
                                    @if($scholarship->deadline)
                                        <div class="detail-item">
                                            <span class="detail-label">Deadline</span>
                                            <span class="detail-value {{ $scholarship->days_remaining > 0 ? 'text-success' : 'text-danger' }}">
                                                @if($scholarship->days_remaining > 0)
                                                    {{ $scholarship->deadline->format('d M, Y') }}
                                                @elseif($scholarship->days_remaining == 0)
                                                    Today
                                                @else
                                                    Expired
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    @if($scholarship->amount)
                                        <div class="detail-item">
                                            <span class="detail-label">Amount</span>
                                            <span class="detail-value">{{ $scholarship->amount }}</span>
                                        </div>
                                    @endif
                                    @if($scholarship->degree_level)
                                        <div class="detail-item">
                                            <span class="detail-label">Degree</span>
                                            <span class="detail-value">{{ $scholarship->degree_level }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="scholarship-card-footer">
                                <a href="{{ route('scholarships.show', $scholarship->slug) }}" class="scholarship-btn">
                                    View Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- List View (Hidden by default) --}}
            <div class="scholarships-list" id="scholarshipsList" style="display: none;">
                @foreach($scholarships as $scholarship)
                    <div class="scholarship-list-item">
                        <div class="scholarship-list-left">
                            <div class="list-badge
                                @if(strtolower($scholarship->scholarship_type) == 'fully funded') badge-fully
                                @elseif(strtolower($scholarship->scholarship_type) == 'partially funded') badge-partial
                                @else badge-other @endif">
                                {{ $scholarship->scholarship_type ?? 'Scholarship' }}
                            </div>
                            <div class="list-content">
                                <h5 class="list-title">{{ $scholarship->title }}</h5>
                                <p class="list-meta">
                                    <span><i class="fas fa-university"></i> {{ $scholarship->university ?? $scholarship->provider ?? 'N/A' }}</span>
                                    @if($scholarship->country)
                                        <span><i class="fas fa-map-marker-alt"></i> {{ $scholarship->country }}</span>
                                    @endif
                                    @if($scholarship->deadline)
                                        <span><i class="fas fa-calendar-alt"></i> {{ $scholarship->deadline->format('d M, Y') }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="scholarship-list-right">
                            @if($scholarship->amount)
                                <span class="list-amount">{{ $scholarship->amount }}</span>
                            @endif
                            <a href="{{ route('scholarships.show', $scholarship->slug) }}" class="list-btn">
                                View <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="pagination-wrapper mt-5">
                {{ $scholarships->links() }}
            </div>

        @else
            <div class="empty-state text-center py-5">
                <div class="empty-state-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h4 class="empty-state-title">No scholarships found</h4>
                <p class="empty-state-desc">Try adjusting your filters or check back later for new opportunities.</p>
                <a href="{{ route('scholarships.index') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-undo me-2"></i> Reset Filters
                </a>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ✅ Grid/List View Toggle
    const gridView = document.getElementById('scholarshipsGrid');
    const listView = document.getElementById('scholarshipsList');
    const viewBtns = document.querySelectorAll('.view-btn');

    viewBtns.forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Remove active class from all buttons
            viewBtns.forEach(function(b) {
                b.classList.remove('active');
            });
            this.classList.add('active');

            const view = this.dataset.view;

            if (view === 'grid') {
                gridView.style.display = 'flex';
                listView.style.display = 'none';

                // Update grid classes
                document.querySelectorAll('.scholarship-item').forEach(function(item) {
                    item.className = 'col-lg-3 col-md-6 scholarship-item';
                });
            } else if (view === 'list') {
                gridView.style.display = 'none';
                listView.style.display = 'block';
            }
        });
    });

});
</script>
@endpush
