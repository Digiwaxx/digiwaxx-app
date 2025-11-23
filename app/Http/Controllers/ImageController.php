<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Rbaskam\LaravelPCloud\App;
use Rbaskam\LaravelPCloud\File;
use App\Helpers\ImageHelper;

class ImageController extends Controller
{
    protected $pCloudApp;

    public function __construct()
    {
        // Initialize pCloud app if configured
        if ($this->isPCloudConfigured()) {
            try {
                $this->pCloudApp = new App();
                $this->pCloudApp->setAccessToken(config('laravel-pcloud.access_token', config('images.pcloud.access_token')));
                $this->pCloudApp->setLocationId(config('laravel-pcloud.location_id', config('images.pcloud.location_id', 1)));
            } catch (\Exception $e) {
                Log::warning('ImageController: Failed to initialize pCloud', ['error' => $e->getMessage()]);
                $this->pCloudApp = null;
            }
        }
    }

    /**
     * Check if pCloud is properly configured
     *
     * @return bool
     */
    private function isPCloudConfigured()
    {
        $accessToken = config('laravel-pcloud.access_token', config('images.pcloud.access_token'));
        return !empty($accessToken) && $accessToken !== 'your_pcloud_access_token';
    }

    /**
     * Serve an image with automatic fallback
     * This replaces the old pCloudImgDownload.php functionality
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function serve(Request $request)
    {
        $fileId = $request->query('fileId', $request->query('fileID'));
        $type = $request->query('type', 'track');
        $localFilename = $request->query('local', '');
        $altFilename = $request->query('alt', '');

        // Validate file ID
        if (empty($fileId) || !is_numeric($fileId)) {
            return $this->serveFallback($localFilename, $altFilename, $type);
        }

        $fileId = (int) $fileId;
        $primarySource = config('images.primary_source', 'local');

        // If primary source is local, try local first
        if ($primarySource === 'local') {
            $localResponse = $this->tryServeLocal($localFilename, $type);
            if ($localResponse) {
                return $localResponse;
            }

            $altResponse = $this->tryServeLocal($altFilename, $type);
            if ($altResponse) {
                return $altResponse;
            }
        }

        // Try pCloud
        $pCloudResponse = $this->tryServePCloud($fileId);
        if ($pCloudResponse) {
            return $pCloudResponse;
        }

        // Fall back to local storage
        return $this->serveFallback($localFilename, $altFilename, $type);
    }

    /**
     * Serve image directly from pCloud (for backward compatibility)
     *
     * @param int $fileId
     * @return \Illuminate\Http\Response
     */
    public function servePCloud($fileId)
    {
        if (!is_numeric($fileId)) {
            return $this->servePlaceholder('track');
        }

        $response = $this->tryServePCloud((int) $fileId);

        if ($response) {
            return $response;
        }

        return $this->servePlaceholder('track');
    }

    /**
     * Attempt to serve an image from pCloud
     *
     * @param int $fileId
     * @return \Illuminate\Http\Response|null
     */
    private function tryServePCloud($fileId)
    {
        if (!$this->pCloudApp) {
            Log::debug('ImageController: pCloud not available');
            return null;
        }

        // Check cache for pCloud URL
        $cacheKey = 'pcloud_image_url_' . $fileId;
        $cachedUrl = Cache::get($cacheKey);

        try {
            $pcloudFile = new File($this->pCloudApp);

            if ($cachedUrl) {
                $url = $cachedUrl;
            } else {
                $url = $pcloudFile->getLink($fileId);

                if (!$url) {
                    Log::debug('ImageController: pCloud returned no URL for file', ['fileId' => $fileId]);
                    $this->markPCloudUnavailable();
                    return null;
                }

                // Cache the URL
                $cacheTtl = config('images.pcloud.cache_ttl', 3600);
                Cache::put($cacheKey, $url, $cacheTtl);
            }

            // Get file info for headers
            $fileInfo = $pcloudFile->getInfo($fileId);

            // Stream the image from pCloud
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
            ]);

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($error || $httpCode >= 400 || empty($imageData)) {
                Log::warning('ImageController: pCloud fetch failed', [
                    'fileId' => $fileId,
                    'httpCode' => $httpCode,
                    'error' => $error
                ]);
                Cache::forget($cacheKey);
                $this->markPCloudUnavailable();
                return null;
            }

