{{-- resources/views/frontend/packages/index.blade.php --}}
@extends('frontend.layouts.app')

@section('title', 'Packages - Rozgar Finder')
@section('page-title', 'Choose Your Plan')
@section('page-subtitle', 'Select the best package for your needs')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Choose Your Plan</h2>
            <p class="text-muted">Select the best package that fits your needs</p>
        </div>

        {{-- Toggle: Employer / Seeker --}}
        <div class="text-center mb-4">
            <div class="btn-group" role="group">
                <input type="radio" class="btn-check" name="packageType" id="employer" value="employer" checked>
                <label class="btn btn-outline-primary" for="employer">👔 For Employers</label>

                <input type="radio" class="btn-check" name="packageType" id="seeker" value="seeker">
                <label class="btn btn-outline-primary" for="seeker">🧑‍💼 For Job Seekers</label>
            </div>
        </div>

        {{-- Employer Packages --}}
        <div id="employerPackages" class="row g-4">
            @foreach($employerPackages as $package)
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
                            <button onclick="buyPackage({{ $package->id }})" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ $package->price > 0 ? 'Buy Now' : 'Get Started' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Seeker Packages --}}
        <div id="seekerPackages" class="row g-4 d-none">
            @foreach($seekerPackages as $package)
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
                            <button onclick="buyPackage({{ $package->id }})" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-shopping-cart me-2"></i>
                                {{ $package->price > 0 ? 'Buy Now' : 'Get Started' }}
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Payment Modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Payment Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="paymentForm">
                        @csrf
                        <input type="hidden" id="packageId" name="package_id">
                        <div class="form-group mb-3">
                            <label>Payment Gateway</label>
                            <select name="gateway" class="form-select" required>
                                <option value="paypal">PayPal</option>
                                <option value="stripe">Stripe</option>
                                <option value="jazzcash">JazzCash</option>
                                <option value="easypaisa">EasyPaisa</option>
                                <option value="bank_transfer">Bank Transfer</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="processPayment()">Proceed to Pay</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <style>
        .package-card {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .package-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .package-card.featured {
            border-width: 2px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.2);
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
@endpush

@push('scripts')
    <script>
        // Toggle between employer and seeker packages
        document.querySelectorAll('input[name="packageType"]').forEach(radio => {
            radio.addEventListener('change', function () {
                if (this.value === 'employer') {
                    document.getElementById('employerPackages').classList.remove('d-none');
                    document.getElementById('seekerPackages').classList.add('d-none');
                } else {
                    document.getElementById('employerPackages').classList.add('d-none');
                    document.getElementById('seekerPackages').classList.remove('d-none');
                }
            });
        });

        function buyPackage(packageId) {
            document.getElementById('packageId').value = packageId;
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            modal.show();
        }

        function processPayment() {
            const packageId = document.getElementById('packageId').value;
            const gateway = document.querySelector('select[name="gateway"]').value;

            if (!gateway) {
                toastr.error('Please select a payment method');
                return;
            }

            // Show loading
            const btn = document.querySelector('#paymentModal .btn-primary');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Processing...';

            fetch('{{ route("payment.initiate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ package_id: packageId, gateway: gateway })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = data.redirect_url;
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(() => {
                    toastr.error('An error occurred. Please try again.');
                })
                .finally(() => {
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
        }
    </script>
@endpush