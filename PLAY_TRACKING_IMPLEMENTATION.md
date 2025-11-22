# Play Tracking Implementation Guide

## Overview

This guide explains how to implement the play tracking feature that was previously broken. Play counts are now properly tracked when users play audio files.

## Backend Implementation

### ✅ COMPLETED - Backend Changes

1. **New Method: `playIncrement()`** - `/app/Models/MemberAllDB.php:4358`
   - Increments `num_plays` field in `tracks_mp3s` table
   - Records play event in `track_member_play` table
   - Awards digi coins (1 point for first play only)
   - Uses Query Builder to prevent SQL injection

2. **New Controller Endpoint: `trackPlay()`** - `/app/Http/Controllers/Members/MemberDashboardController.php:4186`
   - AJAX endpoint to handle play tracking requests
   - Route: `POST /track/play` (add to routes/web.php)
   - Rate limiting: Prevents duplicate plays within 1 hour
   - Returns JSON response

## Frontend Implementation

### ⚠️ REQUIRED - Frontend Changes

You need to add JavaScript to call the play tracking endpoint when audio starts playing.

### Step 1: Add Route

Add this to your `routes/web.php`:

```php
// Play tracking endpoint
Route::post('/track/play', [App\Http\Controllers\Members\MemberDashboardController::class, 'trackPlay'])
    ->middleware(['web'])
    ->name('track.play');
```

### Step 2: Add JavaScript to Audio Player

Add this JavaScript where your audio players are rendered (typically in `resources/views/members/` or `resources/views/clients/`):

```javascript
// Track play counts when audio plays
document.addEventListener('DOMContentLoaded', function() {

    // Find all audio elements on the page
    const audioElements = document.querySelectorAll('audio');

    audioElements.forEach(function(audio) {

        // Track when audio starts playing
        audio.addEventListener('play', function() {

            // Get track data from data attributes
            const mp3Id = this.getAttribute('data-mp3-id');
            const trackId = this.getAttribute('data-track-id');

            if (!mp3Id || !trackId) {
                console.warn('Missing track data attributes for play tracking');
                return;
            }

            // Send AJAX request to track play
            fetch('/track/play', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    mp3Id: mp3Id,
                    trackId: trackId,
                    countryName: 'Unknown', // Optional: Add geolocation
                    countryCode: 'XX'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Play tracked successfully');
                } else {
                    console.error('Failed to track play:', data.error);
                }
            })
            .catch(error => {
                console.error('Error tracking play:', error);
            });
        });
    });
});
```

### Step 3: Update Audio Elements

Ensure your audio elements have the required data attributes:

```html
<audio
    controls
    data-mp3-id="{{ $track->id }}"
    data-track-id="{{ $track->trackId }}"
    src="{{ $track->url }}">
</audio>
```

### Step 4: Add CSRF Meta Tag

Ensure your layout has the CSRF token meta tag (usually in `resources/views/layouts/app.blade.php`):

```html
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
```

## Testing the Implementation

### Test Play Tracking

1. **Login as a member (DJ)**
2. **Navigate to a track page**
3. **Click play on an audio track**
4. **Check browser console** - should see "Play tracked successfully"
5. **Check database:**
   ```sql
   -- Play count should increment
   SELECT id, num_plays FROM tracks_mp3s WHERE id = [mp3_id];

   -- Play should be recorded
   SELECT * FROM track_member_play
   WHERE memberId = [member_id]
   AND mp3Id = [mp3_id]
   ORDER BY playedDateTime DESC
   LIMIT 1;
   ```

### Test Rate Limiting

1. **Play same track**
2. **Refresh page**
3. **Play again within 1 hour**
4. **Check console** - should see "Play already recorded recently"
5. **Play count should NOT increase**

### Test Digi Coins

1. **Play a NEW track (never played before)**
2. **Check member_digicoins table:**
   ```sql
   SELECT * FROM member_digicoins
   WHERE member_id = [member_id]
   AND track_id = [track_id]
   AND type_id = 3;
   ```
3. **Should award 1 digi coin**
4. **Check available coins increased:**
   ```sql
   SELECT available_points FROM member_digicoins_available
   WHERE member_id = [member_id]
   ORDER BY member_digicoin_available_id DESC
   LIMIT 1;
   ```

## Advanced Features (Optional Enhancements)

### 1. IP Geolocation

Add IP-based country detection:

```php
// In trackPlay() method
$ip = $request->ip();
$geoData = $this->getCountryFromIP($ip); // Implement using GeoIP service
$countryName = $geoData['country_name'];
$countryCode = $geoData['country_code'];
```

