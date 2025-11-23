<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ImageHelper
{
    /**
     * Get the URL for an image with automatic fallback support
     *
     * @param string|int|null $pCloudFileID The pCloud file ID
     * @param string|null $localFilename The local filename
     * @param string $type The image type (track, album, logo, member, banner, client_image)
     * @param string|null $alternateFilename Alternative local filename to try
     * @return string The image URL
     */
    public static function getImageUrl($pCloudFileID = null, $localFilename = null, $type = 'track', $alternateFilename = null)
    {
        $primarySource = config('images.primary_source', 'local');
        $enableFallback = config('images.enable_fallback', true);
        $pCloudEnabled = config('images.pcloud.enabled', true);

        // If primary source is pCloud and pCloud is enabled
        if ($primarySource === 'pcloud' && $pCloudEnabled && !empty($pCloudFileID) && is_numeric($pCloudFileID)) {
            // Return pCloud URL - the route will handle fallback if pCloud fails
            return route('image.serve', [
                'fileId' => $pCloudFileID,
                'type' => $type,
                'local' => $localFilename ?? '',
                'alt' => $alternateFilename ?? ''
            ]);
        }

        // Try local file first if primary source is local
        $localUrl = self::getLocalImageUrl($localFilename, $type);
        if ($localUrl) {
            return $localUrl;
        }

        // Try alternate filename if provided
        if ($alternateFilename) {
            $altUrl = self::getLocalImageUrl($alternateFilename, $type);
            if ($altUrl) {
                return $altUrl;
            }
        }

        // If fallback is enabled and pCloud is available, try pCloud as secondary
        if ($enableFallback && $pCloudEnabled && !empty($pCloudFileID) && is_numeric($pCloudFileID)) {
            return route('image.serve', [
                'fileId' => $pCloudFileID,
                'type' => $type,
                'local' => $localFilename ?? '',
                'alt' => $alternateFilename ?? ''
            ]);
        }

        // Return placeholder image
        return self::getPlaceholderUrl($type);
    }

    /**
     * Get URL for a local image file
     *
     * @param string|null $filename The filename
     * @param string $type The image type
     * @return string|null The URL or null if file doesn't exist
     */
    public static function getLocalImageUrl($filename, $type = 'track')
    {
        if (empty($filename)) {
            return null;
        }

        // Determine the path based on type
        $paths = self::getLocalPaths($type);

        foreach ($paths as $path) {
            $fullPath = base_path($path . '/' . $filename);
            if (file_exists($fullPath)) {
                return asset($path . '/' . $filename);
            }
        }

        return null;
    }

    /**
     * Get local paths to check for an image type
     *
     * @param string $type The image type
     * @return array Array of paths to check
     */
    private static function getLocalPaths($type)
    {
        $config = config('images.local', []);

        switch ($type) {
            case 'logo':
                return [
                    $config['logos_path'] ?? 'public/Logos',
                    $config['static_path'] ?? 'public/images',
                ];
            case 'track':
            case 'album':
                return [
                    $config['images_path'] ?? 'ImagesUp',
                    $config['static_path'] ?? 'public/images',
                ];
            case 'banner':
                return [
                    $config['static_path'] ?? 'public/images',
                    $config['images_path'] ?? 'ImagesUp',
                ];
            default:
                return [
                    $config['images_path'] ?? 'ImagesUp',
                    $config['static_path'] ?? 'public/images',
                ];
        }
    }

    /**
     * Get the placeholder image URL for a type
     *
     * @param string $type The image type
     * @return string The placeholder URL
     */
    public static function getPlaceholderUrl($type = 'track')
    {
        $placeholders = config('images.placeholders', []);
        $placeholder = $placeholders[$type] ?? $placeholders['track'] ?? 'public/images/noimage-avl.jpg';
        return asset($placeholder);
    }

    /**
     * Generate data attributes for JavaScript fallback handling
     *
     * @param string|int|null $pCloudFileID The pCloud file ID
     * @param string|null $localFilename The local filename
     * @param string $type The image type
     * @return string HTML data attributes
     */
    public static function getFallbackAttributes($pCloudFileID = null, $localFilename = null, $type = 'track')
    {
        $attributes = [];

        // Add pCloud fallback URL if available
        if (!empty($pCloudFileID) && is_numeric($pCloudFileID)) {
            $pcloudUrl = route('image.serve', ['fileId' => $pCloudFileID, 'type' => $type]);
            $attributes[] = 'data-pcloud-src="' . htmlspecialchars($pcloudUrl) . '"';
        }

        // Add local fallback URL if available
        $localUrl = self::getLocalImageUrl($localFilename, $type);
        if ($localUrl) {
            $attributes[] = 'data-local-src="' . htmlspecialchars($localUrl) . '"';
        }

        // Add placeholder URL
        $placeholderUrl = self::getPlaceholderUrl($type);
        $attributes[] = 'data-placeholder-src="' . htmlspecialchars($placeholderUrl) . '"';

        // Add type for debugging
        $attributes[] = 'data-img-type="' . htmlspecialchars($type) . '"';

        // Add onerror handler
        $attributes[] = 'onerror="handleImageError(this)"';

        return implode(' ', $attributes);
    }

    /**
     * Check if pCloud is available/connected
     *
     * @return bool True if pCloud is available
     */
    public static function isPCloudAvailable()
    {
        // Check cached status first
        $cacheKey = 'pcloud_availability_status';

        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        // Check if pCloud is enabled in config
        if (!config('images.pcloud.enabled', true)) {
            return false;
        }

        // Check if access token is configured
        $accessToken = config('images.pcloud.access_token');
        if (empty($accessToken) || $accessToken === 'your_pcloud_access_token') {
            Cache::put($cacheKey, false, 300); // Cache for 5 minutes
            return false;
        }

        // For now, assume available if configured
        // The actual availability is checked when fetching images
        return true;
    }

    /**
     * Generate a complete img tag with fallback support
     *
     * @param string|int|null $pCloudFileID The pCloud file ID
     * @param string|null $localFilename The local filename
     * @param string $type The image type
     * @param array $attributes Additional HTML attributes
     * @return string Complete HTML img tag
     */
    public static function imgTag($pCloudFileID = null, $localFilename = null, $type = 'track', $attributes = [])
    {
        $url = self::getImageUrl($pCloudFileID, $localFilename, $type);
        $fallbackAttrs = self::getFallbackAttributes($pCloudFileID, $localFilename, $type);

        $htmlAttrs = [];
        foreach ($attributes as $key => $value) {
            $htmlAttrs[] = htmlspecialchars($key) . '="' . htmlspecialchars($value) . '"';
        }

        return '<img src="' . htmlspecialchars($url) . '" ' . $fallbackAttrs . ' ' . implode(' ', $htmlAttrs) . '>';
    }

    /**
     * Get image source for templates (backward compatible with existing code)
     * This replaces the old pCloudImgDownload.php logic
     *
     * @param object|array $item The data object (track, album, etc.)
     * @param string $type The image type
     * @return string The image URL
     */
    public static function getSourceFromItem($item, $type = 'track')
    {
        $item = (object) $item;
        $typeConfig = config('images.types.' . $type, []);

        $pCloudField = $typeConfig['pcloud_field'] ?? 'pCloudFileID';
        $localField = $typeConfig['local_field'] ?? 'imgpage';
        $fallbackField = $typeConfig['fallback_field'] ?? null;

        $pCloudFileID = $item->$pCloudField ?? null;
        $localFilename = $item->$localField ?? null;
        $fallbackFilename = $fallbackField ? ($item->$fallbackField ?? null) : null;

        return self::getImageUrl($pCloudFileID, $localFilename, $type, $fallbackFilename);
    }
}
