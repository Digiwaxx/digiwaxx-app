# Social Media Sharing Implementation Guide

## Overview

This guide provides complete implementation for social media sharing of:
1. **Tracks** (DJs sharing tracks they love)
2. **DJ Reviews** (Artists sharing positive DJ feedback)

---

## Summary of What Was Created

### Database Migrations (5 files)✅
1. `2025_01_21_200001_add_social_sharing_to_tracks.php`
2. `2025_01_21_200002_add_social_sharing_to_tracks_reviews.php`
3. `2025_01_21_200003_add_review_sharing_privacy_to_members.php`
4. `2025_01_21_200004_create_track_shares_table.php`
5. `2025_01_21_200005_create_share_page_views_table.php`

### Controllers ✅
1. `Http/Controllers/ShareController.php` - Handles all sharing functionality

### Routes ✅
See `SOCIAL_SHARING_ROUTES.md` for routes to add

### Views (Create These)
1. Public track page (`resources/views/public/track.blade.php`)
2. Public review page (`resources/views/public/review.blade.php`)
3. Share buttons component (`resources/views/components/share-buttons.blade.php`)

### JavaScript ✅
Share functionality JavaScript (see below)

### CSS ✅
Share buttons styling (see below)

---

## Installation Steps

### Step 1: Run Migrations

```bash
# Run migrations to add sharing columns and tables
php artisan migrate

# The migrations will:
# - Add is_public, share_slug, share_count to tracks table
# - Add is_shareable, share_count to tracks_reviews table
# - Add allow_review_sharing to members table
# - Create track_shares table (tracks all shares)
# - Create share_page_views table (tracks page views)
# - Generate share slugs for all existing tracks
```

### Step 2: Add Routes

Add the routes from `SOCIAL_SHARING_ROUTES.md` to your routes file.

### Step 3: Create Views

Create the following view files in `resources/views/`:

---

## View 1: Public Track Page

