{{-- resources/views/admin/job-postings/scrape.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Scrape Jobs')
@section('page-title', 'Scrape Jobs')
@section('page-subtitle', 'Fetch jobs from external sources')

@push('styles')
    <style>
        /* ✅ Source Cards */
        .source-card {
            border: 2px solid #e5e7eb;
            border-radius: 16px;
            padding: 24px 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            background: #fff;
            position: relative;
            overflow: hidden;
            height: 100%;
        }

        .source-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: #e5e7eb;
            transition: all 0.3s ease;
        }

        .source-card:hover {
            border-color: #6366f1;
            box-shadow: 0 8px 30px rgba(99, 102, 241, 0.12);
            transform: translateY(-4px);
        }

        .source-card.selected {
            border-color: #6366f1;
            background: #f5f3ff;
        }

        .source-card.selected::before {
            background: #6366f1;
        }

        .source-card .source-icon {
            font-size: 40px;
            margin-bottom: 12px;
            color: #6366f1;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: #eff6ff;
            margin: 0 auto 12px;
            transition: all 0.3s ease;
        }

        .source-card.selected .source-icon {
            background: #dbeafe;
            transform: scale(1.05);
        }

        .source-card .source-name {
            font-weight: 700;
            font-size: 16px;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .source-card .source-domain {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 12px;
        }

        .source-card .source-status {
            font-size: 11px;
            padding: 4px 14px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
        }

        .source-card .source-status.active {
            background: #dcfce7;
            color: #166534;
        }

        .source-card .source-status.inactive {
            background: #fee2e2;
            color: #991b1b;
        }

        .source-card .source-status.testing {
            background: #fef3c7;
            color: #92400e;
        }

        .source-card .form-check-input {
            margin-top: 0;
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .source-card .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .source-card .radio-label {
            font-size: 13px;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
        }

        .source-card .badge-count {
            background: #f1f5f9;
            color: #475569;
            padding: 2px 10px;
            border-radius: 12px;
            font-size: 11px;
        }

        .source-card .connection-test-btn {
            border: none;
            background: transparent;
            color: #94a3b8;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 13px;
        }

        .source-card .connection-test-btn:hover {
            background: #f1f5f9;
            color: #6366f1;
        }

        .source-card .connection-status {
            font-size: 12px;
            margin-left: 6px;
        }

        .source-card .connection-status.success {
            color: #22c55e;
        }

        .source-card .connection-status.error {
            color: #ef4444;
        }

        /* ✅ Form Styles */
        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .help-text {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        /* ✅ Alert Styles */
        .alert-info-custom {
            background: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 12px;
            padding: 16px 20px;
            color: #1e40af;
        }

        .alert-warning-custom {
            background: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 12px;
            padding: 16px 20px;
            color: #92400e;
        }

        .alert-success-custom {
            background: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 12px;
            padding: 16px 20px;
            color: #166534;
        }

        /* ✅ Buttons */
        .btn-primary {
            background: #6366f1;
            border: none;
            padding: 10px 28px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            border: 1px solid #e5e7eb;
            padding: 10px 28px;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: #fff;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* ✅ Animation */
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .scraping-active .source-card {
            opacity: 0.6;
            pointer-events: none;
        }

        .scraping-active .source-card.selected {
            opacity: 1;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            border-width: 0.15em;
        }

        /* ✅ Responsive */
        @media (max-width: 768px) {
            .source-card {
                padding: 16px;
            }

            .source-card .source-icon {
                width: 48px;
                height: 48px;
                font-size: 28px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Main Card --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-rss me-2 text-primary"></i> Scrape Jobs from External Sources
                        </h5>
                        <a href="{{ route('admin.job-postings.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        {{-- ✅ Info Alerts --}}
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="alert alert-info-custom">
                                    <div class="d-flex">
                                        <i class="fas fa-shield-alt me-3 mt-1 fs-5"></i>
                                        <div>
                                            <strong class="d-block">Secure Scraping</strong>
                                            <small>Jobs are fetched with rate limiting and proper headers to respect source
                                                servers.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning-custom">
                                    <div class="d-flex">
                                        <i class="fas fa-info-circle me-3 mt-1 fs-5"></i>
                                        <div>
                                            <strong class="d-block">Multiple Fallback Methods</strong>
                                            <small>System tries HTTP, file_get_contents, and cURL methods
                                                automatically.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.job-postings.scrape') }}" method="POST" id="scrapeForm">
                            @csrf

                            {{-- ✅ Source Selection --}}
                            <div class="row mb-4">
                                <div class="col-12 mb-3">
                                    <label class="form-label fs-5">
                                        <i class="fas fa-database me-2 text-primary"></i> Select Source
                                        <span class="required-star">*</span>
                                    </label>
                                    <small class="text-muted d-block">Choose a source to fetch jobs from</small>
                                </div>

                                @forelse($sources as $key => $name)
                                    <div class="col-md-4 mb-4">
                                        <div class="source-card" onclick="selectSource('{{ $key }}')" id="card-{{ $key }}">
                                            <div class="text-center">
                                                {{-- ✅ Icon --}}
                                                <div class="source-icon">
                                                    <i class="fas fa-rss"></i>
                                                </div>

                                                {{-- ✅ Name --}}
                                                <div class="source-name">{{ $name }}</div>
                                                <div class="source-domain">{{ $key }}.com</div>

                                                {{-- ✅ Status Badge --}}
                                                <div class="mb-2">
                                                    <span class="source-status active">
                                                        <i class="fas fa-check-circle me-1"></i> Active
                                                    </span>
                                                    <span class="badge-count ms-2">
                                                        <i class="fas fa-clock me-1"></i> Live
                                                    </span>
                                                </div>

                                                {{-- ✅ Connection Test --}}
                                                <div class="mb-2">
                                                    <button type="button" class="connection-test-btn"
                                                        onclick="testConnection('{{ $key }}')" id="testBtn-{{ $key }}"
                                                        title="Test connection to this source">
                                                        <i class="fas fa-plug me-1"></i> Test Connection
                                                    </button>
                                                    <span class="connection-status" id="status-{{ $key }}"></span>
                                                </div>

                                                {{-- ✅ Select Radio --}}
                                                <div class="mt-2">
                                                    <input type="radio" name="source" id="source_{{ $key }}" value="{{ $key }}"
                                                        class="form-check-input" required>
                                                    <label for="source_{{ $key }}" class="radio-label ms-2">
                                                        Select this source
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="alert alert-warning-custom">
                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                            <strong>No Sources Available</strong>
                                            <p class="mb-0 mt-1">Please configure sources in the JobScrapingService.</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            {{-- ✅ Options --}}
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <hr>
                                    <h6 class="fw-bold mb-3">
                                        <i class="fas fa-sliders-h me-2 text-primary"></i> Scraping Options
                                    </h6>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id" class="form-select">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="help-text">Assign scraped jobs to a category</div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Keywords (Optional)</label>
                                    <input type="text" name="keywords" class="form-control"
                                        placeholder="e.g. Laravel, PHP, Developer" value="{{ old('keywords') }}">
                                    <div class="help-text">Filter jobs by keywords</div>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Limit</label>
                                    <input type="number" name="limit" class="form-control" value="20" min="1" max="50">
                                    <div class="help-text">Maximum jobs to fetch (1-50)</div>
                                </div>

                                {{-- ✅ Auto-Publish Toggle --}}
                                <div class="col-md-12 mb-3">
                                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded-3">
                                        <div class="form-check form-switch mb-0">
                                            <input type="checkbox" name="auto_publish" class="form-check-input"
                                                id="autoPublish" value="1">
                                            <label class="form-check-label fw-bold" for="autoPublish">
                                                <i class="fas fa-check-circle text-success me-1"></i>
                                                Auto-publish scraped jobs
                                            </label>
                                        </div>
                                        <div class="flex-grow-1" id="autoPublishHelp">
                                            <small class="text-muted">
                                                <i class="fas fa-info-circle me-1"></i>
                                                <span id="statusText">Jobs will be saved as <strong>Drafts</strong> for
                                                    review.</span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ✅ Submit Buttons --}}
                            <div class="row mt-3">
                                <div class="col-12">
                                    <hr>
                                    <div class="d-flex gap-3 flex-wrap">
                                        <button type="submit" class="btn btn-primary" id="scrapeBtn">
                                            <i class="fas fa-rss me-2"></i> Start Scraping
                                        </button>
                                        <button type="reset" class="btn btn-secondary">
                                            <i class="fas fa-undo me-2"></i> Reset
                                        </button>
                                        <a href="{{ route('admin.job-postings.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- ✅ Scraping Tips --}}
                <div class="card shadow-sm border-0 mt-4">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas fa-lightbulb me-2 text-warning"></i> Scraping Tips & Guidelines
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 text-center">
                                <div class="p-3 rounded-3 bg-light">
                                    <i class="fas fa-shield-alt text-primary fa-2x mb-2"></i>
                                    <p class="small text-muted mb-0">Secure & Rate Limited</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="p-3 rounded-3 bg-light">
                                    <i class="fas fa-copy text-success fa-2x mb-2"></i>
                                    <p class="small text-muted mb-0">No Duplicates</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="p-3 rounded-3 bg-light">
                                    <i class="fas fa-clock text-warning fa-2x mb-2"></i>
                                    <p class="small text-muted mb-0">Saves as Drafts</p>
                                </div>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="p-3 rounded-3 bg-light">
                                    <i class="fas fa-tags text-info fa-2x mb-2"></i>
                                    <p class="small text-muted mb-0">Auto-categorize</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let toastShown = false;
            let isScraping = false;

            function showToast(type, message) {
                if (toastShown) return;
                if (typeof toastr !== 'undefined') {
                    const titles = { success: '✅ Success!', error: '❌ Error!', warning: '⚠️ Warning!', info: 'ℹ️ Info' };
                    toastr[type](message, titles[type] || 'Notification', {
                        timeOut: 5000,
                        progressBar: true,
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        preventDuplicates: true,
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                    });
                    toastShown = true;
                    setTimeout(() => { toastShown = false; }, 6000);
                } else { alert(message); }
            }

            function selectSource(key) {
                // ✅ Remove selected class from all cards
                document.querySelectorAll('.source-card').forEach(card => {
                    card.classList.remove('selected');
                });

                // ✅ Add selected class to clicked card
                const card = document.getElementById('card-' + key);
                if (card) {
                    card.classList.add('selected');
                }

                // ✅ Check the radio button
                const radio = document.getElementById('source_' + key);
                if (radio) {
                    radio.checked = true;
                }
            }

            function testConnection(key) {
                const statusEl = document.getElementById('status-' + key);
                const testBtn = document.getElementById('testBtn-' + key);

                if (!statusEl || !testBtn) return;

                statusEl.textContent = 'Testing...';
                statusEl.className = 'connection-status';
                testBtn.disabled = true;

                fetch('/admin/job-postings/test-connection', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    body: JSON.stringify({ source: key })
                })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            statusEl.textContent = '✓ Connected';
                            statusEl.className = 'connection-status success';
                            showToast('success', 'Connection to ' + key + ' successful!');
                        } else {
                            statusEl.textContent = '✗ ' + (data.message || 'Failed');
                            statusEl.className = 'connection-status error';
                            showToast('error', 'Connection failed: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        statusEl.textContent = '✗ Error';
                        statusEl.className = 'connection-status error';
                        showToast('error', 'Connection test failed: ' + error.message);
                    })
                    .finally(() => {
                        testBtn.disabled = false;
                        setTimeout(() => {
                            if (statusEl.textContent !== '✓ Connected') {
                                statusEl.textContent = '';
                                statusEl.className = 'connection-status';
                            }
                        }, 5000);
                    });
            }

            // ✅ Auto-publish toggle status update
            document.addEventListener('DOMContentLoaded', function () {
                const autoPublishCheckbox = document.getElementById('autoPublish');
                const statusText = document.getElementById('statusText');

                if (autoPublishCheckbox && statusText) {
                    autoPublishCheckbox.addEventListener('change', function () {
                        if (this.checked) {
                            statusText.innerHTML = 'Jobs will be <strong class="text-success">Published</strong> immediately.';
                        } else {
                            statusText.innerHTML = 'Jobs will be saved as <strong class="text-warning">Drafts</strong> for review.';
                        }
                    });
                }

                // ✅ Auto-select first source
                const firstSource = document.querySelector('input[name="source"]');
                if (firstSource) {
                    firstSource.checked = true;
                    const key = firstSource.value;
                    const card = document.getElementById('card-' + key);
                    if (card) {
                        card.classList.add('selected');
                    }
                }

                // ✅ Form submission
                const form = document.getElementById('scrapeForm');
                const scrapeBtn = document.getElementById('scrapeBtn');

                if (form && scrapeBtn) {
                    form.addEventListener('submit', function () {
                        if (isScraping) return;

                        // ✅ Check if source is selected
                        const selectedSource = document.querySelector('input[name="source"]:checked');
                        if (!selectedSource) {
                            showToast('error', 'Please select a source.');
                            return false;
                        }

                        isScraping = true;
                        scrapeBtn.disabled = true;
                        scrapeBtn.innerHTML = `
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Scraping...
                            `;

                        // ✅ Show scraping status on selected card
                        const card = document.getElementById('card-' + selectedSource.value);
                        if (card) {
                            card.style.opacity = '0.6';
                        }
                    });
                }

                // ✅ Toastr Notifications
                @if(session('toast'))
                    const toast = @json(session('toast'));
                    showToast(toast.type, toast.message);
                @endif

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        showToast('error', '{{ $error }}');
                    @endforeach
                @endif
                });

            // ✅ Add CSRF token meta if not exists
            if (!document.querySelector('meta[name="csrf-token"]')) {
                const meta = document.createElement('meta');
                meta.name = 'csrf-token';
                meta.content = '{{ csrf_token() }}';
                document.head.appendChild(meta);
            }
        </script>
    @endpush
@endsection