### 2. Partial Play Tracking

Only count as "played" if user listens for at least 30 seconds:

```javascript
audio.addEventListener('timeupdate', function() {
    if (this.currentTime >= 30 && !this.dataset.playTracked) {
        // Track play here
        this.dataset.playTracked = 'true';
    }
});
```

### 3. Real-time Analytics

Use WebSockets to update play counts in real-time:

```javascript
// Using Laravel Echo
Echo.channel('track.' + trackId)
    .listen('PlayCountUpdated', (e) => {
        updatePlayCount(e.newCount);
    });
```

## Analytics Queries

### Most Played Tracks

```sql
SELECT
    t.title,
    t.artist,
    tm.num_plays,
    COUNT(DISTINCT tmp.memberId) as unique_listeners
FROM tracks_mp3s tm
JOIN tracks t ON tm.track = t.id
LEFT JOIN track_member_play tmp ON tm.id = tmp.mp3Id
GROUP BY tm.id
ORDER BY tm.num_plays DESC
LIMIT 10;
```

### Plays by Country

```sql
SELECT
    playedCountryCode,
    playedCountry,
    COUNT(*) as play_count
FROM track_member_play
WHERE trackId = ?
GROUP BY playedCountryCode, playedCountry
ORDER BY play_count DESC;
```

### Plays Over Time

```sql
SELECT
    DATE(playedDateTime) as play_date,
    COUNT(*) as plays
FROM track_member_play
WHERE trackId = ?
GROUP BY DATE(playedDateTime)
ORDER BY play_date DESC
LIMIT 30;
```

## Security Features

### ✅ Implemented

1. **Authentication Required** - Only logged-in members can track plays
2. **Input Validation** - mp3Id and trackId must be integers
3. **Rate Limiting** - Prevents spam (1 play per track per hour per user)
4. **SQL Injection Prevention** - Uses Query Builder with parameter binding
5. **CSRF Protection** - Requires valid CSRF token

### Recommendations

1. **Add IP-based rate limiting** - Max 100 plays per IP per hour
2. **Detect bot traffic** - Block known user agents
3. **Verify track ownership** - Ensure mp3Id belongs to trackId
4. **Add logging** - Track suspicious play patterns

## Troubleshooting

### Play count not incrementing

1. Check browser console for errors
2. Verify CSRF token is present
3. Check route is registered: `php artisan route:list | grep track.play`
4. Verify user is logged in: Check `Session::get('memberId')`
5. Check database permissions

### "Unauthorized" error

- User is not logged in
- Session has expired
- Check middleware configuration

### "Failed to record play" error

- Database connection issue
- Invalid mp3Id or trackId
- Check Laravel logs: `storage/logs/laravel.log`

## Files Modified

### Backend
- `/app/Models/MemberAllDB.php` - Added `playIncrement()` method
- `/app/Http/Controllers/Members/MemberDashboardController.php` - Added `trackPlay()` endpoint

### Frontend (YOU NEED TO ADD)
- `/routes/web.php` - Add route for `POST /track/play`
- `/resources/views/members/[your-audio-player-views].blade.php` - Add JavaScript
- `/resources/views/layouts/app.blade.php` - Ensure CSRF meta tag exists

## Migration (If Needed)

If `track_member_play` table doesn't exist, create it:

```sql
CREATE TABLE IF NOT EXISTS `track_member_play` (
  `playId` int(11) NOT NULL AUTO_INCREMENT,
  `memberId` int(11) NOT NULL,
  `trackId` int(11) NOT NULL,
  `mp3Id` int(11) NOT NULL,
  `playedDateTime` datetime NOT NULL,
  `playedCountry` varchar(100) DEFAULT NULL,
  `playedCountryCode` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`playId`),
  KEY `idx_member` (`memberId`),
  KEY `idx_track` (`trackId`),
  KEY `idx_mp3` (`mp3Id`),
  KEY `idx_datetime` (`playedDateTime`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

## Next Steps

1. ✅ Backend implementation complete
2. ⚠️ **Add route** to `routes/web.php`
3. ⚠️ **Add JavaScript** to your views
4. ⚠️ **Add data attributes** to audio elements
5. ⚠️ **Test** the implementation
6. ✅ Deploy to production

## Support

If you encounter issues:

1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Check browser console for JavaScript errors
3. Verify database table exists and has correct schema
4. Test endpoint directly: `curl -X POST /track/play -H "Content-Type: application/json" -d '{"mp3Id":1,"trackId":1}'`

---

**Status:** Backend complete, frontend integration required
**Last Updated:** 2025-11-20
