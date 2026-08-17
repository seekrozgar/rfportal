@extends('admin.layouts.admin')

@section('title', 'Add Job - Rozgar Finder')
@section('page-title', 'Add New Job')
@section('page-subtitle', 'Post a new general job (PPSC/FPSC)')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-plus-circle me-2" style="color: var(--primary-color);"></i> Create New Job</h5>
            <div class="card-actions">
                <a href="{{ route('admin.jobs.index') }}" class="btn-admin-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.jobs.store') }}" class="admin-form" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Job Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title') }}" placeholder="e.g. Assistant Director (BS-17)" required>
                        @error('title')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="category_id">Category <span class="text-danger">*</span></label>
                        <select id="category_id" name="category_id"
                            class="form-control select2 @error('category_id') is-invalid @enderror" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="job_type_id">Job Type <span class="text-danger">*</span></label>
                        <select id="job_type_id" name="job_type_id"
                            class="form-control select2 @error('job_type_id') is-invalid @enderror" required>
                            <option value="">Select Job Type</option>
                            @foreach($jobTypes as $id => $name)
                                <option value="{{ $id }}" {{ old('job_type_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('job_type_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="experience_level_id">Experience Level</label>
                        <select id="experience_level_id" name="experience_level_id"
                            class="form-control select2 @error('experience_level_id') is-invalid @enderror">
                            <option value="">Select Experience</option>
                            @foreach($experienceLevels as $id => $name)
                                <option value="{{ $id }}" {{ old('experience_level_id') == $id ? 'selected' : '' }}>{{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('experience_level_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text" id="location" name="location"
                            class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}"
                            placeholder="e.g. Lahore, Pakistan" required>
                        @error('location')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ✅ ADVERTISEMENT IMAGE -->
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ad_image">Advertisement Image</label>
                        <input type="file" id="ad_image" name="ad_image"
                            class="form-control @error('ad_image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Recommended size: 800x400px. Max: 2MB</small>
                        @error('ad_image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apply_link">Apply Link</label>
                        <input type="url" id="apply_link" name="apply_link"
                            class="form-control @error('apply_link') is-invalid @enderror" value="{{ old('apply_link') }}"
                            placeholder="https://www.ppsc.gov.pk/apply/12345">
                        <small class="text-muted">Direct link to apply on PPSC/FPSC website</small>
                        @error('apply_link')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salary_min">Salary (Min)</label>
                        <input type="text" id="salary_min" name="salary_min"
                            class="form-control @error('salary_min') is-invalid @enderror" value="{{ old('salary_min') }}"
                            placeholder="e.g. 50000">
                        @error('salary_min')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salary_max">Salary (Max)</label>
                        <input type="text" id="salary_max" name="salary_max"
                            class="form-control @error('salary_max') is-invalid @enderror" value="{{ old('salary_max') }}"
                            placeholder="e.g. 100000">
                        @error('salary_max')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salary_period">Salary Period</label>
                        <select id="salary_period" name="salary_period"
                            class="form-control @error('salary_period') is-invalid @enderror">
                            <option value="">Select Period</option>
                            <option value="Monthly" {{ old('salary_period') == 'Monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="Yearly" {{ old('salary_period') == 'Yearly' ? 'selected' : '' }}>Yearly</option>
                            <option value="Hourly" {{ old('salary_period') == 'Hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="Daily" {{ old('salary_period') == 'Daily' ? 'selected' : '' }}>Daily</option>
                        </select>
                        @error('salary_period')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_id">Company (Optional)</label>
                        <select id="company_id" name="company_id"
                            class="form-control select2 @error('company_id') is-invalid @enderror">
                            <option value="">Select Company</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_deadline">Application Deadline <span class="text-danger">*</span></label>
                        <input type="date" id="application_deadline" name="application_deadline"
                            class="form-control @error('application_deadline') is-invalid @enderror"
                            value="{{ old('application_deadline') }}" required>
                        @error('application_deadline')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea id="description" name="description"
                    class="form-control @error('description') is-invalid @enderror" rows="5"
                    placeholder="Detailed job description...">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements"
                    class="form-control @error('requirements') is-invalid @enderror" rows="4"
                    placeholder="Job requirements...">{{ old('requirements') }}</textarea>
                @error('requirements')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="benefits">Benefits</label>
                <textarea id="benefits" name="benefits" class="form-control @error('benefits') is-invalid @enderror"
                    rows="3" placeholder="Job benefits...">{{ old('benefits') }}</textarea>
                @error('benefits')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">Featured</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn-admin-primary">
                    <i class="fas fa-save"></i> Save Job
                </button>
                <a href="{{ route('admin.jobs.index') }}" class="btn-admin-outline">Cancel</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Select option',
                allowClear: true,
                width: '100%'
            });

            // ✅ Preview image before upload
            $('#ad_image').on('change', function () {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        // You can show preview here if needed
                        console.log('Image selected:', file.name);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
@endpush
