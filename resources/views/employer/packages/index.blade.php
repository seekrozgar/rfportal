{{-- resources/views/employer/packages/index.blade.php --}}
@extends('employer.layouts.employer')

@section('title', 'Packages - Employer Dashboard')
@section('page-title', 'Subscription Packages')
@section('page-subtitle', 'Choose a plan that fits your hiring needs')

@section('content')
    <div class="row">
        {{-- Active Subscription Alert --}}
        @if($activeSubscription)
            <div class="col-12 mb-4">
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    You have an active <strong>{{ $activeSubscription->package->name }}</strong> subscription.
                    Valid until <strong>{{ $activeSubscription->end_date->format('d M, Y') }}</strong>.
                    <br>
                    <small>Remaining job posts: <strong>{{ $activeSubscription->remaining_job_posts }}</strong></small>
                    <a href="{{ route('employer.subscription.active') }}" class="btn btn-sm btn-outline-success ms-3">
                        <i class="fas fa-eye"></i> View Details
                    </a>
                </div>
            </div>
        @endif

        {{-- Packages Grid --}}
        @foreach($packages->chunk(3) as $chunk)
            <div class="row g-4 mb-4">
                @foreach($chunk as $package)
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm package-card {{ $package->is_featured ? 'border-primary featured' : '' }}">
                            @if($package->is_featured)
                                <div class="featured-badge">🔥 Most Popular</div>
                            @endif
                            <div class="card-body text-center">
                                <h3 class="card-title">{{ $package->name }}</h3>
                                <div class="price my-3">
                                    <span class="display-4 fw-bold">PKR {{ number_format($package->price) }}</span>
                                    <span class="text-muted">/ {{ $package->duration_days }} days</span>
                                </div>
                                <p class="text-muted small">{{ $package->description }}</p>
                                <hr>
                                <ul class="list-unstyled text-start">
                                    @foreach($package->features as $feature)
                                        <li class="mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            {{ $feature }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="card-footer bg-transparent border-0 text-center pb-3">
                                @if($activeSubscription)
                                    <button class="btn btn-secondary btn-lg w-100" disabled>
                                        <i class="fas fa-lock me-2"></i> Already Subscribed
                                    </button>
                                @else
                                    <a href="{{ route('employer.packages.buy', $package->id) }}" class="btn btn-primary btn-lg w-100">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        {{ $package->price > 0 ? 'Buy Now' : 'Get Started' }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        {{-- My Subscriptions Link --}}
        <div class="col-12 text-center mt-3">
            <a href="{{ route('employer.subscriptions.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-history me-2"></i> View My Subscriptions History
            </a>
        </div>
    </div>

    <style>
        .package-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .package-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .package-card.featured {
            border-width: 2px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.15);
        }

        .featured-badge {
            position: absolute;
            top: 15px;
            right: -35px;
            background: #ffc107;
            color: #000;
            padding: 5px 40px;
            transform: rotate(45deg);
            font-size: 12px;
            font-weight: 600;
            z-index: 1;
        }

        .price .display-4 {
            font-size: 2.5rem;
        }

        .list-unstyled li {
            font-size: 14px;
            color: #4a5568;
        }
    </style>
@endsection