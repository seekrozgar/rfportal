{{-- resources/views/components/language-switcher.blade.php --}}

@php
    $languages = \App\Helpers\LanguageHelper::getLanguages();
    $currentLocale = \App\Helpers\LanguageHelper::getCurrentLocale();
    $currentFlagClass = \App\Helpers\LanguageHelper::getFlagClass($currentLocale);
    $currentName = \App\Helpers\LanguageHelper::getLanguageName($currentLocale);
    $currentNativeName = \App\Helpers\LanguageHelper::getNativeName($currentLocale);
    $isRtl = \App\Helpers\LanguageHelper::getDirection($currentLocale) === 'rtl';
@endphp

<div class="language-switcher" x-data="{ open: false }">
    {{-- ✅ Toggle Button --}}
    <button type="button" class="lang-toggle" @click="open = !open" @click.away="open = false"
        @keydown.escape="open = false" aria-haspopup="true" :aria-expanded="open" id="langToggle">
        {{-- ✅ Flag Icon (CSS - works in all browsers) --}}
        <span class="fi {{ $currentFlagClass }}"></span>
        {{-- ✅ Language Name --}}
        <span class="lang-name">{{ $currentName }}</span>
        {{-- ✅ Chevron --}}
        <i class="fas fa-chevron-down" :class="{ 'rotate': open }"></i>
    </button>

    {{-- ✅ Dropdown Menu --}}
    <ul class="lang-dropdown" id="langDropdown" x-show="open" x-cloak
        :class="{ 'rtl': {{ $isRtl ? 'true' : 'false' }} }" role="menu" aria-labelledby="langToggle">
        @foreach($languages as $language)
            <li role="none">
                <a href="{{ route('language.switch', $language->code) }}"
                    class="{{ $language->code == $currentLocale ? 'active' : '' }}" role="menuitem">
                    {{-- ✅ Flag Icon (CSS - works in all browsers) --}}
                    <span
                        class="fi {{ $language->flag_class ?? \App\Helpers\LanguageHelper::getFlagClass($language->code) }}"></span>
                    {{-- ✅ Language Name --}}
                    <span class="lang-name">{{ $language->native_name ?? $language->name }}</span>

                    {{-- ✅ Default Badge --}}
                    @if($language->is_default ?? false)
                        <span class="default-badge">Default</span>
                    @endif

                    {{-- ✅ Active Check Mark --}}
                    @if($language->code == $currentLocale)
                        <span class="check-mark">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

{{-- ✅ Styles --}}
<style>
    /* ✅ Alpine.js Cloak */
    [x-cloak] {
        display: none !important;
    }

    /* ✅ Language Switcher Container */
    .language-switcher {
        position: relative;
        display: inline-block;
    }

    /* ✅ Toggle Button */
    .lang-toggle {
        background: transparent;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 14px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #1e293b;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
        background: #f8fafc;
        min-width: 120px;
        justify-content: center;
    }

    .lang-toggle:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateY(-1px);
    }

    .lang-toggle:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }

    /* ✅ Flag Icon in Toggle */
    .lang-toggle .fi {
        font-size: 20px;
        width: 24px;
        height: 18px;
        flex-shrink: 0;
        border-radius: 2px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .lang-toggle .lang-name {
        font-size: 13px;
        font-weight: 500;
    }

    .lang-toggle .fa-chevron-down {
        font-size: 11px;
        transition: transform 0.3s ease;
        color: #94a3b8;
    }

    .lang-toggle .fa-chevron-down.rotate {
        transform: rotate(180deg);
    }

    /* ✅ Dropdown Menu */
    .lang-dropdown {
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        min-width: 200px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
        padding: 6px 0;
        margin: 0;
        list-style: none;
        z-index: 1060;
        overflow: hidden;
        animation: slideDown 0.2s ease;
    }

    .lang-dropdown.rtl {
        right: auto;
        left: 0;
    }

    .lang-dropdown li {
        display: block;
        margin: 0;
        padding: 0;
    }

    .lang-dropdown li a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 16px;
        color: #1e293b;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.15s ease;
        border-left: 3px solid transparent;
        cursor: pointer;
    }

    .lang-dropdown li a:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .lang-dropdown li a.active {
        background: #eff6ff;
        color: #6366f1;
        border-left-color: #6366f1;
        font-weight: 600;
    }

    .lang-dropdown li a .fi {
        font-size: 22px;
        width: 28px;
        height: 20px;
        flex-shrink: 0;
        border-radius: 2px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    }

    .lang-dropdown li a .lang-name {
        flex: 1;
    }

    .lang-dropdown li a .default-badge {
        font-size: 9px;
        font-weight: 600;
        background: #6366f1;
        color: #fff;
        padding: 2px 10px;
        border-radius: 12px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .lang-dropdown li a .check-mark {
        color: #22c55e;
        font-size: 16px;
        margin-left: auto;
    }

    /* ✅ Animation */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    /* ✅ Dark Mode Support */
    @media (prefers-color-scheme: dark) {
        .lang-toggle {
            background: #1e293b;
            border-color: #334155;
            color: #e2e8f0;
        }

        .lang-toggle:hover {
            background: #334155;
            border-color: #475569;
        }

        .lang-dropdown {
            background: #1e293b;
            border-color: #334155;
        }

        .lang-dropdown li a {
            color: #e2e8f0;
        }

        .lang-dropdown li a:hover {
            background: #334155;
            color: #f1f5f9;
        }

        .lang-dropdown li a.active {
            background: #1e293b;
            color: #818cf8;
            border-left-color: #818cf8;
        }
    }

    /* ✅ Responsive */
    @media (max-width: 768px) {
        .lang-toggle {
            padding: 6px 12px;
            min-width: 50px;
            font-size: 13px;
        }

        .lang-toggle .lang-name {
            display: none;
        }

        .lang-dropdown {
            min-width: 180px;
            right: -10px;
        }

        .lang-dropdown li a {
            padding: 8px 14px;
            font-size: 13px;
        }
    }
</style>

{{-- ✅ Fallback Script (for browsers without Alpine.js) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('langToggle');
        const dropdown = document.getElementById('langDropdown');

        if (toggle && dropdown) {
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                dropdown.classList.toggle('open');
            });

            document.addEventListener('click', function (e) {
                if (!toggle.contains(e.target) && !dropdown.contains(e.target)) {
                    dropdown.classList.remove('open');
                }
            });

            // ✅ Close on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') {
                    dropdown.classList.remove('open');
                }
            });
        }
    });
</script>