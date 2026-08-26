{{-- resources/views/admin/job-categories/edit.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Edit Category')
@section('page-title', 'Edit Category')
@section('page-subtitle', 'Update job category')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-edit me-2 text-primary"></i> Edit Category
                        </h5>
                        <div class="d-flex gap-2">
                            <span class="badge bg-light text-dark border px-3 py-2">
                                <i class="fas fa-hashtag me-1"></i> ID: {{ $jobCategory->id }}
                            </span>
                            <a href="{{ route('admin.job-categories.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.job-categories.update', $jobCategory) }}" method="POST"
                            enctype="multipart/form-data" id="categoryForm" novalidate>
                            @csrf
                            @method('PUT')

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
                                        value="{{ old('name', $jobCategory->name) }}" placeholder="Enter category name"
                                        required id="categoryName">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i> This will be used as the category display name
                                    </div>
                                </div>

                                {{-- Slug (Read-only) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Slug</label>
                                    <input type="text" class="form-control bg-light" value="{{ $jobCategory->slug }}"
                                        readonly disabled>
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i> Auto-generated from category name
                                    </div>
                                </div>

                                {{-- Parent Category --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Parent Category</label>
                                    <select name="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                                        <option value="">None (Root Category)</option>
                                        @foreach($parents as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', $jobCategory->parent_id) == $parent->id ? 'selected' : '' }}>
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
                                        @if($jobCategory->parent)
                                            Current parent: <strong>{{ $jobCategory->parent->name }}</strong>
                                        @else
                                            This is currently a root category
                                        @endif
                                    </div>
                                </div>

                                {{-- Icon --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Icon</label>
                                    <div class="input-group">
                                        <input type="text" name="icon"
                                            class="form-control @error('icon') is-invalid @enderror"
                                            value="{{ old('icon', $jobCategory->icon ?? 'fas fa-folder') }}"
                                            placeholder="e.g., fas fa-code" id="iconInput">
                                        <span class="input-group-text" style="padding: 0;">
                                            <span class="icon-preview" id="iconPreview">
                                                <i class="{{ $jobCategory->icon ?? 'fas fa-folder' }}"></i>
                                            </span>
                                        </span>
                                    </div>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Font Awesome icon class.
                                        <a href="https://fontawesome.com/icons" target="_blank">
                                            Browse icons <i class="fas fa-external-link-alt fa-xs"></i>
                                        </a>
                                    </div>
                                </div>

                                {{-- Order --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Display Order</label>
                                    <input type="number" name="order"
                                        class="form-control @error('order') is-invalid @enderror"
                                        value="{{ old('order', $jobCategory->order ?? 0) }}" min="0" id="orderInput">
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
                                        id="descriptionInput">{{ old('description', $jobCategory->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text" id="charCount">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="charCountValue" class="char-counter">0</span> characters (recommended:
                                        150-300)
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
                                        @if($jobCategory->featured_image)
                                            <div class="mb-2">
                                                <img src="{{ $jobCategory->featured_image_url }}" class="current-image"
                                                    alt="{{ $jobCategory->name }}">
                                                <br>
                                                <small class="text-muted">
                                                    <i class="fas fa-info-circle"></i> Current image
                                                </small>
                                                <br>
                                                <small class="text-muted">
                                                    <a href="#" onclick="removeImage(event, {{ $jobCategory->id }})"
                                                        class="text-danger" style="font-size: 12px;">
                                                        <i class="fas fa-trash"></i> Remove image
                                                    </a>
                                                </small>
                                            </div>
                                        @endif
                                        <input type="file" name="featured_image"
                                            class="form-control @error('featured_image') is-invalid @enderror"
                                            accept="image/*" id="featuredImage">
                                        <div id="imagePreviewContainer" class="image-preview-container"
                                            style="display: none;">
                                            <img id="imagePreviewImg" src="#" alt="Preview">
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
                                            value="1" {{ old('is_active', $jobCategory->is_active) ? 'checked' : '' }}
                                            onchange="updateStatusBadge(this)">
                                        <label class="form-check-label" for="isActive">
                                            <span id="statusBadge"
                                                class="badge-status {{ old('is_active', $jobCategory->is_active) ? 'bg-success' : 'bg-danger' }}">
                                                <i
                                                    class="fas {{ old('is_active', $jobCategory->is_active) ? 'fa-check-circle' : 'fa-times-circle' }} me-1"></i>
                                                {{ old('is_active', $jobCategory->is_active) ? 'Active' : 'Inactive' }}
                                            </span>
                                        </label>
                                    </div>
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        Inactive categories will not be visible on the frontend
                                    </div>
                                </div>

                                {{-- Category Statistics --}}
                                <div class="col-md-12 mb-3">
                                    <div class="row g-2">
                                        <div class="col-md-3 col-6">
                                            <div class="p-3 bg-light rounded-3 text-center">
                                                <small class="text-muted d-block">Sub-Categories</small>
                                                <span class="fw-bold fs-5">{{ $jobCategory->children->count() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="p-3 bg-light rounded-3 text-center">
                                                <small class="text-muted d-block">Jobs</small>
                                                <span class="fw-bold fs-5">{{ $jobCategory->jobs->count() }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="p-3 bg-light rounded-3 text-center">
                                                <small class="text-muted d-block">Created</small>
                                                <span
                                                    class="fw-bold fs-6">{{ $jobCategory->created_at->format('d M, Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-6">
                                            <div class="p-3 bg-light rounded-3 text-center">
                                                <small class="text-muted d-block">Last Updated</small>
                                                <span
                                                    class="fw-bold fs-6">{{ $jobCategory->updated_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
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
                                        value="{{ old('meta_title', $jobCategory->meta_title) }}" placeholder="SEO title"
                                        id="metaTitle" maxlength="60">
                                    @error('meta_title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="metaTitleCount" class="char-counter">0</span>/60 characters
                                    </div>
                                </div>

                                {{-- SEO - Meta Description --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Meta Description</label>
                                    <textarea name="meta_description"
                                        class="form-control @error('meta_description') is-invalid @enderror" rows="2"
                                        placeholder="SEO description" id="metaDescription"
                                        maxlength="160">{{ old('meta_description', $jobCategory->meta_description) }}</textarea>
                                    @error('meta_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="help-text">
                                        <i class="fas fa-info-circle"></i>
                                        <span id="metaDescriptionCount" class="char-counter">0</span>/160 characters
                                    </div>
                                </div>

                                {{-- SEO - Keywords --}}
                                <div class="col-12 mb-3">
                                    <label class="form-label">Keywords</label>
                                    <input type="text" name="keywords"
                                        class="form-control @error('keywords') is-invalid @enderror"
                                        value="{{ old('keywords', $jobCategory->keywords) }}"
                                        placeholder="Comma separated keywords" id="keywordsInput">
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
                                            <i class="fas fa-save me-2"></i> Update Category
                                        </button>
                                        <a href="{{ route('admin.job-categories.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left me-2"></i> Cancel
                                        </a>
                                        <button type="button" class="btn btn-danger ms-auto"
                                            onclick="deleteItem({{ $jobCategory->id }}, '{{ addslashes($jobCategory->name) }}')">
                                            <i class="fas fa-trash me-2"></i> Delete Category
                                        </button>
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
                const imagePreviewContainer = document.getElementById('imagePreviewContainer');
                const imagePreviewImg = document.getElementById('imagePreviewImg');

                if (featuredImage) {
                    featuredImage.addEventListener('change', function () {
                        const file = this.files[0];
                        if (file) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                imagePreviewImg.src = e.target.result;
                                imagePreviewContainer.style.display = 'block';
                            };
                            reader.readAsDataURL(file);
                        } else {
                            imagePreviewContainer.style.display = 'none';
                        }
                    });
                }

                // ✅ ============================================================
                // ✅ 3. CHARACTER COUNTERS
                // ✅ ============================================================
                function updateCharCounter(input, counterId, max, goodMin) {
                    const counter = document.getElementById(counterId);
                    if (!input || !counter) return;

                    input.addEventListener('input', function () {
                        const count = this.value.length;
                        counter.textContent = count;

                        counter.className = 'char-counter';
                        if (count === 0) {
                            // neutral
                        } else if (goodMin && count >= goodMin && count <= max) {
                            counter.classList.add('good');
                        } else if (count > max) {
                            counter.classList.add('danger');
                        } else {
                            counter.classList.add('warning');
                        }
                    });

                    // Trigger on load
                    input.dispatchEvent(new Event('input'));
                }

                // Description counter
                const descriptionInput = document.getElementById('descriptionInput');
                const charCountValue = document.getElementById('charCountValue');
                if (descriptionInput && charCountValue) {
                    updateCharCounter(descriptionInput, 'charCountValue', 300, 150);
                }

                // Meta Title counter
                const metaTitle = document.getElementById('metaTitle');
                const metaTitleCount = document.getElementById('metaTitleCount');
                if (metaTitle && metaTitleCount) {
                    updateCharCounter(metaTitle, 'metaTitleCount', 60, 30);
                }

                // Meta Description counter
                const metaDescription = document.getElementById('metaDescription');
                const metaDescriptionCount = document.getElementById('metaDescriptionCount');
                if (metaDescription && metaDescriptionCount) {
                    updateCharCounter(metaDescription, 'metaDescriptionCount', 160, 80);
                }

                // ✅ ============================================================
                // ✅ 4. STATUS BADGE TOGGLE
                // ✅ ============================================================
                window.updateStatusBadge = function (checkbox) {
                    const badge = document.getElementById('statusBadge');
                    if (checkbox.checked) {
                        badge.className = 'badge-status bg-success';
                        badge.innerHTML = '<i class="fas fa-check-circle me-1"></i> Active';
                    } else {
                        badge.className = 'badge-status bg-danger';
                        badge.innerHTML = '<i class="fas fa-times-circle me-1"></i> Inactive';
                    }
                };

                // ✅ ============================================================
                // ✅ 5. FORM SUBMISSION WITH LOADER
                // ✅ ============================================================
                const form = document.getElementById('categoryForm');
                const submitBtn = document.getElementById('submitBtn');

                if (form && submitBtn) {
                    form.addEventListener('submit', function () {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = `
                                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                Updating...
                            `;
                    });
                }

                // ✅ ============================================================
                // ✅ 6. REMOVE IMAGE
                // ✅ ============================================================
                window.removeImage = function (e, id) {
                    e.preventDefault();
                    if (!confirm('Are you sure you want to remove this image?')) return;

                    fetch('/admin/job-categories/' + id + '/remove-image', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        }
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                showToast('success', data.message);
                                location.reload();
                            } else {
                                showToast('error', data.message || 'Error removing image');
                            }
                        })
                        .catch(() => {
                            showToast('error', 'An error occurred. Please try again.');
                        });
                };

                // ✅ ============================================================
                // ✅ 7. TOASTR NOTIFICATIONS
                // ✅ ============================================================
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

                // ✅ ============================================================
                // ✅ 8. SESSION TOAST MESSAGES
                // ✅ ============================================================
                @if(session('toast'))
                    const toast = @json(session('toast'));
                    showToast(toast.type, toast.message);
                @endif

                @if(session('success') && !session('toast'))
                    showToast('success', '{{ session('success') }}');
                @endif

                @if(session('error') && !session('toast'))
                    showToast('error', '{{ session('error') }}');
                @endif

                // ✅ ============================================================
                // ✅ 9. CONFIRMATION BEFORE NAVIGATION
                // ✅ ============================================================
                let formChanged = false;

                form.querySelectorAll('input, select, textarea').forEach(element => {
                    element.addEventListener('change', function () {
                        formChanged = true;
                    });
                    element.addEventListener('input', function () {
                        if (this.type !== 'checkbox') {
                            formChanged = true;
                        }
                    });
                });

                window.addEventListener('beforeunload', function (e) {
                    if (formChanged) {
                        e.preventDefault();
                        e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
                        return e.returnValue;
                    }
                });

                form.addEventListener('submit', function () {
                    formChanged = false;
                });
            });

            // ✅ ============================================================
            // ✅ DELETE FUNCTION
            // ✅ ============================================================
            function deleteItem(id, name) {
                if (!confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) return;

                fetch(`/admin/job-categories/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(data.message, '✅ Success!', {
                                    timeOut: 3000,
                                    progressBar: true,
                                    closeButton: true,
                                    positionClass: 'toast-top-right'
                                });
                            }
                            setTimeout(() => {
                                window.location.href = '{{ route("admin.job-categories.index") }}';
                            }, 800);
                        } else {
                            if (typeof toastr !== 'undefined') {
                                toastr.error(data.message || 'Error deleting', '❌ Error!', {
                                    timeOut: 5000,
                                    progressBar: true,
                                    closeButton: true,
                                    positionClass: 'toast-top-right'
                                });
                            } else {
                                alert(data.message || 'Error deleting');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (typeof toastr !== 'undefined') {
                            toastr.error('An error occurred. Please try again.', '❌ Error!', {
                                timeOut: 5000,
                                progressBar: true,
                                closeButton: true,
                                positionClass: 'toast-top-right'
                            });
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    });
            }
        </script>
    @endpush
@endsection