            // Determine content type
            if (empty($contentType) || strpos($contentType, 'image') === false) {
                $contentType = $this->getImageMimeType($fileInfo->metadata->name ?? 'image.jpg');
            }

            $filename = $fileInfo->metadata->name ?? 'image';

            return Response::make($imageData, 200, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
                'Content-Length' => strlen($imageData),
                'Cache-Control' => 'public, max-age=86400',
                'X-Image-Source' => 'pcloud',
            ]);

        } catch (\Exception $e) {
            Log::error('ImageController: pCloud exception', [
                'fileId' => $fileId,
                'error' => $e->getMessage()
            ]);
            $this->markPCloudUnavailable();
            return null;
        }
    }

    /**
     * Attempt to serve a local image
     *
     * @param string $filename
     * @param string $type
     * @return \Illuminate\Http\Response|null
     */
    private function tryServeLocal($filename, $type = 'track')
    {
        if (empty($filename)) {
            return null;
        }

        $paths = $this->getLocalPaths($type);

        foreach ($paths as $path) {
            $fullPath = base_path($path . '/' . $filename);

            if (file_exists($fullPath) && is_file($fullPath)) {
                $mimeType = $this->getImageMimeType($filename);

                return Response::make(file_get_contents($fullPath), 200, [
                    'Content-Type' => $mimeType,
                    'Content-Disposition' => 'inline; filename="' . basename($filename) . '"',
                    'Content-Length' => filesize($fullPath),
                    'Cache-Control' => 'public, max-age=86400',
                    'X-Image-Source' => 'local',
                ]);
            }
        }

        return null;
    }

    /**
     * Serve fallback image (local then placeholder)
     *
     * @param string $localFilename
     * @param string $altFilename
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    private function serveFallback($localFilename, $altFilename, $type)
    {
        // Try local filename first
        $response = $this->tryServeLocal($localFilename, $type);
        if ($response) {
            return $response;
        }

        // Try alternate filename
        $response = $this->tryServeLocal($altFilename, $type);
        if ($response) {
            return $response;
        }

        // Serve placeholder
        return $this->servePlaceholder($type);
    }

    /**
     * Serve the placeholder image for a type
     *
     * @param string $type
     * @return \Illuminate\Http\Response
     */
    private function servePlaceholder($type)
    {
        $placeholders = config('images.placeholders', []);
        $placeholder = $placeholders[$type] ?? $placeholders['track'] ?? 'public/images/noimage-avl.jpg';
        $fullPath = base_path($placeholder);

        if (file_exists($fullPath)) {
            $mimeType = $this->getImageMimeType($placeholder);

            return Response::make(file_get_contents($fullPath), 200, [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline; filename="placeholder.' . pathinfo($placeholder, PATHINFO_EXTENSION) . '"',
                'Content-Length' => filesize($fullPath),
                'Cache-Control' => 'public, max-age=3600',
                'X-Image-Source' => 'placeholder',
            ]);
        }

        // If placeholder doesn't exist, return a 1x1 transparent GIF
        $transparentGif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');

        return Response::make($transparentGif, 200, [
            'Content-Type' => 'image/gif',
            'Content-Length' => strlen($transparentGif),
            'Cache-Control' => 'public, max-age=60',
            'X-Image-Source' => 'fallback-gif',
        ]);
    }

    /**
     * Get local paths to check for an image type
     *
     * @param string $type
     * @return array
     */
    private function getLocalPaths($type)
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
                ];
            default:
                return [
                    $config['images_path'] ?? 'ImagesUp',
                    $config['static_path'] ?? 'public/images',
                ];
        }
    }

    /**
     * Get MIME type for an image filename
     *
     * @param string $filename
     * @return string
     */
    private function getImageMimeType($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'svg' => 'image/svg+xml',
            'bmp' => 'image/bmp',
            'ico' => 'image/x-icon',
        ];

        return $mimeTypes[$extension] ?? 'image/jpeg';
    }

    /**
     * Mark pCloud as temporarily unavailable
     */
    private function markPCloudUnavailable()
    {
        Cache::put('pcloud_availability_status', false, 300); // 5 minutes
    }

    /**
     * Check pCloud availability status
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkStatus()
    {
        $status = [
            'pcloud_configured' => $this->isPCloudConfigured(),
            'pcloud_available' => Cache::get('pcloud_availability_status', true),
            'primary_source' => config('images.primary_source', 'local'),
            'fallback_enabled' => config('images.enable_fallback', true),
        ];

        return response()->json($status);
    }
}
