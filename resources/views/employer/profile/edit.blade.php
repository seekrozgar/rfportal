{{-- resources/views/employer/profile/personal.blade.php --}}

@extends('employer.layouts.employer')

@section('title', 'Personal Profile')
@section('page-title', 'Personal Profile')
@section('page-subtitle', 'Update your personal information')

@section('content')
    <div class="container-fluid px-4">
        <div class="row">
            <div class="col-12">
                {{-- ✅ Personal Information Section --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-user-circle"></i> Personal Information
                    </div>

                    <form action="{{ route('employer.profile.update-info') }}" method="POST" id="infoForm">
                        @csrf
                        @method('PUT')

                        {{-- ✅ Avatar --}}
                        {{-- Avatar --}}
                        <div class="text-center mb-4">

                            @php
                                $avatarUrl = null;

                                if ($user->avatar) {
                                    $avatarValue = ltrim($user->avatar, '/');

                                    $avatarUrl = \Illuminate\Support\Str::startsWith(
                                        $avatarValue,
                                        ['http://', 'https://']
                                    )
                                        ? $avatarValue
                                        : asset('storage/' . $avatarValue);
                                }
                            @endphp

                            <div class="avatar-wrapper {{ $avatarUrl ? 'has-avatar' : 'no-avatar' }}" id="avatarWrapper"
                                title="{{ $avatarUrl ? 'Click to change profile picture' : 'Click to upload profile picture' }}">

                                <img id="avatarPreview"
                                    src="{{ $avatarUrl ? $avatarUrl . '?v=' . ($user->updated_at?->timestamp ?? time()) : '' }}"
                                    alt="{{ $user->name }}" style="{{ $avatarUrl ? '' : 'display:none;' }}">

                                <div id="avatarPlaceholder" class="avatar-placeholder"
                                    style="{{ $avatarUrl ? 'display:none;' : '' }}">
                                    <i class="fas fa-camera avatar-placeholder-icon"></i>
                                    <strong>Upload Photo</strong>
                                    <small>Click here</small>
                                </div>

                                @if($avatarUrl)
                                    <div class="avatar-overlay avatar-overlay-existing" id="avatarExistingOverlay">
                                        <div class="avatar-overlay-change">
                                            <i class="fas fa-camera"></i>
                                            <span>Change Photo</span>
                                        </div>

                                        <button type="button" class="avatar-overlay-remove" id="avatarRemoveBtn">
                                            <i class="fas fa-trash"></i>
                                            <span>Remove</span>
                                        </button>
                                    </div>
                                @else
                                    <div class="avatar-overlay avatar-overlay-empty" id="avatarEmptyOverlay">
                                        <i class="fas fa-camera"></i>
                                        <span>Choose Photo</span>
                                    </div>
                                @endif

                                <input type="file" id="avatarInput" name="avatar"
                                    accept="image/jpeg,image/png,image/jpg,image/gif,image/webp">
                            </div>

                            <div id="avatarStatus" class="mt-3"></div>

                            <small class="text-muted d-block mt-2">
                                JPG, PNG, GIF or WebP • Maximum 2MB
                            </small>
                        </div>

                        {{-- ✅ Personal Details --}}
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Full Name <span class="required-star">*</span></label>
                                <input type="text" name="name"
                                    class="form-control @error('name', 'info') is-invalid @enderror"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                                @error('name', 'info')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email Address</label>
                                <div class="email-readonly">
                                    <span class="email-text">{{ auth()->user()->email }}</span>
                                    <span class="email-badge"><i class="fas fa-lock me-1"></i> Protected</span>
                                </div>
                                <div class="help-text">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Email cannot be changed from here. Contact admin for email change.
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="phone"
                                    class="form-control @error('phone', 'info') is-invalid @enderror"
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}" placeholder="+92-300-1234567">
                                @error('phone', 'info')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Designation</label>
                                <input type="text" name="designation"
                                    class="form-control @error('designation', 'info') is-invalid @enderror"
                                    value="{{ old('designation', auth()->user()->designation ?? '') }}"
                                    placeholder="e.g. HR Manager">
                                @error('designation', 'info')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- ✅ Submit Info --}}
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <button type="submit" class="btn btn-primary" id="infoSubmitBtn">
                                <i class="fas fa-save me-2"></i> Update Information
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ✅ Change Password Section --}}
                <div class="form-section">
                    <div class="form-section-title">
                        <i class="fas fa-lock"></i> Change Password
                    </div>

                    <form action="{{ route('employer.profile.update-password') }}" method="POST" id="passwordForm">
                        @csrf
                        @method('PUT')

                        <p class="text-muted small mb-3">Enter your current password and new password to change.</p>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Current Password <span class="required-star">*</span></label>
                                <input type="password" name="current_password"
                                    class="form-control @error('current_password', 'password') is-invalid @enderror"
                                    placeholder="Enter current password" required>
                                @error('current_password', 'password')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">New Password <span class="required-star">*</span></label>
                                <input type="password" name="new_password"
                                    class="form-control @error('new_password', 'password') is-invalid @enderror"
                                    placeholder="Enter new password" required>
                                @error('new_password', 'password')
                                    <div class="invalid-feedback show">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Confirm Password <span class="required-star">*</span></label>
                                <input type="password" name="new_password_confirmation" class="form-control"
                                    placeholder="Confirm new password" required>
                            </div>
                        </div>

                        {{-- ✅ Submit Password --}}
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <button type="submit" class="btn btn-primary" id="passwordSubmitBtn">
                                <i class="fas fa-key me-2"></i> Change Password
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ✅ Back to Dashboard --}}
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('employer.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                    <a href="{{ route('employer.company-profile.edit') }}" class="btn btn-outline-primary">
                        <i class="fas fa-building me-2"></i> Go to Company Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                const avatarWrapper = document.getElementById('avatarWrapper');
                const avatarInput = document.getElementById('avatarInput');
                const avatarPreview = document.getElementById('avatarPreview');
                const avatarPlaceholder = document.getElementById('avatarPlaceholder');
                const avatarStatus = document.getElementById('avatarStatus');

                /*
                 * =========================================================
                 * CUSTOM TOAST
                 * Does NOT use window.alert() or confirm()
                 * =========================================================
                 */
                function showProfileToast(type, message, title = null) {

                    let container = document.getElementById('profileToastContainer');

                    if (!container) {
                        container = document.createElement('div');
                        container.id = 'profileToastContainer';
                        document.body.appendChild(container);
                    }

                    const titles = {
                        success: title || 'Success',
                        error: title || 'Error',
                        warning: title || 'Warning',
                        info: title || 'Information'
                    };

                    const icons = {
                        success: 'fa-check',
                        error: 'fa-times',
                        warning: 'fa-exclamation',
                        info: 'fa-info'
                    };

                    const toast = document.createElement('div');
                    toast.className = 'profile-toast ' + type;

                    toast.innerHTML = `
                            <div class="profile-toast-icon">
                                <i class="fas ${icons[type] || icons.info}"></i>
                            </div>
                            <div class="profile-toast-body">
                                <div class="profile-toast-title">${escapeHtml(titles[type] || 'Notification')}</div>
                                <div class="profile-toast-message">${escapeHtml(message)}</div>
                            </div>
                            <button type="button" class="profile-toast-close" aria-label="Close">
                                <i class="fas fa-times"></i>
                            </button>
                        `;

                    container.appendChild(toast);

                    requestAnimationFrame(() => {
                        toast.classList.add('show');
                    });

                    const close = () => {
                        toast.classList.remove('show');

                        setTimeout(() => {
                            toast.remove();
                        }, 250);
                    };

                    toast.querySelector('.profile-toast-close')
                        .addEventListener('click', close);

                    setTimeout(close, 4500);
                }

                function escapeHtml(value) {
                    const div = document.createElement('div');
                    div.textContent = value ?? '';
                    return div.innerHTML;
                }

                window.showProfileToast = showProfileToast;


                /*
                 * =========================================================
                 * AVATAR HELPERS
                 * =========================================================
                 */
                function setAvatarPreview(url) {

                    if (!avatarPreview) return;

                    avatarPreview.onload = function () {
                        avatarPreview.style.display = 'block';

                        if (avatarPlaceholder) {
                            avatarPlaceholder.style.display = 'none';
                        }
                    };

                    avatarPreview.onerror = function () {
                        console.error('Avatar image failed to load:', url);

                        avatarPreview.style.display = 'none';

                        if (avatarPlaceholder) {
                            avatarPlaceholder.style.display = 'flex';
                        }

                        showProfileToast(
                            'error',
                            'The image was uploaded, but the browser could not load it. Please check the storage link.',
                            'Image Display Error'
                        );
                    };

                    avatarPreview.src = url + (url.includes('?') ? '&' : '?') + 'v=' + Date.now();
                }

                function setEmptyAvatar() {

                    if (avatarPreview) {
                        avatarPreview.onload = null;
                        avatarPreview.onerror = null;
                        avatarPreview.src = '';
                        avatarPreview.style.display = 'none';
                    }

                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'flex';
                    }

                    avatarWrapper.classList.remove('has-avatar');
                    avatarWrapper.classList.add('no-avatar');

                    avatarWrapper.title = 'Click to upload profile picture';

                    const existingOverlay =
                        document.getElementById('avatarExistingOverlay');

                    if (existingOverlay) {
                        existingOverlay.remove();
                    }

                    if (!document.getElementById('avatarEmptyOverlay')) {

                        const emptyOverlay = document.createElement('div');

                        emptyOverlay.id = 'avatarEmptyOverlay';
                        emptyOverlay.className =
                            'avatar-overlay avatar-overlay-empty';

                        emptyOverlay.innerHTML = `
                                <i class="fas fa-camera"></i>
                                <span>Choose Photo</span>
                            `;

                        avatarWrapper.appendChild(emptyOverlay);
                    }
                }

                function setExistingAvatar(url) {

                    if (avatarPreview) {
                        setAvatarPreview(url);
                    }

                    if (avatarPlaceholder) {
                        avatarPlaceholder.style.display = 'none';
                    }

                    avatarWrapper.classList.remove('no-avatar');
                    avatarWrapper.classList.add('has-avatar');

                    avatarWrapper.title = 'Click to change profile picture';

                    const emptyOverlay =
                        document.getElementById('avatarEmptyOverlay');

                    if (emptyOverlay) {
                        emptyOverlay.remove();
                    }

                    if (!document.getElementById('avatarExistingOverlay')) {

                        const overlay = document.createElement('div');

                        overlay.id = 'avatarExistingOverlay';
                        overlay.className =
                            'avatar-overlay avatar-overlay-existing';

                        overlay.innerHTML = `
                                <div class="avatar-overlay-change">
                                    <i class="fas fa-camera"></i>
                                    <span>Change Photo</span>
                                </div>

                                <button
                                    type="button"
                                    class="avatar-overlay-remove"
                                    id="avatarRemoveBtn">
                                    <i class="fas fa-trash"></i>
                                    <span>Remove</span>
                                </button>
                            `;

                        avatarWrapper.appendChild(overlay);

                        overlay.querySelector('#avatarRemoveBtn')
                            .addEventListener('click', function (event) {
                                event.stopPropagation();
                                removeAvatar();
                            });
                    }
                }


                /*
                 * =========================================================
                 * CLICK AVATAR -> FILE SELECTOR
                 * =========================================================
                 */
                if (avatarWrapper && avatarInput) {

                    avatarWrapper.addEventListener('click', function (event) {

                        if (event.target.closest('#avatarRemoveBtn')) {
                            return;
                        }

                        avatarInput.click();
                    });


                    /*
                     * =====================================================
                     * FILE SELECTED
                     * =====================================================
                     */
                    avatarInput.addEventListener('change', function () {

                        const file = this.files && this.files[0];

                        if (!file) return;

                        /*
                         * Client validation
                         */
                        if (file.size > 2 * 1024 * 1024) {

                            showProfileToast(
                                'error',
                                'File size exceeds the 2MB limit.',
                                'Upload Failed'
                            );

                            this.value = '';
                            return;
                        }

                        const validTypes = [
                            'image/jpeg',
                            'image/png',
                            'image/jpg',
                            'image/gif',
                            'image/webp'
                        ];

                        if (!validTypes.includes(file.type)) {

                            showProfileToast(
                                'error',
                                'Please select a JPG, PNG, GIF or WebP image.',
                                'Invalid Image'
                            );

                            this.value = '';
                            return;
                        }

                        /*
                         * Local preview immediately.
                         */
                        const reader = new FileReader();

                        reader.onload = function (event) {

                            avatarPreview.src = event.target.result;
                            avatarPreview.style.display = 'block';

                            if (avatarPlaceholder) {
                                avatarPlaceholder.style.display = 'none';
                            }
                        };

                        reader.readAsDataURL(file);

                        /*
                         * Upload
                         */
                        const formData = new FormData();
                        formData.append('avatar', file);

                        avatarWrapper.insertAdjacentHTML(
                            'beforeend',
                            '<div class="avatar-loading" id="avatarLoading">' +
                            '<span class="spinner-border text-success"></span>' +
                            '</div>'
                        );

                        showProfileToast(
                            'info',
                            'Uploading your profile picture...',
                            'Please wait'
                        );

                        fetch('{{ route("employer.profile.avatar") }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: formData
                        })
                            .then(async response => {

                                const contentType =
                                    response.headers.get('content-type') || '';

                                if (!contentType.includes('application/json')) {
                                    throw new Error(
                                        'Server did not return a valid JSON response. Check the Laravel log.'
                                    );
                                }

                                const data = await response.json();

                                if (!response.ok || !data.success) {
                                    throw new Error(
                                        data.message || 'Avatar upload failed.'
                                    );
                                }

                                return data;
                            })
                            .then(data => {

                                /*
                                 * IMPORTANT:
                                 * Use the exact URL returned by Laravel.
                                 */
                                if (!data.avatar) {
                                    throw new Error(
                                        'Avatar uploaded but no image URL was returned by the server.'
                                    );
                                }

                                setExistingAvatar(data.avatar);

                                showProfileToast(
                                    'success',
                                    data.message || 'Profile picture updated successfully!',
                                    'Profile Updated'
                                );

                                avatarStatus.innerHTML = '';
                                avatarInput.value = '';

                            })
                            .catch(error => {

                                console.error('Avatar upload error:', error);

                                showProfileToast(
                                    'error',
                                    error.message || 'Unable to upload profile picture.',
                                    'Upload Failed'
                                );

                                /*
                                 * Restore current server image.
                                 */
                                @if($avatarUrl)
                                    setExistingAvatar(@json($avatarUrl));
                                @else
                                    setEmptyAvatar();
                                @endif

                                avatarInput.value = '';

                            })
                            .finally(() => {

                                const loader =
                                    document.getElementById('avatarLoading');

                                if (loader) {
                                    loader.remove();
                                }
                            });
                    });


                    /*
                     * =====================================================
                     * EXISTING REMOVE BUTTON
                     * =====================================================
                     */
                    const initialRemoveBtn =
                        document.getElementById('avatarRemoveBtn');

                    if (initialRemoveBtn) {

                        initialRemoveBtn.addEventListener(
                            'click',
                            function (event) {
                                event.stopPropagation();
                                removeAvatar();
                            }
                        );
                    }
                }


                /*
                 * =========================================================
                 * REMOVE AVATAR - CUSTOM CONFIRM MODAL
                 * =========================================================
                 */
                function removeAvatar() {

                    /*
                     * No window.confirm().
                     */
                    const existingModal =
                        document.getElementById('avatarConfirmModal');

                    if (existingModal) {
                        existingModal.remove();
                    }

                    const modal = document.createElement('div');

                    modal.id = 'avatarConfirmModal';

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
                                        <i class="fas fa-trash"></i>
                                    </div>

                                    <h5 style="
                                        margin:0 0 8px;
                                        font-weight:700;
                                        color:#1e293b;
                                    ">
                                        Remove profile picture?
                                    </h5>

                                    <p style="
                                        margin:0 0 20px;
                                        color:#64748b;
                                        font-size:14px;
                                    ">
                                        Your current profile picture will be removed.
                                    </p>

                                    <div style="
                                        display:flex;
                                        gap:10px;
                                        justify-content:flex-end;
                                    ">
                                        <button
                                            type="button"
                                            id="avatarCancelBtn"
                                            class="btn btn-light">
                                            Cancel
                                        </button>

                                        <button
                                            type="button"
                                            id="avatarConfirmRemoveBtn"
                                            class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i>
                                            Remove
                                        </button>
                                    </div>
                                </div>
                            </div>
                        `;

                    document.body.appendChild(modal);

                    document
                        .getElementById('avatarCancelBtn')
                        .addEventListener('click', () => modal.remove());

                    document
                        .getElementById('avatarConfirmRemoveBtn')
                        .addEventListener('click', () => {

                            modal.remove();
                            performAvatarRemoval();
                        });
                }

                window.removeAvatar = removeAvatar;


                /*
                 * =========================================================
                 * ACTUAL REMOVE REQUEST
                 * =========================================================
                 */
                function performAvatarRemoval() {

                    if (!avatarWrapper) return;

                    avatarWrapper.insertAdjacentHTML(
                        'beforeend',
                        '<div class="avatar-loading" id="avatarLoading">' +
                        '<span class="spinner-border text-danger"></span>' +
                        '</div>'
                    );

                    showProfileToast(
                        'info',
                        'Removing your profile picture...',
                        'Please wait'
                    );

                    fetch('{{ route("employer.profile.remove-avatar") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({})
                    })
                        .then(async response => {

                            const contentType =
                                response.headers.get('content-type') || '';

                            if (!contentType.includes('application/json')) {
                                throw new Error(
                                    'Server did not return a valid JSON response.'
                                );
                            }

                            const data = await response.json();

                            if (!response.ok || !data.success) {
                                throw new Error(
                                    data.message || 'Unable to remove avatar.'
                                );
                            }

                            return data;
                        })
                        .then(data => {

                            setEmptyAvatar();

                            showProfileToast(
                                'success',
                                data.message || 'Profile picture removed successfully!',
                                'Profile Updated'
                            );

                        })
                        .catch(error => {

                            console.error('Avatar removal error:', error);

                            showProfileToast(
                                'error',
                                error.message || 'Unable to remove profile picture.',
                                'Remove Failed'
                            );

                        })
                        .finally(() => {

                            const loader =
                                document.getElementById('avatarLoading');

                            if (loader) {
                                loader.remove();
                            }
                        });
                }


                /*
                 * =========================================================
                 * SESSION TOAST
                 * =========================================================
                 */
                @if(session('toast'))
                    const sessionToast = @json(session('toast'));

                    showProfileToast(
                        sessionToast.type || 'info',
                        sessionToast.message || 'Notification'
                    );
                @endif


                    /*
                     * =========================================================
                     * FORM SUBMIT LOADING
                     * =========================================================
                     */
                    const infoSubmitBtn =
                    document.getElementById('infoSubmitBtn');

                const infoForm =
                    document.getElementById('infoForm');

                if (infoForm && infoSubmitBtn) {

                    infoForm.addEventListener('submit', function () {

                        infoSubmitBtn.disabled = true;

                        infoSubmitBtn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2"></span>' +
                            'Updating...';
                    });
                }


                const passwordSubmitBtn =
                    document.getElementById('passwordSubmitBtn');

                const passwordForm =
                    document.getElementById('passwordForm');

                if (passwordForm && passwordSubmitBtn) {

                    passwordForm.addEventListener('submit', function () {

                        passwordSubmitBtn.disabled = true;

                        passwordSubmitBtn.innerHTML =
                            '<span class="spinner-border spinner-border-sm me-2"></span>' +
                            'Changing...';
                    });
                }

            });
        </script>

    @endpush
@endsection