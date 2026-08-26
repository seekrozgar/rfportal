{{-- resources/views/admin/admissions/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Add Admission')
@section('page-title', 'Add Admission')
@section('page-subtitle', 'Create a new admission announcement')

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

        /* ✅ Error message styles - FIXED */
        .form-group .invalid-feedback {
            display: none !important;
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

        /* ✅ Server error alert */
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
                            <i class="fas fa-plus-circle me-2 text-primary"></i> Add Admission
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- ✅ Server-side Errors --}}
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

                        <form id="admissionForm" action="{{ route('admin.admissions.store') }}" method="POST"
                            enctype="multipart/form-data" novalidate>
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title" id="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Enter admission title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="titleError">Title is required</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Announcement Date</label>
                                        <input type="date" name="announcement_date"
                                            class="form-control @error('announcement_date') is-invalid @enderror"
                                            value="{{ old('announcement_date', date('Y-m-d')) }}">
                                        @error('announcement_date')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Institution <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="institution" id="institution"
                                            class="form-control @error('institution') is-invalid @enderror"
                                            placeholder="e.g. Virtual University of Pakistan"
                                            value="{{ old('institution') }}" required>
                                        @error('institution')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="institutionError">Institution is required</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Category</label>
                                        <select name="category" class="form-select @error('category') is-invalid @enderror">
                                            <option value="">Select Category</option>
                                            <option value="Merit" {{ old('category') == 'Merit' ? 'selected' : '' }}>Merit
                                            </option>
                                            <option value="Self-finance" {{ old('category') == 'Self-finance' ? 'selected' : '' }}>Self-finance</option>
                                            <option value="Foreign" {{ old('category') == 'Foreign' ? 'selected' : '' }}>
                                                Foreign</option>
                                            <option value="Special" {{ old('category') == 'Special' ? 'selected' : '' }}>
                                                Special</option>
                                        </select>
                                        @error('category')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Last Date to Apply</label>
                                        <input type="date" name="last_date" id="last_date"
                                            class="form-control @error('last_date') is-invalid @enderror"
                                            value="{{ old('last_date') }}">
                                        <small class="text-muted">Leave empty if no deadline</small>
                                        @error('last_date')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="lastDateError">Last date cannot be in the past
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Fee</label>
                                        <input type="text" name="fee"
                                            class="form-control @error('fee') is-invalid @enderror"
                                            placeholder="e.g. PKR 50,000 per semester" value="{{ old('fee') }}">
                                        @error('fee')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Apply Through</label>
                                        <select name="apply_through"
                                            class="form-select @error('apply_through') is-invalid @enderror">
                                            <option value="">Select Apply Method</option>
                                            <option value="Online" {{ old('apply_through') == 'Online' ? 'selected' : '' }}>
                                                Online</option>
                                            <option value="Post" {{ old('apply_through') == 'Post' ? 'selected' : '' }}>By
                                                Post</option>
                                            <option value="In-person" {{ old('apply_through') == 'In-person' ? 'selected' : '' }}>In-person</option>
                                            <option value="Online & Post" {{ old('apply_through') == 'Online & Post' ? 'selected' : '' }}>Online & Post</option>
                                        </select>
                                        @error('apply_through')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Apply Link (URL)</label>
                                        <input type="url" name="apply_link" id="apply_link"
                                            class="form-control @error('apply_link') is-invalid @enderror"
                                            placeholder="https://example.com/apply" value="{{ old('apply_link') }}">
                                        @error('apply_link')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="applyLinkError">Please enter a valid URL</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Featured Image <span class="text-muted">(Max
                                                2MB)</span></label>
                                        <div class="image-upload-wrapper @error('featured_image') border-danger @enderror">
                                            <div class="image-preview" id="imagePreview">
                                                <i class="fas fa-image fa-3x text-muted"></i>
                                                <p class="text-muted small mt-2">Click to upload image</p>
                                            </div>
                                            <input type="file" name="featured_image" id="featuredImage"
                                                class="form-control @error('featured_image') is-invalid @enderror"
                                                accept="image/*" style="display:none;">
                                            <small class="text-muted">Supported: JPG, PNG, GIF, WebP (Max 2MB)</small>
                                            @error('featured_image')
                                                <div class="invalid-feedback show">{{ $message }}</div>
                                            @enderror
                                            <div class="invalid-feedback" id="imageError">Image size must be less than 2MB
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Programs Offered --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Programs Offered</label>
                                <textarea name="programs_offered"
                                    class="form-control @error('programs_offered') is-invalid @enderror" rows="3"
                                    placeholder="List programs offered...">{{ old('programs_offered') }}</textarea>
                                @error('programs_offered')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- ✅ CKEditor 5 Fields - Class "ckeditor5" --}}
                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                <textarea name="description" id="descriptionEditor"
                                    class="form-control ckeditor5 @error('description') is-invalid @enderror"
                                    placeholder="Write admission description here...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                                <div class="invalid-feedback" id="descriptionError">Description is required</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Eligibility Criteria</label>
                                        <textarea name="eligibility" id="eligibilityEditor"
                                            class="form-control ckeditor5 @error('eligibility') is-invalid @enderror"
                                            placeholder="List eligibility criteria...">{{ old('eligibility') }}</textarea>
                                        @error('eligibility')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Required Documents</label>
                                        <textarea name="required_documents" id="documentsEditor"
                                            class="form-control ckeditor5 @error('required_documents') is-invalid @enderror"
                                            placeholder="List required documents...">{{ old('required_documents') }}</textarea>
                                        @error('required_documents')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Contact Email</label>
                                        <input type="email" name="contact_email" id="contact_email"
                                            class="form-control @error('contact_email') is-invalid @enderror"
                                            placeholder="admissions@example.com" value="{{ old('contact_email') }}">
                                        @error('contact_email')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                        <div class="invalid-feedback" id="emailError">Please enter a valid email address
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Contact Phone</label>
                                        <input type="text" name="contact_phone"
                                            class="form-control @error('contact_phone') is-invalid @enderror"
                                            placeholder="+92-XXX-XXXXXXX" value="{{ old('contact_phone') }}">
                                        @error('contact_phone')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-3">
                                        <input type="checkbox" name="is_published" class="form-check-input" id="isPublished"
                                            value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="isPublished">
                                            <i class="fas fa-check-circle text-success"></i> Publish Immediately
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-save"></i> Save Admission
                                </button>
                                <a href="{{ route('admin.admissions.index') }}" class="btn btn-secondary">
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

                const form = document.getElementById('admissionForm');
                const preview = document.getElementById('imagePreview');
                const fileInput = document.getElementById('featuredImage');

                if (!form) return;

                const MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2MB

                function showError(field, errorElement, message) {
                    if (field) field.classList.add('is-invalid');

                    if (errorElement) {
                        if (message) errorElement.textContent = message;
                        errorElement.classList.add('show');
                        errorElement.style.display = 'block';
                    }
                }

                function clearError(field, errorElement) {
                    if (field) field.classList.remove('is-invalid');

                    if (errorElement) {
                        errorElement.classList.remove('show');
                        errorElement.style.display = 'none';
                    }
                }

                function clearAllClientErrors() {
                    form.querySelectorAll('.is-invalid').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });

                    [
                        'titleError',
                        'institutionError',
                        'descriptionError',
                        'lastDateError',
                        'applyLinkError',
                        'emailError',
                        'imageError'
                    ].forEach(function (id) {
                        const el = document.getElementById(id);
                        if (el) {
                            el.classList.remove('show');
                            el.style.display = 'none';
                        }
                    });

                    document.querySelectorAll('.ck-editor__editable_inline').forEach(function (el) {
                        el.classList.remove('is-invalid');
                    });
                }

                /*
                 * CKEditor 5 replaces the textarea with an editable DIV.
                 * Read the visible editor text so an empty CKEditor is
                 * correctly detected before the form is submitted.
                 */
                function getEditorText(textarea) {
                    if (!textarea) return '';

                    const editorElement = textarea.parentElement
                        ? textarea.parentElement.querySelector('.ck-editor__editable')
                        : null;

                    if (editorElement) {
                        return editorElement.textContent
                            .replace(/\u00a0/g, ' ')
                            .trim();
                    }

                    return textarea.value
                        .replace(/<[^>]*>/g, ' ')
                        .replace(/\u00a0/g, ' ')
                        .trim();
                }

                function setEditorInvalid(textarea, errorElement) {
                    const editorElement = textarea && textarea.parentElement
                        ? textarea.parentElement.querySelector('.ck-editor__editable')
                        : null;

                    if (textarea) textarea.classList.add('is-invalid');
                    if (editorElement) editorElement.classList.add('is-invalid');

                    showError(textarea, errorElement, 'Description is required.');
                }

                function clearEditorInvalid(textarea, errorElement) {
                    const editorElement = textarea && textarea.parentElement
                        ? textarea.parentElement.querySelector('.ck-editor__editable')
                        : null;

                    if (textarea) textarea.classList.remove('is-invalid');
                    if (editorElement) editorElement.classList.remove('is-invalid');

                    clearError(null, errorElement);
                }

                function resetImagePreview() {
                    if (!preview) return;

                    preview.innerHTML = `
                                                                                                                        <i class="fas fa-image fa-3x text-muted"></i>
                                                                                                                        <p class="text-muted small mt-2">Click to upload image</p>
                                                                                                                    `;
                }

                /*
                 * IMAGE VALIDATION
                 * Checks immediately when an image is selected.
                 */
                if (preview && fileInput) {
                    preview.addEventListener('click', function () {
                        fileInput.click();
                    });

                    fileInput.addEventListener('change', function () {
                        const file = fileInput.files && fileInput.files[0];
                        const imageError = document.getElementById('imageError');

                        clearError(fileInput, imageError);

                        if (!file) {
                            resetImagePreview();
                            return;
                        }

                        if (file.size > MAX_IMAGE_SIZE) {
                            showError(
                                fileInput,
                                imageError,
                                'Image size must be less than 2MB.'
                            );

                            fileInput.value = '';
                            resetImagePreview();
                            return;
                        }

                        const allowedTypes = [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/webp'
                        ];

                        if (!allowedTypes.includes(file.type)) {
                            showError(
                                fileInput,
                                imageError,
                                'Please select a JPG, PNG, GIF, or WebP image.'
                            );

                            fileInput.value = '';
                            resetImagePreview();
                            return;
                        }

                        const reader = new FileReader();

                        reader.onload = function (event) {
                            preview.innerHTML = `
                                                                                                                                <img
                                                                                                                                    src="${event.target.result}"
                                                                                                                                    class="preview-image"
                                                                                                                                    alt="Featured image preview"
                                                                                                                                >
                                                                                                                            `;
                        };

                        reader.readAsDataURL(file);
                    });
                }

                /*
                 * FORM VALIDATION
                 * Runs BEFORE Laravel receives the form.
                 */
                form.addEventListener('submit', function (event) {
                    event.preventDefault();

                    clearAllClientErrors();

                    let isValid = true;
                    let firstInvalidElement = null;

                    function setFirstInvalid(element) {
                        if (!firstInvalidElement && element) {
                            firstInvalidElement = element;
                        }
                    }

                    // 1. TITLE - REQUIRED
                    const title = document.getElementById('title');
                    const titleError = document.getElementById('titleError');

                    if (!title || !title.value.trim()) {
                        showError(title, titleError, 'Title is required.');
                        setFirstInvalid(title);
                        isValid = false;
                    }

                    // 2. INSTITUTION - REQUIRED
                    const institution = document.getElementById('institution');
                    const institutionError = document.getElementById('institutionError');

                    if (!institution || !institution.value.trim()) {
                        showError(
                            institution,
                            institutionError,
                            'Institution is required.'
                        );
                        setFirstInvalid(institution);
                        isValid = false;
                    }

                    // 3. DESCRIPTION - REQUIRED
                    const description = document.getElementById('descriptionEditor');
                    const descriptionError = document.getElementById('descriptionError');

                    if (!getEditorText(description)) {
                        setEditorInvalid(description, descriptionError);

                        const editorElement = description && description.parentElement
                            ? description.parentElement.querySelector('.ck-editor__editable')
                            : null;

                        setFirstInvalid(editorElement || description);
                        isValid = false;
                    } else {
                        clearEditorInvalid(description, descriptionError);
                    }

                    // 4. LAST DATE - OPTIONAL, BUT CANNOT BE IN THE PAST
                    const lastDate = document.getElementById('last_date');
                    const lastDateError = document.getElementById('lastDateError');

                    if (lastDate && lastDate.value) {
                        const selectedDate = new Date(lastDate.value + 'T00:00:00');
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);

                        if (selectedDate < today) {
                            showError(
                                lastDate,
                                lastDateError,
                                'Last date cannot be in the past.'
                            );
                            setFirstInvalid(lastDate);
                            isValid = false;
                        }
                    }

                    // 5. APPLY LINK - OPTIONAL, BUT MUST BE A VALID URL
                    const applyLink = document.getElementById('apply_link');
                    const applyLinkError = document.getElementById('applyLinkError');

                    if (applyLink && applyLink.value.trim()) {
                        try {
                            const url = new URL(applyLink.value.trim());

                            if (!['http:', 'https:'].includes(url.protocol)) {
                                throw new Error('Invalid URL protocol');
                            }

                            clearError(applyLink, applyLinkError);
                        } catch (error) {
                            showError(
                                applyLink,
                                applyLinkError,
                                'Please enter a valid URL (e.g. https://example.com).'
                            );
                            setFirstInvalid(applyLink);
                            isValid = false;
                        }
                    }

                    // 6. CONTACT EMAIL - OPTIONAL, BUT MUST BE VALID
                    const contactEmail = document.getElementById('contact_email');
                    const emailError = document.getElementById('emailError');

                    if (contactEmail && contactEmail.value.trim()) {
                        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                        if (!emailRegex.test(contactEmail.value.trim())) {
                            showError(
                                contactEmail,
                                emailError,
                                'Please enter a valid email address.'
                            );
                            setFirstInvalid(contactEmail);
                            isValid = false;
                        } else {
                            clearError(contactEmail, emailError);
                        }
                    }

                    // 7. FEATURED IMAGE - OPTIONAL, MAXIMUM 2MB
                    const imageInput = document.getElementById('featuredImage');
                    const imageError = document.getElementById('imageError');

                    if (
                        imageInput &&
                        imageInput.files &&
                        imageInput.files.length > 0
                    ) {
                        const file = imageInput.files[0];

                        if (file.size > MAX_IMAGE_SIZE) {
                            showError(
                                imageInput,
                                imageError,
                                'Image size must be less than 2MB.'
                            );
                            setFirstInvalid(imageInput);
                            isValid = false;
                        }
                    }

                    /*
                     * ONLY submit when every client-side validation passes.
                     * Native submit bypasses this event and sends the form
                     * to Laravel's server-side validation.
                     */
                    if (isValid) {
                        const submitButton = document.getElementById('submitBtn');

                        if (submitButton) {
                            submitButton.disabled = true;
                            submitButton.innerHTML = `
                                                                                                                                <span class="spinner-border spinner-border-sm me-1"
                                                                                                                                      role="status"
                                                                                                                                      aria-hidden="true"></span>
                                                                                                                                Saving...
                                                                                                                            `;
                        }

                        HTMLFormElement.prototype.submit.call(form);
                    } else if (firstInvalidElement) {
                        setTimeout(function () {
                            firstInvalidElement.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });

                            if (typeof firstInvalidElement.focus === 'function') {
                                firstInvalidElement.focus();
                            }
                        }, 100);
                    }
                });
            });
        </script>
    @endpush
@endsection