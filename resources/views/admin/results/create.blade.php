{{-- resources/views/admin/results/create.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Add Result - Rozgar Finder')
@section('page-title', 'Add Result')
@section('page-subtitle', 'Create a new result')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-plus-circle me-2 text-primary"></i> Add Result
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

                        <form action="{{ route('admin.results.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Title <span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Enter result title" value="{{ old('title') }}" required>
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Result Date</label>
                                        <input type="date" name="result_date"
                                            class="form-control @error('result_date') is-invalid @enderror"
                                            value="{{ old('result_date', date('Y-m-d')) }}">
                                        @error('result_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Category</label>
                                        <input type="text" name="category"
                                            class="form-control @error('category') is-invalid @enderror"
                                            placeholder="e.g. Jobs, Admissions" value="{{ old('category') }}">
                                        @error('category')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Exam Type</label>
                                        <input type="text" name="exam_type"
                                            class="form-control @error('exam_type') is-invalid @enderror"
                                            placeholder="e.g. CSS, PPSC, FPSC" value="{{ old('exam_type') }}">
                                        @error('exam_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Institution</label>
                                        <input type="text" name="institution"
                                            class="form-control @error('institution') is-invalid @enderror"
                                            placeholder="e.g. University of Punjab" value="{{ old('institution') }}">
                                        @error('institution')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="6" placeholder="Write result description here..."
                                    required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-semibold">Upload File (PDF/Image)</label>
                                        <div class="file-upload-wrapper @error('file') border-danger @enderror">
                                            <div class="file-preview" id="filePreview">
                                                <i class="fas fa-cloud-upload-alt fa-3x text-muted"></i>
                                                <p class="text-muted small mt-2">Click to upload file</p>
                                                <small class="text-muted">Supported: PDF, DOC, DOCX, JPG, PNG (Max
                                                    5MB)</small>
                                            </div>
                                            <input type="file" name="file" id="resultFile"
                                                class="form-control @error('file') is-invalid @enderror"
                                                accept=".pdf,.doc,.docx,.jpeg,.jpg,.png" style="display:none;">
                                            @error('file')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
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
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Save Result
                                </button>
                                <a href="{{ route('admin.results.index') }}" class="btn btn-secondary">
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
                const preview = document.getElementById('filePreview');
                const fileInput = document.getElementById('resultFile');

                preview.addEventListener('click', function () {
                    fileInput.click();
                });

                fileInput.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (file) {
                        if (file.type.startsWith('image/')) {
                            const reader = new FileReader();
                            reader.onload = function (e) {
                                preview.innerHTML = `<img src="${e.target.result}" class="preview-image">`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            const icon = file.type.includes('pdf') ? 'fa-file-pdf' : 'fa-file';
                            preview.innerHTML = `
                                    <i class="fas ${icon} fa-3x text-danger"></i>
                                    <p class="mt-2"><strong>${file.name}</strong></p>
                                    <small class="text-muted">${(file.size / 1024).toFixed(2)} KB</small>
                                `;
                        }
                    }
                });
            });
        </script>
    @endpush

@endsection