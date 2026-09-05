@extends('layouts.app')

@section('title', $scholarship->title . ' - Scholarships')
@section('meta')
    <meta name="description" content="{{ Str::limit(strip_tags($scholarship->description), 160) }}">
@endsection

@section('content')

{{-- ============================================================
    SCHOLARSHIP DETAIL PAGE
============================================================ --}}
<section class="scholarship-detail-section py-4">
    <div class="container">
        <div class="row g-4">
            {{-- Main Content --}}
            <div class="col-lg-8">
                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('scholarships.index') }}">Scholarships</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($scholarship->title, 35) }}</li>
                    </ol>
                </nav>

                {{-- Badge --}}
                @if($scholarship->scholarship_type)
                    <div class="mb-3">
                        <span class="scholarship-detail-badge
                            @if(strtolower($scholarship->scholarship_type) == 'fully funded') badge-fully
                            @elseif(strtolower($scholarship->scholarship_type) == 'partially funded') badge-partial
                            @else badge-other @endif">
                            {{ $scholarship->scholarship_type }}
                        </span>
                    </div>
                @endif

                {{-- Title --}}
                <h1 class="scholarship-detail-title">{{ $scholarship->title }}</h1>

                {{-- University/Provider --}}
                @if($scholarship->university || $scholarship->provider)
                    <p class="scholarship-detail-university">
                        <i class="fas fa-university me-2"></i>
                        {{ $scholarship->university ?? $scholarship->provider }}
                        @if($scholarship->country)
                            <span class="country-separator">•</span>
                            <i class="fas fa-map-marker-alt me-1"></i> {{ $scholarship->country }}
                        @endif
                    </p>
                @endif

                {{-- Quick Info Cards --}}
                <div class="scholarship-quick-info mt-4">
                    <div class="row g-3">
                        @if($scholarship->provider)
                            <div class="col-md-4 col-6">
                                <div class="info-card">
                                    <div class="info-card-icon"><i class="fas fa-building"></i></div>
                                    <div class="info-card-content">
                                        <span class="info-card-label">Provider</span>
                                        <span class="info-card-value">{{ $scholarship->provider }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($scholarship->university)
                            <div class="col-md-4 col-6">
                                <div class="info-card">
                                    <div class="info-card-icon"><i class="fas fa-university"></i></div>
                                    <div class="info-card-content">
                                        <span class="info-card-label">University</span>
                                        <span class="info-card-value">{{ $scholarship->university }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($scholarship->country)
                            <div class="col-md-4 col-6">
                                <div class="info-card">
                                    <div class="info-card-icon"><i class="fas fa-map-marker-alt"></i></div>
                                    <div class="info-card-content">
                                        <span class="info-card-label">Country</span>
                                        <span class="info-card-value">{{ $scholarship->country }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($scholarship->degree_level)
                            <div class="col-md-4 col-6">
                                <div class="info-card">
                                    <div class="info-card-icon"><i class="fas fa-graduation-cap"></i></div>
                                    <div class="info-card-content">
                                        <span class="info-card-label">Degree Level</span>
                                        <span class="info-card-value">{{ $scholarship->degree_level }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($scholarship->amount)
                            <div class="col-md-4 col-6">
                                <div class="info-card">
                                    <div class="info-card-icon"><i class="fas fa-money-bill-wave"></i></div>
                                    <div class="info-card-content">
                                        <span class="info-card-label">Award Amount</span>
                                        <span class="info-card-value">{{ $scholarship->amount }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if($scholarship->deadline)
                            <div class="col-md-4 col-6">
                                <div class="info-card">
                                    <div class="info-card-icon"><i class="fas fa-calendar-alt"></i></div>
                                    <div class="info-card-content">
                                        <span class="info-card-label">Deadline</span>
                                        <span class="info-card-value {{ $scholarship->days_remaining > 0 ? 'text-success' : 'text-danger' }}">
                                            {{ $scholarship->deadline->format('d M, Y') }}
                                            @if($scholarship->days_remaining > 0)
                                                <span class="deadline-badge success">{{ $scholarship->days_remaining }} days left</span>
                                            @elseif($scholarship->days_remaining == 0)
                                                <span class="deadline-badge warning">Today</span>
                                            @else
                                                <span class="deadline-badge danger">Expired</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Featured Image --}}
                @if($scholarship->featured_image_url)
                    <div class="scholarship-featured-image mt-4">
                        <img src="{{ $scholarship->featured_image_url }}" alt="{{ $scholarship->title }}" class="img-fluid">
                    </div>
                @endif

                {{-- Description --}}
                <div class="scholarship-section mt-4">
                    <h5 class="section-title">Description</h5>
                    <div class="section-content">
                        {!! $scholarship->description !!}
                    </div>
                </div>

                {{-- Eligibility --}}
                @if($scholarship->eligibility)
                    <div class="scholarship-section mt-4">
                        <h5 class="section-title">Eligibility Criteria</h5>
                        <div class="section-content">
                            {!! $scholarship->eligibility !!}
                        </div>
                    </div>
                @endif

                {{-- Benefits --}}
                @if($scholarship->benefits)
                    <div class="scholarship-section mt-4">
                        <h5 class="section-title">Benefits</h5>
                        <div class="section-content">
                            {!! $scholarship->benefits !!}
                        </div>
                    </div>
                @endif

                {{-- Required Documents --}}
                @if($scholarship->required_documents)
                    <div class="scholarship-section mt-4">
                        <h5 class="section-title">Required Documents</h5>
                        <div class="section-content">
                            {!! $scholarship->required_documents !!}
                        </div>
                    </div>
                @endif

                {{-- Apply Button --}}
                @if($scholarship->apply_link)
                    <div class="scholarship-apply-section mt-4">
                        <a href="{{ $scholarship->apply_link }}" target="_blank" rel="noopener" class="scholarship-apply-btn">
                            <i class="fas fa-external-link-alt me-2"></i> Apply Now
                            <i class="fas fa-arrow-right ms-2"></i>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="scholarship-sidebar">
                    {{-- Quick Apply Card --}}
                    <div class="sidebar-card">
                        <h6 class="sidebar-card-title">
                            <i class="fas fa-rocket me-2"></i> Quick Apply
                        </h6>
                        @if($scholarship->apply_link)
                            <a href="{{ $scholarship->apply_link }}" target="_blank" class="sidebar-apply-btn">
                                <i class="fas fa-external-link-alt me-2"></i> Apply Now
                            </a>
                        @else
                            <p class="text-muted text-center mb-0">No application link available</p>
                        @endif
                    </div>

                    {{-- Share --}}
                    <div class="sidebar-card">
                        <h6 class="sidebar-card-title">
                            <i class="fas fa-share-alt me-2"></i> Share This Scholarship
                        </h6>
                        <div class="share-buttons">
                            <a href="#" class="share-btn facebook" onclick="shareOnFacebook(event)">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="share-btn twitter" onclick="shareOnTwitter(event)">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="share-btn whatsapp" onclick="shareOnWhatsApp(event)">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                            <a href="#" class="share-btn linkedin" onclick="shareOnLinkedIn(event)">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <a href="#" class="share-btn copy" onclick="copyLink(event)">
                                <i class="fas fa-link"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Source Info --}}
                    @if($scholarship->source)
                        <div class="sidebar-card">
                            <h6 class="sidebar-card-title">
                                <i class="fas fa-info-circle me-2"></i> Source
                            </h6>
                            <p class="sidebar-source">{{ $scholarship->source_name }}</p>
                            @if($scholarship->source_url)
                                <a href="{{ $scholarship->source_url }}" target="_blank" class="sidebar-source-link">
                                    View Original <i class="fas fa-external-link-alt ms-1"></i>
                                </a>
                            @endif
                        </div>
                    @endif

                    {{-- Contact --}}
                    @if($scholarship->contact_email || $scholarship->contact_phone)
                        <div class="sidebar-card">
                            <h6 class="sidebar-card-title">
                                <i class="fas fa-phone me-2"></i> Contact
                            </h6>
                            @if($scholarship->contact_email)
                                <p class="sidebar-contact">
                                    <i class="fas fa-envelope me-2"></i> {{ $scholarship->contact_email }}
                                </p>
                            @endif
                            @if($scholarship->contact_phone)
                                <p class="sidebar-contact mb-0">
                                    <i class="fas fa-phone me-2"></i> {{ $scholarship->contact_phone }}
                                </p>
                            @endif
                        </div>
                    @endif

                    {{-- Quick Info Summary --}}
                    <div class="sidebar-card">
                        <h6 class="sidebar-card-title">
                            <i class="fas fa-list-ul me-2"></i> Quick Summary
                        </h6>
                        <ul class="sidebar-summary">
                            @if($scholarship->scholarship_type)
                                <li>
                                    <span class="summary-label">Type</span>
                                    <span class="summary-value">{{ $scholarship->scholarship_type }}</span>
                                </li>
                            @endif
                            @if($scholarship->degree_level)
                                <li>
                                    <span class="summary-label">Degree</span>
                                    <span class="summary-value">{{ $scholarship->degree_level }}</span>
                                </li>
                            @endif
                            @if($scholarship->country)
                                <li>
                                    <span class="summary-label">Country</span>
                                    <span class="summary-value">{{ $scholarship->country }}</span>
                                </li>
                            @endif
                            @if($scholarship->deadline)
                                <li>
                                    <span class="summary-label">Deadline</span>
                                    <span class="summary-value">{{ $scholarship->deadline->format('d M, Y') }}</span>
                                </li>
                            @endif
                            @if($scholarship->amount)
                                <li>
                                    <span class="summary-label">Amount</span>
                                    <span class="summary-value">{{ $scholarship->amount }}</span>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================================================
    RELATED SCHOLARSHIPS
============================================================ --}}
@if(isset($related) && $related->count() > 0)
<section class="related-scholarships py-5">
    <div class="container">
        <div class="related-header">
            <h3 class="related-title">
                <i class="fas fa-th-list me-2 text-gradient"></i> Related <span class="text-gradient">Scholarships</span>
            </h3>
            <a href="{{ route('scholarships.index') }}" class="related-view-all">
                View All <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>
        <div class="row g-4">
            @foreach($related as $item)
                <div class="col-lg-3 col-md-6">
                    <div class="related-card">
                        @if($item->scholarship_type)
                            <span class="related-badge badge-other">{{ $item->scholarship_type }}</span>
                        @endif
                        <h6 class="related-card-title">{{ Str::limit($item->title, 40) }}</h6>
                        <p class="related-card-university">
                            <i class="fas fa-university"></i> {{ $item->university ?? $item->provider ?? 'N/A' }}
                        </p>
                        <div class="related-card-footer">
                            <span class="related-deadline">
                                <i class="fas fa-calendar-alt"></i> {{ $item->deadline?->format('d M, Y') ?? 'N/A' }}
                            </span>
                            <a href="{{ route('scholarships.show', $item->slug) }}" class="related-card-link">
                                View <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function shareOnFacebook(e) {
    e.preventDefault();
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank', 'width=600,height=400');
}

function shareOnTwitter(e) {
    e.preventDefault();
    const url = encodeURIComponent(window.location.href);
    const text = encodeURIComponent('{{ $scholarship->title }} - Scholarship');
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank', 'width=600,height=400');
}

function shareOnWhatsApp(e) {
    e.preventDefault();
    const url = encodeURIComponent(window.location.href);
    window.open(`https://api.whatsapp.com/send?text=${url}`, '_blank');
}

function shareOnLinkedIn(e) {
    e.preventDefault();
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}`, '_blank', 'width=600,height=400');
}

function copyLink(e) {
    e.preventDefault();
    navigator.clipboard.writeText(window.location.href).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endpush
