# pCloud Integration Setup Guide

## Overview

This application uses **pCloud.com** for cloud storage of all audio files and images. This guide explains how the integration works and how to configure it.

**Benefits of pCloud Storage:**
- Global CDN for fast file delivery
- Automatic backups and redundancy
- Reduced server storage costs
- Scalable storage (add more space as needed)
- Reduced bandwidth usage on main server

---

## Current Integration Status

### ✅ What's Already Integrated

- **Admin Audio Uploads**: Track uploads from admin panel already use pCloud
- **Client Audio Submissions**: Client track submissions stored in pCloud
- **Image Storage**: Track artwork and logos stored in pCloud
- **Audio Streaming**: Direct streaming URLs from pCloud CDN
- **File Deletion**: Automatic cleanup when tracks are deleted
- **Database Integration**: Tracks store pCloud file IDs for reference

### 🔧 Recent Enhancements

- **Centralized Service**: New `PCloudService` class for all pCloud operations
- **Automatic Retry**: Failed uploads retry 3 times with exponential backoff
- **URL Caching**: Streaming URLs cached for 1 hour (reduces API calls)
- **File Validation**: MIME type, size, and extension validation
- **Error Handling**: Comprehensive logging and error messages
- **Unique Filenames**: Automatic sanitization and collision prevention

---

## Configuration

### Environment Variables

All pCloud credentials are stored in `.env` (not in git repo for security).

**Required Variables:**

```env
# pCloud Storage Configuration
PCLOUD_ACCESS_TOKEN=your_pcloud_access_token_here
PCLOUD_LOCATION_ID=1  # 1=US, 2=EU

# Folder IDs (get these from pCloud)
PCLOUD_AUDIO_PATH=123456789
PCLOUD_CLIENT_AUDIO_PATH=987654321
PCLOUD_IMAGE_PATH=456789123
PCLOUD_LOGO_PATH=789123456
```

**Optional Performance Settings:**

```env
# Caching (recommended for production)
PCLOUD_CACHE_ENABLED=true
PCLOUD_CACHE_DURATION=3600  # 1 hour

# Timeouts (in seconds)
PCLOUD_UPLOAD_TIMEOUT=300  # 5 minutes
PCLOUD_DOWNLOAD_TIMEOUT=120  # 2 minutes

# Retry Logic
PCLOUD_RETRY_ATTEMPTS=3
PCLOUD_RETRY_DELAY=1000  # milliseconds

# Debugging
PCLOUD_LOG_API_CALLS=false  # Set true for debugging
PCLOUD_LOG_FILE_OPERATIONS=true
```

### Configuration File

Settings are defined in `config/laravel-pcloud.php`:

- **Validation Rules**: File size limits, allowed MIME types, allowed extensions
- **API Endpoints**: US vs EU endpoints based on location_id
- **Cache Settings**: Duration and enable/disable
- **Retry Logic**: Attempts and delays

---

## How to Get pCloud Credentials

### 1. Sign Up for pCloud

1. Go to **https://www.pcloud.com**
2. Sign up for an account (Premium 500GB or Premium Plus 2TB recommended)
3. Choose your data region: **US** or **EU**
   - US: Faster for North American users
   - EU: GDPR compliant, faster for European users

### 2. Generate API Access Token

**Method 1: Using pCloud Web Interface**

1. Log in to pCloud web interface
2. Go to **Settings** → **My Account** → **Security**
3. Scroll to **App Passwords** or **Access Tokens**
4. Click **Create App Password**
5. Name it: "Digiwaxx Application"
6. Copy the generated token (save it securely - you won't see it again!)

**Method 2: Using pCloud API (if web method not available)**

```bash
# Replace YOUR_EMAIL and YOUR_PASSWORD
curl "https://api.pcloud.com/userinfo?getauth=1&logout=1&username=YOUR_EMAIL&password=YOUR_PASSWORD"
```

Response will include `"auth": "YOUR_ACCESS_TOKEN"`

### 3. Get Folder IDs

You need to create folders in pCloud and get their folder IDs.

**Step 1: Create Folders in pCloud**

Using the web interface, create this folder structure:

```
/Digiwaxx/
├── audio/              (for approved admin uploads)
├── client_audio/       (for pending client submissions)
├── images/             (for track artwork)
└── logos/              (for artist/label logos)
```

**Step 2: Get Folder IDs Using API**

```bash
# List all folders (replace YOUR_ACCESS_TOKEN)
curl "https://api.pcloud.com/listfolder?access_token=YOUR_ACCESS_TOKEN&folderid=0"
```

Look for your folders in the JSON response. Each folder has a `"folderid"` field.

**Example Response:**

```json
{
  "metadata": {
    "contents": [
      {
        "name": "Digiwaxx",
        "folderid": 123456789,
        "contents": [
          {"name": "audio", "folderid": 111111111},
          {"name": "client_audio", "folderid": 222222222},
          {"name": "images", "folderid": 333333333},
          {"name": "logos", "folderid": 444444444}
        ]
      }
    ]
  }
}
```

**Alternative: Use pCloud Web Interface**

1. Right-click on a folder → **Share**
2. Create a public link
3. The URL will look like: `https://my.pcloud.com/#page=publink&code=XXXXX`
4. Use the API to resolve the public link to a folder ID:

```bash
curl "https://api.pcloud.com/getfolderpublink?code=XXXXX&access_token=YOUR_TOKEN"
```

### 4. Determine Your Location ID

- If you chose **US** when signing up: `PCLOUD_LOCATION_ID=1`
- If you chose **EU** when signing up: `PCLOUD_LOCATION_ID=2`

**To verify your location:**

```bash
curl "https://api.pcloud.com/userinfo?access_token=YOUR_ACCESS_TOKEN"
```

Look for `"region"` in the response.

### 5. Update .env File

Once you have all credentials, update your `.env`:

```env
PCLOUD_ACCESS_TOKEN=eXaMpLeToKeN123456789
PCLOUD_LOCATION_ID=1

PCLOUD_AUDIO_PATH=111111111
PCLOUD_CLIENT_AUDIO_PATH=222222222
PCLOUD_IMAGE_PATH=333333333
PCLOUD_LOGO_PATH=444444444
```

---

## Using the PCloudService

### Basic Usage

```php
use App\Services\PCloudService;

class YourController extends Controller
{
    protected $pCloud;

    public function __construct(PCloudService $pCloud)
    {
        $this->pCloud = $pCloud;
    }

    public function uploadTrack(Request $request)
    {
        $audioFile = $request->file('audio');
        $trackId = 123;

        // Upload audio file
        $fileInfo = $this->pCloud->uploadAudio($audioFile, $trackId, 'admin');

        // Store in database
        DB::table('tracks')->insert([
            'pCloudFileID' => $fileInfo['file_id'],
            'pCloudParentFolderID' => $fileInfo['folder_id'],
            'filename' => $fileInfo['filename'],
            'filesize' => $fileInfo['size']
        ]);
    }
}
```

### Available Methods

**Upload Files:**

```php
// Upload audio file (admin or client)
$pCloud->uploadAudio($file, $trackId = null, $type = 'admin');
// Returns: ['file_id' => int, 'folder_id' => int, 'filename' => string, 'size' => int, 'mime_type' => string]

// Upload image file
$pCloud->uploadImage($file, $trackId = null, $type = 'image');
// Types: 'image', 'logo', 'admin', 'client'
```

**Get URLs:**

```php
// Get streaming URL (cached for 1 hour)
$streamUrl = $pCloud->getStreamingUrl($fileId);

// Get download URL
$downloadUrl = $pCloud->getDownloadUrl($fileId);
```

**File Management:**

```php
// Delete file
$pCloud->deleteFile($fileId);

// Get file info
$info = $pCloud->getFileInfo($fileId);
// Returns: ['file_id', 'name', 'size', 'created', 'modified', 'is_deleted']

// Move file to different folder
$pCloud->moveFile($fileId, $toFolderId);

// Create folder if not exists
$folderId = $pCloud->createFolderIfNotExists('folder_name', $parentFolderId);
```

### Validation Rules

**Audio Files:**
- **Max Size**: 50MB
- **Allowed MIME Types**: audio/mpeg, audio/mp3, audio/wav, audio/x-wav, audio/flac, audio/aac, audio/ogg, audio/x-m4a, audio/x-ms-wma
- **Allowed Extensions**: mp3, wav, flac, aac, ogg, m4a, wma

**Image Files:**
- **Max Size**: 5MB
- **Allowed MIME Types**: image/jpeg, image/jpg, image/png, image/gif, image/webp
- **Allowed Extensions**: jpg, jpeg, png, gif, webp

Validation happens automatically in `uploadAudio()` and `uploadImage()`.

### Error Handling

The service automatically retries failed operations 3 times with exponential backoff:

```php
try {
    $fileInfo = $pCloud->uploadAudio($file, $trackId);
} catch (Exception $e) {
    // Upload failed after 3 retries
    Log::error('pCloud upload failed: ' . $e->getMessage());
    return back()->with('error', 'File upload failed. Please try again.');
}
```

---

## Migration: Moving Local Files to pCloud

If you have existing files stored locally, use the migration command:

```bash
# Migrate all local files to pCloud
php artisan pcloud:migrate-local-files

# Dry run (see what would be migrated)
php artisan pcloud:migrate-local-files --dry-run

# Keep local files after migration (don't delete)
php artisan pcloud:migrate-local-files --keep-local
```

The command will:
1. Find all tracks with local file paths in `tracks_mp3s.location`
2. Upload each file to pCloud
3. Update database with pCloud file IDs
4. Optionally delete local files

---

## Database Schema

### tracks

| Column | Type | Description |
|--------|------|-------------|
| pCloudFileID | bigint | pCloud file ID for main audio file |
| pCloudParentFolderID | bigint | pCloud folder ID where file is stored |
| imgpage | varchar | pCloud file ID for track artwork |

### tracks_submitted

| Column | Type | Description |
|--------|------|-------------|
| pCloudFileID | bigint | Main audio file ID |
| pCloudParentFolderID | bigint | Folder ID (client_audio folder) |
| amr1-amr6 | bigint | pCloud file IDs for up to 6 versions |
| title1-title6 | varchar | Names for each version |
| version1-version6 | varchar | Version types (Clean, Dirty, Radio, etc.) |

### tracks_mp3s

| Column | Type | Description |
|--------|------|-------------|
| location | varchar | pCloud file ID (numeric) or local path (string) |

**Migration Note**: After migrating, all `location` values should be numeric pCloud file IDs.

---

## Folder Structure in pCloud

### Recommended Structure

```
/Digiwaxx/
├── audio/                          (PCLOUD_AUDIO_PATH)
│   ├── track_123/                  (auto-created per track)
│   │   ├── Song_Title_abc123.mp3
│   │   └── Song_Title_def456.wav
│   └── track_124/
│       └── Another_Song_xyz789.mp3
│
├── client_audio/                   (PCLOUD_CLIENT_AUDIO_PATH)
│   ├── track_456/                  (pending submissions)
│   │   ├── Client_Song_111.mp3
│   │   ├── Client_Song_222.mp3    (version 2)
│   │   └── Client_Song_333.mp3    (version 3)
│   └── track_457/
│       └── Another_Client_Song.mp3
│
├── images/                         (PCLOUD_IMAGE_PATH)
│   ├── track_123/
│   │   └── artwork_abc123.jpg
│   └── track_124/
│       └── artwork_def456.png
│
└── logos/                          (PCLOUD_LOGO_PATH)
    ├── artist_logo_1.png
    └── label_logo_2.jpg
```

**Automatic Subfolder Creation:**
- When uploading with `$trackId`, PCloudService automatically creates `track_{$trackId}` subfolder
- Keeps files organized by track
- Multiple versions of same track stored together

---

## Testing pCloud Connection

### Test Configuration

```bash
php artisan tinker
```

```php
// Test connection
$pCloud = app(\App\Services\PCloudService::class);

// Get folder info (verify credentials work)
$folderInfo = $pCloud->getFolder()->getInfo(config('laravel-pcloud.audio_path'));
print_r($folderInfo);

// Test if access token works
$app = $pCloud->getApp();
$app->setAccessToken(config('laravel-pcloud.access_token'));
$app->setLocationId(config('laravel-pcloud.location_id'));
// If no exception, credentials are valid!
```

### Test File Upload

```php
// Create a test file
$testFile = \Illuminate\Http\UploadedFile::fake()->create('test.mp3', 1000, 'audio/mpeg');

// Upload
$result = $pCloud->uploadAudio($testFile, null, 'admin');
print_r($result);

// Clean up
$pCloud->deleteFile($result['file_id']);
```

---

## Troubleshooting

### "Invalid access token" Error

**Cause**: Access token is incorrect or expired.

**Fix**:
1. Regenerate access token in pCloud settings
2. Update `PCLOUD_ACCESS_TOKEN` in `.env`
3. Clear config cache: `php artisan config:clear`

### "Folder not found" Error

**Cause**: Folder ID is incorrect.

**Fix**:
1. Verify folder IDs using API: `curl "https://api.pcloud.com/listfolder?access_token=TOKEN&folderid=0"`
2. Update folder IDs in `.env`
3. Clear config cache: `php artisan config:clear`

### "API endpoint not responding" Error

**Cause**: Wrong location_id (using EU endpoint when account is US, or vice versa).

**Fix**:
1. Verify your account region in pCloud settings
2. Update `PCLOUD_LOCATION_ID` (1=US, 2=EU)
3. Clear config cache: `php artisan config:clear`

### Upload Timeout

**Cause**: Large files or slow connection.

**Fix**:
1. Increase timeout in `.env`: `PCLOUD_UPLOAD_TIMEOUT=600` (10 minutes)
2. Clear config cache: `php artisan config:clear`

### "File too large" Error

**Cause**: File exceeds validation limits.

**Fix**:
1. Check file size limits in `config/laravel-pcloud.php`
2. Increase `max_size` if needed (default: 50MB for audio, 5MB for images)
3. Clear config cache: `php artisan config:clear`

### Cache Issues (URLs not updating)

**Cause**: Cached URLs are stale.

**Fix**:
```bash
# Clear all cache
php artisan cache:clear

# Or disable caching temporarily
PCLOUD_CACHE_ENABLED=false
```

---

## Performance Optimization

### URL Caching

Streaming and download URLs are cached for 1 hour by default. This reduces API calls by ~90%.

**Adjust cache duration:**

```env
PCLOUD_CACHE_DURATION=7200  # 2 hours
```

**Disable for development:**

```env
PCLOUD_CACHE_ENABLED=false
```

### Retry Logic

Failed uploads automatically retry 3 times with exponential backoff:
- 1st retry: Wait 1 second
- 2nd retry: Wait 2 seconds
- 3rd retry: Wait 3 seconds

**Adjust retry settings:**

```env
PCLOUD_RETRY_ATTEMPTS=5      # More retries
PCLOUD_RETRY_DELAY=2000      # Longer delay (milliseconds)
```

### Logging

Enable detailed logging for debugging:

```env
PCLOUD_LOG_API_CALLS=true
PCLOUD_LOG_FILE_OPERATIONS=true
```

Logs are written to `storage/logs/laravel.log` with prefix `[PCloudService]`.

---

## Security Best Practices

### 1. Never Commit Credentials

**DO NOT** add credentials to git:
- `.env` is in `.gitignore` (keep it there)
- `config/laravel-pcloud.php` uses `env()` (no hardcoded tokens)

### 2. Regenerate Tokens Periodically

Regenerate access tokens every 6-12 months for security.

### 3. Use Environment-Specific Tokens

- **Development**: Use separate pCloud account or folder
- **Production**: Use production account with proper backup

### 4. Validate All Uploads

The service validates:
- MIME type (prevents uploading .exe as .mp3)
- File extension (whitelist only)
- File size (prevents abuse)

### 5. Monitor Usage

Check pCloud dashboard regularly:
- Storage usage
- Bandwidth usage
- API call volume

---

## API Rate Limits

pCloud has rate limits:
- **500 requests per minute** per access token
- **10,000 requests per day** per access token

The PCloudService caching reduces API calls significantly. For high-traffic sites, consider:
1. Increase cache duration
2. Use multiple access tokens (load balancing)
3. Contact pCloud for enterprise limits

---

## Backup Strategy

### pCloud Advantages

- **Automatic backups**: pCloud provides redundancy
- **Version history**: pCloud keeps file versions (Premium Plus)
- **Trash**: Deleted files stay in trash for 30 days

### Additional Recommendations

1. **Database backups**: pCloud doesn't backup your database (only files)
   - Back up MySQL regularly
   - Store file ID mappings in database backups

2. **Critical files**: Download critical files periodically as offline backup

3. **Migration plan**: Document folder structure for easy migration if needed

---

## Support

### pCloud Support

- **Email**: support@pcloud.com
- **Help Center**: https://www.pcloud.com/help
- **API Docs**: https://docs.pcloud.com/

### Package Documentation

- **rbaskam/laravel-pcloud**: https://github.com/rbaskam/laravel-pcloud

### Internal Documentation

- **PCloudService Source**: `app/Services/PCloudService.php`
- **Configuration**: `config/laravel-pcloud.php`
- **Migration Guide**: This document

---

## Next Steps

1. **Verify Configuration**: Test pCloud connection using tinker
2. **Migrate Local Files**: Run migration command if needed
3. **Monitor Logs**: Check `storage/logs/laravel.log` for pCloud operations
4. **Performance Tuning**: Adjust cache and timeout settings based on usage
5. **Documentation**: Share this guide with your team

---

**Last Updated**: 2025-01-21
**Version**: 1.0
**Author**: Digiwaxx Development Team
