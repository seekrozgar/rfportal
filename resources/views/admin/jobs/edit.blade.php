@extends('admin.layouts.admin')

@section('title', 'Edit Job - Rozgar Finder')
@section('page-title', 'Edit Job')
@section('page-subtitle', 'Update job details')

@section('content')
    <div class="admin-card">
        <div class="card-header">
            <h5><i class="fas fa-edit me-2" style="color: var(--primary-color);"></i> Edit Job: {{ $job->title }}</h5>
            <div class="card-actions">
                <a href="{{ route('admin.jobs.index') }}" class="btn-admin-outline">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.jobs.update', $job) }}" class="admin-form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- ============================================================
            BASIC INFORMATION
            ============================================================ -->
            <h6 class="mb-3" style="color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                <i class="fas fa-info-circle me-2"></i> Basic Information
            </h6>

            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="title">Job Title <span class="text-danger">*</span></label>
                        <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror"
                            value="{{ old('title', $job->title) }}" required>
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
                                <option value="{{ $id }}" {{ old('category_id', $job->category_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ============================================================
            JOB DETAILS
            ============================================================ -->
            <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                <i class="fas fa-briefcase me-2"></i> Job Details
            </h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="job_type_id">Job Type <span class="text-danger">*</span></label>
                        <select id="job_type_id" name="job_type_id"
                            class="form-control select2 @error('job_type_id') is-invalid @enderror" required>
                            <option value="">Select Job Type</option>
                            @foreach($jobTypes as $id => $name)
                                <option value="{{ $id }}" {{ old('job_type_id', $job->job_type_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('job_type_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="job_shift_id">Job Shift</label>
                        <select id="job_shift_id" name="job_shift_id"
                            class="form-control select2 @error('job_shift_id') is-invalid @enderror">
                            <option value="">Select Job Shift</option>
                            @foreach($jobShifts as $id => $name)
                                <option value="{{ $id }}" {{ old('job_shift_id', $job->job_shift_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('job_shift_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="location">Location <span class="text-danger">*</span></label>
                        <input type="text" id="location" name="location"
                            class="form-control @error('location') is-invalid @enderror"
                            value="{{ old('location', $job->location) }}" required>
                        @error('location')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="experience_level_id">Experience Level</label>
                        <select id="experience_level_id" name="experience_level_id"
                            class="form-control select2 @error('experience_level_id') is-invalid @enderror">
                            <option value="">Select Experience</option>
                            @foreach($experienceLevels as $id => $name)
                                <option value="{{ $id }}" {{ old('experience_level_id', $job->experience_level_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
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
                        <label for="career_level_id">Career Level</label>
                        <select id="career_level_id" name="career_level_id"
                            class="form-control select2 @error('career_level_id') is-invalid @enderror">
                            <option value="">Select Career Level</option>
                            @foreach($careerLevels as $id => $name)
                                <option value="{{ $id }}" {{ old('career_level_id', $job->career_level_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('career_level_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="industry_id">Industry</label>
                        <select id="industry_id" name="industry_id"
                            class="form-control select2 @error('industry_id') is-invalid @enderror">
                            <option value="">Select Industry</option>
                            @foreach($industries as $id => $name)
                                <option value="{{ $id }}" {{ old('industry_id', $job->industry_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('industry_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="functional_area_id">Functional Area</label>
                        <select id="functional_area_id" name="functional_area_id"
                            class="form-control select2 @error('functional_area_id') is-invalid @enderror">
                            <option value="">Select Functional Area</option>
                            @foreach($functionalAreas as $id => $name)
                                <option value="{{ $id }}" {{ old('functional_area_id', $job->functional_area_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('functional_area_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="degree_level_id">Degree Level</label>
                        <select id="degree_level_id" name="degree_level_id"
                            class="form-control select2 @error('degree_level_id') is-invalid @enderror">
                            <option value="">Select Degree Level</option>
                            @foreach($degreeLevels as $id => $name)
                                <option value="{{ $id }}" {{ old('degree_level_id', $job->degree_level_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('degree_level_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="gender_id">Gender</label>
                        <select id="gender_id" name="gender_id"
                            class="form-control select2 @error('gender_id') is-invalid @enderror">
                            <option value="">Select Gender</option>
                            @foreach($genders as $id => $name)
                                <option value="{{ $id }}" {{ old('gender_id', $job->gender_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('gender_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="marital_status_id">Marital Status</label>
                        <select id="marital_status_id" name="marital_status_id"
                            class="form-control select2 @error('marital_status_id') is-invalid @enderror">
                            <option value="">Select Marital Status</option>
                            @foreach($maritalStatuses as $id => $name)
                                <option value="{{ $id }}" {{ old('marital_status_id', $job->marital_status_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('marital_status_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="company_id">Company (Optional)</label>
                        <select id="company_id" name="company_id"
                            class="form-control select2 @error('company_id') is-invalid @enderror">
                            <option value="">Select Company</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ old('company_id', $job->company_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('company_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ============================================================
            SALARY
            ============================================================ -->
            <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                <i class="fas fa-money-bill-wave me-2"></i> Salary Information
            </h6>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salary_min">Salary (Min)</label>
                        <input type="text" id="salary_min" name="salary_min"
                            class="form-control @error('salary_min') is-invalid @enderror"
                            value="{{ old('salary_min', $job->salary_min) }}" placeholder="e.g. 50000">
                        @error('salary_min')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salary_max">Salary (Max)</label>
                        <input type="text" id="salary_max" name="salary_max"
                            class="form-control @error('salary_max') is-invalid @enderror"
                            value="{{ old('salary_max', $job->salary_max) }}" placeholder="e.g. 100000">
                        @error('salary_max')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="salary_period_id">Salary Period</label>
                        <select id="salary_period_id" name="salary_period_id"
                            class="form-control select2 @error('salary_period_id') is-invalid @enderror">
                            <option value="">Select Period</option>
                            @foreach($salaryPeriods as $id => $name)
                                <option value="{{ $id }}" {{ old('salary_period_id', $job->salary_period_id) == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('salary_period_id')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ============================================================
            ADVERTISEMENT
            ============================================================ -->
            <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                <i class="fas fa-image me-2"></i> Advertisement
            </h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ad_image">Advertisement Image</label>
                        @if($job->ad_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/jobs/' . $job->ad_image) }}" alt="{{ $job->title }}"
                                    style="width: 200px; height: 100px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd;">
                            </div>
                        @endif
                        <input type="file" id="ad_image" name="ad_image"
                            class="form-control @error('ad_image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Leave empty to keep current image. Max: 2MB</small>
                        @error('ad_image')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="apply_link">Apply Link</label>
                        <input type="url" id="apply_link" name="apply_link"
                            class="form-control @error('apply_link') is-invalid @enderror"
                            value="{{ old('apply_link', $job->apply_link) }}"
                            placeholder="https://www.ppsc.gov.pk/apply/12345">
                        <small class="text-muted">Direct link to apply on PPSC/FPSC website</small>
                        @error('apply_link')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- ============================================================
            DESCRIPTION
            ============================================================ -->
            <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                <i class="fas fa-file-alt me-2"></i> Description
            </h6>

            <div class="form-group">
                <label for="description">Description <span class="text-danger">*</span></label>
                <textarea id="description" name="description"
                    class="form-control @error('description') is-invalid @enderror"
                    rows="5">{{ old('description', $job->description) }}</textarea>
                @error('description')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="requirements">Requirements</label>
                <textarea id="requirements" name="requirements"
                    class="form-control @error('requirements') is-invalid @enderror"
                    rows="4">{{ old('requirements', $job->requirements) }}</textarea>
                @error('requirements')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="benefits">Benefits</label>
                <textarea id="benefits" name="benefits"
                    class="form-control @error('benefits') is-invalid @enderror"
                    rows="3">{{ old('benefits', $job->benefits) }}</textarea>
                @error('benefits')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- ============================================================
            DEADLINE & STATUS
            ============================================================ -->
            <h6 class="mb-3 mt-4" style="color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 8px;">
                <i class="fas fa-calendar-alt me-2"></i> Deadline & Status
            </h6>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="application_deadline">Application Deadline <span class="text-danger">*</span></label>
                        <input type="date" id="application_deadline" name="application_deadline"
                            class="form-control @error('application_deadline') is-invalid @enderror"
                            value="{{ old('application_deadline', $job->application_deadline->format('Y-m-d')) }}" required>
                        @error('application_deadline')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="is_active" name="is_active" class="form-check-input" value="1" {{ old('is_active', $job->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="form-check">
                                    <input type="checkbox" id="is_featured" name="is_featured" class="form-check-input" value="1" {{ old('is_featured', $job->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">Featured</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <button type="submit" class="btn-admin-primary">
                    <i class="fas fa-save"></i> Update Job
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
        });
    </script>
@endpush
