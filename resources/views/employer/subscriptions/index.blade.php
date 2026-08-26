{{-- resources/views/employer/packages/subscriptions.blade.php --}}
@extends('employer.layouts.dashboard')

@section('title', 'My Subscriptions')
@section('page-title', 'Subscription History')
@section('page-subtitle', 'View all your past and active subscriptions')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-history me-2"></i> My Subscriptions</h5>
        </div>
        <div class="card-body">
            @if($subscriptions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Package</th>
                                <th>Price</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Status</th>
                                <th>Jobs Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscriptions as $sub)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $sub->package->name }}</strong></td>
                                    <td>PKR {{ number_format($sub->package->price) }}</td>
                                    <td>{{ $sub->start_date->format('d M, Y') }}</td>
                                    <td>{{ $sub->end_date->format('d M, Y') }}</td>
                                    <td>
                                        @if($sub->status === 'active' && $sub->end_date > now())
                                            <span class="badge bg-success">Active</span>
                                        @elseif($sub->status === 'expired' || $sub->end_date <= now())
                                            <span class="badge bg-danger">Expired</span>
                                        @elseif($sub->status === 'cancelled')
                                            <span class="badge bg-warning">Cancelled</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($sub->status) }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $sub->job_posts_used }} / {{ $sub->job_posts_limit }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $subscriptions->links() }}
            @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3 d-block"></i>
                    <p class="text-muted">No subscriptions found.</p>
                    <a href="{{ route('employer.packages') }}" class="btn btn-primary">
                        <i class="fas fa-shopping-cart me-2"></i> Buy a Package
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection