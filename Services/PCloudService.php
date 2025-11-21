<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Rbaskam\LaravelPCloud\App as PCloudApp;
use Rbaskam\LaravelPCloud\File as PCloudFile;
use Rbaskam\LaravelPCloud\Folder as PCloudFolder;
use Exception;

/**
 * PCloud Service
 *
 * Centralized service for all pCloud file operations.
 * Handles uploads, downloads, streaming, and file management.
 *
 * Features:
 * - Automatic retry on failures
 * - URL caching for performance
 * - Comprehensive error handling
 * - Logging for debugging
 * - File validation
 *
 * @package App\Services
 */
class PCloudService
{
    protected $pCloudApp;
    protected $pCloudFile;
    protected $pCloudFolder;
    protected $config;

    /**
     * Initialize pCloud connection
     */
    public function __construct()
    {
        $this->config = config('laravel-pcloud');

        // Initialize pCloud app
        $this->pCloudApp = new PCloudApp();
        $this->pCloudApp->setAccessToken($this->config['access_token']);
        $this->pCloudApp->setLocationId($this->config['location_id']);

        // Initialize file and folder services
        $this->pCloudFile = new PCloudFile($this->pCloudApp);
        $this->pCloudFolder = new PCloudFolder($this->pCloudApp);

        $this->log('PCloudService initialized', ['location_id' => $this->config['location_id']]);
    }

    /**
     * Upload audio file to pCloud
     *
     * @param \Illuminate\Http\UploadedFile $file File to upload
     * @param int|null $trackId Track ID (creates subfolder)
     * @param string $type 'admin' or 'client'
     * @return array File info: ['file_id' => int, 'folder_id' => int, 'filename' => string, 'size' => int]
     * @throws Exception
     */
    public function uploadAudio($file, $trackId = null, $type = 'admin')
    {
        $this->validateAudioFile($file);

        $folderPath = $type === 'client' ? 'client_audio_path' : 'audio_path';
        $parentFolderId = $this->config[$folderPath];

        if (!$parentFolderId) {
            throw new Exception("pCloud folder not configured for type: {$type}");
        }

        // Create track-specific subfolder if trackId provided
        if ($trackId) {
            $folderName = "track_{$trackId}";
            $folderId = $this->createFolderIfNotExists($folderName, $parentFolderId);
        } else {
            $folderId = $parentFolderId;
        }

        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);

