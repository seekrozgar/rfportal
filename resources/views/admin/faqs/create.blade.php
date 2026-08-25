{{-- resources/views/admin/faqs/create.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Add FAQ - Rozgar Finder')
@section('page-title', 'Add FAQ')
@section('page-subtitle', 'Create a new frequently asked question')


@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div
                        class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-plus-circle me-2 text-primary"></i> Add New FAQ
                        </h5>
                        <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.faqs.store') }}" method="POST" id="faqForm" novalidate>
                            @csrf

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
                                        value="{{ old('question') }}" placeholder="Enter the question" required>
                                    @error('question')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Category</label>
                                    <select name="category_id"
                                        class="form-select @error('category_id') is-invalid @enderror">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                    <textarea name="answer" class="form-control @error('answer') is-invalid @enderror"
                                        id="answerEditor" rows="8">{{ old('answer') }}</textarea>
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
                                        value="{{ old('order', 0) }}" min="0">
                                    <div class="help-text">Lower numbers appear first</div>
                                    @error('order')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive"
                                            value="1" checked>
                                        <label class="form-check-label" for="isActive">
                                            <i class="fas fa-circle text-success me-1"></i> Active
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="form-check form-switch mt-4">
                                        <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured"
                                            value="1" {{ old('is_featured') ? 'checked' : '' }}>
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
                                        value="{{ old('meta_title') }}" placeholder="SEO title" maxlength="60">
                                    @error('meta_title')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description"
                                        class="form-control @error('meta_description') is-invalid @enderror" rows="2"
                                        placeholder="SEO description"
                                        maxlength="160">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 mb-3">
                                    <label class="form-label">Meta Keywords</label>
                                    <input type="text" name="meta_keywords"
                                        class="form-control @error('meta_keywords') is-invalid @enderror"
                                        value="{{ old('meta_keywords') }}" placeholder="Comma separated keywords">
                                    @error('meta_keywords')
                                    <div class="invalid-feedback show">{{ $message }}</div>@enderror
                                </div>

                                {{-- Submit --}}
                                <div class="col-12 mt-4">
                                    <hr>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i> Save FAQ
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
                    const el = document.getElementById('answerEditor');
                    if (el && !el.dataset.ckeditor) {
                        ClassicEditor.create(el, {
                            toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'link', '|', 'undo', 'redo'],
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
                                Saving...
                            `;
                    });
                }

                // ✅ Toastr Notifications
                @if(session('toast'))
                    const toast = @json(session('toast'));
                    showToast(toast.type, toast.message);
                @endif
                });

            function showToast(type, message) {
                if (typeof toastr !== 'undefined') {
                    const titles = { success: '✅ Success!', error: '❌ Error!', warning: '⚠️ Warning!', info: 'ℹ️ Info' };
                    toastr[type](message, titles[type] || 'Notification', {
                        timeOut: 5000, progressBar: true, closeButton: true,
                        positionClass: 'toast-top-right', preventDuplicates: true,
                        showMethod: 'slideDown', hideMethod: 'slideUp',
                    });
                } else { alert(message); }
            }
        </script>
    @endpush
@endsection
