{{--
    Language Switcher Component

    Allows users to switch between available languages.
    Only displays when TRANSLATIONS_ENABLED=true in .env

    Usage: <x-language-switcher />
--}}

@if(config('localization.enabled', false) && !config('localization.hide_switcher', false))
<div class="language-switcher">
    <select
        id="language-select"
        class="language-select"
        onchange="changeLanguage(this.value)"
        aria-label="{{ __('Select Language') }}"
    >
        @foreach(config('app.available_locales', []) as $code => $locale)
            <option value="{{ $code }}" {{ app()->getLocale() === $code ? 'selected' : '' }}>
                {{ $locale['flag'] ?? '' }} {{ $locale['native'] ?? $locale['name'] ?? $code }}
            </option>
        @endforeach
    </select>
</div>

<style>
.language-switcher {
    display: inline-block;
    position: relative;
}

.language-select {
    padding: 8px 32px 8px 12px;
    border-radius: 8px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(255, 255, 255, 0.1);
    color: inherit;
    font-size: 14px;
    cursor: pointer;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23888' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
    min-width: 140px;
}

.language-select:hover {
    border-color: rgba(255, 255, 255, 0.4);
    background-color: rgba(255, 255, 255, 0.15);
}

.language-select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 2px rgba(76, 175, 80, 0.2);
}

.language-select option {
    background: #1a1a2e;
    color: #fff;
    padding: 10px;
}

/* Light theme variant */
.light-theme .language-select {
    border-color: #ddd;
    background-color: #fff;
    color: #333;
}

.light-theme .language-select option {
    background: #fff;
    color: #333;
}

/* Compact variant */
.language-switcher.compact .language-select {
    padding: 6px 28px 6px 10px;
    font-size: 13px;
    min-width: 120px;
}

@media (max-width: 768px) {
    .language-select {
        font-size: 16px; /* Prevent zoom on iOS */
        min-width: 100px;
    }
}
</style>

<script>
/**
 * Change the language by redirecting to the localized URL
 * @param {string} locale - The locale code (e.g., 'es', 'pt', 'en')
 */
function changeLanguage(locale) {
    // Save preference to localStorage
    localStorage.setItem('preferred_language', locale);

    // Get current path
    let currentPath = window.location.pathname;

    // Remove any existing locale prefix from path
    // Matches: /es, /es/, /es/anything, /pt, /pt/, etc.
    const localePattern = /^\/(es|pt|fr|de|ja|ko)(\/|$)/;
    currentPath = currentPath.replace(localePattern, '/');

    // Ensure path starts with /
    if (!currentPath.startsWith('/')) {
        currentPath = '/' + currentPath;
    }

    // Build new URL
    let newUrl;
    if (locale === 'en') {
        // English = no prefix (original URLs)
        newUrl = currentPath === '/' ? '/' : currentPath;
    } else {
        // Other languages = with prefix
        newUrl = '/' + locale + (currentPath === '/' ? '' : currentPath);
    }

    // Only redirect if URL actually changed
    if (newUrl !== window.location.pathname) {
        window.location.href = newUrl;
    }
}

/**
 * On page load, check localStorage for saved language preference
 * Only auto-switch if user has explicitly chosen a language before
 */
document.addEventListener('DOMContentLoaded', function() {
    const savedLocale = localStorage.getItem('preferred_language');
    const currentLocale = '{{ app()->getLocale() }}';
    const translationsEnabled = {{ config('localization.enabled', false) ? 'true' : 'false' }};

    // Only auto-redirect if:
    // 1. Translations are enabled
    // 2. User has a saved preference
    // 3. Saved preference differs from current locale
    // 4. User hasn't manually navigated to a different locale URL
    if (translationsEnabled && savedLocale && savedLocale !== currentLocale) {
        // Check if user manually typed a locale URL (don't override their choice)
        const pathHasLocale = /^\/(es|pt|fr|de|ja|ko)(\/|$)/.test(window.location.pathname);
        const isEnglishPath = !pathHasLocale;

        // Only auto-redirect from English if user preference is different
        // Don't redirect if user explicitly navigated to a localized URL
        if (isEnglishPath && savedLocale !== 'en') {
            // Uncomment the line below to enable auto-redirect based on saved preference
            // changeLanguage(savedLocale);
        }
    }
});
</script>
@endif
