{{-- resources/views/admin/faqs/edit.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Edit FAQ - Rozgar Finder')
@section('page-title', 'Edit FAQ')
@section('page-subtitle', 'Update frequently asked question')

@push('styles')
    <style>
        /* ✅ Form Styles */
        .form-label {
            font-weight: 600;
            font-size: 13px;
            color: #1e293b;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 10px 14px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .invalid-feedback {
            font-size: 12px;
            color: #ef4444;
            margin-top: 4px;
            display: none;
        }

        .invalid-feedback.show {
            display: block;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
        }

        .help-text {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 4px;
        }

        .form-section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-section-title i {
            color: #6366f1;
            margin-right: 8px;
        }

        .btn-primary {
            background: #6366f1;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #4f46e5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .btn-secondary {
            border: 1px solid #e2e8f0;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            border-color: #cbd5e1;
        }

        /* ✅ CKEditor 5 */
        .ck-editor__editable_inline {
            min-height: 250px !important;
            max-height: 450px !important;
        }

        .ck-editor__editable {
            border-radius: 8px !important;
            border-color: #e2e8f0 !important;
        }

        .ck-editor__editable:focus {
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1) !important;
        }

        .ck-editor__top {
            border-radius: 8px 8px 0 0 !important;
        }

        .ck-editor__bottom {
            border-radius: 0 0 8px 8px !important;
        }

        .ck.ck-editor {
            width: 100% !important;
        }

        .ck.ck-toolbar {
            border-radius: 8px 8px 0 0 !important;
            background: #f8fafc !important;
        }

        /* ✅ ============================================================
           ✅ PROFESSIONAL STATS CARDS - BEAUTIFUL
           ✅ ============================================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 14px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 14px 14px;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 1px solid #f1f5f9;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            min-height: 130px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            transition: height 0.3s ease;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.05);
            transition: height 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .stat-card:hover::before {
            height: 5px;
        }

        .stat-card:hover::after {
            height: 100%;
        }

        /* ✅ Card Colors */
        .stat-card.views::before {
            background: linear-gradient(90deg, #6366f1, #818cf8, #a78bfa);
        }

        .stat-card.views .stat-icon {
            background: #eef2ff;
            color: #6366f1;
        }

        .stat-card.views .stat-number {
            color: #6366f1;
        }

        .stat-card.views .stat-status {
            background: #eef2ff;
            color: #6366f1;
        }

        .stat-card.views:hover {
            border-color: #6366f1;
            box-shadow: 0 12px 40px rgba(99, 102, 241, 0.15);
        }

        .stat-card.helpful::before {
            background: linear-gradient(90deg, #22c55e, #4ade80, #86efac);
        }

        .stat-card.helpful .stat-icon {
            background: #dcfce7;
            color: #22c55e;
        }

        .stat-card.helpful .stat-number {
            color: #22c55e;
        }

        .stat-card.helpful .stat-status {
            background: #dcfce7;
            color: #22c55e;
        }

        .stat-card.helpful:hover {
            border-color: #22c55e;
            box-shadow: 0 12px 40px rgba(34, 197, 94, 0.15);
        }

        .stat-card.not-helpful::before {
            background: linear-gradient(90deg, #ef4444, #f87171, #fca5a5);
        }

        .stat-card.not-helpful .stat-icon {
            background: #fee2e2;
            color: #ef4444;
        }

        .stat-card.not-helpful .stat-number {
            color: #ef4444;
        }

        .stat-card.not-helpful .stat-status {
            background: #fee2e2;
            color: #ef4444;
        }

        .stat-card.not-helpful:hover {
            border-color: #ef4444;
            box-shadow: 0 12px 40px rgba(239, 68, 68, 0.15);
        }

        .stat-card.created::before {
            background: linear-gradient(90deg, #8b5cf6, #a78bfa, #c4b5fd);
        }

        .stat-card.created .stat-icon {
            background: #ede9fe;
            color: #8b5cf6;
        }

        .stat-card.created .stat-number {
            color: #8b5cf6;
        }

        .stat-card.created .stat-status {
            background: #ede9fe;
            color: #8b5cf6;
        }

        .stat-card.created:hover {
            border-color: #8b5cf6;
            box-shadow: 0 12px 40px rgba(139, 92, 246, 0.15);
        }

        .stat-card.updated::before {
            background: linear-gradient(90deg, #0ea5e9, #38bdf8, #7dd3fc);
        }

        .stat-card.updated .stat-icon {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .stat-card.updated .stat-number {
            color: #0ea5e9;
        }

        .stat-card.updated .stat-status {
            background: #e0f2fe;
            color: #0ea5e9;
        }

        .stat-card.updated:hover {
            border-color: #0ea5e9;
            box-shadow: 0 12px 40px rgba(14, 165, 233, 0.15);
        }

        /* ✅ Stat Card Content */
        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            font-size: 18px;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .stat-card:hover .stat-icon {
            transform: scale(1.08) rotate(-2deg);
        }

        .stat-card .stat-number {
            font-size: 24px;
            font-weight: 800;
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .stat-card .stat-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 4px;
        }

        .stat-card .stat-status {
            font-size: 11px;
            font-weight: 600;
            margin-top: 6px;
            padding: 4px 14px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.3s ease;
        }

        .stat-card:hover .stat-status {
            transform: scale(1.02);
        }

        /* ✅ Responsive */
        @media (max-width: 992px) {
            .stats-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .form-section-title {
                font-size: 13px;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .d-flex.gap-2 {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .stat-card {
                padding: 14px 10px;
                min-height: 110px;
            }

            .stat-card .stat-number {
                font-size: 20px;
            }

            .stat-card .stat-icon {
                width: 38px;
                height: 38px;
                font-size: 16px;
            }

            .stat-card .stat-status {
                font-size: 10px;
                padding: 2px 10px;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 8px;
            }

            .stat-card {
                padding: 12px 8px;
                min-height: 100px;
            }

            .stat-card .stat-number {
                font-size: 18px;
            }

            .stat-card .stat-icon {
                width: 34px;
                height: 34px;
                font-size: 14px;
            }

            .stat-card .stat-label {
                font-size: 9px;
                letter-spacing: 0.4px;
            }

            .stat-card .stat-status {
                font-size: 9px;
                padding: 1px 8px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-edit me-2 text-primary"></i> Edit FAQ
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border px-3 py-2">
                                <i class="fas fa-hashtag me-1"></i> ID: {{ $faq->id }}
                            </span>
                            <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        {{-- ✅ Professional Stats Cards --}}
                        <div class="stats-grid">
                            {{-- Views --}}
                            <div class="stat-card views">
                                <div class="stat-icon">
                                    <i class="fas fa-eye"></i>
                                </div>
                                <div class="stat-number">{{ number_format($faq->views_count) }}</div>
                                <div class="stat-label">Views</div>
                                <div class="stat-status">
                                    @if($faq->views_count > 100)
                                        🔥 Popular
                                    @elseif($faq->views_count > 50)
                                        📈 Growing
                                    @else
                                        🆕 New
                                    @endif
                                </div>
                            </div>

                            {{-- Helpful --}}
                            <div class="stat-card helpful">
                                <div class="stat-icon">
                                    <i class="fas fa-thumbs-up"></i>
                                </div>
                                <div class="stat-number">{{ number_format($faq->helpful_count) }}</div>
                                <div class="stat-label">Helpful</div>
                                <div class="stat-status">
                                    👍 {{ $faq->helpful_percentage }}%
                                </div>
                            </div>

                            {{-- Not Helpful --}}
                            <div class="stat-card not-helpful">
                                <div class="stat-icon">
                                    <i class="fas fa-thumbs-down"></i>
                                </div>
                                <div class="stat-number">{{ number_format($faq->not_helpful_count) }}</div>
                                <div class="stat-label">Not Helpful</div>
                                <div class="stat-status">
                                    👎 {{ 100 - $faq->helpful_percentage }}%
                                </div>
                            </div>

                            {{-- Created --}}
                            <div class="stat-card created">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="stat-number">{{ $faq->created_at->format('d M') }}</div>
                                <div class="stat-label">Created</div>
                                <div class="stat-status">
                                    📅 {{ $faq->created_at->diffForHumans() }}
                                </div>
                            </div>

                            {{-- Updated --}}
                            <div class="stat-card updated">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-number">{{ $faq->updated_at->format('d M') }}</div>
                                <div class="stat-label">Updated</div>
                                <div class="stat-status">
                                    ⏰ {{ $faq->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('admin.faqs.update', $faq) }}" method="POST" id="faqForm" novalidate>
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Basic Information --}}
                                <div class="col-12 mb-3">
                                    <div class="form-section-title"><i class="fas fa-info-circle"></i> Basic Information
                                    </div>
                                </div>

                                <div class="col-md-8 mb-3">
                                    <label class="form-label">Question <span class="required-star">*</span></label>
                                    <input type="text" name="question"
                                        class="form-control @error('question') is-invalid @enderror"
                                        value="{{ old('question', $faq->question) }}" placeholder="Enter the question"
                                        required>
                                    @error('question')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $faq->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                {{-- Answer --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Answer <span class="required-star">*</span></label>
                                    <textarea name="answer"
                                        class="form-control ckeditor5 @error('answer') is-invalid @enderror"
                                        id="answerEditor" rows="8">{{ old('answer', $faq->answer) }}</textarea>
                                    @error('answer')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                {{-- Settings --}}
                                <div class="col-12 mb-3 mt-2">
                                    <div class="form-section-title"><i class="fas fa-cog"></i> Settings</div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="order"
                                        class="form-control @error('order') is-invalid @enderror"
                                        value="{{ old('order', $faq->order ?? 0) }}" min="0">
                                    <div class="help-text">Lower numbers appear first</div>
                                    @error('order')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive"
                                            value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isActive">
                                            <i class="fas fa-circle text-success me-1"></i> Active
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured"
                                            value="1" {{ old('is_featured', $faq->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isFeatured">
                                            <i class="fas fa-star text-warning me-1"></i> Featured
                                        </label>
                                    </div>
                                </div>

                                {{-- SEO --}}
                                <div class="col-12 mb-3 mt-2">
                                    <div class="form-section-title"><i class="fas fa-search"></i> SEO Information</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title"
                                        class="form-control @error('meta_title') is-invalid @enderror"
                                        value="{{ old('meta_title', $faq->meta_title) }}" placeholder="SEO title"
                                        maxlength="60">
                                    @error('meta_title')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description"
                                        class="form-control @error('meta_description') is-invalid @enderror" rows="2"
                                        placeholder="SEO description"
                                        maxlength="160">{{ old('meta_description', $faq->meta_description) }}</textarea>
                                    @error('meta_description')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords"
                                        class="form-control @error('meta_keywords') is-invalid @enderror"
                                        value="{{ old('meta_keywords', $faq->meta_keywords) }}"
                                        placeholder="Comma separated keywords">
                                    @error('meta_keywords')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 mt-4">
                                    <hr>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i> Update FAQ
                                        </button>
                                        <button type="reset" class="btn btn-outline-secondary">
                                            <i class="fas fa-undo me-2"></i> Reset
                                        </button>
                                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i> Cancel
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
            document.addEventListener('DOMContentLoaded', function () {
                // ✅ CKEditor 5
                if (typeof ClassicEditor !== 'undefined') {
                    if (window.answerEditor) {
                        window.answerEditor.destroy().catch(e => console.warn('Editor destroy error:', e));
                    }

                    const el = document.getElementById('answerEditor');
                    if (el && !el.dataset.ckeditor) {
                        ClassicEditor.create(el, {
                            toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|',
                                'bulletedList', 'numberedList', '|', 'blockQuote', 'link',
                                '|', 'undo', 'redo'],
                            removePlugins: ['Title'],
                        })
                            .then(editor => {
                                window.answerEditor = editor;
                                el.dataset.ckeditor = 'true';
                            })
                            .catch(error => console.error('CKEditor error:', error));
                    }
                }

                // ✅ Form Submission
                const form = document.getElementById('faqForm');
                const submitBtn = document.getElementById('submitBtn');

                if (form && submitBtn) {
                    form.addEventListener('submit', function () {
                        if (window.answerEditor) {
                            window.answerEditor.updateSourceElement();
                        }
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        Updating...
                    `;
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

            function showToast(type, message) {
                if (typeof toastr !== 'undefined') {
                    const titles = {
                        success: '✅ Success!',
                        error: '❌ Error!',
                        warning: '⚠️ Warning!',
                        info: 'ℹ️ Info'
                    };
                    toastr[type](message, titles[type] || 'Notification', {
                        timeOut: 5000,
                        progressBar: true,
                        closeButton: true,
                        positionClass: 'toast-top-right',
                        preventDuplicates: true,
                        showMethod: 'slideDown',
                        hideMethod: 'slideUp',
                    });
                } else {
                    alert(message);
                }
            }
        </script>
    @endpush
@endsection
