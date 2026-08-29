@extends('employer.layouts.employer')

@section('title', 'Notifications')
@section('page-title', 'Notifications')
@section('page-subtitle', 'Your latest notifications')

@section('content')

    <div class="container-fluid px-4">

        <div class="card border-0 shadow-sm">

            <div class="card-header bg-white py-3">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-bell text-success me-2"></i>
                            Notifications
                        </h5>

                        <small class="text-muted">
                            Company and account notifications
                        </small>
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-success" id="employerMarkAllRead">
                        <i class="fas fa-check-double me-1"></i>
                        Mark All Read
                    </button>

                </div>

            </div>

            <div class="card-body p-0">

                @forelse($notifications as $notification)

                    <div class="d-flex gap-3 p-3 border-bottom">

                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="
                                    width:42px;
                                    height:42px;
                                    min-width:42px;
                                    background:#ecfdf5;
                                    color:#059669;
                                ">
                            <i class="fas fa-{{ $notification->icon ?: 'bell' }}"></i>
                        </div>

                        <div class="flex-grow-1">

                            <div class="d-flex justify-content-between">

                                <strong>
                                    {{ $notification->title }}
                                </strong>

                                <small class="text-muted">
                                    {{ $notification->created_at->diffForHumans() }}
                                </small>

                            </div>

                            <div class="text-muted small mt-1">
                                {{ $notification->message }}
                            </div>

                            @if($notification->action_url)

                                <a href="{{ $notification->action_url }}" class="btn btn-sm btn-outline-success mt-2">
                                    View
                                </a>

                            @endif

                        </div>

                    </div>

                @empty

                    <div class="text-center py-5 text-muted">

                        <i class="fas fa-bell-slash fa-3x mb-3"></i>

                        <div>
                            No notifications yet.
                        </div>

                    </div>

                @endforelse

            </div>

            @if($notifications->hasPages())

                <div class="card-footer bg-white">
                    {{ $notifications->links() }}
                </div>
            @endif

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const button =
                document.getElementById('employerMarkAllRead');

            if (!button) {
                return;
            }

            button.addEventListener('click', async function () {

                await fetch(
                    '{{ route("notifications.mark-all-read") }}',
                    {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN':
                                '{{ csrf_token() }}'
                        }
                    }
                );

                window.location.reload();

            });

        });
    </script>

@endsection