<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Translations Feature Flag
    |--------------------------------------------------------------------------
    |
    | SAFETY: Set to false to disable all multi-language features.
    | When disabled:
    | - Language switcher is hidden
    | - Localized routes are disabled
    | - Site operates in English only (original behavior)
    |
    | Set to true only after thorough testing in staging.
    |
    */

    'enabled' => env('TRANSLATIONS_ENABLED', false),

    /*
    |--------------------------------------------------------------------------
    | Hide Language Switcher
    |--------------------------------------------------------------------------
    |
    | Set to true to hide the language switcher even when translations
    | are enabled. Useful for soft launches.
    |
    */

    'hide_switcher' => env('HIDE_LANGUAGE_SWITCHER', false),

    /*
    |--------------------------------------------------------------------------
    | Auto-Translate Dynamic Content
    |--------------------------------------------------------------------------
    |
    | Set to true to enable automatic translation of user-uploaded content
    | (track titles, descriptions) via translation API.
    |
    */

    'auto_translate_content' => env('AUTO_TRANSLATE_CONTENT', false),

    /*
    |--------------------------------------------------------------------------
    | Translation API Provider
    |--------------------------------------------------------------------------
    |
    | Options: 'deepl', 'google', 'claude'
    |
    */

    'translation_provider' => env('TRANSLATION_PROVIDER', 'deepl'),

    /*
    |--------------------------------------------------------------------------
    | DeepL API Configuration
    |--------------------------------------------------------------------------
    */

    'deepl' => [
        'api_key' => env('DEEPL_API_KEY'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Translations
    |--------------------------------------------------------------------------
    |
    | Cache translations to improve performance.
    |
    */

    'cache_translations' => env('CACHE_TRANSLATIONS', true),
    'cache_ttl' => env('TRANSLATION_CACHE_TTL', 3600), // 1 hour
];