        // Upload with retry
        return $this->retryOperation(function() use ($file, $folderId, $filename) {
            $this->log('Uploading audio file', [
                'filename' => $filename,
                'folder_id' => $folderId,
                'size' => $file->getSize()
            ]);

            $uploadData = $this->pCloudFile->upload($file->getPathname(), $folderId, $filename);

            return [
                'file_id' => $uploadData['metadata'][0]['fileid'],
                'folder_id' => $folderId,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName()
            ];
        }, 'audio upload');
    }

    /**
     * Upload image file to pCloud
     *
     * @param \Illuminate\Http\UploadedFile $file File to upload
     * @param int|null $trackId Track ID (creates subfolder)
     * @param string $type 'image', 'logo', 'admin', 'client'
     * @return array File info
     * @throws Exception
     */
    public function uploadImage($file, $trackId = null, $type = 'image')
    {
        $this->validateImageFile($file);

        // Determine folder path based on type
        $folderConfig = [
            'image' => 'image_path',
            'logo' => 'logo_path',
            'admin' => 'audio_path',
            'client' => 'client_audio_path'
        ];

        $folderPath = $folderConfig[$type] ?? 'image_path';
        $parentFolderId = $this->config[$folderPath];

        if (!$parentFolderId) {
            throw new Exception("pCloud folder not configured for type: {$type}");
        }

        // Create track-specific subfolder if trackId provided
        if ($trackId) {
            $folderName = "track_{$trackId}";
            $folderId = $this->createFolderIfNotExists($folderName, $parentFolderId);
        } else {
            $folderId = $parentFolderId;
        }

        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);

        // Upload with retry
        return $this->retryOperation(function() use ($file, $folderId, $filename) {
            $this->log('Uploading image file', [
                'filename' => $filename,
                'folder_id' => $folderId,
                'size' => $file->getSize()
            ]);

            $uploadData = $this->pCloudFile->upload($file->getPathname(), $folderId, $filename);

            return [
                'file_id' => $uploadData['metadata'][0]['fileid'],
                'folder_id' => $folderId,
                'filename' => $filename,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'original_name' => $file->getClientOriginalName()
            ];
        }, 'image upload');
    }

    /**
     * Delete file from pCloud
     *
     * @param int $fileId pCloud file ID
     * @return bool Success
     * @throws Exception
     */
    public function deleteFile($fileId)
    {
        return $this->retryOperation(function() use ($fileId) {
            $this->log('Deleting file', ['file_id' => $fileId]);

            $result = $this->pCloudFile->delete($fileId);

            // Clear cache
            $this->clearFileCache($fileId);

            return true;
        }, 'file deletion');
    }

    /**
     * Get streaming URL for audio file
     *
     * @param int $fileId pCloud file ID
     * @param bool $cache Whether to cache the URL
     * @return string Streaming URL
     * @throws Exception
     */
    public function getStreamingUrl($fileId, $cache = true)
    {
        $cacheKey = "pcloud_stream_url_{$fileId}";

        if ($cache && $this->config['cache_enabled']) {
            return Cache::remember($cacheKey, $this->config['cache_duration'], function() use ($fileId) {
                return $this->fetchStreamingUrl($fileId);
            });
        }

        return $this->fetchStreamingUrl($fileId);
    }

    /**
     * Get download link for file
     *
     * @param int $fileId pCloud file ID
     * @param bool $cache Whether to cache the URL
     * @return string Download URL
     * @throws Exception
     */
    public function getDownloadUrl($fileId, $cache = true)
    {
        $cacheKey = "pcloud_download_url_{$fileId}";

        if ($cache && $this->config['cache_enabled']) {
            return Cache::remember($cacheKey, $this->config['cache_duration'], function() use ($fileId) {
                return $this->fetchDownloadUrl($fileId);
            });
        }

        return $this->fetchDownloadUrl($fileId);
    }

    /**
     * Get file metadata
     *
     * @param int $fileId pCloud file ID
     * @return array File metadata
     * @throws Exception
     */
    public function getFileInfo($fileId)
    {
        return $this->retryOperation(function() use ($fileId) {
            $this->log('Fetching file info', ['file_id' => $fileId]);

            $info = $this->pCloudFile->getInfo($fileId);

            return [
                'file_id' => $info['metadata']['fileid'],
                'name' => $info['metadata']['name'],
                'size' => $info['metadata']['size'],
                'created' => $info['metadata']['created'],
                'modified' => $info['metadata']['modified'],
                'is_deleted' => $info['metadata']['isdeleted'] ?? false
            ];
        }, 'file info fetch');
    }

    /**
     * Move file to different folder
     *
     * @param int $fileId File to move
     * @param int $toFolderId Destination folder
     * @return bool Success
     * @throws Exception
     */
    public function moveFile($fileId, $toFolderId)
    {
        return $this->retryOperation(function() use ($fileId, $toFolderId) {
            $this->log('Moving file', [
                'file_id' => $fileId,
                'to_folder' => $toFolderId
            ]);

            // pCloud API: rename can also move
            $this->pCloudFile->move($fileId, $toFolderId);

            // Clear cache
            $this->clearFileCache($fileId);

            return true;
        }, 'file move');
    }

    /**
     * Create folder if it doesn't exist
     *
     * @param string $folderName Folder name
     * @param int $parentId Parent folder ID
     * @return int Folder ID
     * @throws Exception
     */
    public function createFolderIfNotExists($folderName, $parentId)
    {
        try {
            $this->log('Creating folder if not exists', [
                'name' => $folderName,
                'parent_id' => $parentId
            ]);

            $folderData = $this->pCloudFolder->createFolderIfNotExists($folderName, $parentId);

            return $folderData['metadata']['folderid'];
        } catch (Exception $e) {
            $this->log('Folder creation failed', [
                'name' => $folderName,
                'parent_id' => $parentId,
                'error' => $e->getMessage()
            ], 'error');

            throw $e;
        }
    }

    /**
     * Validate audio file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @throws Exception
     */
    protected function validateAudioFile($file)
    {
        $config = $this->config['validation']['audio'];

        // Check file exists and is valid
        if (!$file->isValid()) {
            throw new Exception('Invalid file upload');
        }

        // Check file size
        if ($file->getSize() > $config['max_size']) {
            $maxMB = $config['max_size'] / 1048576;
            throw new Exception("Audio file exceeds maximum size of {$maxMB}MB");
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $config['allowed_mimes'])) {
            throw new Exception("Invalid audio file type: {$mimeType}");
        }

        // Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $config['allowed_extensions'])) {
            throw new Exception("Invalid audio file extension: {$extension}");
        }
    }

    /**
     * Validate image file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @throws Exception
     */
    protected function validateImageFile($file)
    {
        $config = $this->config['validation']['image'];

        // Check file exists and is valid
        if (!$file->isValid()) {
            throw new Exception('Invalid file upload');
        }

        // Check file size
        if ($file->getSize() > $config['max_size']) {
            $maxMB = $config['max_size'] / 1048576;
            throw new Exception("Image file exceeds maximum size of {$maxMB}MB");
        }

        // Check MIME type
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $config['allowed_mimes'])) {
            throw new Exception("Invalid image file type: {$mimeType}");
        }

        // Check extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $config['allowed_extensions'])) {
            throw new Exception("Invalid image file extension: {$extension}");
        }
    }

    /**
     * Generate unique filename
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return string
     */
    protected function generateUniqueFilename($file)
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Sanitize filename
        $sanitized = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $originalName);
        $sanitized = substr($sanitized, 0, 100); // Limit length

        // Add unique ID and timestamp
        $unique = uniqid() . '_' . time();

        return "{$sanitized}_{$unique}.{$extension}";
    }

    /**
     * Fetch streaming URL from pCloud API
     *
     * @param int $fileId
     * @return string
     * @throws Exception
     */
    protected function fetchStreamingUrl($fileId)
    {
        return $this->retryOperation(function() use ($fileId) {
            $link = $this->pCloudFile->getLink($fileId);

            // pCloud returns array with 'link' key
            if (isset($link['link'])) {
                return $link['link'];
            }

            throw new Exception('Failed to get streaming URL from pCloud');
        }, 'streaming URL fetch');
    }

    /**
     * Fetch download URL from pCloud API
     *
     * @param int $fileId
     * @return string
     * @throws Exception
     */
    protected function fetchDownloadUrl($fileId)
    {
        return $this->retryOperation(function() use ($fileId) {
            $link = $this->pCloudFile->getLink($fileId);

            if (isset($link['link'])) {
                return $link['link'];
            }

            throw new Exception('Failed to get download URL from pCloud');
        }, 'download URL fetch');
    }

    /**
     * Retry operation with exponential backoff
     *
     * @param callable $operation
     * @param string $operationName For logging
     * @return mixed
     * @throws Exception
     */
    protected function retryOperation(callable $operation, $operationName = 'operation')
    {
        $attempts = $this->config['retry_attempts'];
        $delay = $this->config['retry_delay'];

        for ($i = 1; $i <= $attempts; $i++) {
            try {
                return $operation();
            } catch (Exception $e) {
                $this->log("Retry attempt {$i}/{$attempts} for {$operationName}", [
                    'error' => $e->getMessage()
                ], 'warning');

                if ($i === $attempts) {
                    // Final attempt failed
                    $this->log("{$operationName} failed after {$attempts} attempts", [
                        'error' => $e->getMessage()
                    ], 'error');

                    throw new Exception("{$operationName} failed: " . $e->getMessage());
                }

                // Exponential backoff
                usleep($delay * 1000 * $i);
            }
        }
    }

    /**
     * Clear cached URLs for a file
     *
     * @param int $fileId
     */
    protected function clearFileCache($fileId)
    {
        if ($this->config['cache_enabled']) {
            Cache::forget("pcloud_stream_url_{$fileId}");
            Cache::forget("pcloud_download_url_{$fileId}");
        }
    }

    /**
     * Log message
     *
     * @param string $message
     * @param array $context
     * @param string $level
     */
    protected function log($message, $context = [], $level = 'info')
    {
        if ($level === 'error' || $this->config['log_api_calls']) {
            Log::channel('stack')->{$level}("[PCloudService] {$message}", $context);
        }
    }

    /**
     * Get pCloud app instance (for advanced operations)
     *
     * @return PCloudApp
     */
    public function getApp()
    {
        return $this->pCloudApp;
    }

    /**
     * Get pCloud file instance (for advanced operations)
     *
     * @return PCloudFile
     */
    public function getFile()
    {
        return $this->pCloudFile;
    }

    /**
     * Get pCloud folder instance (for advanced operations)
     *
     * @return PCloudFolder
     */
    public function getFolder()
    {
        return $this->pCloudFolder;
    }
}
