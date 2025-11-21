# Social Sharing Routes Configuration

## Routes to Add to Your Application

Add these routes to your web routes file (typically `routes/web.php` or similar):

```php
<?php

use App\Http\Controllers\ShareController;

// ============================================
// PUBLIC SHAREABLE PAGES (No Authentication Required)
// ============================================

// Public track page (for DJs sharing tracks)
Route::get('/track/{slug}', [ShareController::class, 'showTrack'])
    ->name('track.public');

// Public review page (for artists sharing DJ feedback)
Route::get('/track/{slug}/review/{reviewId}', [ShareController::class, 'showReview'])
    ->name('review.public');

// ============================================
// API ENDPOINTS FOR SHARING (Authenticated)
// ============================================

// Get track share data (for JavaScript share buttons)
Route::get('/api/tracks/{id}/share-data', [ShareController::class, 'getTrackShareData'])
    ->middleware('auth') // or your auth middleware
    ->name('api.track.share-data');

// Get review share data (for JavaScript share buttons)
Route::get('/api/reviews/{id}/share-data', [ShareController::class, 'getReviewShareData'])
    ->middleware('auth') // or your auth middleware
    ->name('api.review.share-data');

// Track share action (analytics)
Route::post('/api/share-tracking', [ShareController::class, 'trackShare'])
    ->middleware('auth') // or your auth middleware
    ->name('api.share.track');

// Generate shareable review image (for Instagram/TikTok)
Route::get('/api/reviews/{id}/shareable-image', [ShareController::class, 'generateReviewImage'])
    ->middleware('auth') // or your auth middleware
    ->name('api.review.shareable-image');
```

## Route Naming Conventions

- `track.public` - Public track page
- `review.public` - Public review page
- `api.track.share-data` - Get track sharing data
- `api.review.share-data` - Get review sharing data
- `api.share.track` - Track share analytics
- `api.review.shareable-image` - Generate shareable image

## Example URLs

**Public Track Page:**
```
https://digiwaxx.com/track/summer-vibes-remix-dj-alex-123
```

**Public Review Page:**
```
https://digiwaxx.com/track/summer-vibes-remix-dj-alex-123/review/456
```

**API Endpoints:**
```
GET  /api/tracks/123/share-data
GET  /api/reviews/456/share-data
POST /api/share-tracking
GET  /api/reviews/456/shareable-image
```

## Middleware Configuration

The API endpoints should use your application's authentication middleware. Update `'auth'` to match your middleware name:

- If you use `'member'` middleware for DJs: Replace `->middleware('auth')` with `->middleware('member')`
- If you use `'client'` middleware for artists: Use appropriate middleware
- If you have custom session auth: Use your session middleware name

Example for custom middleware:
```php
Route::get('/api/tracks/{id}/share-data', [ShareController::class, 'getTrackShareData'])
    ->middleware('member'); // For DJ authentication
```

## CSRF Protection

The POST route (`/api/share-tracking`) requires CSRF protection. Make sure you include the CSRF token in your JavaScript:

```javascript
fetch('/api/share-tracking', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({...})
});
```

## Testing Routes

After adding routes, test them:

```bash
# List all routes
php artisan route:list | grep -i share

# Test public track page (replace with actual slug)
curl https://your-domain.com/track/test-track-123

# Test API endpoint (requires authentication)
curl https://your-domain.com/api/tracks/1/share-data \
    -H "Cookie: your-session-cookie"
```

## Route Permissions Summary

| Route | Authentication | Who Can Access |
|-------|---------------|----------------|
| `/track/{slug}` | None (Public) | Anyone |
| `/track/{slug}/review/{reviewId}` | None (Public) | Anyone |
| `/api/tracks/{id}/share-data` | Required | DJs (members) |
| `/api/reviews/{id}/share-data` | Required | Artists (clients) |
| `/api/share-tracking` | Required | DJs or Artists |
| `/api/reviews/{id}/shareable-image` | Required | Artists (clients) |

## Next Steps

1. Add these routes to your routes file
2. Clear route cache: `php artisan route:clear`
3. Test public pages work
4. Test API endpoints with authentication
5. Verify share buttons work in dashboard

