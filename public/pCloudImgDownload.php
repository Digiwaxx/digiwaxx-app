<?php
/**
 * pCloud Image Download Handler - Local Only Mode
 *
 * Serves images from local storage only.
 * pCloud is disabled due to revoked access token.
 */

// Get parameters
$fileId = $_GET['fileID'] ?? $_GET['fileId'] ?? null;
$localFile = $_GET['local'] ?? '';
$type = $_GET['type'] ?? 'track';

// Base path
$basePath = dirname(__DIR__);

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

// Get MIME type
function getMimeType($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $mimeTypes = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
    ];
    return $mimeTypes[$ext] ?? 'image/jpeg';
}

// Try to serve local file
function serveLocalFile($basePath, $filename, $type) {
    if (empty($filename)) {
        return false;
    }

    $paths = getLocalPaths($type);

    foreach ($paths as $path) {
        $fullPath = $basePath . '/' . $path . '/' . $filename;
        if (file_exists($fullPath) && is_file($fullPath)) {
            header('Content-Type: ' . getMimeType($filename));
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
function servePlaceholder($basePath, $type) {
    $placeholders = [
        'track' => 'public/images/noimage-avl.jpg',
        'album' => 'public/images/noimage-avl.jpg',
        'logo' => 'public/images/logo.png',
        'banner' => 'public/images/banner-default.jpg',
    ];

    $placeholder = $placeholders[$type] ?? $placeholders['track'];
    $fullPath = $basePath . '/' . $placeholder;

    if (file_exists($fullPath)) {
        header('Content-Type: ' . getMimeType($placeholder));
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

// Main: Try local file, then placeholder
if (!serveLocalFile($basePath, $localFile, $type)) {
    servePlaceholder($basePath, $type);
}