**File:** `resources/views/public/track.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO & Social Media Meta Tags -->
    <title>{{ $track->title }} by {{ $track->artist }} | Digiwaxx</title>
    <meta name="description" content="Listen to {{ $track->title }} by {{ $track->artist }} on Digiwaxx - supported by {{ $reviewStats->total_reviews }} DJs">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="music.song">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $track->title }} by {{ $track->artist }}">
    <meta property="og:description" content="🎧 Supported by {{ $reviewStats->total_reviews }} DJs on Digiwaxx! Listen now.">
    <meta property="og:image" content="{{ $track->imgpage ?? $track->img ?? asset('images/default-artwork.jpg') }}">
    <meta property="og:site_name" content="Digiwaxx">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $track->title }} by {{ $track->artist }}">
    <meta name="twitter:description" content="🎧 Supported by {{ $reviewStats->total_reviews }} DJs on Digiwaxx!">
    <meta name="twitter:image" content="{{ $track->imgpage ?? $track->img }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .artwork {
            width: 100%;
            aspect-ratio: 1;
            background: #000;
            position: relative;
        }

        .artwork img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .content {
            padding: 30px;
        }

        .track-info {
            margin-bottom: 30px;
        }

        h1 {
            font-size: 32px;
            color: #1a1a1a;
            margin-bottom: 10px;
        }

        .artist {
            font-size: 24px;
            color: #666;
            margin-bottom: 20px;
        }

        .meta {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .meta-item {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 14px;
        }

        .meta-item i {
            color: #667eea;
        }

        .stats {
            display: flex;
            gap: 30px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .stat {
            text-align: center;
        }

        .stat-value {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .audio-player {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .audio-player audio {
            width: 100%;
        }

        .cta {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
        }

        .cta h3 {
            color: white;
            margin-bottom: 15px;
        }

        .btn {
            display: inline-block;
            padding: 15px 30px;
            background: white;
            color: #667eea;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .logo {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            h1 { font-size: 24px; }
            .artist { font-size: 18px; }
            .stats { flex-direction: column; gap: 15px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Artwork -->
        <div class="artwork">
            <img src="{{ $track->imgpage ?? $track->img ?? asset('images/default-artwork.jpg') }}" alt="{{ $track->title }} artwork">
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Track Info -->
            <div class="track-info">
                <h1>{{ $track->title }}</h1>
                <div class="artist">{{ $track->artist }}</div>

                <div class="meta">
                    @if($track->label)
                    <div class="meta-item">
                        <i class="fas fa-record-vinyl"></i>
                        <span>{{ $track->label }}</span>
                    </div>
                    @endif

                    @if($track->bpm)
                    <div class="meta-item">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>{{ $track->bpm }} BPM</span>
                    </div>
                    @endif

                    @if($track->genre)
                    <div class="meta-item">
                        <i class="fas fa-music"></i>
                        <span>{{ $track->genre }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Stats -->
            <div class="stats">
                <div class="stat">
                    <div class="stat-value">{{ $reviewStats->total_reviews }}</div>
                    <div class="stat-label">DJ Reviews</div>
                </div>

                <div class="stat">
                    <div class="stat-value">{{ number_format($reviewStats->avg_rating, 1) }} <i class="fas fa-star" style="color: #ffc107; font-size: 18px;"></i></div>
                    <div class="stat-label">Avg Rating</div>
                </div>

                <div class="stat">
                    <div class="stat-value">{{ $reviewStats->will_play_count }}</div>
                    <div class="stat-label">DJs Playing</div>
                </div>
            </div>

            <!-- Audio Preview -->
            @if($mp3s && $mp3s->count() > 0)
            <div class="audio-player">
                <h3 style="margin-bottom: 15px; color: #1a1a1a;">
                    <i class="fas fa-headphones"></i> Preview
                </h3>
                @foreach($mp3s as $mp3)
                    @if($mp3->preview == 1)
                    <audio controls preload="metadata">
                        <source src="{{ route('member.pcloud.stream', $mp3->id) }}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>
                    @break
                    @endif
                @endforeach
            </div>
            @endif

            <!-- CTA -->
            <div class="cta">
                <h3>Get Full Access to Thousands of Tracks</h3>
                <a href="{{ url('/') }}" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Join Digiwaxx
                </a>
            </div>
        </div>

        <!-- Logo -->
        <div class="logo">
            <strong>DIGIWAXX</strong> - Professional DJ Pool
        </div>
    </div>

    <script>
        // Track audio play event
        document.querySelector('audio')?.addEventListener('play', function() {
            // Could track engagement via API
            console.log('Audio played');
        });
    </script>
</body>
</html>
```

---

## View 2: Public Review Page

