<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

/**
 * Middleware to set the application locale based on URL prefix.
 *
 * URL Structure:
 * - / (no prefix) = English (default)
 * - /es/ = Spanish
 * - /pt/ = Portuguese
 * - /fr/ = French
 * - /de/ = German
 * - /ja/ = Japanese
 * - /ko/ = Korean
 *
 * SAFETY:
 * - English routes have NO prefix (backward compatible)
 * - If translations disabled, always defaults to English
 * - Invalid locales default to English
 */
class SetLocale
{
    /**
     * Supported locales that use URL prefix
     */
    private const PREFIXED_LOCALES = ['es', 'pt', 'fr', 'de', 'ja', 'ko'];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if translations feature is enabled
        $translationsEnabled = config('localization.enabled', false);

        if (!$translationsEnabled) {
            // Feature disabled - force English
            App::setLocale('en');
            View::share('currentLocale', 'en');
            View::share('translationsEnabled', false);
            return $next($request);
        }

        // Extract first segment of URL path
        $firstSegment = $request->segment(1);

        // Check if first segment is a valid locale prefix
        if (in_array($firstSegment, self::PREFIXED_LOCALES, true)) {
            // Set the locale
            App::setLocale($firstSegment);
            View::share('currentLocale', $firstSegment);
        } else {
            // No locale prefix = English (default)
            App::setLocale('en');
            View::share('currentLocale', 'en');
        }

        // Share translation status with views
        View::share('translationsEnabled', true);
        View::share('availableLocales', config('app.available_locales', []));

        return $next($request);
    }

    /**
     * Get the URL for a different locale
     *
     * @param string $locale
     * @param string|null $currentPath
     * @return string
     */
    public static function getLocalizedUrl(string $locale, ?string $currentPath = null): string
    {
        $currentPath = $currentPath ?? request()->path();

        // Remove any existing locale prefix from path
        $cleanPath = preg_replace('#^(es|pt|fr|de|ja|ko)/?#', '', $currentPath);
        $cleanPath = '/' . ltrim($cleanPath, '/');

        if ($locale === 'en') {
            // English = no prefix
            return $cleanPath === '/' ? '/' : $cleanPath;
        }

        // Other languages = with prefix
        return '/' . $locale . ($cleanPath === '/' ? '' : $cleanPath);
    }

    /**
     * Check if current locale is RTL
     *
     * @return bool
     */
    public static function isRtl(): bool
    {
        $locale = App::getLocale();
        $locales = config('app.available_locales', []);

        return $locales[$locale]['rtl'] ?? false;
    }
}
