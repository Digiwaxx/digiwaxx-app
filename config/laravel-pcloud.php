<?php

return [
    /*
    |--------------------------------------------------------------------------
    | pCloud Access Token
    |--------------------------------------------------------------------------
    |
    | Your pCloud API access token. Generate this from:
    | pCloud Web → Settings → Security → App Passwords
    |
    | IMPORTANT: Keep this secret! Never commit to git.
    |
    */
    'access_token' => env('PCLOUD_ACCESS_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | pCloud Location/Region ID
    |--------------------------------------------------------------------------
    |
    | Determines which pCloud API endpoint to use:
    | 1 = US region (api.pcloud.com)
    | 2 = EU region (eapi.pcloud.com)
    |
    | Default: 1 (US)
    |
    */
    'location_id' => env('PCLOUD_LOCATION_ID', 1),

    /*
    |--------------------------------------------------------------------------
    | pCloud Folder Paths
    |--------------------------------------------------------------------------
    |
    | Folder IDs for storing different types of files in pCloud.
    | These are numeric IDs, not folder names.
    |
    | How to get folder IDs:
    | 1. Create folders in pCloud web interface
    | 2. Use pCloud API: /listfolder?folderid=0&auth=[token]
    | 3. Find "folderid" in response JSON
    |
    */

    // Admin track uploads (images and audio)
    'audio_path' => env('PCLOUD_AUDIO_PATH'),

    // Client track submissions (separate folder for pending approval)
    'client_audio_path' => env('PCLOUD_CLIENT_AUDIO_PATH'),

    // Image storage paths
    'image_path' => env('PCLOUD_IMAGE_PATH', env('PCLOUD_AUDIO_PATH')), // Fallback to audio_path if not set

    // Logo storage
    'logo_path' => env('PCLOUD_LOGO_PATH', env('PCLOUD_AUDIO_PATH')),

    /*
    |--------------------------------------------------------------------------
    | pCloud API Settings
    |--------------------------------------------------------------------------
    */

    // API endpoint URLs (determined by location_id)
    'api_endpoints' => [
        1 => 'https://api.pcloud.com',  // US
        2 => 'https://eapi.pcloud.com', // EU
    ],

    // Upload timeout in seconds
    'upload_timeout' => env('PCLOUD_UPLOAD_TIMEOUT', 300), // 5 minutes

    // Download timeout in seconds
    'download_timeout' => env('PCLOUD_DOWNLOAD_TIMEOUT', 120), // 2 minutes

    // Retry failed uploads
    'retry_attempts' => env('PCLOUD_RETRY_ATTEMPTS', 3),

    // Delay between retries (milliseconds)
    'retry_delay' => env('PCLOUD_RETRY_DELAY', 1000),

    /*
    |--------------------------------------------------------------------------
    | File Validation
    |--------------------------------------------------------------------------
    */

    'validation' => [
        // Audio file settings
        'audio' => [
            'max_size' => 52428800, // 50MB in bytes
            'allowed_mimes' => [
                'audio/mpeg',
                'audio/mp3',
                'audio/wav',
                'audio/x-wav',
                'audio/flac',
                'audio/aac',
                'audio/ogg',
                'audio/mp4',
                'audio/x-m4a',
                'audio/x-ms-wma',
            ],
            'allowed_extensions' => ['mp3', 'wav', 'flac', 'aac', 'ogg', 'm4a', 'wma'],
        ],

        // Image file settings
        'image' => [
            'max_size' => 5242880, // 5MB in bytes
            'allowed_mimes' => [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/gif',
                'image/webp',
            ],
            'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */

    // Cache file URLs to reduce API calls
    'cache_enabled' => env('PCLOUD_CACHE_ENABLED', true),

    // Cache duration for file URLs (in seconds)
    'cache_duration' => env('PCLOUD_CACHE_DURATION', 3600), // 1 hour

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */

    // Log pCloud API calls for debugging
    'log_api_calls' => env('PCLOUD_LOG_API_CALLS', false),

    // Log uploads/downloads
    'log_file_operations' => env('PCLOUD_LOG_FILE_OPERATIONS', true),
];
