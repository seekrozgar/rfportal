{{-- resources/views/admin/scholarships/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Add Scholarship - Rozgar Finder')
@section('page-title', 'Add Scholarship')
@section('page-subtitle', 'Create a new scholarship opportunity')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-plus-circle me-2 text-primary"></i> Add Scholarship
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

                    <form action="{{ route('admin.scholarships.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title" id="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           placeholder="Enter scholarship title" value="{{ old('title') }}" required>
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
                                           value="{{ old('deadline') }}">
                                    @error('deadline')
                                        <div class="invalid-feedback show">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="deadlineError">Deadline cannot be in the past</div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Provider</label>
                                    <input type="text" name="provider" id="provider"
                                           class="form-control @error('provider') is-invalid @enderror"
                                           placeholder="e.g. HEC, Higher Education Commission" value="{{ old('provider') }}">
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
                                           placeholder="e.g. Virtual University of Pakistan" value="{{ old('university') }}">
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
                                    <select name="degree_level" class="form-select @error('degree_level') is-invalid @enderror">
                                        <option value="">Select Degree Level</option>
                                        <option value="Bachelor" {{ old('degree_level') == 'Bachelor' ? 'selected' : '' }}>Bachelor</option>
                                        <option value="Master" {{ old('degree_level') == 'Master' ? 'selected' : '' }}>Master</option>
                                        <option value="M.Phil" {{ old('degree_level') == 'M.Phil' ? 'selected' : '' }}>M.Phil</option>
                                        <option value="PhD" {{ old('degree_level') == 'PhD' ? 'selected' : '' }}>PhD</option>
                                        <option value="Post Doc" {{ old('degree_level') == 'Post Doc' ? 'selected' : '' }}>Post Doc</option>
                                    </select>
                                    @error('degree_level')
                                        <div class="invalid-feedback show">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Scholarship Type</label>
                                    <select name="scholarship_type" class="form-select @error('scholarship_type') is-invalid @enderror">
                                        <option value="">Select Type</option>
                                        <option value="Fully Funded" {{ old('scholarship_type') == 'Fully Funded' ? 'selected' : '' }}>Fully Funded</option>
                                        <option value="Partial Funded" {{ old('scholarship_type') == 'Partial Funded' ? 'selected' : '' }}>Partial Funded</option>
                                        <option value="Tuition Waiver" {{ old('scholarship_type') == 'Tuition Waiver' ? 'selected' : '' }}>Tuition Waiver</option>
                                        <option value="Stipend" {{ old('scholarship_type') == 'Stipend' ? 'selected' : '' }}>Stipend</option>
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
                                           placeholder="e.g. PKR 100,000 or USD 1,000" value="{{ old('amount') }}">
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
                                           placeholder="e.g. Pakistan, USA, UK" value="{{ old('country') }}">
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
                                           placeholder="https://example.com/apply" value="{{ old('apply_link') }}">
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
                                      placeholder="Write scholarship description here...">{{ old('description') }}</textarea>
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
                                              placeholder="List eligibility criteria...">{{ old('eligibility') }}</textarea>
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
                                              placeholder="List benefits...">{{ old('benefits') }}</textarea>
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
                                              placeholder="List required documents...">{{ old('required_documents') }}</textarea>
                                    @error('required_documents')
                                        <div class="invalid-feedback show">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Featured Image</label>
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
                                        <div class="invalid-feedback" id="imageError">Image size must be less than 2MB</div>
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
                                           placeholder="https://propakistani.pk/edunation/scholarships/..." value="{{ old('source_url') }}">
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
                                        <option value="propakistani" {{ old('source') == 'propakistani' ? 'selected' : '' }}>Propakistani</option>
                                        <option value="official" {{ old('source') == 'official' ? 'selected' : '' }}>Official</option>
                                        <option value="other" {{ old('source') == 'other' ? 'selected' : '' }}>Other</option>
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
                                           placeholder="scholarships@example.com" value="{{ old('contact_email') }}">
                                    @error('contact_email')
                                        <div class="invalid-feedback show">{{ $message }}</div>
                                    @enderror
                                    <div class="invalid-feedback" id="emailError">Please enter a valid email address</div>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <div class="form-check mb-3 mt-4">
                                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublished"
                                           value="1" {{ old('is_published', true) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="isPublished">
                                        <i class="fas fa-check-circle text-success"></i> Publish Immediately
                                    </label>
                                    <br>
                                    <input type="checkbox" name="is_draft" class="form-check-input" id="isDraft"
                                           value="1" {{ old('is_draft') ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="isDraft">
                                        <i class="fas fa-file-alt text-info"></i> Save as Draft
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Scholarship
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
document.addEventListener('DOMContentLoaded', function() {
    // Image Preview
    const preview = document.getElementById('imagePreview');
    const fileInput = document.getElementById('featuredImage');

    if (preview && fileInput) {
        preview.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            const imageError = document.getElementById('imageError');
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    imageError.style.display = 'block';
                    fileInput.classList.add('is-invalid');
                    fileInput.value = '';
                    preview.innerHTML = `
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted small mt-2">Click to upload image</p>
                    `;
                    return;
                }
                imageError.style.display = 'none';
                fileInput.classList.remove('is-invalid');
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="preview-image">`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // Form Validation
    document.getElementById('admissionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        // Clear errors
        document.querySelectorAll('.is-invalid').forEach(function(el) {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(function(el) {
            el.style.display = 'none';
            el.classList.remove('show');
        });

        let isValid = true;

        // Title
        const title = document.getElementById('title');
        if (!title.value.trim()) {
            title.classList.add('is-invalid');
            document.getElementById('titleError').style.display = 'block';
            document.getElementById('titleError').classList.add('show');
            isValid = false;
        }

        // Deadline
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

        if (isValid) {
            document.getElementById('admissionForm').submit();
        }
    });
});
</script>
@endpush
@endsection
