# Image Loading System with Fallback Support

This document describes the flexible image loading system implemented for Digiwaxx that supports both pCloud storage and local storage with automatic fallback capabilities.

## Overview

The image loading system provides:
- **Dual source support**: Load images from pCloud or local storage
- **Automatic fallback**: If the primary source fails, automatically try the secondary source
- **Configuration-based switching**: Easily switch between sources via `.env` settings
- **Client-side fallback**: JavaScript handles image errors gracefully
- **Backward compatibility**: Existing `pCloudImgDownload.php` URLs continue to work

## Configuration

### Environment Variables (.env)

```env
# Primary source for images: 'local' or 'pcloud'
IMAGE_PRIMARY_SOURCE=local

# Enable automatic fallback to the other source
IMAGE_ENABLE_FALLBACK=true

# Enable/disable pCloud completely
PCLOUD_ENABLED=true

# Cache pCloud URLs (in seconds)
PCLOUD_CACHE_TTL=3600
```

### Quick Configuration Options

**Option 1: Local only (pCloud disconnected)**
```env
IMAGE_PRIMARY_SOURCE=local
IMAGE_ENABLE_FALLBACK=false
PCLOUD_ENABLED=false
```

**Option 2: Local with pCloud fallback**
```env
IMAGE_PRIMARY_SOURCE=local
IMAGE_ENABLE_FALLBACK=true
PCLOUD_ENABLED=true
```

**Option 3: pCloud primary with local fallback (recommended when pCloud is working)**
```env
IMAGE_PRIMARY_SOURCE=pcloud
IMAGE_ENABLE_FALLBACK=true
PCLOUD_ENABLED=true
```

## Usage

### In Blade Templates

#### Method 1: Using the Image Serve Route (Recommended)

```php
<?php
$pcloud_id = $track->pCloudFileID ?? '';
$local_file = $track->imgpage ?? '';
$placeholder = asset('public/images/noimage-avl.jpg');

// Build URL with fallback parameters
$img_url = url('/image/serve') . '?' . http_build_query([
    'fileId' => $pcloud_id,
    'local' => $local_file,
    'type' => 'track'  // track, album, logo, banner, member
]);
?>

<img src="<?php echo $img_url; ?>"
     data-local-src="<?php echo asset('ImagesUp/' . $local_file); ?>"
     data-placeholder-src="<?php echo $placeholder; ?>"
     onerror="handleImageError(this)"
     alt="Track artwork">
```

#### Method 2: Using the ImageHelper Class

```php
<?php
use App\Helpers\ImageHelper;

// Get image URL with automatic fallback
$url = ImageHelper::getImageUrl(
    $track->pCloudFileID,  // pCloud file ID
    $track->imgpage,       // local filename
    'track'                // type
);

// Or generate a complete img tag
echo ImageHelper::imgTag(
    $track->pCloudFileID,
    $track->imgpage,
    'track',
    ['class' => 'img-fluid', 'alt' => 'Track artwork']
);
?>
```

#### Method 3: Backward Compatible (existing code)

Existing code using `pCloudImgDownload.php` will continue to work:

```php
// This still works - routed to ImageController
$img = url('/pCloudImgDownload.php?fileID=' . $track->pCloudFileID);
```

### Image Types

The system supports different image types with their own configurations:

| Type | pCloud Field | Local Field | Default Location |
|------|-------------|-------------|------------------|
| track | pCloudFileID | imgpage | ImagesUp/ |
| album | pCloudFileID_album | album_page_image | ImagesUp/ |
| logo | pCloudFileID_logo | logo | public/Logos/ |
| member | pCloudFileID_mem_image | member_image | ImagesUp/ |
| banner | pCloudFileID | banner_image | public/images/ |

### JavaScript Fallback Handler

The `image-fallback.js` script provides client-side error handling. It's automatically included in all layouts.

**Manual usage:**

```html
<img src="primary-url.jpg"
     data-local-src="local-fallback.jpg"
     data-pcloud-src="pcloud-fallback.jpg"
     data-placeholder-src="placeholder.jpg"
     onerror="handleImageError(this)">
```

**Reinitialize after dynamic content:**

```javascript
// Call after loading new images via AJAX
reinitializeImageFallbacks();
```

**Check pCloud availability:**

```javascript
checkPCloudAvailability(function(isAvailable) {
    if (!isAvailable) {
        console.log('pCloud is not available');
    }
});
```

## API Endpoints

### GET /image/serve

Serves images with automatic fallback.

**Parameters:**
- `fileId` or `fileID`: pCloud file ID
- `local`: Local filename
- `type`: Image type (track, album, logo, banner, member)

**Response Headers:**
- `X-Image-Source`: Indicates where the image was loaded from (pcloud, local, placeholder, fallback-gif)

### GET /image/pcloud/{fileId}

Serves images directly from pCloud.

### GET /image/status

Returns JSON status of the image system:

```json
{
    "pcloud_configured": true,
    "pcloud_available": true,
    "primary_source": "local",
    "fallback_enabled": true
}
```

## Local Image Storage

### Directory Structure

```
/ImagesUp/           - Track and album artwork
/public/Logos/       - Label/company logos
/public/images/      - Static images and placeholders
```

### Placeholder Images

Ensure these placeholder images exist:
- `public/images/noimage-avl.jpg` - Default track/album placeholder
- `public/images/logo.png` - Default logo
- `public/images/default-avatar.png` - Default member avatar
- `public/images/banner-default.jpg` - Default banner

## Migrating Images from pCloud to Local

To migrate images from pCloud to local storage:

1. Download images from pCloud to the appropriate local directory
2. Ensure filenames match the `imgpage` or equivalent field in the database
3. Update `.env` to use local as primary:
   ```env
   IMAGE_PRIMARY_SOURCE=local
   ```
4. Clear the cache: `php artisan cache:clear`

## Troubleshooting

### Images not loading

1. Check the browser console for JavaScript errors
2. Check the network tab for image request failures
3. Verify `.env` settings are correct
4. Clear Laravel cache: `php artisan config:clear`

### pCloud connection issues

1. Verify `PCLOUD_ACCESS_TOKEN` is valid
2. Check `IMAGE_PRIMARY_SOURCE=local` to use local fallback
3. Monitor `/image/status` endpoint for availability

### Placeholder not showing

1. Ensure placeholder images exist in `public/images/`
2. Check file permissions
3. Verify `config/images.php` has correct placeholder paths

## Configuration File Reference

See `config/images.php` for full configuration options including:
- Primary source settings
- pCloud configuration
- Local storage paths
- Placeholder images
- Image type field mappings
