{{-- resources/views/employer/company-profile/edit.blade.php --}}

@extends('employer.layouts.employer')

@section('title', 'Company Profile')
@section('page-title', 'Company Profile')
@section('page-subtitle', 'Update your company information')


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
                    </div>

                    @if(isset($company))

    <div class="company-verification-status-wrapper mt-3">

        @if($company->verification_status === 'verified')

            <div class="company-verification-notice success">

                <div class="notice-icon">
                    <i class="fas fa-check-circle"></i>
                </div>

                <div class="notice-content">

                    <div class="notice-title">
                        Company Verified
                    </div>

                    <div class="notice-message">
                        Your company has been successfully verified.
                    </div>

                </div>

            </div>


        @elseif($company->verification_status === 'pending')

            <div class="company-verification-notice warning">

                <div class="notice-icon">
                    <i class="fas fa-clock"></i>
                </div>

                <div class="notice-content">

                    <div class="notice-title">
                        Verification Pending
                    </div>

                    <div class="notice-message">
                        Your verification request has been submitted and is currently waiting for admin review.
                    </div>

                    @if($company->verification_requested_at)
                        <div class="notice-meta">
                            Requested
                            {{ $company->verification_requested_at->diffForHumans() }}
                        </div>
                    @endif

                </div>

            </div>


        @elseif($company->verification_status === 'rejected')

            <div class="company-verification-notice danger">

                <div class="notice-icon">
                    <i class="fas fa-times-circle"></i>
                </div>

                <div class="notice-content">

                    <div class="notice-title">
                        Verification Rejected
                    </div>

                    <div class="notice-message">
                        Your company verification request was rejected by the admin.
                    </div>

                    @if($company->verification_rejection_reason)
                        <div class="notice-reason">
                            <strong>Reason:</strong>
                            {{ $company->verification_rejection_reason }}
                        </div>
                    @endif

                    <div class="notice-help">
                        Please correct the issue mentioned above and submit your company for verification again.
                    </div>

                    @if($company->is_complete)
                        <div class="mt-3">
                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                onclick="requestVerification()"
                            >
                                <i class="fas fa-redo me-2"></i>
                                Request Verification Again
                            </button>
                        </div>
                    @endif

                </div>

            </div>


        @elseif($company->is_suspended)

            <div class="company-verification-notice danger">

                <div class="notice-icon">
                    <i class="fas fa-ban"></i>
                </div>

                <div class="notice-content">

                    <div class="notice-title">
                        Company Account Suspended
                    </div>

                    <div class="notice-message">
                        Your company account is currently suspended and verification cannot be requested.
                    </div>

                    @if($company->suspension_reason)
                        <div class="notice-reason">
                            <strong>Reason:</strong>
                            {{ $company->suspension_reason }}
                        </div>
                    @endif

                </div>

            </div>


        @elseif($company->is_fraud)

            <div class="company-verification-notice danger">

                <div class="notice-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>

                <div class="notice-content">

                    <div class="notice-title">
                        Verification Restricted
                    </div>

                    <div class="notice-message">
                        This company has been flagged for fraud and cannot submit a verification request.
                    </div>

                </div>

            </div>


        @elseif($company->is_complete)

            <div class="company-verification-action mt-3">

                <button
                    type="button"
                    class="btn btn-warning"
                    onclick="requestVerification()"
                >
                    <i class="fas fa-shield-alt me-2"></i>
                    Request Verification
                </button>

            </div>


        @else

            <div class="company-verification-notice info">

                <div class="notice-icon">
                    <i class="fas fa-info-circle"></i>
                </div>

                <div class="notice-content">

                    <div class="notice-title">
                        Complete Your Company Profile
                    </div>

                    <div class="notice-message">
                        Please complete at least 80% of your company profile before requesting verification.
                    </div>

                </div>

            </div>

        @endif

    </div>

@endif
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

    if (!confirmed) {
        return;
    }

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


        /*
         * Always safely parse JSON.
         */
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
                'Verification request failed.'
            );
        }


        /*
         * Success toast
         */
        showToast(
            'success',
            data.message ||
            'Verification request submitted successfully.',
            'Verification Submitted'
        );


        /*
         * Update UI immediately instead of making the user
         * click again.
         */
        const verificationWrapper =
            document.querySelector(
                '.company-verification-status-wrapper'
            );

        if (verificationWrapper) {

            verificationWrapper.innerHTML = `
                <div class="company-verification-notice warning">

                    <div class="notice-icon">
                        <i class="fas fa-clock"></i>
                    </div>

                    <div class="notice-content">

                        <div class="notice-title">
                            Verification Pending
                        </div>

                        <div class="notice-message">
                            Your verification request has been submitted and is currently waiting for admin review.
                        </div>

                    </div>

                </div>
            `;
        }


        /*
         * Prevent duplicate clicks.
         */
        document
            .querySelectorAll(
                '.company-verification-action button'
            )
            .forEach(button => {
                button.disabled = true;
            });


    } catch (error) {

        console.error(
            'Verification error:',
            error
        );

        showToast(
            'error',
            error.message ||
            'Verification request failed.',
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
