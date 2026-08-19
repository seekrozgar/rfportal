@php
    $languages = \App\Helpers\LanguageHelper::getLanguages();
    $currentLocale = \App\Helpers\LanguageHelper::getCurrentLocale();
@endphp

<div class="language-switcher">
    <button class="lang-toggle" id="langToggle">
        <span class="flag">{{ \App\Helpers\LanguageHelper::getFlag($currentLocale) }}</span>
        <span class="lang-name">{{ \App\Helpers\LanguageHelper::getLanguageName($currentLocale) }}</span>
        <i class="fas fa-chevron-down"></i>
    </button>
    <ul class="lang-dropdown" id="langDropdown">
        @foreach($languages as $language)
            <li>
                <a href="{{ route('language.switch', $language->code) }}"
                   class="{{ $language->code == $currentLocale ? 'active' : '' }}">
                    <span class="flag">{{ $language->flag ?? \App\Helpers\LanguageHelper::getFlag($language->code) }}</span>
                    <span class="lang-name">{{ $language->name ?? \App\Helpers\LanguageHelper::getLanguageName($language->code) }}</span>
                    @if(isset($language->is_default) && $language->is_default)
                        <span class="default-badge">Default</span>
                    @endif
                </a>
            </li>
        @endforeach
    </ul>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const langToggle = document.getElementById('langToggle');
    const langDropdown = document.getElementById('langDropdown');

    if (langToggle && langDropdown) {
        langToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            langDropdown.classList.toggle('open');
        });

        document.addEventListener('click', function(e) {
            if (!langToggle.contains(e.target) && !langDropdown.contains(e.target)) {
                langDropdown.classList.remove('open');
            }
        });
    }
});
</script>

<style>
.language-switcher {
    position: relative;
    display: inline-block;
}

.lang-toggle {
    background: transparent;
    border: 1px solid var(--admin-border);
    border-radius: 6px;
    padding: 6px 12px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--admin-text-dark);
    font-size: 14px;
    transition: all 0.3s ease;
}

.lang-toggle:hover {
    background: var(--admin-bg);
}

.lang-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    min-width: 160px;
    background: var(--bg-card);
    border: 1px solid var(--admin-border);
    border-radius: 8px;
    box-shadow: var(--shadow-lg);
    padding: 6px 0;
    margin-top: 4px;
    list-style: none;
    display: none;
    z-index: 1060;
}

.lang-dropdown.open {
    display: block;
}

.lang-dropdown li a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    color: var(--admin-text-dark);
    text-decoration: none;
    font-size: 13px;
    transition: all 0.3s ease;
}

.lang-dropdown li a:hover {
    background: var(--admin-bg);
}

.lang-dropdown li a.active {
    background: rgba(17, 153, 142, 0.1);
    color: var(--primary-color);
}

.lang-dropdown li a .flag {
    font-size: 18px;
}

.lang-dropdown li a .default-badge {
    font-size: 9px;
    background: var(--primary-color);
    color: #fff;
    padding: 1px 8px;
    border-radius: 10px;
    margin-left: auto;
}
</style>