**File:** `resources/views/public/review.blade.php`

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @php
        $djName = $member->display_name ?? $member->fname . ' ' . $member->lname ?? 'Anonymous DJ';
        $comment = urldecode($review->additionalcomments ?? '');
        $stars = str_repeat('⭐', $review->whatrate);
    @endphp

    <!-- SEO & Social Media Meta Tags -->
    <title>{{ $stars }} DJ {{ $djName }} Review | {{ $track->title }}</title>
    <meta name="description" content="{{ $comment }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="music.song">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="DJ {{ $djName }} rated '{{ $track->title }}' {{ $review->whatrate }} stars!">
    <meta property="og:description" content="{{ Str::limit($comment, 200) }}">
    <meta property="og:image" content="{{ $track->imgpage ?? $track->img }}">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="{{ $stars }} from DJ {{ $djName }}">
    <meta name="twitter:description" content="{{ Str::limit($comment, 200) }}">
    <meta name="twitter:image" content="{{ $track->imgpage ?? $track->img }}">

    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .header {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .stars {
            font-size: 48px;
            margin-bottom: 20px;
            line-height: 1;
        }

        .review-badge {
            background: rgba(255,255,255,0.2);
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .quote-section {
            padding: 40px 30px;
        }

        .quote-mark {
            font-size: 60px;
            color: #f093fb;
            line-height: 0;
            height: 30px;
            margin-bottom: 20px;
        }

        .review-text {
            font-size: 20px;
            line-height: 1.6;
            color: #1a1a1a;
            font-style: italic;
            margin-bottom: 30px;
        }

        .dj-info {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 12px;
            margin-bottom: 30px;
        }

        .dj-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .dj-details {
            flex: 1;
        }

        .dj-name {
            font-weight: bold;
            font-size: 18px;
            color: #1a1a1a;
        }

        .dj-location {
            font-size: 14px;
            color: #666;
        }

        .track-info {
            text-align: center;
            padding: 30px;
            background: #f8f9fa;
            border-radius: 12px;
        }

        .track-artwork {
            width: 120px;
            height: 120px;
            border-radius: 12px;
            margin: 0 auto 20px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .track-artwork img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .track-title {
            font-size: 20px;
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .track-artist {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            transition: transform 0.2s;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .logo {
            text-align: center;
            padding: 20px;
            color: #999;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .stars { font-size: 36px; }
            .review-text { font-size: 18px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header with Stars -->
        <div class="header">
            <div class="stars">{{ $stars }}</div>
            <div class="review-badge">DJ Review</div>
        </div>

        <!-- Quote Section -->
        <div class="quote-section">
            <div class="quote-mark">"</div>
            <div class="review-text">{{ $comment }}</div>

            <!-- DJ Info -->
            <div class="dj-info">
                <div class="dj-avatar">
                    {{ strtoupper(substr($djName, 0, 1)) }}
                </div>
                <div class="dj-details">
                    <div class="dj-name">{{ $djName }}</div>
                    @if($member->city)
                    <div class="dj-location">
                        <i class="fas fa-map-marker-alt"></i> {{ $member->city }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Track Info -->
            <div class="track-info">
                <div class="track-artwork">
                    <img src="{{ $track->imgpage ?? $track->img }}" alt="{{ $track->title }}">
                </div>
                <div class="track-title">{{ $track->title }}</div>
                <div class="track-artist">{{ $track->artist }}</div>
                <a href="{{ url('/') }}" class="btn">
                    <i class="fas fa-headphones"></i> Listen on Digiwaxx
                </a>
            </div>
        </div>

        <!-- Logo -->
        <div class="logo">
            <strong>DIGIWAXX</strong> - Professional DJ Pool
        </div>
    </div>
</body>
</html>
```

---

## Component: Share Buttons

**File:** `resources/views/components/share-buttons.blade.php`

```html
@props(['type' => 'track', 'id' => null, 'showTitle' => true])

<div class="share-buttons-component" data-type="{{ $type }}" data-id="{{ $id }}">
    @if($showTitle)
    <h4 class="share-title">
        <i class="fas fa-share-alt"></i> Share{{ $type === 'review' ? ' This Review' : ' This Track' }}
    </h4>
    @endif

    <div class="button-group">
        <button onclick="shareItem('facebook', '{{ $type }}', {{ $id }})" class="share-btn btn-facebook" title="Share on Facebook">
            <i class="fab fa-facebook-f"></i>
            <span>Facebook</span>
        </button>

        <button onclick="shareItem('twitter', '{{ $type }}', {{ $id }})" class="share-btn btn-twitter" title="Share on Twitter">
            <i class="fab fa-twitter"></i>
            <span>Twitter</span>
        </button>

        <button onclick="shareItem('instagram', '{{ $type }}', {{ $id }})" class="share-btn btn-instagram" title="Copy for Instagram">
            <i class="fab fa-instagram"></i>
            <span>Instagram</span>
        </button>

        <button onclick="shareItem('tiktok', '{{ $type }}', {{ $id }})" class="share-btn btn-tiktok" title="Copy for TikTok">
            <i class="fab fa-tiktok"></i>
            <span>TikTok</span>
        </button>

        <button onclick="copyShareLink('{{ $type }}', {{ $id }})" class="share-btn btn-copy" title="Copy Link">
            <i class="fas fa-link"></i>
            <span>Copy Link</span>
        </button>

        @if($type === 'review')
        <button onclick="downloadReviewImage({{ $id }})" class="share-btn btn-download" title="Download Image">
            <i class="fas fa-image"></i>
            <span>Download</span>
        </button>
        @endif
    </div>
</div>

<style>
.share-buttons-component {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
    margin: 20px 0;
}

.share-title {
    font-size: 16px;
    color: #1a1a1a;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.button-group {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 10px;
}

.share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s;
    color: white;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

.share-btn i {
    font-size: 16px;
}

.btn-facebook {
    background: #1877f2;
}

.btn-twitter {
    background: #1da1f2;
}

.btn-instagram {
    background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
}

.btn-tiktok {
    background: #000000;
}

.btn-copy {
    background: #6c757d;
}

.btn-download {
    background: #28a745;
}

@media (max-width: 768px) {
    .button-group {
        grid-template-columns: repeat(2, 1fr);
    }

    .share-btn span {
        font-size: 12px;
    }
}
</style>
```

---

## JavaScript: Share Functions

**File:** `public/js/social-sharing.js` (or add to existing JS file)

```javascript
/**
 * Social Sharing Functions
 * Handles all social media sharing for tracks and reviews
 */

// Get CSRF token
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

/**
 * Main share function
 * @param {string} platform - Social platform (facebook, twitter, instagram, tiktok)
 * @param {string} type - Type of content (track or review)
 * @param {int} id - ID of track or review
 */
async function shareItem(platform, type, id) {
    try {
        // Get share data from API
        const endpoint = type === 'track'
            ? `/api/tracks/${id}/share-data`
            : `/api/reviews/${id}/share-data`;

        const response = await fetch(endpoint);
        const data = await response.json();

        if (data.error) {
            alert(data.error);
            return;
        }

        // Share based on platform
        switch(platform) {
            case 'facebook':
                shareFacebook(data.public_url);
                break;
            case 'twitter':
                shareTwitter(data.public_url, data.twitter_text);
                break;
            case 'instagram':
                shareInstagram(data.instagram_caption);
                break;
            case 'tiktok':
                shareTikTok(data.tiktok_caption);
                break;
        }

        // Track the share
        trackShare(type, id, platform, data[`${platform}_text`] || '');

    } catch (error) {
        console.error('Share error:', error);
        alert('Failed to get share information. Please try again.');
    }
}

/**
 * Share on Facebook
 */
function shareFacebook(url) {
    const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    window.open(shareUrl, 'facebook-share', 'width=600,height=400,left=200,top=100');
}

/**
 * Share on Twitter
 */
function shareTwitter(url, text) {
    const shareUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
    window.open(shareUrl, 'twitter-share', 'width=600,height=400,left=200,top=100');
}

/**
 * Share on Instagram (copy caption)
 */
function shareInstagram(caption) {
    navigator.clipboard.writeText(caption).then(() => {
        showModal('Instagram Sharing', `
            <p><strong>Caption copied to clipboard!</strong></p>
            <p>To share on Instagram:</p>
            <ol>
                <li>Open Instagram app</li>
                <li>Create a new post or story</li>
                <li>Paste the caption we copied for you</li>
                <li>Add your own photo/video</li>
            </ol>
            <textarea readonly style="width:100%;height:100px;margin-top:15px;padding:10px;border:1px solid #ddd;border-radius:8px;">${caption}</textarea>
        `);
    }).catch(() => {
        alert('Failed to copy to clipboard');
    });
}

/**
 * Share on TikTok (copy caption)
 */
function shareTikTok(caption) {
    navigator.clipboard.writeText(caption).then(() => {
        showModal('TikTok Sharing', `
            <p><strong>Caption copied to clipboard!</strong></p>
            <p>To share on TikTok:</p>
            <ol>
                <li>Open TikTok app</li>
                <li>Create a new video</li>
                <li>Paste the caption we copied for you</li>
                <li>Add music and publish</li>
            </ol>
            <textarea readonly style="width:100%;height:100px;margin-top:15px;padding:10px;border:1px solid #ddd;border-radius:8px;">${caption}</textarea>
        `);
    }).catch(() => {
        alert('Failed to copy to clipboard');
    });
}

/**
 * Copy share link
 */
async function copyShareLink(type, id) {
    try {
        const endpoint = type === 'track'
            ? `/api/tracks/${id}/share-data`
            : `/api/reviews/${id}/share-data`;

        const response = await fetch(endpoint);
        const data = await response.json();

        navigator.clipboard.writeText(data.public_url).then(() => {
            showNotification('✓ Link copied to clipboard!', 'success');
            trackShare(type, id, 'copy_link', data.public_url);
        });
    } catch (error) {
        alert('Failed to copy link');
    }
}

/**
 * Download review image (for Instagram/TikTok posts)
 */
function downloadReviewImage(reviewId) {
    window.location.href = `/api/reviews/${reviewId}/shareable-image`;
    trackShare('review', reviewId, 'download_image', '');
}

/**
 * Track share action (analytics)
 */
async function trackShare(type, id, platform, shareText) {
    try {
        await fetch('/api/share-tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                type: type,
                id: id,
                platform: platform,
                share_text: shareText
            })
        });
    } catch (error) {
        console.error('Failed to track share:', error);
    }
}

/**
 * Show modal dialog
 */
function showModal(title, content) {
    // Create modal overlay
    const overlay = document.createElement('div');
    overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 20px;
    `;

    // Create modal
    const modal = document.createElement('div');
    modal.style.cssText = `
        background: white;
        border-radius: 12px;
        max-width: 500px;
        width: 100%;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    `;

    modal.innerHTML = `
        <h3 style="margin: 0 0 20px 0; color: #1a1a1a;">${title}</h3>
        <div style="color: #666;">${content}</div>
        <button onclick="this.closest('[style*=fixed]').remove()" style="
            margin-top: 20px;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
            width: 100%;
        ">Close</button>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    // Close on overlay click
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            overlay.remove();
        }
    });
}

/**
 * Show notification toast
 */
function showNotification(message, type = 'info') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#28a745' : '#007bff'};
        color: white;
        padding: 15px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10000;
        animation: slideIn 0.3s ease;
    `;
    toast.textContent = message;

    // Add animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(400px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
```

---

## How to Use Share Buttons in Your Views

### Example 1: DJ Dashboard (Share Track)

```php
<!-- In DJ track listing page -->
@foreach($tracks as $track)
<div class="track-item">
    <h3>{{ $track->title }}</h3>
    <p>{{ $track->artist }}</p>

    <!-- Share buttons for track -->
    <x-share-buttons type="track" :id="$track->id" />
</div>
@endforeach
```

### Example 2: Artist Dashboard (Share Review)

```php
<!-- In artist's reviews section -->
@foreach($reviews as $review)
    @if($review->whatrate >= 4 && $review->is_shareable)
    <div class="review-item">
        <div class="rating">{{ str_repeat('⭐', $review->whatrate) }}</div>
        <p>{{ urldecode($review->additionalcomments) }}</p>
        <p class="dj-name">- DJ {{ $review->dj_name }}</p>

        <!-- Share buttons for review -->
        <x-share-buttons type="review" :id="$review->id" />
    </div>
    @endif
@endforeach
```

---

## Analytics Dashboard

### Viewing Share Statistics

Add to DJ Dashboard:

```php
// In DashboardController
public function dashboard() {
    $userId = session('memberId');

    // Get share stats for DJ
    $shareStats = DB::table('track_shares')
        ->where('user_id', $userId)
        ->where('user_type', 'member')
        ->selectRaw('
            platform,
            COUNT(*) as count
        ')
        ->groupBy('platform')
        ->get();

    return view('member.dashboard', compact('shareStats'));
}
```

Add to Artist Dashboard:

```php
// In ClientDashboardController
public function dashboard() {
    $clientId = session('clientId');

    // Get tracks owned by this client
    $trackIds = DB::table('tracks')
        ->where('client', $clientId)
        ->pluck('id');

    // Get review IDs for this client's tracks
    $reviewIds = DB::table('tracks_reviews')
        ->whereIn('track', $trackIds)
        ->pluck('id');

    // Get review share stats
    $reviewShareStats = DB::table('track_shares')
        ->where('shareable_type', 'review')
        ->whereIn('shareable_id', $reviewIds)
        ->selectRaw('
            platform,
            COUNT(*) as count
        ')
        ->groupBy('platform')
        ->get();

    // Get most shared tracks
    $topSharedTracks = DB::table('tracks')
        ->where('client', $clientId)
        ->orderBy('share_count', 'DESC')
        ->limit(5)
        ->get();

    return view('client.dashboard', compact('reviewShareStats', 'topSharedTracks'));
}
```

---

## Testing Checklist

### Functionality Tests
- [ ] Run migrations successfully
- [ ] Share slugs generated for all tracks
- [ ] Public track page loads correctly
- [ ] Public review page loads correctly (4-5 star reviews only)
- [ ] Share buttons appear in DJ dashboard
- [ ] Share buttons appear in artist dashboard
- [ ] Facebook share opens popup
- [ ] Twitter share opens popup with correct text
- [ ] Instagram copy to clipboard works
- [ ] TikTok copy to clipboard works
- [ ] Copy link works
- [ ] Share tracking increments counts
- [ ] Page view tracking works

### Permission Tests
- [ ] Artists can only share 4-5 star reviews
- [ ] Artists cannot share 1-3 star reviews
- [ ] Artists cannot share reviews from DJs who opted out
- [ ] DJs can share any track they downloaded
- [ ] Non-public tracks return 404

### Social Media Meta Tags
- [ ] Open Graph tags present on public pages
- [ ] Twitter Card tags present on public pages
- [ ] Test Facebook preview: https://developers.facebook.com/tools/debug/
- [ ] Test Twitter preview: https://cards-dev.twitter.com/validator

### Analytics Tests
- [ ] Shares tracked in track_shares table
- [ ] Share counts increment correctly
- [ ] Page views tracked in share_page_views table
- [ ] Dashboard shows correct statistics

---

## Next Steps After Implementation

1. **Run Migrations:**
   ```bash
   php artisan migrate
   ```

2. **Add Routes:**
   See `SOCIAL_SHARING_ROUTES.md`

3. **Test Public Pages:**
   - Visit `/track/your-track-slug`
   - Visit `/track/your-track-slug/review/123`

4. **Test Share Buttons:**
   - Click Facebook share
   - Click Twitter share
   - Try Instagram copy
   - Test link copying

5. **Monitor Analytics:**
   - Check `track_shares` table for data
   - Check `share_page_views` table for data
   - View dashboard statistics

6. **Optional Enhancements:**
   - Implement shareable image generation (requires Intervention Image package)
   - Add GeoIP lookup for visitor locations
   - Create admin analytics dashboard
   - Add email notifications when reviews are shared
   - Implement UTM tracking for campaign analysis

---

## Support & Troubleshooting

### Common Issues

**Share buttons not working:**
- Check JavaScript is loaded: `public/js/social-sharing.js`
- Check CSRF token is present in page meta tags
- Check console for JavaScript errors

**Public pages return 404:**
- Verify routes are added
- Run `php artisan route:clear`
- Check track has `is_public = true`
- Check track has `share_slug` generated

**Reviews can't be shared:**
- Check review has `whatrate >= 4`
- Check review has `is_shareable = true`
- Check DJ has `allow_review_sharing = true`

**Share counts not incrementing:**
- Check `track_shares` table for insert errors
- Verify CSRF token is correct
- Check API endpoint `/api/share-tracking` is working

---

## File Summary

**Created Files:**
1. `database/migrations/2025_01_21_200001_add_social_sharing_to_tracks.php`
2. `database/migrations/2025_01_21_200002_add_social_sharing_to_tracks_reviews.php`
3. `database/migrations/2025_01_21_200003_add_review_sharing_privacy_to_members.php`
4. `database/migrations/2025_01_21_200004_create_track_shares_table.php`
5. `database/migrations/2025_01_21_200005_create_share_page_views_table.php`
6. `Http/Controllers/ShareController.php`
7. `SOCIAL_SHARING_ROUTES.md`
8. `SOCIAL_SHARING_IMPLEMENTATION_GUIDE.md` (this file)

**To Create:**
1. `resources/views/public/track.blade.php`
2. `resources/views/public/review.blade.php`
3. `resources/views/components/share-buttons.blade.php`
4. `public/js/social-sharing.js`

**To Modify:**
1. Your routes file (add routes from `SOCIAL_SHARING_ROUTES.md`)
2. DJ dashboard (add share buttons)
3. Artist dashboard (add share buttons for reviews)

---

**Implementation Status:** ✅ Backend Complete | ⏳ Frontend Templates Provided | 📋 Integration Required

