{{-- resources/views/employer/packages/buy.blade.php --}}
@extends('employer.layouts.dashboard')

@section('title', 'Buy Package - Employer Dashboard')
@section('page-title', 'Confirm Purchase')
@section('page-subtitle', 'Complete your subscription purchase')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i> Order Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Package Details</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Package Name</strong></td>
                                <td>{{ $package->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Price</strong></td>
                                <td><strong>PKR {{ number_format($package->price) }}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Duration</strong></td>
                                <td>{{ $package->duration_days }} days</td>
                            </tr>
                            <tr>
                                <td><strong>Job Posts</strong></td>
                                <td>{{ $package->job_posts_limit ?? 'Unlimited' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Features Included</h6>
                        <ul class="list-unstyled">
                            @foreach($package->features as $feature)
                                <li class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-12">
                        <h6>Select Payment Method</h6>
                        <form id="paymentForm">
                            @csrf
                            <input type="hidden" name="package_id" value="{{ $package->id }}">
                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="gateway" value="paypal" id="paypal">
                                        <label class="form-check-label w-100" for="paypal">
                                            <i class="fab fa-paypal me-2"></i> PayPal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="gateway" value="stripe" id="stripe">
                                        <label class="form-check-label w-100" for="stripe">
                                            <i class="fab fa-cc-stripe me-2"></i> Stripe
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="gateway" value="jazzcash" id="jazzcash">
                                        <label class="form-check-label w-100" for="jazzcash">
                                            <i class="fas fa-mobile-alt me-2"></i> JazzCash
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="gateway" value="easypaisa" id="easypaisa">
                                        <label class="form-check-label w-100" for="easypaisa">
                                            <i class="fas fa-mobile-alt me-2"></i> EasyPaisa
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check payment-option">
                                        <input class="form-check-input" type="radio" name="gateway" value="bank_transfer" id="bank_transfer">
                                        <label class="form-check-label w-100" for="bank_transfer">
                                            <i class="fas fa-university me-2"></i> Bank Transfer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('employer.packages') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button onclick="processPayment()" class="btn btn-primary btn-lg">
                    <i class="fas fa-lock me-2"></i> Pay PKR {{ number_format($package->price) }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.payment-option {
    padding: 12px 15px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.payment-option:hover {
    border-color: #2563eb;
    background: #f8fafc;
}

.payment-option input:checked + .form-check-label {
    color: #2563eb;
}

.payment-option:has(input:checked) {
    border-color: #2563eb;
    background: #eff6ff;
}
</style>

<script>
function processPayment() {
    const selected = document.querySelector('input[name="gateway"]:checked');
    if (!selected) {
        toastr.error('Please select a payment method');
        return;
    }

    const btn = document.querySelector('.card-footer .btn-primary');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Processing...';

    fetch('{{ route("payment.initiate") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            package_id: '{{ $package->id }}',
            gateway: selected.value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.redirect_url) {
            window.location.href = data.redirect_url;
        } else {
            toastr.error(data.message || 'Something went wrong');
            btn.disabled = false;
            btn.textContent = originalText;
        }
    })
    .catch(() => {
        toastr.error('An error occurred');
        btn.disabled = false;
        btn.textContent = originalText;
    });
}
</script>
@endsection
