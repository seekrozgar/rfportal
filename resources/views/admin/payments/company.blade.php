{{-- resources/views/admin/payments/company.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Company Payments')
@section('page-title', 'Company Payments')
@section('page-subtitle', 'Manage employer subscription payments')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Stats Cards --}}
                <div class="row g-4 mb-4">
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-success">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">PKR {{ number_format($totalAmount ?? 0) }}</div>
                                    <div class="stats-label">Total Revenue</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 100%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $payments->total() }}</div>
                                    <div class="stats-label">T Transactions</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 75%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-completed">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $payments->where('status', 'completed')->count() }}</div>
                                    <div class="stats-label">Completed</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 60%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="stats-card">
                            <div class="stats-card-body">
                                <div class="stats-icon-wrapper bg-danger">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                                <div class="stats-info">
                                    <div class="stats-number">{{ $payments->where('status', 'failed')->count() }}</div>
                                    <div class="stats-label">Failed</div>
                                </div>
                            </div>
                            <div class="stats-progress-bar">
                                <div class="stats-progress-fill" style="width: 20%;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ✅ Payments Table --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-building me-2 text-primary"></i> Company Payments
                        </h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-2" onclick="location.reload()">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportPayments()">
                                <i class="fas fa-file-export"></i> Export
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="paymentsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-center" style="width: 50px;">#</th>
                                        <th>Transaction ID</th>
                                        <th>Company</th>
                                        <th>Package</th>
                                        <th class="text-end">Amount</th>
                                        <th class="text-center">Gateway</th>
                                        <th class="text-center">Status</th>
                                        <th style="width: 160px;">Date</th>
                                        <th class="text-center" style="width: 80px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr id="row-{{ $payment->id }}">
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <code class="transaction-id">{{ $payment->transaction_id ?? 'N/A' }}</code>
                                            </td>
                                            <td>
                                                <div class="company-info">
                                                    <strong>{{ $payment->user->name ?? 'N/A' }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $payment->user->email ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="package-badge">
                                                    <i class="fas fa-box me-1"></i>
                                                    {{ $payment->package->name ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="amount-text">PKR {{ number_format($payment->amount) }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="gateway-badge">
                                                    <i class="fas fa-credit-card me-1"></i>
                                                    {{ ucfirst($payment->gateway) }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($payment->status === 'completed')
                                                    <span class="status-badge status-completed">
                                                        <i class="fas fa-check-circle me-1"></i> Completed
                                                    </span>
                                                @elseif($payment->status === 'pending')
                                                    <span class="status-badge status-pending">
                                                        <i class="fas fa-clock me-1"></i> Pending
                                                    </span>
                                                @elseif($payment->status === 'failed')
                                                    <span class="status-badge status-failed">
                                                        <i class="fas fa-times-circle me-1"></i> Failed
                                                    </span>
                                                @elseif($payment->status === 'refunded')
                                                    <span class="status-badge status-refunded">
                                                        <i class="fas fa-undo me-1"></i> Refunded
                                                    </span>
                                                @else
                                                    <span class="status-badge status-unknown">{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="date-info">
                                                    <div>{{ $payment->created_at->format('d M, Y') }}</div>
                                                    <small
                                                        class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <button onclick="viewPayment({{ $payment->id }})" class="btn-action"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center py-5">
                                                <div class="empty-state">
                                                    <i class="fas fa-building fa-4x d-block mb-3 text-muted"></i>
                                                    <h5 class="text-muted">No Company Payments</h5>
                                                    <p class="text-muted small">No employer subscription payments found.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                Showing {{ $payments->firstItem() ?? 0 }} to {{ $payments->lastItem() ?? 0 }} of
                                {{ $payments->total() }} entries
                            </div>
                            <div>
                                {{ $payments->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- View Payment Modal --}}
    <div class="modal fade" id="paymentModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-credit-card me-2 text-primary"></i> Payment Details
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="paymentDetails">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading payment details...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ✅ Stats Cards */
        .stats-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #eef2f6;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.08);
            border-color: #dbeafe;
        }

        .stats-card-body {
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .stats-icon-wrapper {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 22px;
            flex-shrink: 0;
        }

        .stats-icon-wrapper.bg-success {
            background: linear-gradient(135deg, #22c55e, #16a34a);
        }

        .stats-icon-wrapper.bg-primary {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
        }

        .stats-icon-wrapper.bg-completed {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .stats-icon-wrapper.bg-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }

        .stats-info {
            flex: 1;
            min-width: 0;
        }

        .stats-number {
            font-size: 24px;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .stats-label {
            font-size: 12px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            font-weight: 600;
            margin-top: 2px;
        }

        .stats-progress-bar {
            height: 3px;
            background: #f1f5f9;
            width: 100%;
        }

        .stats-progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            border-radius: 0 2px 2px 0;
            transition: width 1s ease;
        }

        /* ✅ Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .status-completed {
            background: #dcfce7;
            color: #166534;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-refunded {
            background: #e0e7ff;
            color: #3730a3;
        }

        .status-unknown {
            background: #f1f5f9;
            color: #475569;
        }

        /* ✅ Package Badge */
        .package-badge {
            display: inline-flex;
            align-items: center;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 4px 14px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        /* ✅ Gateway Badge */
        .gateway-badge {
            display: inline-flex;
            align-items: center;
            background: #f8fafc;
            color: #475569;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            border: 1px solid #e2e8f0;
        }

        /* ✅ Transaction ID */
        .transaction-id {
            background: #f8fafc;
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
            color: #475569;
            font-family: monospace;
        }

        /* ✅ Amount Text */
        .amount-text {
            font-weight: 600;
            color: #0f172a;
            font-size: 14px;
        }

        /* ✅ Company Info */
        .company-info {
            line-height: 1.4;
        }

        .company-info strong {
            font-size: 14px;
            color: #0f172a;
        }

        /* ✅ Date Info */
        .date-info {
            line-height: 1.4;
            font-size: 13px;
        }

        /* ✅ Action Button */
        .btn-action {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            border-radius: 8px;
            color: #94a3b8;
            transition: all 0.2s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-action:hover {
            background: #eff6ff;
            color: #2563eb;
            transform: scale(1.05);
        }

        /* ✅ Empty State */
        .empty-state i {
            opacity: 0.3;
        }

        /* ✅ Table Styling */
        .table th {
            font-weight: 600;
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e2e8f0;
            padding: 12px 16px;
        }

        .table td {
            padding: 14px 16px;
            vertical-align: middle;
            font-size: 13px;
            border-bottom: 1px solid #f1f5f9;
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        /* ✅ Responsive */
        @media (max-width: 768px) {
            .stats-card-body {
                padding: 14px 16px;
            }

            .stats-icon-wrapper {
                width: 40px;
                height: 40px;
                font-size: 16px;
            }

            .stats-number {
                font-size: 18px;
            }

            .stats-label {
                font-size: 10px;
            }
        }

        @media (max-width: 576px) {
            .stats-card-body {
                flex-direction: column;
                text-align: center;
                gap: 8px;
            }

            .stats-info {
                text-align: center;
            }

            .stats-number {
                font-size: 16px;
            }
        }
    </style>
@endsection
@push('scripts')
    <script>
        function viewPayment(id) {
            const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
            const body = document.getElementById('paymentDetails');

            body.innerHTML = `
                            <div class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-3 text-muted">Loading payment details...</p>
                            </div>
                        `;

            modal.show();

            fetch(`/admin/payments/${id}`)
                .then(response => response.text())
                .then(html => {
                    body.innerHTML = html;
                })
                .catch(() => {
                    body.innerHTML = `
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        Failed to load payment details.
                                    </div>
                                `;
                });
        }

        function exportPayments() {
            alert('Export functionality coming soon!');
        }
    </script>
@endpush