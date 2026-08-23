{{-- resources/views/admin/job-categories/create.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Add Category - Rozgar Finder')
@section('page-title', 'Add Category')
@section('page-subtitle', 'Create a new job category')

@push('styles')
    <style>
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
        }

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

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        .form-check-input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
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
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-plus-circle me-2 text-primary"></i> Add New Category
                        </h5>
                        <a href="{{ route('admin.job-categories.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to List
                        </a>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.job-categories.store') }}" method="POST" enctype="multipart/form-data"
                            id="categoryForm" novalidate>
                            @csrf

                            <div class="row">
                                {{-- ✅ Basic Information Section --}}
                                <div class="col-12 mb-3">
                                    <div class="form-section-title">
                                        <i class="fas fa-info-circle"></i> Basic Information
                                    </div>
                                </div>

                                {{-- Name --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">
                                        Category Name <span class="required-star">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name') }}" placeholder="Enter category name" required
                                        id="categoryName">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i> This will be used as the category display name
                                    </div>
                                </div>

                                {{-- Parent Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Parent Category</label>
                                    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                        <option value="">None (Root Category)</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', request('parent_id')) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                                @if($parent->children->count() > 0)
                                                    ({{ $parent->children->count() }} sub-categories)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        @if(request('parent_id'))
                                            Currently adding subcategory to selected parent.
                                        @else
                                            Select a parent category to create a sub-category.
                                        @endif
                                    </div>
                                </div>

                                {{-- Icon --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Icon</label>
                                    <div class="input-group">
                                        <input type="text" name="icon"
                                            class="form-control @error('icon') is-invalid @enderror"
                                            value="{{ old('icon', 'fas fa-folder') }}" placeholder="e.g., fas fa-code"
                                            id="iconInput">
                                        <span class="input-group-text" style="padding: 0;">
                                            <span class="icon-preview" id="iconPreview">
                                                <i class="fas fa-folder"></i>
                                            </span>
                                        </span>
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Font Awesome icon class.
                                        <a href="https://fontawesome.com/icons" target="_blank" class="text-primary">
                                            Browse icons <i class="fas fa-external-link-alt fa-xs"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Order --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="order"
                                        class="form-control @error('order') is-invalid @enderror"
                                        value="{{ old('order', 0) }}" min="0" id="orderInput">
                                    @error('order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i> Lower numbers appear first in listings
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea name="description"
                                        class="form-control @error('description') is-invalid @enderror" rows="4"
                                        placeholder="Brief description of this category"
                                        id="descriptionInput">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text" id="charCount">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="charCountValue">0</span> characters (recommended: 150-300)
                                    </div>
                                </div>

                                {{-- ✅ Media & Status Section --}}
                                <div class="col-12 mb-3 mt-3">
                                    <div class="form-section-title">
                                        <i class="fas fa-image"></i> Media & Status
                                    </div>
                                </div>

                                {{-- Featured Image --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Featured Image</label>
                                    <div class="image-upload-wrapper">
                                        <input type="file" name="featured_image"
                                            class="form-control @error('featured_image') is-invalid @enderror"
                                            accept="image/*" id="featuredImage">
                                        <div id="imagePreview" class="mt-2" style="display: none;">
                                            <img id="imagePreviewImg" src="#" alt="Preview"
                                                style="max-width: 150px; max-height: 100px; border-radius: 8px; border: 2px solid #e2e8f0; padding: 4px;">
                                        </div>
                                    </div>
                                    @error('featured_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Max 2MB. Supported: JPG, PNG, GIF, WebP
                                    </div>
                                </div>

                                {{-- Status --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="form-check form-switch mt-2">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" name="is_active" class="form-check-input" id="isActive"
                                            value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                            onchange="updateStatusBadge(this)">
                                        <label class="form-check-label" for="isActive">
                                            <span id="statusBadge"
                                                class="badge {{ old('is_active', true) ? 'bg-success' : 'bg-danger' }}">
                                                {{ old('is_active', true) ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Inactive categories will not be visible on the frontend
                                    </div>
                                </div>

                                {{-- ✅ SEO Section --}}
                                <div class="col-12 mb-3 mt-3">
                                    <div class="form-section-title">
                                        <i class="fas fa-search"></i> SEO Information
                                    </div>
                                </div>

                                {{-- SEO - Meta Title --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Title</label>
                                    <input type="text" name="meta_title"
                                        class="form-control @error('meta_title') is-invalid @enderror"
                                        value="{{ old('meta_title') }}" placeholder="SEO title" id="metaTitle"
                                        maxlength="60">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="metaTitleCount">0</span>/60 characters
                                    </div>
                                </div>

                                {{-- SEO - Meta Description --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description"
                                        class="form-control @error('meta_description') is-invalid @enderror" rows="2"
                                        placeholder="SEO description" id="metaDescription"
                                        maxlength="160">{{ old('meta_description') }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="metaDescriptionCount">0</span>/160 characters
                                    </div>
                                </div>

                                {{-- SEO - Keywords --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" name="keywords"
                                        class="form-control @error('keywords') is-invalid @enderror"
                                        value="{{ old('keywords') }}" placeholder="Comma separated keywords"
                                        id="keywordsInput">
                                    @error('keywords')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Comma separated keywords for SEO (e.g., jobs, careers, employment)
                                    </div>
                                </div>

                                {{-- ✅ Submit Buttons --}}
                                <div class="col-12 mt-4">
                                    <hr>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <button type="submit" class="btn btn-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i> Save Category
                                        </button>
                                        <a href="{{ route('admin.job-categories.index') }}" class="btn btn-secondary">
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
                // ✅ ============================================================
                // ✅ 1. ICON PREVIEW
                // ✅ ============================================================
                const iconInput = document.getElementById('iconInput');
                const iconPreview = document.getElementById('iconPreview');

                if (iconInput && iconPreview) {
                    iconInput.addEventListener('input', function () {
                        const iconClass = this.value.trim() || 'fas fa-folder';
                        iconPreview.innerHTML = `<i class="${iconClass}"></i>`;
                    });
                }

                // ✅ ============================================================
                // ✅ 2. IMAGE PREVIEW
                // ✅ ============================================================
                const featuredImage = document.getElementById('featuredImage');
                const imagePreview = document.getElementById('imagePreview');
                const imagePreviewImg = document.getElementById('imagePreviewImg');

                if (featuredImage) {
                    featuredImage.addEventListener('change', function (e) {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                imagePreviewImg.src = e.target.result;
                                imagePreview.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        } else {
                            imagePreview.style.display = 'none';
                        }
                    });
                }

                // ✅ ============================================================
                // ✅ 3. CHARACTER COUNTERS
                // ✅ ============================================================
                // Description counter
                const descriptionInput = document.getElementById('descriptionInput');
                const charCountValue = document.getElementById('charCountValue');

                if (descriptionInput && charCountValue) {
                    descriptionInput.addEventListener('input', function () {
                        const count = this.value.length;
                        charCountValue.textContent = count;

                        // Color change based on length
                        if (count < 150) {
                            charCountValue.style.color = '#94a3b8';
                        } else if (count >= 150 && count <= 300) {
                            charCountValue.style.color = '#22c55e';
                        } else {
                            charCountValue.style.color = '#ef4444';
                        }
                    });

                    // Trigger on load
                    descriptionInput.dispatchEvent(new Event('input'));
                }

                // Meta Title counter
                const metaTitle = document.getElementById('metaTitle');
                const metaTitleCount = document.getElementById('metaTitleCount');

                if (metaTitle && metaTitleCount) {
                    metaTitle.addEventListener('input', function () {
                        const count = this.value.length;
                        metaTitleCount.textContent = count;

                        if (count <= 60) {
                            metaTitleCount.style.color = '#22c55e';
                        } else {
                            metaTitleCount.style.color = '#ef4444';
                        }
                    });

                    metaTitle.dispatchEvent(new Event('input'));
                }

                // Meta Description counter
                const metaDescription = document.getElementById('metaDescription');
                const metaDescriptionCount = document.getElementById('metaDescriptionCount');

                if (metaDescription && metaDescriptionCount) {
                    metaDescription.addEventListener('input', function () {
                        const count = this.value.length;
                        metaDescriptionCount.textContent = count;

                        if (count <= 160) {
                            metaDescriptionCount.style.color = '#22c55e';
                        } else {
                            metaDescriptionCount.style.color = '#ef4444';
                        }
                    });

                    metaDescription.dispatchEvent(new Event('input'));
                }

                // ✅ ============================================================
                // ✅ 4. STATUS TOGGLE
                // ✅ ============================================================
                const isActiveCheckbox = document.getElementById('isActive');
                const statusLabel = document.getElementById('statusLabel');

                if (isActiveCheckbox && statusLabel) {
                    isActiveCheckbox.addEventListener('change', function () {
                        const isActive = this.checked;
                        statusLabel.innerHTML = `
                                                                                                                                <span class="badge ${isActive ? 'bg-success' : 'bg-danger'}">
                                                                                                                                    ${isActive ? 'Active' : 'Inactive'}
                                                                                                                                </span>
                                                                                                                            `;
                    });
                }

                // ✅ ============================================================
                // ✅ 5. FORM SUBMISSION WITH LOADER
                // ✅ ============================================================
                const form = document.getElementById('categoryForm');
                const submitBtn = document.getElementById('submitBtn');

                if (form && submitBtn) {
                    form.addEventListener('submit', function (e) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                                                                                                                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                                                                                                                Saving...
                                                                                                                            `;

                        // ✅ Enable button again if form validation fails
                        setTimeout(() => {
                            if (!form.checkValidity()) {
                                submitBtn.disabled = false;
                                submitBtn.innerHTML = '<i class="fas fa-save me-2"></i> Save Category';
                            }
                        }, 500);
                    });
                }

                // ✅ ============================================================
                // ✅ 6. TOASTR NOTIFICATIONS (from session)
                // ✅ ============================================================
                @if(session('success'))
                    toastr.success('{{ session('success') }}', 'Success!', {
                        timeOut: 5000,
                        progressBar: true,
                        closeButton: true,
                        positionClass: 'toast-top-right'
                    });
                @endif

                @if(session('error'))
                    toastr.error('{{ session('error') }}', 'Error!', {
                        timeOut: 5000,
                        progressBar: true,
                        closeButton: true,
                        positionClass: 'toast-top-right'
                    });
                @endif

                @if($errors->any())
                    @foreach($errors->all() as $error)
                        toastr.error('{{ $error }}', 'Validation Error', {
                            timeOut: 5000,
                            progressBar: true,
                            closeButton: true,
                            positionClass: 'toast-top-right'
                        });
                    @endforeach
                @endif

                // ✅ ============================================================
                // ✅ 7. CONFIRMATION BEFORE NAVIGATION (if form has changes)
                // ✅ ============================================================
                let formChanged = false;

                form.querySelectorAll('input, select, textarea').forEach(element => {
                    element.addEventListener('change', function () {
                        formChanged = true;
                    });
                    element.addEventListener('input', function () {
                        formChanged = true;
                    });
                });

                window.addEventListener('beforeunload', function (e) {
                    if (formChanged) {
                        e.preventDefault();
                        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                        return e.returnValue;
                    }
                });

                // Reset form changed state on submit
                form.addEventListener('submit', function () {
                    formChanged = false;
                });
            });
            function updateStatusBadge(checkbox) {
                const badge = document.getElementById('statusBadge');
                if (checkbox.checked) {
                    badge.className = 'badge bg-success';
                    badge.textContent = 'Active';
                } else {
                    badge.className = 'badge bg-danger';
                    badge.textContent = 'Inactive';
                }
            }
        </script>
    @endpush
@endsection
