{{-- resources/views/admin/news/edit.blade.php --}}
@extends('admin.layouts.admin')

@section('title', 'Edit News')
@section('page-title', 'Edit News')
@section('page-subtitle', 'Update news post')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-edit me-2 text-primary"></i> Edit News
                    </h5>
                </div>
                <div class="card-body">
                    {{-- ✅ Error Messages --}}
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
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

                    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                           class="form-control @error('title') is-invalid @enderror"
                                           placeholder="Enter news title"
                                           value="{{ old('title', $news->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">News Date</label>
                                    <input type="date" name="news_date"
                                           class="form-control @error('news_date') is-invalid @enderror"
                                           value="{{ old('news_date', $news->news_date?->format('Y-m-d') ?? date('Y-m-d')) }}">
                                    @error('news_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label fw-semibold">Content <span class="text-danger">*</span></label>
                            <textarea name="content"
                                      class="form-control @error('content') is-invalid @enderror"
                                      rows="8" placeholder="Write news content here..."
                                      required>{{ old('content', $news->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                {{-- ✅ Featured Image Upload --}}
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Featured Image</label>

                                    {{-- Show current image --}}
                                    @if($news->featured_image)
                                        <div class="current-image mb-2">
                                            <p class="mb-1">Current Image:</p>
                                            <img src="{{ asset('storage/' . $news->featured_image) }}"
                                                 alt="{{ $news->title }}" class="current-preview">
                                        </div>
                                    @endif

                                    {{-- ✅ Show uploaded image preview if exists --}}
                                    @if(old('featured_image_temp'))
                                        <div class="mb-2">
                                            <p class="mb-1">New Image:</p>
                                            <img src="{{ old('featured_image_temp') }}" class="temp-preview">
                                        </div>
                                    @endif

                                    <div class="image-upload-wrapper @error('featured_image') border-danger @enderror">
                                        <div class="image-preview" id="imagePreview">
                                            @if(old('featured_image_temp'))
                                                <img src="{{ old('featured_image_temp') }}" class="preview-image">
                                            @elseif($news->featured_image)
                                                <img src="{{ asset('storage/' . $news->featured_image) }}"
                                                     alt="{{ $news->title }}" class="preview-image">
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
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label fw-semibold">Source</label>
                                    <input type="text" name="source"
                                           class="form-control @error('source') is-invalid @enderror"
                                           placeholder="e.g. PPSC, FPSC, Newspaper"
                                           value="{{ old('source', $news->source) }}">
                                    @error('source')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" name="is_published" class="form-check-input" id="isPublished"
                                           value="1" {{ old('is_published', $news->is_published) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="isPublished">
                                        <i class="fas fa-check-circle text-success"></i> Publish
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save"></i> Update News
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">
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
        const preview = document.getElementById('imagePreview');
        const fileInput = document.getElementById('featuredImage');

        preview.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" class="preview-image">`;
                };
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush

@endsection
