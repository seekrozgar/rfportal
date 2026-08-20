{{-- resources/views/admin/payments/show.blade.php --}}
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-user me-2"></i> User Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Name</strong></td>
                        <td>{{ $payment->user->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Email</strong></td>
                        <td>{{ $payment->user->email ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Role</strong></td>
                        <td><span class="badge bg-primary">{{ ucfirst($payment->user->role ?? 'N/A') }}</span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-box me-2"></i> Package Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm">
                    <tr>
                        <td><strong>Package</strong></td>
                        <td>{{ $payment->package->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Type</strong></td>
                        <td><span
                                class="badge {{ $payment->package->type === 'employer' ? 'bg-primary' : 'bg-success' }}">
                                {{ ucfirst($payment->package->type) }}
                            </span></td>
                    </tr>
                    <tr>
                        <td><strong>Duration</strong></td>
                        <td>{{ $payment->package->duration_days ?? 'N/A' }} days</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-light">
                <h6 class="mb-0"><i class="fas fa-credit-card me-2"></i> Payment Details</h6>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <td><strong>Transaction ID</strong></td>
                        <td><code>{{ $payment->transaction_id ?? 'N/A' }}</code></td>
                    </tr>
                    <tr>
                        <td><strong>Amount</strong></td>
                        <td><strong>PKR {{ number_format($payment->amount) }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Gateway</strong></td>
                        <td><span class="badge bg-secondary">{{ ucfirst($payment->gateway) }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>
                            @if($payment->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                            @elseif($payment->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($payment->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                            @elseif($payment->status === 'refunded')
                                <span class="badge bg-info">Refunded</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Date</strong></td>
                        <td>{{ $payment->created_at->format('d M, Y h:i A') }}</td>
                    </tr>
                    @if($payment->gateway_response)
                        <tr>
                            <td><strong>Gateway Response</strong></td>
                            <td>
                                <pre
                                    class="small bg-light p-2 rounded">{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</pre>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>