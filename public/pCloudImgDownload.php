<?php
/**
 * pCloud Image Download Handler
 *
 * This file provides backward compatibility for pCloudImgDownload.php URLs.
 * It redirects to the Laravel route handler for proper fallback support.
 *
 * For full functionality, the Laravel ImageController should be used,
 * but this file ensures basic operation when accessed directly.
 */

// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Get the fileID parameter
$fileId = $_GET['fileID'] ?? $_GET['fileId'] ?? null;
$localFile = $_GET['local'] ?? '';
$type = $_GET['type'] ?? 'track';

// Configuration
$primarySource = env('IMAGE_PRIMARY_SOURCE', 'local');
$fallbackEnabled = env('IMAGE_ENABLE_FALLBACK', true);
$pcloudEnabled = env('PCLOUD_ENABLED', true);

// Define local paths based on type
function getLocalPaths($type) {
    switch ($type) {
        case 'logo':
            return ['public/Logos', 'public/images'];
        case 'track':
        case 'album':
            return ['ImagesUp', 'public/images'];
        case 'banner':
            return ['public/images'];
        default:
            return ['ImagesUp', 'public/images'];
    }
}

// Get placeholder image
function getPlaceholder($type) {
    $placeholders = [
        'track' => 'public/images/noimage-avl.jpg',
        'album' => 'public/images/noimage-avl.jpg',
        'logo' => 'public/images/logo.png',
        'banner' => 'public/images/banner-default.jpg',
    ];
    return $placeholders[$type] ?? $placeholders['track'];
}

// Try to serve local file
function serveLocalFile($filename, $type) {
    if (empty($filename)) {
        return false;
    }

    $basePath = dirname(__DIR__);
    $paths = getLocalPaths($type);

    foreach ($paths as $path) {
        $fullPath = $basePath . '/' . $path . '/' . $filename;
        if (file_exists($fullPath) && is_file($fullPath)) {
            $mimeTypes = [
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
                'webp' => 'image/webp',
            ];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            $mime = $mimeTypes[$ext] ?? 'image/jpeg';

            header('Content-Type: ' . $mime);
            header('Content-Length: ' . filesize($fullPath));
            header('Cache-Control: public, max-age=86400');
            header('X-Image-Source: local');
            readfile($fullPath);
            return true;
        }
    }
    return false;
}

// Serve placeholder
function servePlaceholder($type) {
    $basePath = dirname(__DIR__);
    $placeholder = getPlaceholder($type);
    $fullPath = $basePath . '/' . $placeholder;

    if (file_exists($fullPath)) {
        $ext = strtolower(pathinfo($placeholder, PATHINFO_EXTENSION));
        $mimeTypes = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif'];
        $mime = $mimeTypes[$ext] ?? 'image/jpeg';

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($fullPath));
        header('Cache-Control: public, max-age=3600');
        header('X-Image-Source: placeholder');
        readfile($fullPath);
        return true;
    }

    // Return 1x1 transparent GIF as last resort
    $transparentGif = base64_decode('R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7');
    header('Content-Type: image/gif');
    header('Content-Length: ' . strlen($transparentGif));
    header('X-Image-Source: fallback-gif');
    echo $transparentGif;
    return true;
}

// Try pCloud (only if enabled and configured)
function tryPCloud($fileId) {
    if (empty($fileId) || !is_numeric($fileId)) {
        return false;
    }

    $accessToken = env('PCLOUD_ACCESS_TOKEN');
    if (empty($accessToken) || $accessToken === 'your_pcloud_access_token') {
        return false;
    }

    try {
        // Use pCloud API to get file link
        $locationId = env('PCLOUD_LOCATION_ID', 1);
        $apiBase = $locationId == 2 ? 'https://eapi.pcloud.com' : 'https://api.pcloud.com';

        $url = $apiBase . '/getfilelink?fileid=' . intval($fileId) . '&access_token=' . urlencode($accessToken);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_CONNECTTIMEOUT => 5,
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($response)) {
            return false;
        }

        $data = json_decode($response, true);
        if (empty($data['hosts']) || empty($data['path'])) {
            return false;
        }

        $imageUrl = 'https://' . $data['hosts'][0] . $data['path'];

        // Fetch and stream the image
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $imageUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
        ]);
        $imageData = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || empty($imageData)) {
            return false;
        }

        if (empty($contentType) || strpos($contentType, 'image') === false) {
            $contentType = 'image/jpeg';
        }

        header('Content-Type: ' . $contentType);
        header('Content-Length: ' . strlen($imageData));
        header('Cache-Control: public, max-age=86400');
        header('X-Image-Source: pcloud');
        echo $imageData;
        return true;

    } catch (Exception $e) {
        error_log('pCloud error: ' . $e->getMessage());
        return false;
    }
}

// Main logic
$served = false;

// Try primary source first
if ($primarySource === 'local') {
    // Try local first
    $served = serveLocalFile($localFile, $type);

    // Try pCloud as fallback if enabled
    if (!$served && $fallbackEnabled && $pcloudEnabled) {
        $served = tryPCloud($fileId);
    }
} else {
    // Try pCloud first
    if ($pcloudEnabled) {
        $served = tryPCloud($fileId);
    }

    // Try local as fallback if enabled
    if (!$served && $fallbackEnabled) {
        $served = serveLocalFile($localFile, $type);
    }
}

// If nothing worked, serve placeholder
if (!$served) {
    servePlaceholder($type);
}
