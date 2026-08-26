{{-- resources/views/admin/scholarships/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit Scholarship')
@section('page-title', 'Edit Scholarship')
@section('page-subtitle', 'Update scholarship opportunity')

@push('styles')
    <style>
        .image-upload-wrapper {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 16px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .image-upload-wrapper:hover {
            border-color: #11998e;
            background: #f0fdf4;
        }

        .image-upload-wrapper.border-danger {
            border-color: #dc3545;
        }

        .image-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 150px;
            cursor: pointer;
        }

        .image-preview .preview-image {
            max-width: 100%;
            max-height: 200px;
            border-radius: 6px;
            object-fit: cover;
        }

        .image-preview i {
            color: #9ca3af;
        }

        .image-preview p {
            margin: 0;
            color: #9ca3af;
            font-size: 14px;
        }

        .current-image {
            margin-bottom: 8px;
        }

        .current-image p {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 4px;
        }

        .current-preview {
            max-width: 100px;
            max-height: 60px;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
            object-fit: cover;
        }

        /* ✅ Error message styles */
        .form-group .invalid-feedback {
            display: none;
            font-size: 13px;
            margin-top: 5px;
            color: #dc3545;
        }

        .form-group .invalid-feedback.show {
            display: block !important;
        }

        .form-control.is-invalid {
            border-color: #dc3545 !important;
            padding-right: calc(1.5em + 0.75rem);
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 12' width='12' height='12' fill='none' stroke='%23dc3545'%3e%3ccircle cx='6' cy='6' r='4.5'/%3e%3cpath stroke-linejoin='round' d='M5.8 3.6h.4L6 6.5z'/%3e%3ccircle cx='6' cy='8.2' r='.6' fill='%23dc3545' stroke='none'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right calc(0.375em + 0.1875rem) center;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }

        .ck-editor__editable {
            min-height: 200px;
        }

        .ck-editor__editable.is-invalid {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2) !important;
        }

        .ck-editor__editable.ck-focused {
            border-color: #11998e !important;
            box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.1) !important;
        }

        .alert ul {
            padding-left: 20px;
            margin-top: 4px;
            margin-bottom: 0;
        }

        .alert ul li {
            font-size: 14px;
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
                            <i class="fas fa-edit me-2 text-primary"></i> Edit Scholarship
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form id="scholarshipForm" action="{{ route('admin.scholarships.update', $scholarship) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Enter scholarship title"
                                            value="{{ old('title', $scholarship->title) }}" required>
                                        @error('title')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="titleError">Title is required</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Deadline</label>
                                        <input type="date" name="deadline" id="deadline"
                                            class="form-control @error('deadline') is-invalid @enderror"
                                            value="{{ old('deadline', $scholarship->deadline?->format('Y-m-d')) }}">
                                        @error('deadline')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="deadlineError">Deadline cannot be in the past
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Provider</label>
                                        <input type="text" name="provider" id="provider"
                                            class="form-control @error('provider') is-invalid @enderror"
                                            placeholder="e.g. HEC, Higher Education Commission"
                                            value="{{ old('provider', $scholarship->provider) }}">
                                        @error('provider')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">University/Institution</label>
                                        <input type="text" name="university"
                                            class="form-control @error('university') is-invalid @enderror"
                                            placeholder="e.g. Virtual University of Pakistan"
                                            value="{{ old('university', $scholarship->university) }}">
                                        @error('university')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Degree Level</label>
                                        <select name="degree_level"
                                            class="form-select @error('degree_level') is-invalid @enderror">
                                            <option value="">Select Degree Level</option>
                                            <option value="Bachelor" {{ old('degree_level', $scholarship->degree_level) == 'Bachelor' ? 'selected' : '' }}>Bachelor
                                            </option>
                                            <option value="Master" {{ old('degree_level', $scholarship->degree_level) == 'Master' ? 'selected' : '' }}>Master</option>
                                            <option value="M.Phil" {{ old('degree_level', $scholarship->degree_level) == 'M.Phil' ? 'selected' : '' }}>M.Phil</option>
                                            <option value="PhD" {{ old('degree_level', $scholarship->degree_level) == 'PhD' ? 'selected' : '' }}>PhD</option>
                                            <option value="Post Doc" {{ old('degree_level', $scholarship->degree_level) == 'Post Doc' ? 'selected' : '' }}>Post Doc
                                            </option>
                                        </select>
                                        @error('degree_level')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Scholarship Type</label>
                                        <select name="scholarship_type"
                                            class="form-select @error('scholarship_type') is-invalid @enderror">
                                            <option value="">Select Type</option>
                                            <option value="Fully Funded" {{ old('scholarship_type', $scholarship->scholarship_type) == 'Fully Funded' ? 'selected' : '' }}>Fully
                                                Funded</option>
                                            <option value="Partial Funded" {{ old('scholarship_type', $scholarship->scholarship_type) == 'Partial Funded' ? 'selected' : '' }}>
                                                Partial Funded</option>
                                            <option value="Tuition Waiver" {{ old('scholarship_type', $scholarship->scholarship_type) == 'Tuition Waiver' ? 'selected' : '' }}>
                                                Tuition Waiver</option>
                                            <option value="Stipend" {{ old('scholarship_type', $scholarship->scholarship_type) == 'Stipend' ? 'selected' : '' }}>Stipend
                                            </option>
                                        </select>
                                        @error('scholarship_type')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Amount</label>
                                        <input type="text" name="amount"
                                            class="form-control @error('amount') is-invalid @enderror"
                                            placeholder="e.g. PKR 100,000 or USD 1,000"
                                            value="{{ old('amount', $scholarship->amount) }}">
                                        @error('amount')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Country</label>
                                        <input type="text" name="country"
                                            class="form-control @error('country') is-invalid @enderror"
                                            placeholder="e.g. Pakistan, USA, UK"
                                            value="{{ old('country', $scholarship->country) }}">
                                        @error('country')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Apply Link (URL)</label>
                                        <input type="url" name="apply_link"
                                            class="form-control @error('apply_link') is-invalid @enderror"
                                            placeholder="https://example.com/apply"
                                            value="{{ old('apply_link', $scholarship->apply_link) }}">
                                        @error('apply_link')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="applyLinkError">Please enter a valid URL</div>
                                    </div>
                                </div>
                            </div>

                            {{-- ✅ Description with CKEditor --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" id="descriptionEditor"
                                    class="form-control ckeditor5 @error('description') is-invalid @enderror"
                                    placeholder="Write scholarship description here...">{{ old('description', $scholarship->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ✅ Eligibility & Benefits --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Eligibility Criteria</label>
                                        <textarea name="eligibility" id="eligibilityEditor"
                                            class="form-control ckeditor5 @error('eligibility') is-invalid @enderror"
                                            placeholder="List eligibility criteria...">{{ old('eligibility', $scholarship->eligibility) }}</textarea>
                                        @error('eligibility')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Benefits</label>
                                        <textarea name="benefits" id="benefitsEditor"
                                            class="form-control ckeditor5 @error('benefits') is-invalid @enderror"
                                            placeholder="List benefits...">{{ old('benefits', $scholarship->benefits) }}</textarea>
                                        @error('benefits')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Required Documents</label>
                                        <textarea name="required_documents" id="documentsEditor"
                                            class="form-control ckeditor5 @error('required_documents') is-invalid @enderror"
                                            placeholder="List required documents...">{{ old('required_documents', $scholarship->required_documents) }}</textarea>
                                        @error('required_documents')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Featured Image</label>

                                        @if($scholarship->featured_image)
                                            <div class="current-image mb-2">
                                                <p class="mb-1">Current Image:</p>
                                                <img src="{{ asset('storage/' . $scholarship->featured_image) }}"
                                                    class="current-preview">
                                            </div>
                                        @endif

                                        <div class="image-upload-wrapper @error('featured_image') border-danger @enderror">
                                            <div class="image-preview" id="imagePreview">
                                                @if($scholarship->featured_image)
                                                    <img src="{{ asset('storage/' . $scholarship->featured_image) }}"
                                                        class="preview-image">
                                                @else
                                                    <i class="fas fa-image fa-3x text-muted"></i>
                                                    <p class="text-muted small mt-2">Click to change image</p>
                                                @endif
                                            </div>
                                            <input type="file" name="featured_image" id="featuredImage"
                                                class="form-control @error('featured_image') is-invalid @enderror"
                                                accept="image/*" style="display:none;">
                                            <small class="text-muted">Supported: JPG, PNG, GIF, WebP (Max 2MB)</small>
                                            <small class="text-muted d-block">Leave empty to keep current image</small>
                                            @error('featured_image')
                                                <div class="invalid-feedback show">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback" id="imageError">Image size must be less than 2MB
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Source URL</label>
                                        <input type="url" name="source_url"
                                            class="form-control @error('source_url') is-invalid @enderror"
                                            placeholder="https://propakistani.pk/edunation/scholarships/..."
                                            value="{{ old('source_url', $scholarship->source_url) }}">
                                        @error('source_url')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Source</label>
                                        <select name="source" class="form-select @error('source') is-invalid @enderror">
                                            <option value="">Select Source</option>
                                            <option value="propakistani" {{ old('source', $scholarship->source) == 'propakistani' ? 'selected' : '' }}>Propakistani
                                            </option>
                                            <option value="official" {{ old('source', $scholarship->source) == 'official' ? 'selected' : '' }}>Official</option>
                                            <option value="other" {{ old('source', $scholarship->source) == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('source')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Contact Email</label>
                                        <input type="email" name="contact_email"
                                            class="form-control @error('contact_email') is-invalid @enderror"
                                            placeholder="scholarships@example.com"
                                            value="{{ old('contact_email', $scholarship->contact_email) }}">
                                        @error('contact_email')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="emailError">Please enter a valid email address
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Contact Phone</label>
                                        <input type="text" name="contact_phone"
                                            class="form-control @error('contact_phone') is-invalid @enderror"
                                            placeholder="+92-XXX-XXXXXXX"
                                            value="{{ old('contact_phone', $scholarship->contact_phone) }}">
                                        @error('contact_phone')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-3 mt-4">
                                        <div>
                                            <input type="checkbox" name="is_published" class="form-check-input"
                                                id="isPublished" value="1" {{ old('is_published', $scholarship->is_published) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="isPublished">
                                                <i class="fas fa-check-circle text-success"></i> Publish
                                            </label>
                                        </div>
                                        <div class="mt-2">
                                            <input type="checkbox" name="is_draft" class="form-check-input" id="isDraft"
                                                value="1" {{ old('is_draft', $scholarship->is_draft) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-semibold" for="isDraft">
                                                <i class="fas fa-file-alt text-info"></i> Save as Draft
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Scholarship
                                </button>
                                <a href="{{ route('admin.scholarships.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Cancel
                                </a>
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
                // ✅ Image Preview with Size Validation
                const preview = document.getElementById('imagePreview');
                const fileInput = document.getElementById('featuredImage');

                if (preview && fileInput) {
                    preview.addEventListener('click', function () {
                        fileInput.click();
                    });

                    fileInput.addEventListener('change', function (e) {
                        const file = e.target.files[0];
                        const imageError = document.getElementById('imageError');
                        if (file) {
                            if (file.size > 2 * 1024 * 1024) {
                                imageError.style.display = 'block';
                                fileInput.classList.add('is-invalid');
                                fileInput.value = '';
                                preview.innerHTML = `
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="text-muted small mt-2">Click to change image</p>
                                    `;
                                return;
                            }
                            imageError.style.display = 'none';
                            fileInput.classList.remove('is-invalid');
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                preview.innerHTML = `<img src="${e.target.result}" class="preview-image">`;
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                }

                // ✅ Form Validation
                document.getElementById('scholarshipForm').addEventListener('submit', function (e) {
                    e.preventDefault();

                    // Clear all previous errors
                    document.querySelectorAll('.is-invalid').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });
                    document.querySelectorAll('.invalid-feedback').forEach(function (el) {
                        el.style.display = 'none';
                        el.classList.remove('show');
                    });
                    document.querySelectorAll('.ck-editor__editable_inline').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });

                    let isValid = true;

                    // ✅ Title
                    const title = document.getElementById('title');
                    if (!title.value.trim()) {
                        title.classList.add('is-invalid');
                        document.getElementById('titleError').style.display = 'block';
                        document.getElementById('titleError').classList.add('show');
                        isValid = false;
                    }

                    // ✅ Deadline
                    const deadline = document.getElementById('deadline');
                    if (deadline.value) {
                        const selectedDate = new Date(deadline.value);
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        if (selectedDate < today) {
                            deadline.classList.add('is-invalid');
                            document.getElementById('deadlineError').style.display = 'block';
                            document.getElementById('deadlineError').classList.add('show');
                            isValid = false;
                        }
                    }

                    // ✅ Description (CKEditor 5)
                    const descEditor = document.querySelector('#descriptionEditor');
                    if (descEditor) {
                        const data = descEditor.value;
                        if (!data.trim() || data === '<p>&nbsp;</p>' || data === '<p><br></p>') {
                            descEditor.classList.add('is-invalid');
                            const ckWrapper = descEditor.closest('.ck-editor__editable_inline');
                            if (ckWrapper) {
                                ckWrapper.classList.add('is-invalid');
                            }
                            // Show error for description
                            const descError = document.querySelector('#descriptionEditor + .invalid-feedback') ||
                                document.createElement('div');
                            if (!descError.classList.contains('invalid-feedback')) {
                                descError.className = 'invalid-feedback show';
                                descError.style.display = 'block';
                                descError.textContent = 'Description is required';
                                descEditor.parentNode.insertBefore(descError, descEditor.nextSibling);
                            }
                            isValid = false;
                        }
                    }

                    // ✅ Apply Link
                    const applyLink = document.getElementById('apply_link');
                    if (applyLink.value.trim()) {
                        try {
                            new URL(applyLink.value.trim());
                        } catch (e) {
                            applyLink.classList.add('is-invalid');
                            document.getElementById('applyLinkError').style.display = 'block';
                            document.getElementById('applyLinkError').classList.add('show');
                            isValid = false;
                        }
                    }

                    // ✅ Email
                    const contactEmail = document.getElementById('contact_email');
                    if (contactEmail.value.trim()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(contactEmail.value.trim())) {
                            contactEmail.classList.add('is-invalid');
                            document.getElementById('emailError').style.display = 'block';
                            document.getElementById('emailError').classList.add('show');
                            isValid = false;
                        }
                    }

                    // ✅ Image
                    const imageInput = document.getElementById('featuredImage');
                    if (imageInput.files.length > 0) {
                        const file = imageInput.files[0];
                        if (file.size > 2 * 1024 * 1024) {
                            imageInput.classList.add('is-invalid');
                            document.getElementById('imageError').style.display = 'block';
                            document.getElementById('imageError').classList.add('show');
                            isValid = false;
                        }
                    }

                    if (isValid) {
                        // Update CKEditor data
                        if (typeof CKEDITOR !== 'undefined') {
                            for (let instance in CKEDITOR.instances) {
                                CKEDITOR.instances[instance].updateElement();
                            }
                        }
                        document.getElementById('scholarshipForm').submit();
                    } else {
                        const firstError = document.querySelector('.is-invalid');
                        if (firstError) {
                            setTimeout(function () {
                                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                firstError.focus();
                            }, 300);
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection