{{-- resources/views/admin/job-postings/create.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Add Job - Rozgar Finder')
@section('page-title', 'Add New Job')
@section('page-subtitle', 'Post a new job')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle me-2 text-primary"></i> Add New Job
                    </h5>
                    <a href="{{ route('admin.job-postings.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.job-postings.store') }}" method="POST" enctype="multipart/form-data" id="jobForm" novalidate>
                        @csrf

                        <div class="row">
                            {{-- Basic Information --}}
                            <div class="col-12 mb-3">
                                <div class="form-section-title"><i class="fas fa-info-circle"></i> Basic Information</div>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="form-label">Job Title <span class="required-star">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title') }}" placeholder="e.g. Assistant Director (BS-17)" required>
                                @error('title')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Category <span class="required-star">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Job Details --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="form-section-title"><i class="fas fa-briefcase"></i> Job Details</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Job Type <span class="required-star">*</span></label>
                                <select name="job_type_id" class="form-select @error('job_type_id') is-invalid @enderror" required>
                                    <option value="">Select Job Type</option>
                                    @foreach($jobTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('job_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_type_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Job Shift</label>
                                <select name="job_shift_id" class="form-select @error('job_shift_id') is-invalid @enderror">
                                    <option value="">Select Job Shift</option>
                                    @foreach($jobShifts as $shift)
                                        <option value="{{ $shift->id }}" {{ old('job_shift_id') == $shift->id ? 'selected' : '' }}>
                                            {{ $shift->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('job_shift_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Location <span class="required-star">*</span></label>
                                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                    value="{{ old('location') }}" placeholder="e.g. Lahore, Pakistan" required>
                                @error('location')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Experience Level</label>
                                <select name="experience_level_id" class="form-select @error('experience_level_id') is-invalid @enderror">
                                    <option value="">Select Experience</option>
                                    @foreach($experienceLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('experience_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('experience_level_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Career Level</label>
                                <select name="career_level_id" class="form-select @error('career_level_id') is-invalid @enderror">
                                    <option value="">Select Career Level</option>
                                    @foreach($careerLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('career_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('career_level_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Industry</label>
                                <select name="industry_id" class="form-select @error('industry_id') is-invalid @enderror">
                                    <option value="">Select Industry</option>
                                    @foreach($industries as $industry)
                                        <option value="{{ $industry->id }}" {{ old('industry_id') == $industry->id ? 'selected' : '' }}>
                                            {{ $industry->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('industry_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Functional Area</label>
                                <select name="functional_area_id" class="form-select @error('functional_area_id') is-invalid @enderror">
                                    <option value="">Select Functional Area</option>
                                    @foreach($functionalAreas as $area)
                                        <option value="{{ $area->id }}" {{ old('functional_area_id') == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('functional_area_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Degree Level</label>
                                <select name="degree_level_id" class="form-select @error('degree_level_id') is-invalid @enderror">
                                    <option value="">Select Degree Level</option>
                                    @foreach($degreeLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('degree_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('degree_level_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Degree Type</label>
                                <select name="degree_type_id" class="form-select @error('degree_type_id') is-invalid @enderror">
                                    <option value="">Select Degree Type</option>
                                    @foreach($degreeTypes as $type)
                                        <option value="{{ $type->id }}" {{ old('degree_type_id') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('degree_type_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Major Subject</label>
                                <select name="major_subject_id" class="form-select @error('major_subject_id') is-invalid @enderror">
                                    <option value="">Select Major Subject</option>
                                    @foreach($majorSubjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('major_subject_id') == $subject->id ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('major_subject_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender_id" class="form-select @error('gender_id') is-invalid @enderror">
                                    <option value="">Select Gender</option>
                                    @foreach($genders as $gender)
                                        <option value="{{ $gender->id }}" {{ old('gender_id') == $gender->id ? 'selected' : '' }}>
                                            {{ $gender->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('gender_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Marital Status</label>
                                <select name="marital_status_id" class="form-select @error('marital_status_id') is-invalid @enderror">
                                    <option value="">Select Marital Status</option>
                                    @foreach($maritalStatuses as $status)
                                        <option value="{{ $status->id }}" {{ old('marital_status_id') == $status->id ? 'selected' : '' }}>
                                            {{ $status->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('marital_status_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Language Level</label>
                                <select name="language_level_id" class="form-select @error('language_level_id') is-invalid @enderror">
                                    <option value="">Select Language Level</option>
                                    @foreach($languageLevels as $level)
                                        <option value="{{ $level->id }}" {{ old('language_level_id') == $level->id ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('language_level_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Salary Information --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="form-section-title"><i class="fas fa-money-bill-wave"></i> Salary Information</div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary (Min)</label>
                                <input type="number" name="salary_min" class="form-control @error('salary_min') is-invalid @enderror"
                                    value="{{ old('salary_min') }}" placeholder="e.g. 50000" step="0.01">
                                @error('salary_min')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary (Max)</label>
                                <input type="number" name="salary_max" class="form-control @error('salary_max') is-invalid @enderror"
                                    value="{{ old('salary_max') }}" placeholder="e.g. 100000" step="0.01">
                                @error('salary_max')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="form-label">Salary Period</label>
                                <select name="salary_period_id" class="form-select @error('salary_period_id') is-invalid @enderror">
                                    <option value="">Select Period</option>
                                    @foreach($salaryPeriods as $period)
                                        <option value="{{ $period->id }}" {{ old('salary_period_id') == $period->id ? 'selected' : '' }}>
                                            {{ $period->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('salary_period_id')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Advertisement --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="form-section-title"><i class="fas fa-image"></i> Advertisement</div>
                            </div>

                            {{-- ✅ Professional Image Upload with Permanent Default Image --}}
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label fw-semibold">Featured Image</label>

                                    @php
                                        /*
                                         * IMPORTANT:
                                         * Put the supplied no-image-icon-15.png at:
                                         * public/images/no-image-icon-15.png
                                         *
                                         * This is a permanent static image. It is NOT uploaded to
                                         * storage again when a job is submitted without a real image.
                                         */
                                        $defaultJobImage = \Illuminate\Support\Facades\Storage::url('images/no-image-icon-15.png');
                                        $defaultJobImageName = 'no-image-icon-15.png';
                                    @endphp

                                    <div class="image-upload-wrapper @error('advertisement_image') border-danger @enderror"
                                         id="imageUploadWrapper">

                                        <div class="image-preview" id="imagePreview">
                                            {{-- Default image is always available --}}
                                            <img id="previewImage"
                                                 src="{{ $defaultJobImage }}"
                                                 data-default-src="{{ $defaultJobImage }}"
                                                 alt="Default job image"
                                                 class="preview-image show"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%27200%27 height=%27200%27 viewBox=%270 0 200 200%27%3E%3Crect width=%27200%27 height=%27200%27 fill=%27%23f1f3f5%27/%3E%3Ctext x=%2750%%27 y=%2750%%27 dominant-baseline=%27middle%27 text-anchor=%27middle%27 font-family=%27Arial%27 font-size=%2718%27 fill=%27%236c757d%27%3ENo Image%3C/text%3E%3C/svg%3E';">

                                            {{-- Shown only while a real image is selected --}}
                                            <i class="fas fa-image fa-3x text-muted placeholder-icon"
                                               id="placeholderIcon"
                                               style="display:none;"></i>

                                            <p class="text-muted small mt-2 placeholder-text"
                                               id="placeholderText"
                                               style="display:none;">
                                                Click to upload image
                                            </p>
                                        </div>

                                        <input type="file"
                                               name="advertisement_image"
                                               id="featuredImage"
                                               class="form-control @error('advertisement_image') is-invalid @enderror"
                                               accept="image/jpeg,image/png,image/gif,image/webp"
                                               style="display:none;">

                                        {{-- Backend should use this value when no real file is selected --}}
                                        <input type="hidden"
                                               name="advertisement_image_default"
                                               id="advertisementImageDefault"
                                               value="{{ old('advertisement_image_default', $defaultJobImageName) }}">

                                        <button type="button"
                                                class="remove-image-btn"
                                                id="removeImage"
                                                title="Use default image"
                                                aria-label="Use default image">
                                            <i class="fas fa-undo"></i>
                                        </button>

                                        @error('advertisement_image')
                                            <div class="invalid-feedback show">{{ $message }}</div>
                                        @enderror

                                        <div class="invalid-feedback" id="imageError">
                                            Image size must be less than 2MB
                                        </div>

                                        <small class="text-muted">
                                            Supported: JPG, PNG, GIF, WebP (Max 2MB).
                                            If no image is selected, the default image will be used.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Apply Link</label>
                                <input type="url" name="apply_link" class="form-control @error('apply_link') is-invalid @enderror"
                                    value="{{ old('apply_link') }}" placeholder="https://www.example.com/apply">
                                <div class="help-text">Direct link to apply</div>
                                @error('apply_link')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Description with CKEditor --}}
                            <div class="col-12 mb-3">
                                <label class="form-label">Description <span class="required-star">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                    id="descriptionEditor" rows="6">{{ old('description') }}</textarea>
                                @error('description')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Requirements</label>
                                <textarea name="requirements" class="form-control @error('requirements') is-invalid @enderror"
                                    id="requirementsEditor" rows="4">{{ old('requirements') }}</textarea>
                                @error('requirements')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Benefits</label>
                                <textarea name="benefits" class="form-control @error('benefits') is-invalid @enderror"
                                    id="benefitsEditor" rows="4">{{ old('benefits') }}</textarea>
                                @error('benefits')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Skills Required</label>
                                <textarea name="skills_required" class="form-control @error('skills_required') is-invalid @enderror"
                                    rows="3">{{ old('skills_required') }}</textarea>
                                <div class="help-text">Comma separated skills (e.g., PHP, Laravel, JavaScript)</div>
                                @error('skills_required')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Responsibilities</label>
                                <textarea name="responsibilities" class="form-control @error('responsibilities') is-invalid @enderror"
                                    rows="3">{{ old('responsibilities') }}</textarea>
                                @error('responsibilities')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Application Details --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="form-section-title"><i class="fas fa-envelope"></i> Application Details</div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Application Instructions</label>
                                <textarea name="application_instructions" class="form-control @error('application_instructions') is-invalid @enderror"
                                    rows="2">{{ old('application_instructions') }}</textarea>
                                <div class="help-text">Instructions for applicants on how to apply</div>
                                @error('application_instructions')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Deadline & Status --}}
                            <div class="col-12 mb-3 mt-2">
                                <div class="form-section-title"><i class="fas fa-calendar-alt"></i> Deadline & Status</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Application Deadline <span class="required-star">*</span></label>
                                <input type="date" name="deadline" class="form-control @error('deadline') is-invalid @enderror"
                                    value="{{ old('deadline') }}" required>
                                @error('deadline')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="form-label">Vacancies</label>
                                <input type="number" name="vacancies" class="form-control @error('vacancies') is-invalid @enderror"
                                    value="{{ old('vacancies', 1) }}" min="1">
                                @error('vacancies')<div class="invalid-feedback show">{{ $message }}</div>@enderror
                            </div>

                            {{-- Status Switches: ALL OFF by default; only explicitly enabled switches are saved as 1 --}}
                            <div class="col-md-6 mb-3">
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="is_active" class="form-check-input" id="isActive" value="1" {{ old('is_active', false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isActive">
                                                <i class="fas fa-circle text-success me-1"></i> Active
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isFeatured">
                                                <i class="fas fa-star text-warning me-1"></i> Featured
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="is_urgent" class="form-check-input" id="isUrgent" value="1" {{ old('is_urgent') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isUrgent">
                                                <i class="fas fa-fire text-danger me-1"></i> Urgent
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="is_remote" class="form-check-input" id="isRemote" value="1" {{ old('is_remote') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isRemote">
                                                <i class="fas fa-globe text-info me-1"></i> Remote
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="is_fresh" class="form-check-input" id="isFresh" value="1" {{ old('is_fresh') ? 'checked' : '' }}>
                                            <label class="form-check-label" for="isFresh">
                                                <i class="fas fa-leaf text-success me-1"></i> Fresh
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12 mt-4">
                                <hr>
                                <div class="d-flex gap-2 flex-wrap">
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-2"></i> Post Job
                                    </button>
                                    <button type="reset" class="btn btn-outline-secondary">
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
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ✅ ============================================================
    // ✅ 1. PROFESSIONAL IMAGE UPLOAD + PERMANENT DEFAULT IMAGE
    // ✅ ============================================================
    const preview = document.getElementById('imagePreview');
    const fileInput = document.getElementById('featuredImage');
    const imageError = document.getElementById('imageError');
    const removeBtn = document.getElementById('removeImage');
    const previewImage = document.getElementById('previewImage');
    const placeholderIcon = document.getElementById('placeholderIcon');
    const placeholderText = document.getElementById('placeholderText');
    const defaultInput = document.getElementById('advertisementImageDefault');

    // Default image is a static asset. It is never uploaded by JavaScript.
    const defaultImageUrl = previewImage
        ? previewImage.dataset.defaultSrc
        : @json(asset('images/no-image-icon-15.png'));

    const defaultImageName = defaultInput
        ? defaultInput.value
        : 'no-image-icon-15.png';

    let hasRealImage = false;

    function showDefaultImage() {
        hasRealImage = false;

        if (previewImage) {
            previewImage.src = defaultImageUrl;
            previewImage.classList.add('show');
            previewImage.style.display = 'block';
        }

        if (placeholderIcon) {
            placeholderIcon.classList.add('hidden');
            placeholderIcon.style.display = 'none';
        }

        if (placeholderText) {
            placeholderText.classList.add('hidden');
            placeholderText.style.display = 'none';
        }

        if (removeBtn) {
            removeBtn.classList.remove('show');
            removeBtn.title = 'Use default image';
            removeBtn.setAttribute('aria-label', 'Use default image');
        }

        if (defaultInput) {
            defaultInput.value = defaultImageName;
        }

        if (fileInput) {
            fileInput.value = '';
            fileInput.classList.remove('is-invalid');
        }

        if (imageError) {
            imageError.classList.remove('show');
            imageError.style.display = 'none';
        }
    }

    function showRealImage(src) {
        hasRealImage = true;

        if (previewImage) {
            previewImage.src = src;
            previewImage.classList.add('show');
            previewImage.style.display = 'block';
        }

        if (placeholderIcon) {
            placeholderIcon.classList.add('hidden');
            placeholderIcon.style.display = 'none';
        }

        if (placeholderText) {
            placeholderText.classList.add('hidden');
            placeholderText.style.display = 'none';
        }

        if (removeBtn) {
            removeBtn.classList.add('show');
            removeBtn.title = 'Remove image';
            removeBtn.setAttribute('aria-label', 'Remove image');
        }

        // Empty means: backend must store the uploaded real file.
        if (defaultInput) {
            defaultInput.value = '';
        }
    }

    function resetImageSelection() {
        showDefaultImage();
    }

    // Always start with the permanent default image.
    showDefaultImage();

    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];

            if (!file) {
                resetImageSelection();
                return;
            }

            // Validate file size (2MB max).
            if (file.size > 2 * 1024 * 1024) {
                if (imageError) {
                    imageError.textContent = 'Image size must be less than 2MB.';
                    imageError.classList.add('show');
                    imageError.style.display = 'block';
                }

                fileInput.classList.add('is-invalid');
                resetImageSelection();
                return;
            }

            // Validate MIME type.
            const validTypes = [
                'image/jpeg',
                'image/png',
                'image/gif',
                'image/webp'
            ];

            if (!validTypes.includes(file.type)) {
                if (imageError) {
                    imageError.textContent =
                        'Invalid file type. Please upload JPG, PNG, GIF or WebP.';
                    imageError.classList.add('show');
                    imageError.style.display = 'block';
                }

                fileInput.classList.add('is-invalid');
                resetImageSelection();
                return;
            }

            if (imageError) {
                imageError.classList.remove('show');
                imageError.style.display = 'none';
            }

            fileInput.classList.remove('is-invalid');

            const reader = new FileReader();

            reader.onload = function(e) {
                showRealImage(e.target.result);
            };

            reader.onerror = function() {
                if (imageError) {
                    imageError.textContent = 'Unable to read the selected image.';
                    imageError.classList.add('show');
                    imageError.style.display = 'block';
                }

                resetImageSelection();
            };

            reader.readAsDataURL(file);
        });
    }

    // Click preview to trigger file input.
    if (preview && fileInput) {
        preview.addEventListener('click', function() {
            fileInput.click();
        });
    }

    // Remove real image -> immediately return to default image.
    if (removeBtn) {
        removeBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            resetImageSelection();
        });
    }

    // ✅ ============================================================
    // ✅ 2. CKEDITOR 5
    // ✅ ============================================================
    if (typeof ClassicEditor !== 'undefined') {
        if (window.ckEditors) {
            window.ckEditors.forEach(editor => {
                if (editor && editor.destroy) {
                    editor.destroy().catch(e => console.warn('Editor destroy error:', e));
                }
            });
        }
        window.ckEditors = [];

        const editors = [
            { id: 'descriptionEditor', toolbar: ['heading', '|', 'bold', 'italic', 'underline', '|', 'bulletedList', 'numberedList', '|', 'blockQuote', 'link', '|', 'undo', 'redo'] },
            { id: 'requirementsEditor', toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', '|', 'undo', 'redo'] },
            { id: 'benefitsEditor', toolbar: ['bold', 'italic', 'bulletedList', 'numberedList', '|', 'undo', 'redo'] }
        ];

        editors.forEach(({ id, toolbar }) => {
            const el = document.getElementById(id);
            if (el && !el.dataset.ckeditor) {
                ClassicEditor.create(el, {
                    toolbar: toolbar,
                    removePlugins: ['Title'],
                })
                .then(editor => {
                    window.ckEditors.push(editor);
                    el.dataset.ckeditor = 'true';
                })
                .catch(error => console.error('CKEditor error:', error));
            }
        });
    }

    // ✅ ============================================================
    // ✅ 3. FORM SUBMISSION
    // ✅ ============================================================
    const form = document.getElementById('jobForm');
    const submitBtn = document.getElementById('submitBtn');

    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // ✅ Update CKEditor content before submit
            if (window.ckEditors) {
                window.ckEditors.forEach(editor => {
                    editor.updateSourceElement();
                });
            }
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Posting...
            `;
        });
    }

    // ✅ ============================================================
    // ✅ 4. ONLY SESSION TOAST (NOT VALIDATION ERRORS)
    // ✅ ============================================================
    @if(session('toast'))
        const toast = @json(session('toast'));
        showToast(toast.type, toast.message);
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
