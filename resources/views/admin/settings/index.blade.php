{{-- resources/views/admin/settings/index.blade.php --}}

@extends('admin.layouts.admin')

@section('title', 'Site Settings - Rozgar Finder')
@section('page-title', 'Site Settings')
@section('page-subtitle', 'Manage your site configuration')

@push('styles')
<style>
    /* ✅ Form Styles */
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
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    .form-section-title {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #f1f5f9;
    }
    .form-section-title i {
        color: #6366f1;
        margin-right: 8px;
    }
    .btn-primary {
        background: #6366f1;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background: #4f46e5;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }
    .btn-secondary {
        border: 1px solid #e2e8f0;
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-secondary:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
    }

    /* ✅ Tabs - Professional */
    .settings-tabs {
        border-bottom: 2px solid #e5e7eb;
        margin-bottom: 0;
        padding-bottom: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }
    .settings-tabs .nav-link {
        border: none;
        padding: 10px 20px;
        font-weight: 500;
        color: #64748b;
        transition: all 0.3s ease;
        border-radius: 8px 8px 0 0;
        font-size: 13px;
        position: relative;
        background: transparent;
        cursor: pointer;
    }
    .settings-tabs .nav-link:hover {
        color: #6366f1;
        background: #f1f5f9;
    }
    .settings-tabs .nav-link.active {
        color: #6366f1;
        background: #eff6ff;
        font-weight: 600;
    }
    .settings-tabs .nav-link.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: #6366f1;
    }
    .settings-tabs .nav-link i {
        margin-right: 8px;
        width: 16px;
        text-align: center;
    }

    /* ✅ Tab Content */
    .tab-pane {
        padding-top: 20px;
        animation: fadeIn 0.3s ease;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ✅ Image Upload - Professional */
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
        border-color: #6366f1;
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
        display: block;
    }
    .image-upload-wrapper .image-preview .placeholder-icon.hidden {
        display: none;
    }
    .image-upload-wrapper .image-preview .placeholder-text {
        color: #94a3b8;
        font-size: 13px;
        margin-top: 6px;
        display: block;
    }
    .image-upload-wrapper .image-preview .placeholder-text.hidden {
        display: none;
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

    /* ✅ Responsive */
    @media (max-width: 768px) {
        .settings-tabs .nav-link {
            padding: 8px 14px;
            font-size: 12px;
        }
        .settings-tabs .nav-link i {
            margin-right: 4px;
        }
        .tab-pane {
            padding-top: 16px;
        }
        .image-upload-wrapper {
            min-height: 120px;
            padding: 16px;
        }
    }
    @media (max-width: 480px) {
        .settings-tabs .nav-link {
            padding: 6px 10px;
            font-size: 11px;
        }
        .settings-tabs .nav-link i {
            font-size: 12px;
        }
        .image-upload-wrapper {
            min-height: 100px;
            padding: 12px;
        }
        .image-upload-wrapper .image-preview .preview-image {
            max-height: 80px;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-cog me-2 text-primary"></i> Site Settings
                    </h5>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="resetSettings()">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                        @csrf
                        @method('PUT')

                        {{-- Tabs --}}
                        <ul class="nav nav-tabs settings-tabs" id="settingsTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab">
                                    <i class="fas fa-globe"></i> General
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-social" type="button" role="tab">
                                    <i class="fas fa-share-alt"></i> Social
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-ads" type="button" role="tab">
                                    <i class="fas fa-ad"></i> Ads
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-captcha" type="button" role="tab">
                                    <i class="fas fa-shield-alt"></i> Captcha
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-social-login" type="button" role="tab">
                                    <i class="fas fa-users"></i> Social Login
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-payments" type="button" role="tab">
                                    <i class="fas fa-credit-card"></i> Payments
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-analytics" type="button" role="tab">
                                    <i class="fas fa-chart-line"></i> Analytics
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-cookie" type="button" role="tab">
                                    <i class="fas fa-cookie-bite"></i> Cookie
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content">
                            {{-- TAB 1: GENERAL (with Logo & Favicon INSIDE) --}}
                            <div class="tab-pane fade show active" id="tab-general" role="tabpanel">
                                {{-- ✅ Logo & Favicon Section - INSIDE GENERAL TAB --}}
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <div class="form-section-title"><i class="fas fa-image"></i> Logo & Favicon</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Site Logo</label>
                                        <div class="image-upload-wrapper" id="logoWrapper" onclick="document.getElementById('logoInput').click()">
                                            <div class="image-preview" id="logoPreview">
                                                <i class="fas fa-image fa-3x text-muted placeholder-icon" id="logoPlaceholderIcon"></i>
                                                <p class="text-muted small mt-2 placeholder-text" id="logoPlaceholderText">Click to upload logo</p>
                                                <img id="logoPreviewImg" src="#" alt="Preview" class="preview-image">
                                            </div>
                                            <input type="file" id="logoInput" accept="image/*" onchange="uploadImage(this, 'logo')">
                                            <button type="button" class="remove-image-btn" id="removeLogoBtn" onclick="removeImage('logo')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <div class="help-text">Recommended: 200x60px. Max: 2MB</div>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-semibold">Favicon</label>
                                        <div class="image-upload-wrapper" id="faviconWrapper" onclick="document.getElementById('faviconInput').click()">
                                            <div class="image-preview" id="faviconPreview">
                                                <i class="fas fa-image fa-3x text-muted placeholder-icon" id="faviconPlaceholderIcon"></i>
                                                <p class="text-muted small mt-2 placeholder-text" id="faviconPlaceholderText">Click to upload favicon</p>
                                                <img id="faviconPreviewImg" src="#" alt="Preview" class="preview-image">
                                            </div>
                                            <input type="file" id="faviconInput" accept="image/*" onchange="uploadImage(this, 'favicon')">
                                            <button type="button" class="remove-image-btn" id="removeFaviconBtn" onclick="removeImage('favicon')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                        <div class="help-text">Recommended: 32x32px. Max: 1MB</div>
                                    </div>
                                </div>

                                {{-- General Settings Fields --}}
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Site Name</label>
                                        <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings->site_name ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tagline</label>
                                        <input type="text" name="site_tagline" class="form-control" value="{{ old('site_tagline', $settings->site_tagline ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Site Email</label>
                                        <input type="email" name="site_email" class="form-control" value="{{ old('site_email', $settings->site_email ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="site_phone" class="form-control" value="{{ old('site_phone', $settings->site_phone ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">WhatsApp</label>
                                        <input type="text" name="site_whatsapp" class="form-control" value="{{ old('site_whatsapp', $settings->site_whatsapp ?? '') }}">
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea name="site_address" class="form-control" rows="2">{{ old('site_address', $settings->site_address ?? '') }}</textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Time Zone</label>
                                        <select name="timezone" class="form-select">
                                            <option value="Asia/Karachi" {{ ($settings->timezone ?? '') == 'Asia/Karachi' ? 'selected' : '' }}>Asia/Karachi (PKT)</option>
                                            <option value="Asia/Dubai" {{ ($settings->timezone ?? '') == 'Asia/Dubai' ? 'selected' : '' }}>Asia/Dubai (GST)</option>
                                            <option value="America/New_York" {{ ($settings->timezone ?? '') == 'America/New_York' ? 'selected' : '' }}>America/New York (EST)</option>
                                            <option value="Europe/London" {{ ($settings->timezone ?? '') == 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Currency</label>
                                        <select name="currency" class="form-select">
                                            <option value="PKR" {{ ($settings->currency ?? '') == 'PKR' ? 'selected' : '' }}>PKR - Rupee</option>
                                            <option value="USD" {{ ($settings->currency ?? '') == 'USD' ? 'selected' : '' }}>USD - Dollar</option>
                                            <option value="EUR" {{ ($settings->currency ?? '') == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                            <option value="GBP" {{ ($settings->currency ?? '') == 'GBP' ? 'selected' : '' }}>GBP - Pound</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Currency Symbol</label>
                                        <input type="text" name="currency_symbol" class="form-control" value="{{ old('currency_symbol', $settings->currency_symbol ?? 'Rs.') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Date Format</label>
                                        <select name="date_format" class="form-select">
                                            <option value="d-m-Y" {{ ($settings->date_format ?? '') == 'd-m-Y' ? 'selected' : '' }}>DD-MM-YYYY</option>
                                            <option value="m-d-Y" {{ ($settings->date_format ?? '') == 'm-d-Y' ? 'selected' : '' }}>MM-DD-YYYY</option>
                                            <option value="Y-m-d" {{ ($settings->date_format ?? '') == 'Y-m-d' ? 'selected' : '' }}>YYYY-MM-DD</option>
                                            <option value="d M, Y" {{ ($settings->date_format ?? '') == 'd M, Y' ? 'selected' : '' }}>DD M, YYYY</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 2: SOCIAL NETWORKS --}}
                            <div class="tab-pane fade" id="tab-social" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-facebook text-primary"></i> Facebook</label>
                                        <input type="url" name="facebook" class="form-control" value="{{ old('facebook', $settings->facebook ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-twitter text-info"></i> Twitter</label>
                                        <input type="url" name="twitter" class="form-control" value="{{ old('twitter', $settings->twitter ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-instagram text-danger"></i> Instagram</label>
                                        <input type="url" name="instagram" class="form-control" value="{{ old('instagram', $settings->instagram ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-linkedin text-primary"></i> LinkedIn</label>
                                        <input type="url" name="linkedin" class="form-control" value="{{ old('linkedin', $settings->linkedin ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-youtube text-danger"></i> YouTube</label>
                                        <input type="url" name="youtube" class="form-control" value="{{ old('youtube', $settings->youtube ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-tiktok text-dark"></i> TikTok</label>
                                        <input type="url" name="tiktok" class="form-control" value="{{ old('tiktok', $settings->tiktok ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-pinterest text-danger"></i> Pinterest</label>
                                        <input type="url" name="pinterest" class="form-control" value="{{ old('pinterest', $settings->pinterest ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label"><i class="fab fa-telegram text-primary"></i> Telegram</label>
                                        <input type="url" name="telegram" class="form-control" value="{{ old('telegram', $settings->telegram ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 3: MANAGE ADS --}}
                            <div class="tab-pane fade" id="tab-ads" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="ads_enabled" class="form-check-input" id="adsEnabled" value="1" {{ old('ads_enabled', $settings->ads_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="adsEnabled">Enable Ads</label>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Header Ad Code</label>
                                        <textarea name="header_ad_code" class="form-control" rows="3">{{ old('header_ad_code', $settings->header_ad_code ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Sidebar Ad Code</label>
                                        <textarea name="sidebar_ad_code" class="form-control" rows="3">{{ old('sidebar_ad_code', $settings->sidebar_ad_code ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Footer Ad Code</label>
                                        <textarea name="footer_ad_code" class="form-control" rows="3">{{ old('footer_ad_code', $settings->footer_ad_code ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">In-Content Ad Code</label>
                                        <textarea name="in_content_ad_code" class="form-control" rows="3">{{ old('in_content_ad_code', $settings->in_content_ad_code ?? '') }}</textarea>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Popup Ad Code</label>
                                        <textarea name="popup_ad_code" class="form-control" rows="3">{{ old('popup_ad_code', $settings->popup_ad_code ?? '') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 4: CAPTCHA --}}
                            <div class="tab-pane fade" id="tab-captcha" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="captcha_enabled" class="form-check-input" id="captchaEnabled" value="1" {{ old('captcha_enabled', $settings->captcha_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="captchaEnabled">Enable Captcha</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Site Key</label>
                                        <input type="text" name="captcha_site_key" class="form-control" value="{{ old('captcha_site_key', $settings->captcha_site_key ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Secret Key</label>
                                        <input type="text" name="captcha_secret_key" class="form-control" value="{{ old('captcha_secret_key', $settings->captcha_secret_key ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="captcha_on_login" class="form-check-input" id="captchaLogin" value="1" {{ old('captcha_on_login', $settings->captcha_on_login ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="captchaLogin">On Login</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="captcha_on_register" class="form-check-input" id="captchaRegister" value="1" {{ old('captcha_on_register', $settings->captcha_on_register ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="captchaRegister">On Register</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="captcha_on_contact" class="form-check-input" id="captchaContact" value="1" {{ old('captcha_on_contact', $settings->captcha_on_contact ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="captchaContact">On Contact</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 5: SOCIAL LOGIN --}}
                            <div class="tab-pane fade" id="tab-social-login" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="social_login_enabled" class="form-check-input" id="socialLoginEnabled" value="1" {{ old('social_login_enabled', $settings->social_login_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="socialLoginEnabled">Enable Social Login</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fab fa-facebook text-primary"></i> Facebook Login</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="facebook_login" class="form-check-input" id="fbLogin" value="1" {{ old('facebook_login', $settings->facebook_login ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="fbLogin">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Client ID</label>
                                        <input type="text" name="facebook_client_id" class="form-control" value="{{ old('facebook_client_id', $settings->facebook_client_id ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Client Secret</label>
                                        <input type="text" name="facebook_client_secret" class="form-control" value="{{ old('facebook_client_secret', $settings->facebook_client_secret ?? '') }}">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fab fa-google text-danger"></i> Google Login</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="google_login" class="form-check-input" id="googleLogin" value="1" {{ old('google_login', $settings->google_login ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="googleLogin">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Client ID</label>
                                        <input type="text" name="google_client_id" class="form-control" value="{{ old('google_client_id', $settings->google_client_id ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Client Secret</label>
                                        <input type="text" name="google_client_secret" class="form-control" value="{{ old('google_client_secret', $settings->google_client_secret ?? '') }}">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fab fa-linkedin text-primary"></i> LinkedIn Login</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="linkedin_login" class="form-check-input" id="linkedinLogin" value="1" {{ old('linkedin_login', $settings->linkedin_login ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="linkedinLogin">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Client ID</label>
                                        <input type="text" name="linkedin_client_id" class="form-control" value="{{ old('linkedin_client_id', $settings->linkedin_client_id ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Client Secret</label>
                                        <input type="text" name="linkedin_client_secret" class="form-control" value="{{ old('linkedin_client_secret', $settings->linkedin_client_secret ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 6: PAYMENT GATEWAYS --}}
                            <div class="tab-pane fade" id="tab-payments" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="payment_enabled" class="form-check-input" id="paymentEnabled" value="1" {{ old('payment_enabled', $settings->payment_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="paymentEnabled">Enable Payments</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fas fa-mobile-alt text-success"></i> EasyPaisa</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="easypaisa_enabled" class="form-check-input" id="easypaisaEnabled" value="1" {{ old('easypaisa_enabled', $settings->easypaisa_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="easypaisaEnabled">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Merchant ID</label>
                                        <input type="text" name="easypaisa_merchant_id" class="form-control" value="{{ old('easypaisa_merchant_id', $settings->easypaisa_merchant_id ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">API Key</label>
                                        <input type="text" name="easypaisa_api_key" class="form-control" value="{{ old('easypaisa_api_key', $settings->easypaisa_api_key ?? '') }}">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fas fa-mobile-alt text-primary"></i> JazzCash</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="jazzcash_enabled" class="form-check-input" id="jazzcashEnabled" value="1" {{ old('jazzcash_enabled', $settings->jazzcash_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="jazzcashEnabled">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Merchant ID</label>
                                        <input type="text" name="jazzcash_merchant_id" class="form-control" value="{{ old('jazzcash_merchant_id', $settings->jazzcash_merchant_id ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">API Key</label>
                                        <input type="text" name="jazzcash_api_key" class="form-control" value="{{ old('jazzcash_api_key', $settings->jazzcash_api_key ?? '') }}">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fab fa-paypal text-primary"></i> PayPal</h6>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="paypal_enabled" class="form-check-input" id="paypalEnabled" value="1" {{ old('paypal_enabled', $settings->paypal_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="paypalEnabled">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="paypal_sandbox" class="form-check-input" id="paypalSandbox" value="1" {{ old('paypal_sandbox', $settings->paypal_sandbox ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="paypalSandbox">Sandbox Mode</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Client ID</label>
                                        <input type="text" name="paypal_client_id" class="form-control" value="{{ old('paypal_client_id', $settings->paypal_client_id ?? '') }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Client Secret</label>
                                        <input type="text" name="paypal_client_secret" class="form-control" value="{{ old('paypal_client_secret', $settings->paypal_client_secret ?? '') }}">
                                    </div>
                                    <div class="col-md-12 mb-2">
                                        <h6><i class="fab fa-stripe text-primary"></i> Stripe</h6>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="stripe_enabled" class="form-check-input" id="stripeEnabled" value="1" {{ old('stripe_enabled', $settings->stripe_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="stripeEnabled">Enable</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Publishable Key</label>
                                        <input type="text" name="stripe_publishable_key" class="form-control" value="{{ old('stripe_publishable_key', $settings->stripe_publishable_key ?? '') }}">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Secret Key</label>
                                        <input type="text" name="stripe_secret_key" class="form-control" value="{{ old('stripe_secret_key', $settings->stripe_secret_key ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 7: ANALYTICS --}}
                            <div class="tab-pane fade" id="tab-analytics" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="analytics_enabled" class="form-check-input" id="analyticsEnabled" value="1" {{ old('analytics_enabled', $settings->analytics_enabled ?? false) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="analyticsEnabled">Enable Analytics</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Measurement ID</label>
                                        <input type="text" name="analytics_measurement_id" class="form-control" value="{{ old('analytics_measurement_id', $settings->analytics_measurement_id ?? '') }}" placeholder="G-XXXXXXXXXX">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Property ID</label>
                                        <input type="text" name="analytics_property_id" class="form-control" value="{{ old('analytics_property_id', $settings->analytics_property_id ?? '') }}" placeholder="UA-XXXXXXXXX-X">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">API Key</label>
                                        <input type="text" name="analytics_api_key" class="form-control" value="{{ old('analytics_api_key', $settings->analytics_api_key ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-check form-switch mt-4">
                                            <input type="checkbox" name="analytics_anonymize_ip" class="form-check-input" id="anonymizeIp" value="1" {{ old('analytics_anonymize_ip', $settings->analytics_anonymize_ip ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="anonymizeIp">Anonymize IP</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB 8: GDPR COOKIE POLICY --}}
                            <div class="tab-pane fade" id="tab-cookie" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <div class="form-check form-switch">
                                            <input type="checkbox" name="cookie_consent_enabled" class="form-check-input" id="cookieEnabled" value="1" {{ old('cookie_consent_enabled', $settings->cookie_consent_enabled ?? true) ? 'checked' : '' }}>
                                            <label class="form-check-label fw-bold" for="cookieEnabled">Enable Cookie Consent</label>
                                        </div>
                                    </div>
                                    <div class="col-12 mb-3">
                                        <label class="form-label">Cookie Message</label>
                                        <textarea name="cookie_consent_message" class="form-control" rows="3">{{ old('cookie_consent_message', $settings->cookie_consent_message ?? '') }}</textarea>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cookie Policy URL</label>
                                        <input type="url" name="cookie_policy_url" class="form-control" value="{{ old('cookie_policy_url', $settings->cookie_policy_url ?? '') }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Cookie Expiry (Days)</label>
                                        <input type="number" name="cookie_expiry_days" class="form-control" value="{{ old('cookie_expiry_days', $settings->cookie_expiry_days ?? 365) }}" min="1" max="730">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Save All Settings
                                </button>
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
// ✅ ============================================================
// ✅ 1. INITIALIZE IMAGE PREVIEW ON LOAD
// ✅ ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // ✅ Logo
    const logoPreview = document.getElementById('logoPreviewImg');
    const logoRemoveBtn = document.getElementById('removeLogoBtn');
    const logoPlaceholderIcon = document.getElementById('logoPlaceholderIcon');
    const logoPlaceholderText = document.getElementById('logoPlaceholderText');

    @if($settings && $settings->site_logo)
        logoPreview.src = "{{ asset('storage/' . $settings->site_logo) }}";
        logoPreview.classList.add('show');
        logoRemoveBtn.classList.add('show');
        logoPlaceholderIcon.style.display = 'none';
        logoPlaceholderText.style.display = 'none';
    @else
        logoPreview.src = '#';
        logoPreview.classList.remove('show');
        logoRemoveBtn.classList.remove('show');
        logoPlaceholderIcon.style.display = 'block';
        logoPlaceholderText.style.display = 'block';
    @endif

    // ✅ Favicon
    const faviconPreview = document.getElementById('faviconPreviewImg');
    const faviconRemoveBtn = document.getElementById('removeFaviconBtn');
    const faviconPlaceholderIcon = document.getElementById('faviconPlaceholderIcon');
    const faviconPlaceholderText = document.getElementById('faviconPlaceholderText');

    @if($settings && $settings->site_favicon)
        faviconPreview.src = "{{ asset('storage/' . $settings->site_favicon) }}";
        faviconPreview.classList.add('show');
        faviconRemoveBtn.classList.add('show');
        faviconPlaceholderIcon.style.display = 'none';
        faviconPlaceholderText.style.display = 'none';
    @else
        faviconPreview.src = '#';
        faviconPreview.classList.remove('show');
        faviconRemoveBtn.classList.remove('show');
        faviconPlaceholderIcon.style.display = 'block';
        faviconPlaceholderText.style.display = 'block';
    @endif

    // ✅ Toastr notifications
    @if(session('toast'))
        const toast = @json(session('toast'));
        showToast(toast.type, toast.message);
    @endif
});

// ✅ ============================================================
// ✅ 2. IMAGE UPLOAD
// ✅ ============================================================
function uploadImage(input, type) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        showToast('error', 'File size exceeds 2MB limit.');
        input.value = '';
        return;
    }

    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];
    if (!validTypes.includes(file.type)) {
        showToast('error', 'Invalid file type. Please upload JPG, PNG, GIF, WebP or SVG.');
        input.value = '';
        return;
    }

    const formData = new FormData();
    formData.append('logo', file);
    formData.append('type', type);

    const previewId = type === 'logo' ? 'logoPreviewImg' : 'faviconPreviewImg';
    const removeBtnId = type === 'logo' ? 'removeLogoBtn' : 'removeFaviconBtn';
    const placeholderIconId = type === 'logo' ? 'logoPlaceholderIcon' : 'faviconPlaceholderIcon';
    const placeholderTextId = type === 'logo' ? 'logoPlaceholderText' : 'faviconPlaceholderText';

    const preview = document.getElementById(previewId);
    const removeBtn = document.getElementById(removeBtnId);
    const placeholderIcon = document.getElementById(placeholderIconId);
    const placeholderText = document.getElementById(placeholderTextId);

    fetch('{{ route("admin.settings.upload-logo") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (preview) {
                preview.src = data.path;
                preview.classList.add('show');
            }
            if (placeholderIcon) placeholderIcon.style.display = 'none';
            if (placeholderText) placeholderText.style.display = 'none';
            if (removeBtn) removeBtn.classList.add('show');
            showToast('success', data.message);
        } else {
            showToast('error', data.message);
        }
    })
    .catch(error => {
        showToast('error', 'Upload failed: ' + error.message);
    });
}

function removeImage(type) {
    if (!confirm('Are you sure you want to remove this image?')) return;

    const previewId = type === 'logo' ? 'logoPreviewImg' : 'faviconPreviewImg';
    const removeBtnId = type === 'logo' ? 'removeLogoBtn' : 'removeFaviconBtn';
    const placeholderIconId = type === 'logo' ? 'logoPlaceholderIcon' : 'faviconPlaceholderIcon';
    const placeholderTextId = type === 'logo' ? 'logoPlaceholderText' : 'faviconPlaceholderText';

    const preview = document.getElementById(previewId);
    const removeBtn = document.getElementById(removeBtnId);
    const placeholderIcon = document.getElementById(placeholderIconId);
    const placeholderText = document.getElementById(placeholderTextId);

    fetch('{{ route("admin.settings.remove-logo") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (preview) {
                preview.src = '#';
                preview.classList.remove('show');
            }
            if (placeholderIcon) placeholderIcon.style.display = 'block';
            if (placeholderText) placeholderText.style.display = 'block';
            if (removeBtn) removeBtn.classList.remove('show');
            showToast('success', data.message);
        } else {
            showToast('error', data.message);
        }
    });
}

// ✅ ============================================================
// ✅ 3. RESET SETTINGS
// ✅ ============================================================
function resetSettings() {
    if (!confirm('Are you sure you want to reset all settings to default?')) return;

    fetch('{{ route("admin.settings.reset") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showToast('success', data.message);
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast('error', data.message);
        }
    });
}

// ✅ ============================================================
// ✅ 4. TOAST FUNCTION
// ✅ ============================================================
function showToast(type, message) {
    if (typeof toastr !== 'undefined') {
        const titles = { success: '✅ Success!', error: '❌ Error!', warning: '⚠️ Warning!', info: 'ℹ️ Info' };
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
