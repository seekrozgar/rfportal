{{-- resources/views/admin/scholarships/scrape.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Scrape Scholarships - Rozgar Finder')
@section('page-title', 'Scrape Scholarships')
@section('page-subtitle', 'Fetch scholarships from RSS feeds')

@push('styles')
    <style>
        .source-card {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .source-card:hover {
            border-color: #3b82f6;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .source-card.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .source-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .source-card .source-icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .source-card .source-name {
            font-weight: 600;
            font-size: 16px;
        }

        .source-card .source-status {
            font-size: 12px;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .source-card .source-status.active {
            background: #dcfce7;
            color: #166534;
        }

        .source-card .source-status.inactive {
            background: #fee2e2;
            color: #991b1b;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-rss me-2 text-primary"></i> Fetch Scholarships from RSS Feeds
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Select an RSS source to fetch scholarships. Scholarships will be saved as
                                    <strong>Drafts</strong> by default.
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.scholarships.scrape') }}" method="POST">
                            @csrf

                            <div class="row">
                                @foreach($sources as $key => $source)
                                    <div class="col-md-4 mb-4">
                                        <div class="source-card {{ $source['enabled'] ? '' : 'disabled' }}"
                                            onclick="selectSource('{{ $key }}')">
                                            <div class="source-icon text-center">
                                                <i class="fas fa-rss-square text-primary"></i>
                                            </div>
                                            <div class="source-name text-center">{{ $source['name'] }}</div>
                                            <div class="text-center mt-2">
                                                <span class="source-status {{ $source['enabled'] ? 'active' : 'inactive' }}">
                                                    {{ $source['enabled'] ? '✓ Active' : '✗ Disabled' }}
                                                </span>
                                            </div>
                                            <div class="text-center mt-2 small text-muted">
                                                <i class="fas fa-link"></i> {{ Str::limit($source['url'], 40) }}
                                            </div>
                                            <div class="text-center mt-3">
                                                <input type="radio" name="source" id="source_{{ $key }}" value="{{ $key }}"
                                                    class="form-check-input" {{ !$source['enabled'] ? 'disabled' : '' }}
                                                    required>
                                                <label for="source_{{ $key }}" class="ms-2">Select this source</label>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="row mt-3">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-rss me-2"></i> Fetch Selected Source
                                        </button>
                                        <a href="{{ route('admin.scholarships.scrape.all') }}" class="btn btn-success">
                                            <i class="fas fa-rss me-2"></i> Fetch All Active Sources
                                        </a>
                                        <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i> Back to List
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function selectSource(key) {
                const radio = document.getElementById('source_' + key);
                if (!radio.disabled) {
                    radio.checked = true;

                    // Remove selected class from all cards
                    document.querySelectorAll('.source-card').forEach(card => {
                        card.classList.remove('selected');
                    });

                    // Add selected class to the clicked card
                    const card = radio.closest('.source-card');
                    if (card) {
                        card.classList.add('selected');
                    }
                }
            }

            // Auto-select the first enabled source
            document.addEventListener('DOMContentLoaded', function () {
                const firstEnabled = document.querySelector('.source-card:not(.disabled) input[type="radio"]');
                if (firstEnabled) {
                    firstEnabled.checked = true;
                    const card = firstEnabled.closest('.source-card');
                    if (card) {
                        card.classList.add('selected');
                    }
                }
            });
        </script>
    @endpush
@endsection