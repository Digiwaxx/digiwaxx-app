<?php
/**
 * pCloud Image Download Handler - With pCloud Support
 *
 * Serves images from pCloud or local storage based on configuration.
 * Supports automatic fallback between sources.
 */

// Get parameters
$fileId = $_GET['fileID'] ?? $_GET['fileId'] ?? null;
$localFile = $_GET['local'] ?? '';
$type = $_GET['type'] ?? 'track';

// Base path - use the public folder (where this file is located)
$publicPath = __DIR__;
$basePath = dirname(__DIR__);

// Load Laravel's database config
require $basePath . '/vendor/autoload.php';

// Read .env configuration
function getEnvConfig($basePath) {
    $envFile = $basePath . '/.env';
    $env = [];
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                $env[trim($key)] = trim($value, '"\'');
            }
        }
    }
    return $env;
}

$config = getEnvConfig($basePath);
$primarySource = $config['IMAGE_PRIMARY_SOURCE'] ?? 'pcloud';
$enableFallback = ($config['IMAGE_ENABLE_FALLBACK'] ?? 'true') === 'true';
$pcloudEnabled = ($config['PCLOUD_ENABLED'] ?? 'true') === 'true';

// Simple database connection using PDO
function getDbConnection($config) {
    $host = $config['DB_HOST'] ?? '127.0.0.1';
    $port = $config['DB_PORT'] ?? '3306';
    $database = $config['DB_DATABASE'] ?? 'digiwaxx';
    $username = $config['DB_USERNAME'] ?? 'root';
    $password = $config['DB_PASSWORD'] ?? '';

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    } catch (PDOException $e) {
        return null;
    }
}

// Look up local filename from pCloud file ID
function getLocalFilename($config, $fileId) {
    if (empty($fileId) || !is_numeric($fileId)) {
        return null;
    }

    $db = getDbConnection($config);
    if (!$db) {
        return null;
    }

    // Search in tracks table
    $stmt = $db->prepare("SELECT imgpage FROM tracks WHERE pCloudFileID = ? LIMIT 1");
    $stmt->execute([$fileId]);
    $result = $stmt->fetch();
    if ($result && !empty($result['imgpage'])) {
        return $result['imgpage'];
    }

    // Search in tracks_submitted table
    $stmt = $db->prepare("SELECT imgpage FROM tracks_submitted WHERE pCloudFileID = ? LIMIT 1");
    $stmt->execute([$fileId]);
    $result = $stmt->fetch();
    if ($result && !empty($result['imgpage'])) {
        return $result['imgpage'];
    }

    // Search in albums table
    $stmt = $db->prepare("SELECT album_page_image FROM albums WHERE pCloudFileID_album = ? LIMIT 1");
    $stmt->execute([$fileId]);
    $result = $stmt->fetch();
    if ($result && !empty($result['album_page_image'])) {
        return $result['album_page_image'];
    }

    // Search in website_logo table
    $stmt = $db->prepare("SELECT logo FROM website_logo WHERE pCloudFileID_logo = ? LIMIT 1");
    $stmt->execute([$fileId]);
    $result = $stmt->fetch();
    if ($result && !empty($result['logo'])) {
        return $result['logo'];
    }

    return null;
}

// Try to fetch image from pCloud
function tryPCloud($config, $fileId) {
    if (empty($fileId) || !is_numeric($fileId)) {
        return false;
    }

    $accessToken = $config['PCLOUD_ACCESS_TOKEN'] ?? '';
    if (empty($accessToken) || $accessToken === 'your_pcloud_access_token') {
        return false;
    }

    try {
        $locationId = $config['PCLOUD_LOCATION_ID'] ?? 1;
        $apiBase = $locationId == 2 ? 'https://eapi.pcloud.com' : 'https://api.pcloud.com';

        // Get file link from pCloud
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
        return false;
    }
}

// Define local paths based on type
function getLocalPaths($type) {
    switch ($type) {
        case 'logo':
            return ['../public/Logos', 'Logos', 'images'];
        case 'track':
        case 'album':
            return ['../ImagesUp', 'images'];
        case 'banner':
            return ['images', '../public/images'];
        default:
            return ['../ImagesUp', 'images'];
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
function serveLocalFile($publicPath, $filename, $type) {
    if (empty($filename)) {
        return false;
    }

    $paths = getLocalPaths($type);

    foreach ($paths as $path) {
        $fullPath = $publicPath . '/' . $path . '/' . $filename;
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
function servePlaceholder($publicPath, $type) {
    $placeholders = [
        'track' => 'images/noimage-avl.jpg',
        'album' => 'images/noimage-avl.jpg',
        'logo' => 'images/logo.png',
        'banner' => 'images/banner-default.jpg',
    ];

    $placeholder = $placeholders[$type] ?? $placeholders['track'];
    $fullPath = $publicPath . '/' . $placeholder;

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

// Main logic
$served = false;

// Look up local filename if not provided
if (empty($localFile) && !empty($fileId)) {
    $localFile = getLocalFilename($config, $fileId);
}

// Try primary source first
if ($primarySource === 'pcloud' && $pcloudEnabled) {
    // Try pCloud first
    $served = tryPCloud($config, $fileId);

    // Fallback to local if enabled
    if (!$served && $enableFallback) {
        $served = serveLocalFile($publicPath, $localFile, $type);
    }
} else {
    // Try local first
    $served = serveLocalFile($publicPath, $localFile, $type);

    // Fallback to pCloud if enabled
    if (!$served && $enableFallback && $pcloudEnabled) {
        $served = tryPCloud($config, $fileId);
    }
}

// Serve placeholder if nothing worked
if (!$served) {
    servePlaceholder($publicPath, $type);
}
