{{-- resources/views/employer/company-profile/edit.blade.php --}}

@extends('employer.layouts.employer')

@section('title', 'Company Profile')
@section('page-title', 'Company Profile')
@section('page-subtitle', 'Update your company information')

@push('styles')
    <style>
        /* ✅ Form Styles */
        .form-section {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            border: 1px solid #eef2f6;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .form-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 2px solid #f1f5f9;
        }

        .form-section-title i {
            color: #11998e;
            margin-right: 8px;
        }

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
            border-color: #11998e;
            box-shadow: 0 0 0 3px rgba(17, 153, 142, 0.1);
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
            display: none;
        }

        .invalid-feedback.show {
            display: block;
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

        .btn-primary {
            background: #11998e;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: #0e7a71;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(17, 153, 142, 0.3);
        }

        .btn-secondary {
            border: 1px solid #e2e8f0;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: #fff;
            color: #1e293b;
        }

        .btn-secondary:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
        }

        /* ✅ Image Upload */
        .image-upload-wrapper {
            position: relative;
            border: 2px dashed #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #fafbfc;
            min-height: 140px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .image-upload-wrapper:hover {
            border-color: #11998e;
            background: #f8fafc;
        }

        .image-upload-wrapper .image-preview {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
        }

        .image-upload-wrapper .image-preview .preview-image {
            max-width: 100%;
            max-height: 120px;
            border-radius: 8px;
            object-fit: contain;
            display: none;
        }

        .image-upload-wrapper .image-preview .preview-image.show {
            display: block;
        }

        .image-upload-wrapper .image-preview .placeholder-icon {
            color: #94a3b8;
            opacity: 0.5;
        }

        .image-upload-wrapper .image-preview .placeholder-text {
            color: #94a3b8;
            font-size: 13px;
            margin-top: 6px;
        }

        .image-upload-wrapper .remove-image-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: #fee2e2;
            color: #ef4444;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: none;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 13px;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .image-upload-wrapper .remove-image-btn:hover {
            background: #ef4444;
            color: #fff;
            transform: scale(1.1);
        }

        .image-upload-wrapper .remove-image-btn.show {
            display: flex;
        }

        .image-upload-wrapper input[type="file"] {
            display: none;
        }

        /* ✅ Completion Progress */
        .completion-progress {
            background: #f8fafc;
            border-radius: 12px;
            padding: 16px 20px;
            border: 1px solid #eef2f6;
            margin-bottom: 24px;
        }

        .completion-progress .progress-bar {
            height: 8px;
            border-radius: 4px;
            background: #e2e8f0;
            overflow: hidden;
            margin-top: 8px;
        }

        .completion-progress .progress-fill {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(90deg, #11998e, #38ef7d);
            transition: width 0.8s ease;
        }

        .completion-progress .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .completion-progress .progress-label .percentage {
            font-weight: 700;
            color: #11998e;
            font-size: 18px;
        }

        .completion-progress .progress-label .text {
            font-size: 14px;
            color: #64748b;
        }

        .completion-progress .progress-label .status {
            font-size: 13px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
        }

        .completion-progress .progress-label .status.complete {
            background: #dcfce7;
            color: #166534;
        }

        .completion-progress .progress-label .status.incomplete {
            background: #fef3c7;
            color: #92400e;
        }

        /* ✅ Responsive */
        @media (max-width: 768px) {
            .form-section {
                padding: 16px;
            }

            .image-upload-wrapper {
                min-height: 120px;
                padding: 16px;
            }

            .image-upload-wrapper .image-preview .preview-image {
                max-height: 100px;
            }

            .completion-progress .progress-label {
                flex-wrap: wrap;
                gap: 8px;
            }
        }

        @media (max-width: 480px) {
            .form-section {
                padding: 12px;
            }

            .image-upload-wrapper {
                min-height: 100px;
                padding: 12px;
            }

            .image-upload-wrapper .image-preview .preview-image {
                max-height: 80px;
            }

            .btn-primary,
            .btn-secondary {
                width: 100%;
                justify-content: center;
            }

            .d-flex.gap-2 {
                flex-direction: column;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Completion Progress --}}
                <div class="completion-progress">
                    <div class="progress-label">
                        <div>
                            <span class="text">Profile Completion</span>
                            <span class="percentage">{{ $completionPercentage ?? 0 }}%</span>
                        </div>
                        <span class="status {{ ($completionPercentage ?? 0) >= 80 ? 'complete' : 'incomplete' }}">
                            {{ ($completionPercentage ?? 0) >= 80 ? '✅ Complete' : '⚠️ Incomplete' }}
                        </span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $completionPercentage ?? 0 }}%;"></div>
                    </div>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-info-circle me-1"></i>
                        Complete at least 80% to unlock all features
                    </small>
                </div>

                <form action="{{ route('employer.company-profile.update') }}" method="POST" enctype="multipart/form-data"
                    id="companyForm">
                    @csrf
                    @method('PUT')

                    {{-- ✅ Basic Information --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-building"></i> Basic Information
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Company Name <span class="required-star">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $company->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Industry</label>
                                <select name="industry" class="form-select @error('industry') is-invalid @enderror">
                                    <option value="">Select Industry</option>
                                    @foreach($industries as $key => $value)
                                        <option value="{{ $key }}" {{ old('industry', $company->industry) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('industry')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $company->email) }}">
                                @error('email')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $company->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-control @error('website') is-invalid @enderror"
                                    value="{{ old('website', $company->website) }}" placeholder="https://www.example.com">
                                @error('website')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                                    rows="2">{{ old('address', $company->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ✅ Company Details --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-info-circle"></i> Company Details
                        </div>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                    rows="4"
                                    placeholder="Describe your company, mission, and values">{{ old('description', $company->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Company Size</label>
                                <select name="company_size" class="form-select @error('company_size') is-invalid @enderror">
                                    <option value="">Select Size</option>
                                    @foreach($companySizes as $key => $value)
                                        <option value="{{ $key }}" {{ old('company_size', $company->company_size) == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('company_size')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Founded Year</label>
                                <input type="text" name="founded_year"
                                    class="form-control @error('founded_year') is-invalid @enderror"
                                    value="{{ old('founded_year', $company->founded_year) }}" placeholder="e.g. 2010">
                                @error('founded_year')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Headquarters</label>
                                <input type="text" name="headquarters"
                                    class="form-control @error('headquarters') is-invalid @enderror"
                                    value="{{ old('headquarters', $company->headquarters) }}"
                                    placeholder="e.g. Lahore, Pakistan">
                                @error('headquarters')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ✅ Media & Branding --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-image"></i> Media & Branding
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Company Logo</label>
                                <div class="image-upload-wrapper" id="logoWrapper"
                                    onclick="document.getElementById('logoInput').click()">
                                    <div class="image-preview" id="logoPreview">
                                        @if($company->logo)
                                            <img id="logoPreviewImg" src="{{ asset('storage/' . $company->logo) }}" alt="Logo"
                                                class="preview-image show">
                                            <i class="fas fa-image fa-3x text-muted placeholder-icon"
                                                style="display: none;"></i>
                                            <p class="text-muted small mt-2 placeholder-text" style="display: none;">Click to
                                                change logo</p>
                                        @else
                                            <i class="fas fa-image fa-3x text-muted placeholder-icon"></i>
                                            <p class="text-muted small mt-2 placeholder-text">Click to upload logo</p>
                                            <img id="logoPreviewImg" src="#" alt="Preview" class="preview-image">
                                        @endif
                                    </div>
                                    <input type="file" id="logoInput" accept="image/*" onchange="uploadImage(this, 'logo')">
                                    <button type="button" class="remove-image-btn" id="removeLogoBtn"
                                        onclick="removeImage('logo')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="help-text">Recommended: 200x60px. Max: 2MB</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Cover Image</label>
                                <div class="image-upload-wrapper" id="coverWrapper"
                                    onclick="document.getElementById('coverInput').click()">
                                    <div class="image-preview" id="coverPreview">
                                        @if($company->cover_image)
                                            <img id="coverPreviewImg" src="{{ asset('storage/' . $company->cover_image) }}"
                                                alt="Cover" class="preview-image show">
                                            <i class="fas fa-image fa-3x text-muted placeholder-icon"
                                                style="display: none;"></i>
                                            <p class="text-muted small mt-2 placeholder-text" style="display: none;">Click to
                                                change cover</p>
                                        @else
                                            <i class="fas fa-image fa-3x text-muted placeholder-icon"></i>
                                            <p class="text-muted small mt-2 placeholder-text">Click to upload cover image</p>
                                            <img id="coverPreviewImg" src="#" alt="Preview" class="preview-image">
                                        @endif
                                    </div>
                                    <input type="file" id="coverInput" accept="image/*"
                                        onchange="uploadImage(this, 'cover')">
                                    <button type="button" class="remove-image-btn" id="removeCoverBtn"
                                        onclick="removeImage('cover')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="help-text">Recommended: 1200x400px. Max: 5MB</div>
                            </div>
                        </div>
                    </div>

                    {{-- ✅ Verification Documents --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-shield-alt"></i> Verification Documents
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">NTN Number</label>
                                <input type="text" name="ntn_number"
                                    class="form-control @error('ntn_number') is-invalid @enderror"
                                    value="{{ old('ntn_number', $company->ntn_number) }}" placeholder="e.g. 1234567-8">
                                <div class="help-text">National Tax Number</div>
                                @error('ntn_number')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">SECP Number</label>
                                <input type="text" name="secp_number"
                                    class="form-control @error('secp_number') is-invalid @enderror"
                                    value="{{ old('secp_number', $company->secp_number) }}" placeholder="e.g. 0123456">
                                <div class="help-text">SECP Registration Number</div>
                                @error('secp_number')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Business License</label>
                                <div class="image-upload-wrapper" id="licenseWrapper"
                                    onclick="document.getElementById('licenseInput').click()">
                                    <div class="image-preview" id="licensePreview">
                                        @if($company->business_license)
                                            @php
                                                $ext = pathinfo($company->business_license, PATHINFO_EXTENSION);
                                            @endphp
                                            @if(in_array($ext, ['pdf']))
                                                <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                                <p class="text-muted small mt-2">Current License (PDF)</p>
                                            @else
                                                <img id="licensePreviewImg"
                                                    src="{{ asset('storage/' . $company->business_license) }}" alt="License"
                                                    class="preview-image show">
                                                <i class="fas fa-image fa-3x text-muted placeholder-icon"
                                                    style="display: none;"></i>
                                                <p class="text-muted small mt-2 placeholder-text" style="display: none;">Click to
                                                    change</p>
                                            @endif
                                        @else
                                            <i class="fas fa-file-upload fa-3x text-muted"></i>
                                            <p class="text-muted small mt-2">Click to upload business license</p>
                                            <img id="licensePreviewImg" src="#" alt="Preview" class="preview-image">
                                        @endif
                                    </div>
                                    <input type="file" id="licenseInput" accept=".pdf,image/*"
                                        onchange="uploadImage(this, 'license')">
                                    <button type="button" class="remove-image-btn" id="removeLicenseBtn"
                                        onclick="removeImage('license')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                                <div class="help-text">Supported: PDF, JPG, PNG. Max: 5MB</div>
                            </div>
                        </div>
                    </div>

                    {{-- ✅ Social Media --}}
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-share-alt"></i> Social Media
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                                <input type="url" name="facebook"
                                    class="form-control @error('facebook') is-invalid @enderror"
                                    value="{{ old('facebook', $company->facebook) }}"
                                    placeholder="https://facebook.com/yourpage">
                                @error('facebook')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fab fa-linkedin text-primary"></i> LinkedIn</label>
                                <input type="url" name="linkedin"
                                    class="form-control @error('linkedin') is-invalid @enderror"
                                    value="{{ old('linkedin', $company->linkedin) }}"
                                    placeholder="https://linkedin.com/company/yourpage">
                                @error('linkedin')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                                <input type="url" name="twitter" class="form-control @error('twitter') is-invalid @enderror"
                                    value="{{ old('twitter', $company->twitter) }}"
                                    placeholder="https://twitter.com/yourpage">
                                @error('twitter')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                                <input type="url" name="instagram"
                                    class="form-control @error('instagram') is-invalid @enderror"
                                    value="{{ old('instagram', $company->instagram) }}"
                                    placeholder="https://instagram.com/yourpage">
                                @error('instagram')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label"><i class="fab fa-youtube text-danger"></i> YouTube</label>
                                <input type="url" name="youtube" class="form-control @error('youtube') is-invalid @enderror"
                                    value="{{ old('youtube', $company->youtube) }}"
                                    placeholder="https://youtube.com/@yourchannel">
                                @error('youtube')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ✅ Submit Buttons --}}
                    <div class="d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i> Save Company Profile
                        </button>
                        <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                        </a>
@if(isset($company))

    @if($company->verification_status === 'verified')

        <span class="btn btn-success disabled">
            <i class="fas fa-check-circle me-2"></i>
            Verified Company
        </span>

    @elseif($company->verification_status === 'pending')

        <span class="btn btn-warning disabled">
            <i class="fas fa-clock me-2"></i>
            Verification Pending
        </span>

    @elseif($company->verification_status === 'rejected')

    <div class="alert alert-danger mb-3">

        <strong>
            <i class="fas fa-exclamation-circle me-1"></i>
            Verification Rejected
        </strong>

        <div class="mt-1">
            {{ $company->verification_rejection_reason }}
        </div>

        <small class="d-block mt-2">
            Please correct the issue and submit again.
        </small>

    </div>
        <button type="button"
                class="btn btn-danger"
                onclick="requestVerification()">
            <i class="fas fa-redo me-2"></i>
            Request Verification Again
        </button>

    @elseif($company->is_complete)

        <button type="button"
                class="btn btn-warning"
                onclick="requestVerification()">
            <i class="fas fa-shield-alt me-2"></i>
            Request Verification
        </button>

    @endif

@endif
                    </div>
                </form>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {

            /*
             * ============================================================
             * COMPANY PROFILE MEDIA
             * Logo + Cover + Business License
             * No browser alert()/confirm()
             * ============================================================
             */

            const uploadConfig = {
                logo: {
                    inputId: 'logoInput',
                    previewId: 'logoPreviewImg',
                    wrapperId: 'logoWrapper',
                    removeBtnId: 'removeLogoBtn',
                    maxSize: 2 * 1024 * 1024,
                    label: 'Company logo'
                },
                cover: {
                    inputId: 'coverInput',
                    previewId: 'coverPreviewImg',
                    wrapperId: 'coverWrapper',
                    removeBtnId: 'removeCoverBtn',
                    maxSize: 5 * 1024 * 1024,
                    label: 'Cover image'
                },
                license: {
                    inputId: 'licenseInput',
                    previewId: 'licensePreviewImg',
                    wrapperId: 'licenseWrapper',
                    removeBtnId: 'removeLicenseBtn',
                    maxSize: 5 * 1024 * 1024,
                    label: 'Business license'
                }
            };

            /*
             * ------------------------------------------------------------
             * Custom Toast
             * ------------------------------------------------------------
             */
            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = value ?? '';
                return div.innerHTML;
            }

            function showToast(type, message, title = null) {

                let container = document.getElementById('companyToastContainer');

                if (!container) {
                    container = document.createElement('div');
                    container.id = 'companyToastContainer';

                    Object.assign(container.style, {
                        position: 'fixed',
                        top: '24px',
                        right: '24px',
                        zIndex: '99999',
                        width: 'min(390px, calc(100vw - 32px))',
                        pointerEvents: 'none'
                    });

                    document.body.appendChild(container);
                }

                const meta = {
                    success: {
                        title: title || 'Success',
                        icon: 'fa-check',
                        iconClass: 'success'
                    },
                    error: {
                        title: title || 'Error',
                        icon: 'fa-times',
                        iconClass: 'error'
                    },
                    warning: {
                        title: title || 'Warning',
                        icon: 'fa-exclamation',
                        iconClass: 'warning'
                    },
                    info: {
                        title: title || 'Information',
                        icon: 'fa-info',
                        iconClass: 'info'
                    }
                };

                const item = meta[type] || meta.info;

                const toast = document.createElement('div');

                toast.innerHTML = `
                    <div class="company-profile-toast ${item.iconClass}">
                        <div class="company-profile-toast-icon">
                            <i class="fas ${item.icon}"></i>
                        </div>

                        <div class="company-profile-toast-body">
                            <div class="company-profile-toast-title">
                                ${escapeHtml(item.title)}
                            </div>
                            <div class="company-profile-toast-message">
                                ${escapeHtml(message)}
                            </div>
                        </div>

                        <button type="button"
                                class="company-profile-toast-close"
                                aria-label="Close">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                const toastElement = toast.firstElementChild;

                toastElement.style.cssText = `
                    display:flex;
                    align-items:flex-start;
                    gap:12px;
                    padding:14px 16px;
                    margin-bottom:10px;
                    border-radius:12px;
                    background:#fff;
                    color:#1e293b;
                    border:1px solid #e2e8f0;
                    box-shadow:0 12px 35px rgba(15,23,42,.16);
                    opacity:0;
                    transform:translateY(-10px);
                    transition:all .25s ease;
                    pointer-events:auto;
                `;

                const icon = toastElement.querySelector('.company-profile-toast-icon');

                icon.style.cssText = `
                    width:34px;
                    height:34px;
                    flex:0 0 34px;
                    border-radius:50%;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                `;

                const iconBackgrounds = {
                    success: ['#dcfce7', '#15803d'],
                    error: ['#fee2e2', '#dc2626'],
                    warning: ['#fef3c7', '#b45309'],
                    info: ['#dbeafe', '#2563eb']
                };

                const colors = iconBackgrounds[item.iconClass] || iconBackgrounds.info;
                icon.style.background = colors[0];
                icon.style.color = colors[1];

                toastElement.querySelector('.company-profile-toast-body')
                    .style.cssText = 'flex:1;min-width:0;';

                toastElement.querySelector('.company-profile-toast-title')
                    .style.cssText = 'font-size:13px;font-weight:700;margin-bottom:2px;';

                toastElement.querySelector('.company-profile-toast-message')
                    .style.cssText = 'font-size:12px;line-height:1.45;color:#64748b;';

                const closeButton =
                    toastElement.querySelector('.company-profile-toast-close');

                closeButton.style.cssText = `
                    border:0;
                    background:transparent;
                    color:#94a3b8;
                    padding:0 2px;
                    cursor:pointer;
                `;

                container.appendChild(toastElement);

                requestAnimationFrame(() => {
                    toastElement.style.opacity = '1';
                    toastElement.style.transform = 'translateY(0)';
                });

                function closeToast() {
                    toastElement.style.opacity = '0';
                    toastElement.style.transform = 'translateY(-10px)';

                    setTimeout(() => {
                        toastElement.remove();
                    }, 250);
                }

                closeButton.addEventListener('click', closeToast);

                setTimeout(closeToast, 4500);
            }

            window.showToast = showToast;


            /*
             * ------------------------------------------------------------
             * Custom confirmation modal
             * ------------------------------------------------------------
             */
            function showConfirm(title, message, confirmText = 'Remove') {

                return new Promise(resolve => {

                    const oldModal =
                        document.getElementById('companyConfirmModal');

                    if (oldModal) {
                        oldModal.remove();
                    }

                    const modal = document.createElement('div');

                    modal.id = 'companyConfirmModal';

                    modal.innerHTML = `
                        <div style="
                            position:fixed;
                            inset:0;
                            z-index:100000;
                            background:rgba(15,23,42,.55);
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            padding:20px;
                        ">
                            <div style="
                                width:min(420px,100%);
                                background:#fff;
                                border-radius:16px;
                                padding:24px;
                                box-shadow:0 20px 60px rgba(15,23,42,.25);
                            ">
                                <div style="
                                    width:48px;
                                    height:48px;
                                    border-radius:50%;
                                    background:#fee2e2;
                                    color:#dc2626;
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    margin-bottom:14px;
                                ">
                                    <i class="fas fa-trash-alt"></i>
                                </div>

                                <h5 style="
                                    margin:0 0 8px;
                                    font-weight:700;
                                    color:#1e293b;
                                ">
                                    ${escapeHtml(title)}
                                </h5>

                                <p style="
                                    margin:0 0 20px;
                                    color:#64748b;
                                    font-size:14px;
                                ">
                                    ${escapeHtml(message)}
                                </p>

                                <div style="
                                    display:flex;
                                    gap:10px;
                                    justify-content:flex-end;
                                ">
                                    <button type="button"
                                            class="btn btn-secondary"
                                            id="companyConfirmCancel">
                                        Cancel
                                    </button>

                                    <button type="button"
                                            class="btn btn-danger"
                                            id="companyConfirmOk">
                                        <i class="fas fa-trash-alt me-1"></i>
                                        ${escapeHtml(confirmText)}
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;

                    document.body.appendChild(modal);

                    modal.querySelector('#companyConfirmCancel')
                        .addEventListener('click', () => {
                            modal.remove();
                            resolve(false);
                        });

                    modal.querySelector('#companyConfirmOk')
                        .addEventListener('click', () => {
                            modal.remove();
                            resolve(true);
                        });

                    modal.addEventListener('click', event => {
                        if (event.target === modal.firstElementChild) {
                            modal.remove();
                            resolve(false);
                        }
                    });
                });
            }


            /*
             * ------------------------------------------------------------
             * Loading overlay
             * ------------------------------------------------------------
             */
            function setLoading(wrapper, loading) {

                if (!wrapper) return;

                const existing =
                    wrapper.querySelector('.company-media-loading');

                if (loading) {

                    if (existing) return;

                    const loader = document.createElement('div');

                    loader.className = 'company-media-loading';

                    loader.style.cssText = `
                        position:absolute;
                        inset:0;
                        z-index:20;
                        display:flex;
                        align-items:center;
                        justify-content:center;
                        background:rgba(255,255,255,.72);
                        backdrop-filter:blur(2px);
                        border-radius:12px;
                    `;

                    loader.innerHTML = `
                        <span class="spinner-border spinner-border-sm text-success"
                              role="status"></span>
                    `;

                    wrapper.appendChild(loader);

                } else if (existing) {
                    existing.remove();
                }
            }


            /*
             * ------------------------------------------------------------
             * Normalize URL returned by controller
             * ------------------------------------------------------------
             */
            function getReturnedUrl(data) {

                if (!data) return null;

                let url =
                    data.url ||
                    data.avatar ||
                    data.image ||
                    data.file_url ||
                    data.path_url ||
                    null;

                if (!url && data.path) {
                    url = `{{ asset('storage') }}/${String(data.path).replace(/^\/+/, '')}`;
                }

                if (!url && data.file) {
                    url = `{{ asset('storage') }}/${String(data.file).replace(/^\/+/, '')}`;
                }

                return url;
            }


            /*
             * ------------------------------------------------------------
             * Set image preview
             * ------------------------------------------------------------
             */
            function setImagePreview(type, url) {

                const config = uploadConfig[type];

                if (!config) return;

                const preview =
                    document.getElementById(config.previewId);

                const wrapper =
                    document.getElementById(config.wrapperId);

                if (!preview || !url) return;

                const icon =
                    wrapper?.querySelector('.placeholder-icon');

                const text =
                    wrapper?.querySelector('.placeholder-text');

                preview.onload = function () {

                    preview.style.display = 'block';
                    preview.classList.add('show');

                    if (icon) icon.style.display = 'none';
                    if (text) text.style.display = 'none';
                };

                preview.onerror = function () {

                    preview.style.display = 'none';
                    preview.classList.remove('show');

                    if (icon) icon.style.display = 'block';
                    if (text) text.style.display = 'block';

                    showToast(
                        'error',
                        `${config.label} was uploaded, but could not be displayed. Please check the storage link.`,
                        'Display Error'
                    );
                };

                preview.src =
                    url + (url.includes('?') ? '&' : '?') + 'v=' + Date.now();

                const removeButton =
                    document.getElementById(config.removeBtnId);

                if (removeButton) {
                    removeButton.classList.add('show');
                }
            }


            /*
             * ------------------------------------------------------------
             * Reset placeholder
             * ------------------------------------------------------------
             */
            function resetPreview(type) {

                const config = uploadConfig[type];

                if (!config) return;

                const preview =
                    document.getElementById(config.previewId);

                const wrapper =
                    document.getElementById(config.wrapperId);

                const removeButton =
                    document.getElementById(config.removeBtnId);

                if (preview) {
                    preview.src = '#';
                    preview.style.display = 'none';
                    preview.classList.remove('show');
                }

                if (wrapper) {

                    const icon =
                        wrapper.querySelector('.placeholder-icon');

                    const text =
                        wrapper.querySelector('.placeholder-text');

                    if (icon) icon.style.display = 'block';
                    if (text) text.style.display = 'block';

                    /*
                     * License placeholder can have no .placeholder-icon.
                     * Leave its existing markup untouched if absent.
                     */
                }

                if (removeButton) {
                    removeButton.classList.remove('show');
                }
            }


            /*
             * ------------------------------------------------------------
             * Upload
             * ------------------------------------------------------------
             */
            async function uploadImage(input, type) {

                const config = uploadConfig[type];

                if (!config || !input.files || !input.files[0]) {
                    return;
                }

                const file = input.files[0];

                const isLicense = type === 'license';

                const allowedTypes = isLicense
                    ? [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                        'application/pdf'
                    ]
                    : [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp'
                    ];

                if (file.size > config.maxSize) {

                    showToast(
                        'error',
                        `${config.label} exceeds the ${config.maxSize / 1024 / 1024}MB limit.`,
                        'Upload Failed'
                    );

                    input.value = '';
                    return;
                }

                if (!allowedTypes.includes(file.type)) {

                    showToast(
                        'error',
                        isLicense
                            ? 'Please upload a PDF, JPG, PNG, GIF or WebP file.'
                            : 'Please upload a JPG, PNG, GIF or WebP image.',
                        'Invalid File'
                    );

                    input.value = '';
                    return;
                }

                const wrapper =
                    document.getElementById(config.wrapperId);

                const preview =
                    document.getElementById(config.previewId);

                /*
                 * Local preview before server request.
                 */
                if (preview && !isLicense) {

                    const reader = new FileReader();

                    reader.onload = event => {

                        preview.src = event.target.result;
                        preview.style.display = 'block';
                        preview.classList.add('show');

                        const icon =
                            wrapper?.querySelector('.placeholder-icon');

                        const text =
                            wrapper?.querySelector('.placeholder-text');

                        if (icon) icon.style.display = 'none';
                        if (text) text.style.display = 'none';
                    };

                    reader.readAsDataURL(file);
                }

                const formData = new FormData();

                /*
                 * IMPORTANT:
                 * Existing controller expects "logo" for all three types.
                 */
                formData.append('logo', file);
                formData.append('type', type);

                setLoading(wrapper, true);

                showToast(
                    'info',
                    `Uploading ${config.label.toLowerCase()}...`,
                    'Please wait'
                );

                try {

                    const response = await fetch(
                        '{{ route("employer.company-profile.upload") }}',
                        {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        }
                    );

                    const contentType =
                        response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        throw new Error(
                            'Server returned an invalid response. Please check Laravel logs.'
                        );
                    }

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message ||
                            `${config.label} upload failed.`
                        );
                    }

                    /*
                     * If controller returns URL/path, immediately use it.
                     */
                    const returnedUrl = getReturnedUrl(data);

                    if (returnedUrl && !isLicense) {
                        setImagePreview(type, returnedUrl);
                    }

                    if (returnedUrl && isLicense) {

                        /*
                         * For a PDF, replace preview with a simple file card.
                         */
                        if (file.type === 'application/pdf' && wrapper) {

                            const previewBox =
                                document.getElementById('licensePreview');

                            if (previewBox) {

                                previewBox.innerHTML = `
                                    <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                    <p class="text-muted small mt-2 mb-0">
                                        Business License PDF uploaded
                                    </p>
                                `;
                            }

                            const removeButton =
                                document.getElementById('removeLicenseBtn');

                            if (removeButton) {
                                removeButton.classList.add('show');
                            }
                        } else {
                            setImagePreview(type, returnedUrl);
                        }
                    }

                    /*
                     * If controller doesn't return a URL, keep the local
                     * preview; after refresh the DB value will be loaded.
                     */
                    showToast(
                        'success',
                        data.message || `${config.label} updated successfully!`,
                        'Profile Updated'
                    );

                    input.value = '';

                } catch (error) {

                    console.error(`${type} upload error:`, error);

                    showToast(
                        'error',
                        error.message || `${config.label} upload failed.`,
                        'Upload Failed'
                    );

                } finally {

                    setLoading(wrapper, false);
                }
            }

            window.uploadImage = uploadImage;


            /*
             * ------------------------------------------------------------
             * Remove
             * ------------------------------------------------------------
             */
            async function removeImage(type) {

                const config = uploadConfig[type];

                if (!config) return;

                const confirmed = await showConfirm(
                    `Remove ${config.label}?`,
                    `Your current ${config.label.toLowerCase()} will be removed.`,
                    'Remove'
                );

                if (!confirmed) return;

                const wrapper =
                    document.getElementById(config.wrapperId);

                setLoading(wrapper, true);

                try {

                    const response = await fetch(
                        '{{ route("employer.company-profile.remove-image") }}',
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                type: type
                            })
                        }
                    );

                    const contentType =
                        response.headers.get('content-type') || '';

                    if (!contentType.includes('application/json')) {
                        throw new Error(
                            'Server returned an invalid response.'
                        );
                    }

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message ||
                            `Unable to remove ${config.label.toLowerCase()}.`
                        );
                    }

                    resetPreview(type);

                    /*
                     * Restore original license placeholder structure.
                     */
                    if (type === 'license') {

                        const previewBox =
                            document.getElementById('licensePreview');

                        if (previewBox) {
                            previewBox.innerHTML = `
                                <i class="fas fa-file-upload fa-3x text-muted placeholder-icon"></i>
                                <p class="text-muted small mt-2 placeholder-text">
                                    Click to upload business license
                                </p>
                                <img
                                    id="licensePreviewImg"
                                    src="#"
                                    alt="Preview"
                                    class="preview-image"
                                >
                            `;
                        }
                    }

                    showToast(
                        'success',
                        data.message || `${config.label} removed successfully!`,
                        'Profile Updated'
                    );

                } catch (error) {

                    console.error(`${type} removal error:`, error);

                    showToast(
                        'error',
                        error.message ||
                        `Unable to remove ${config.label.toLowerCase()}.`,
                        'Remove Failed'
                    );

                } finally {

                    setLoading(wrapper, false);
                }
            }

            window.removeImage = removeImage;


            /*
             * ------------------------------------------------------------
             * Request Verification
             * ------------------------------------------------------------
             */
            async function requestVerification() {

                const confirmed = await showConfirm(
                    'Request verification?',
                    'Your company profile will be submitted for verification.',
                    'Request'
                );

                if (!confirmed) return;

                try {

                    const response = await fetch(
                        '{{ route("employer.company-profile.verify") }}',
                        {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message || 'Verification request failed.'
                        );
                    }

                    showToast(
                        'success',
                        data.message || 'Verification request submitted.',
                        'Verification'
                    );

                } catch (error) {

                    console.error('Verification error:', error);

                    showToast(
                        'error',
                        error.message || 'Verification request failed.',
                        'Verification Failed'
                    );
                }
            }

            window.requestVerification = requestVerification;


            /*
             * ------------------------------------------------------------
             * Existing previews
             * ------------------------------------------------------------
             */
            Object.keys(uploadConfig).forEach(type => {

                const config = uploadConfig[type];

                const preview =
                    document.getElementById(config.previewId);

                const removeButton =
                    document.getElementById(config.removeBtnId);

                if (
                    preview &&
                    preview.getAttribute('src') &&
                    preview.getAttribute('src') !== '#'
                ) {

                    if (
                        type !== 'license' ||
                        !preview.src.toLowerCase().endsWith('.pdf')
                    ) {
                        preview.classList.add('show');
                        preview.style.display = 'block';

                        const wrapper =
                            document.getElementById(config.wrapperId);

                        if (wrapper) {

                            const icon =
                                wrapper.querySelector('.placeholder-icon');

                            const text =
                                wrapper.querySelector('.placeholder-text');

                            if (icon) icon.style.display = 'none';
                            if (text) text.style.display = 'none';
                        }
                    }

                    if (removeButton) {
                        removeButton.classList.add('show');
                    }
                }
            });


            /*
             * ------------------------------------------------------------
             * Prevent nested remove buttons from opening file picker.
             * Keep whole upload box clickable.
             * ------------------------------------------------------------
             */
            Object.keys(uploadConfig).forEach(type => {

                const config = uploadConfig[type];

                const wrapper =
                    document.getElementById(config.wrapperId);

                const input =
                    document.getElementById(config.inputId);

                const removeButton =
                    document.getElementById(config.removeBtnId);

                if (!wrapper || !input) return;

                /*
                 * Remove old inline onclick behaviour by stopping the
                 * click before it bubbles to the wrapper.
                 */
                if (removeButton) {

                    removeButton.addEventListener('click', event => {
                        event.preventDefault();
                        event.stopPropagation();
                    });
                }
            });


            /*
             * ------------------------------------------------------------
             * Session toast
             * ------------------------------------------------------------
             */
            @if(session('toast'))
                const sessionToast = @json(session('toast'));

                showToast(
                    sessionToast.type || 'info',
                    sessionToast.message || 'Notification'
                );
            @endif


            /*
             * ------------------------------------------------------------
             * Company form loading state
             * ------------------------------------------------------------
             */
            const companyForm =
                document.getElementById('companyForm');

            if (companyForm) {

                companyForm.addEventListener('submit', function () {

                    const button =
                        companyForm.querySelector(
                            'button[type="submit"]'
                        );

                    if (button) {

                        button.disabled = true;

                        button.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2"></span>' +
                            'Saving...';
                    }
                });
            }

        });
        </script>
    @endpush

@endsection
