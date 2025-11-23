<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Image Storage Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration controls how images are loaded throughout the application.
    | You can switch between 'pcloud' and 'local' storage, with automatic fallback
    | when the primary source is unavailable.
    |
    */

    // Primary image source: 'pcloud' or 'local'
    // When set to 'pcloud', images will be fetched from pCloud first, then fall back to local
    // When set to 'local', images will only be fetched from local storage
    'primary_source' => env('IMAGE_PRIMARY_SOURCE', 'local'),

    // Enable fallback to local storage when pCloud is unavailable
    'enable_fallback' => env('IMAGE_ENABLE_FALLBACK', true),

    // pCloud configuration
    'pcloud' => [
        'enabled' => env('PCLOUD_ENABLED', true),
        'access_token' => env('PCLOUD_ACCESS_TOKEN'),
        'location_id' => env('PCLOUD_LOCATION_ID', 1),
        'folder_id' => env('PCLOUD_FOLDER_ID'),
        'audio_path' => env('PCLOUD_AUDIO_PATH'),
        'client_audio_path' => env('PCLOUD_CLIENT_AUDIO_PATH'),
        // Cache pCloud URLs for this many seconds (to reduce API calls)
        'cache_ttl' => env('PCLOUD_CACHE_TTL', 3600),
    ],

    // Local storage paths
    'local' => [
        // Primary upload directory for track images
        'images_path' => 'ImagesUp',
        // Logo directory
        'logos_path' => 'public/Logos',
        // Static images directory
        'static_path' => 'public/images',
    ],

    // Default placeholder images when no image is available
    'placeholders' => [
        'track' => 'public/images/noimage-avl.jpg',
        'album' => 'public/images/noimage-avl.jpg',
        'logo' => 'public/images/logo.png',
        'member' => 'public/images/default-avatar.png',
        'banner' => 'public/images/banner-default.jpg',
    ],

    // Image types and their corresponding pCloud field names
    'types' => [
        'track' => [
            'pcloud_field' => 'pCloudFileID',
            'local_field' => 'imgpage',
            'fallback_field' => 'img',
        ],
        'album' => [
            'pcloud_field' => 'pCloudFileID_album',
            'local_field' => 'album_page_image',
            'fallback_field' => null,
        ],
        'logo' => [
            'pcloud_field' => 'pCloudFileID_logo',
            'local_field' => 'logo',
            'fallback_field' => null,
        ],
        'member' => [
            'pcloud_field' => 'pCloudFileID_mem_image',
            'local_field' => 'member_image',
            'fallback_field' => null,
        ],
        'client_image' => [
            'pcloud_field' => 'pCloudFileID_client_image',
            'local_field' => 'client_image',
            'fallback_field' => null,
        ],
        'banner' => [
            'pcloud_field' => 'pCloudFileID',
            'local_field' => 'banner_image',
            'fallback_field' => null,
        ],
    ],
];
