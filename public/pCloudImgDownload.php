<?php
/**
 * pCloud Image Download Handler - Local Only Mode
 *
 * Serves images from local storage only.
 * Looks up local filename from database using pCloud file ID.
 */

// Get parameters
$fileId = $_GET['fileID'] ?? $_GET['fileId'] ?? null;
$localFile = $_GET['local'] ?? '';
$type = $_GET['type'] ?? 'track';

// Base path
$basePath = dirname(__DIR__);

// Load Laravel's database config
require $basePath . '/vendor/autoload.php';

// Simple database connection using PDO
function getDbConnection($basePath) {
    // Read .env file
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

    $host = $env['DB_HOST'] ?? '127.0.0.1';
    $port = $env['DB_PORT'] ?? '3306';
    $database = $env['DB_DATABASE'] ?? 'digiwaxx';
    $username = $env['DB_USERNAME'] ?? 'root';
    $password = $env['DB_PASSWORD'] ?? '';

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
function getLocalFilename($basePath, $fileId) {
    if (empty($fileId) || !is_numeric($fileId)) {
        return null;
    }

    $db = getDbConnection($basePath);
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

// Main logic
// If local filename provided, use it
if (empty($localFile) && !empty($fileId)) {
    // Look up local filename from database using pCloud file ID
    $localFile = getLocalFilename($basePath, $fileId);
}

// Try local file, then placeholder
if (!serveLocalFile($basePath, $localFile, $type)) {
    servePlaceholder($basePath, $type);
}
