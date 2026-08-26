{{-- resources/views/admin/faqs/categories/edit.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit FAQ Category')
@section('page-subtitle', 'Update FAQ category')

@push('styles')
<style>
    .form-label { font-weight: 600; font-size: 13px; color: #1e293b; }
    .form-control, .form-select { border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 14px; font-size: 14px; transition: all 0.2s ease; }
    .form-control:focus, .form-select:focus { border-color: #6366f1; box-shadow: 0 0 0 3px rgba(99,102,241,0.1); }
    .form-control.is-invalid, .form-select.is-invalid { border-color: #ef4444; box-shadow: 0 0 0 3px rgba(239,68,68,0.1); }
    .invalid-feedback { font-size: 12px; color: #ef4444; margin-top: 4px; display: none; }
    .invalid-feedback.show { display: block; }
    .required-star { color: #ef4444; margin-left: 2px; }
    .help-text { font-size: 12px; color: #94a3b8; margin-top: 4px; }
    .form-section-title { font-size: 14px; font-weight: 600; color: #1e293b; margin-bottom: 16px; padding-bottom: 8px; border-bottom: 2px solid #f1f5f9; }
    .form-section-title i { color: #6366f1; margin-right: 8px; }
    .btn-primary { background: #6366f1; border: none; padding: 10px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }
    .btn-primary:hover { background: #4f46e5; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(99,102,241,0.3); }
    .btn-secondary { border: 1px solid #e2e8f0; padding: 10px 24px; border-radius: 8px; font-weight: 600; transition: all 0.3s ease; }
    .btn-secondary:hover { background: #f1f5f9; border-color: #cbd5e1; }
    .icon-preview {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        background: #f1f5f9;
        font-size: 24px;
        color: #6366f1;
        transition: all 0.3s ease;
    }
    .icon-preview:hover {
        background: #e2e8f0;
        transform: scale(1.05);
    }
    .stats-mini {
        background: #f8fafc;
        border-radius: 8px;
        padding: 12px 16px;
        text-align: center;
        border: 1px solid #e2e8f0;
    }
    .stats-mini .number {
        font-size: 20px;
        font-weight: 700;
        color: #0f172a;
    }
    .stats-mini .label {
        font-size: 11px;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i> Edit FAQ Category
                    </h5>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="fas fa-hashtag me-1"></i> ID: {{ $faqCategory->id }}
                        </span>
                        <a href="{{ route('admin.faq-categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Stats Mini --}}
                    <div class="row g-3 mb-4">
                        <div class="col-md-3">
                            <div class="stats-mini">
                                <div class="number">{{ $faqCategory->faqs->count() }}</div>
                                <div class="label">Total FAQs</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini">
                                <div class="number">{{ $faqCategory->faqs()->where('is_active', true)->count() }}</div>
                                <div class="label">Active FAQs</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini">
                                <div class="number">{{ $faqCategory->created_at->format('d M, Y') }}</div>
                                <div class="label">Created</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stats-mini">
                                <div class="number">{{ $faqCategory->updated_at->diffForHumans() }}</div>
                                <div class="label">Last Updated</div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.faq-categories.update', $faqCategory) }}" method="POST" id="categoryForm" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-12 mb-3">
                                <div class="form-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category Name <span class="required-star">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $faqCategory->name) }}" placeholder="e.g. General, Technical, Support" required>
                                @error('name')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Icon</label>
                                <div class="input-group">
                                    <input type="text" name="icon" class="form-control @error('icon') is-invalid @enderror"
                                        value="{{ old('icon', $faqCategory->icon ?? 'fas fa-folder') }}" placeholder="e.g. fas fa-question-circle" id="iconInput">
                                    <span class="input-group-text" style="padding: 0;">
                                        <span class="icon-preview" id="iconPreview">
                                            <i class="{{ $faqCategory->icon ?? 'fas fa-folder' }}"></i>
                                        </span>
                                    </span>
                                </div>
                                <div class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    Font Awesome icon class.
                                    <a href="https://fontawesome.com/icons" target="_blank" class="text-primary">
                                        Browse icons <i class="fas fa-external-link-alt fa-xs"></i>
                                    </a>
                                </div>
                                @error('icon')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="3" placeholder="Brief description of this category">{{ old('description', $faqCategory->description) }}</textarea>
                                @error('description')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Settings --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="form-section-title"><i class="fas fa-cog"></i> Settings</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="order" class="form-control @error('order') is-invalid @enderror"
                                    value="{{ old('order', $faqCategory->order ?? 0) }}" min="0">
                                <div class="help-text">Lower numbers appear first</div>
                                @error('order')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', $faqCategory->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isActive">
                                        <i class="fas fa-circle text-success me-1"></i> Active
                                    </label>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12 mt-4">
                                <hr>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i> Update Category
                                    </button>
                                    <a href="{{ route('admin.faq-categories.index') }}" class="btn btn-secondary">
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
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Icon Preview
    const iconInput = document.getElementById('iconInput');
    const iconPreview = document.getElementById('iconPreview');

    if (iconInput && iconPreview) {
        iconInput.addEventListener('input', function() {
            const iconClass = this.value.trim() || 'fas fa-folder';
            iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
        });
    }

    // ✅ Form Submission
    const form = document.getElementById('categoryForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function() {
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
