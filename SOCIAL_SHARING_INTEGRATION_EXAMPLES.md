# Social Sharing Integration Examples

## Quick Integration Guide

This document shows how to integrate the social sharing buttons into your existing dashboards.

---

## 1. Include Required Assets

Add to your layout file (e.g., `header.blade.php` or `master.blade.php`):

```html
<!-- In the <head> section -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Before closing </body> tag -->
<script src="{{ asset('js/social-sharing.js') }}"></script>
```

---

## 2. DJ Dashboard Integration

### Example 1: Track List with Share Buttons

```php
<!-- In your DJ dashboard track listing page -->

<div class="tracks-list">
    @foreach($tracks as $track)
    <div class="track-item">
        <div class="track-info">
            <img src="{{ $track->imgpage ?? $track->img }}" alt="{{ $track->title }}">
            <div class="track-details">
                <h3>{{ $track->title }}</h3>
                <p>{{ $track->artist }}</p>
                @if($track->label)
                <p class="label">{{ $track->label }}</p>
                @endif
            </div>
        </div>

        <!-- Download button (existing) -->
        <button class="btn-download">
            <i class="fas fa-download"></i> Download
        </button>

        <!-- ADD: Share buttons -->
        <x-share-buttons type="track" :id="$track->id" :showTitle="false" />
    </div>
    @endforeach
</div>
```

### Example 2: Track Detail Page

```php
<!-- In track detail/info page -->

<div class="track-detail">
    <div class="track-header">
        <img src="{{ $track->imgpage ?? $track->img }}" alt="{{ $track->title }}" class="artwork-large">
        <div class="track-meta">
            <h1>{{ $track->title }}</h1>
            <h2>{{ $track->artist }}</h2>
            <div class="meta-info">
                <span>{{ $track->label }}</span>
                <span>{{ $track->bpm }} BPM</span>
                <span>{{ $track->genre }}</span>
            </div>
        </div>
    </div>

    <!-- Audio player -->
    <audio controls>
        <source src="{{ $streamUrl }}" type="audio/mpeg">
    </audio>

    <!-- Download button -->
    <button class="btn-primary btn-download-track">
        <i class="fas fa-download"></i> Download Track
    </button>

    <!-- ADD: Share buttons -->
    <x-share-buttons type="track" :id="$track->id" />
</div>
```

### Example 3: Downloaded Tracks Page

```php
<!-- In member's downloaded tracks history -->

<h2>My Downloaded Tracks</h2>
<p>Tracks you've downloaded and can share with your followers</p>

<div class="downloaded-tracks">
    @foreach($downloadedTracks as $download)
    <div class="download-item">
        <div class="track-info">
            <img src="{{ $download->track->imgpage }}" alt="{{ $download->track->title }}">
            <div>
                <h4>{{ $download->track->title }}</h4>
                <p>{{ $download->track->artist }}</p>
                <p class="download-date">Downloaded {{ $download->created_at->diffForHumans() }}</p>
            </div>
        </div>

        <!-- ADD: Share buttons with call-to-action -->
        <div class="share-section">
            <p class="share-cta">Love this track? Share it with your followers!</p>
            <x-share-buttons type="track" :id="$download->track->id" />
        </div>
    </div>
    @endforeach
</div>
```

---

## 3. Artist/Client Dashboard Integration

### Example 1: Track Reviews List

```php
<!-- In client's track reviews page -->

<h2>DJ Reviews for "{{ $track->title }}"</h2>

<div class="reviews-list">
    @foreach($reviews as $review)
    <div class="review-item {{ $review->whatrate >= 4 ? 'positive-review' : '' }}">
        <div class="review-header">
            <div class="dj-info">
                <strong>{{ $review->dj_name }}</strong>
                @if($review->dj_location)
                <span class="location">{{ $review->dj_location }}</span>
                @endif
            </div>
            <div class="rating">
                @for($i = 1; $i <= 5; $i++)
                    @if($i <= $review->whatrate)
                    <i class="fas fa-star"></i>
                    @else
                    <i class="far fa-star"></i>
                    @endif
                @endfor
            </div>
        </div>

        <div class="review-content">
            <p>{{ urldecode($review->additionalcomments) }}</p>
        </div>

        <div class="review-meta">
            <span>Reviewed {{ $review->created_at->diffForHumans() }}</span>
            @if($review->willplay)
            <span class="badge-success">Will Play</span>
            @endif
        </div>

        <!-- ADD: Share buttons for positive reviews (4-5 stars) -->
        @if($review->whatrate >= 4 && $review->is_shareable)
        <div class="share-section">
            <p class="share-hint"><i class="fas fa-bullhorn"></i> This positive review is shareable!</p>
            <x-share-buttons type="review" :id="$review->id" />
        </div>
        @endif

        <!-- Show message for non-shareable reviews -->
        @if($review->whatrate < 4)
        <p class="text-muted"><small>Reviews below 4 stars cannot be shared publicly</small></p>
        @endif
    </div>
    @endforeach
</div>
```

### Example 2: Track Dashboard with Review Highlights

```php
<!-- In client's main track dashboard -->

<div class="track-card">
    <div class="track-info">
        <img src="{{ $track->imgpage }}" alt="{{ $track->title }}">
        <div>
            <h3>{{ $track->title }}</h3>
            <p>{{ $track->artist }}</p>
        </div>
    </div>

    <div class="track-stats">
        <div class="stat">
            <span class="value">{{ $track->download_count }}</span>
            <span class="label">Downloads</span>
        </div>
        <div class="stat">
            <span class="value">{{ $track->reviews_count }}</span>
            <span class="label">Reviews</span>
        </div>
        <div class="stat">
            <span class="value">{{ number_format($track->avg_rating, 1) }}</span>
            <span class="label">Avg Rating</span>
        </div>
    </div>

    <!-- Best review (highest rated, shareable) -->
    @if($track->best_review)
    <div class="highlight-review">
        <h4><i class="fas fa-trophy"></i> Top Review</h4>
        <div class="review-preview">
            <div class="stars">{{ str_repeat('⭐', $track->best_review->whatrate) }}</div>
            <p>"{{ Str::limit(urldecode($track->best_review->additionalcomments), 120) }}"</p>
            <p class="dj-name">- {{ $track->best_review->dj_name }}</p>
        </div>

        <!-- ADD: Share this top review -->
        <x-share-buttons type="review" :id="$track->best_review->id" :showTitle="false" />
    </div>
    @endif

    <!-- Track share button -->
    <div class="track-actions">
        <button class="btn-edit">Edit Track</button>
        <button class="btn-view-reviews">View All Reviews ({{ $track->reviews_count }})</button>

        <!-- ADD: Share track button -->
        <button onclick="shareItem('copy_link', 'track', {{ $track->id }})" class="btn-share">
            <i class="fas fa-link"></i> Get Share Link
        </button>
    </div>
</div>
```

### Example 3: Notifications with Share Prompts

```php
<!-- In client's notifications page -->

<div class="notifications">
    @foreach($notifications as $notification)

    @if($notification->type === 'new_review' && $notification->review->whatrate >= 4)
    <div class="notification notification-positive">
        <i class="fas fa-star notification-icon"></i>
        <div class="notification-content">
            <h4>New 5-Star Review!</h4>
            <p>DJ {{ $notification->review->dj_name }} gave "{{ $notification->track->title }}" {{ $notification->review->whatrate }} stars!</p>
            <p class="review-excerpt">"{{ Str::limit(urldecode($notification->review->additionalcomments), 100) }}"</p>

            <!-- ADD: Quick share buttons -->
            <div class="notification-actions">
                <a href="{{ url('/client/tracks/' . $notification->track->id . '/reviews') }}" class="btn-view">View Review</a>

                <!-- Quick share actions -->
                <button onclick="shareItem('facebook', 'review', {{ $notification->review->id }})" class="btn-quick-share">
                    <i class="fab fa-facebook"></i> Share
                </button>
                <button onclick="shareItem('twitter', 'review', {{ $notification->review->id }})" class="btn-quick-share">
                    <i class="fab fa-twitter"></i> Tweet
                </button>
            </div>
        </div>
        <span class="notification-time">{{ $notification->created_at->diffForHumans() }}</span>
    </div>
    @endif

    @endforeach
</div>
```

---

## 4. Controller Examples

### DJ Dashboard Controller

```php
<?php

namespace App\Http\Controllers\Members;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class MemberDashboardController extends Controller
{
    /**
     * Show downloaded tracks
     */
    public function downloadedTracks()
    {
        $memberId = Session::get('memberId');

        // Get tracks this DJ has downloaded
        $downloadedTracks = DB::table('track_downloads')
            ->join('tracks', 'track_downloads.track_id', '=', 'tracks.id')
            ->where('track_downloads.member_id', $memberId)
            ->select('tracks.*', 'track_downloads.created_at as downloaded_at')
            ->orderBy('track_downloads.created_at', 'DESC')
            ->get();

        return view('member.downloaded-tracks', compact('downloadedTracks'));
    }

    /**
     * Show DJ share statistics
     */
    public function shareStats()
    {
        $memberId = Session::get('memberId');

        // Get share statistics
        $shareStats = DB::table('track_shares')
            ->where('user_id', $memberId)
            ->where('user_type', 'member')
            ->selectRaw('
                platform,
                COUNT(*) as count,
                DATE(shared_at) as share_date
            ')
            ->groupBy('platform', 'share_date')
            ->orderBy('share_date', 'DESC')
            ->get();

        // Most shared tracks by this DJ
        $topSharedTracks = DB::table('track_shares')
            ->join('tracks', 'track_shares.shareable_id', '=', 'tracks.id')
            ->where('track_shares.user_id', $memberId)
            ->where('track_shares.user_type', 'member')
            ->where('track_shares.shareable_type', 'track')
            ->selectRaw('
                tracks.*,
                COUNT(*) as share_count
            ')
            ->groupBy('tracks.id')
            ->orderBy('share_count', 'DESC')
            ->limit(10)
            ->get();

        return view('member.share-stats', compact('shareStats', 'topSharedTracks'));
    }
}
```

### Artist/Client Dashboard Controller

```php
<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Session;

class ClientDashboardController extends Controller
{
    /**
     * Show track with reviews
     */
    public function trackReviews($trackId)
    {
        $clientId = Session::get('clientId');

        // Get track (verify ownership)
        $track = DB::table('tracks')
            ->where('id', $trackId)
            ->where('client', $clientId)
            ->first();

        if (!$track) {
            abort(404, 'Track not found');
        }

        // Get reviews with DJ info
        $reviews = DB::table('tracks_reviews')
            ->join('members', 'tracks_reviews.member', '=', 'members.id')
            ->where('tracks_reviews.track', $trackId)
            ->select([
                'tracks_reviews.*',
                DB::raw("CONCAT(members.fname, ' ', members.lname) as dj_name"),
                'members.city as dj_location',
                'members.allow_review_sharing'
            ])
            ->orderBy('tracks_reviews.whatrate', 'DESC')
            ->orderBy('tracks_reviews.created_at', 'DESC')
            ->get();

        // Calculate if review is shareable
        foreach ($reviews as $review) {
            $review->is_shareable = (
                $review->whatrate >= 4 &&
                $review->allow_review_sharing &&
                ($review->is_shareable ?? true)
            );
        }

        return view('client.track-reviews', compact('track', 'reviews'));
    }

    /**
     * Show review share statistics
     */
    public function reviewShareStats()
    {
        $clientId = Session::get('clientId');

        // Get track IDs owned by this client
        $trackIds = DB::table('tracks')
            ->where('client', $clientId)
            ->pluck('id');

        // Get review IDs for these tracks
        $reviewIds = DB::table('tracks_reviews')
            ->whereIn('track', $trackIds)
            ->pluck('id');

        // Get share statistics for reviews
        $reviewShareStats = DB::table('track_shares')
            ->where('shareable_type', 'review')
            ->whereIn('shareable_id', $reviewIds)
            ->selectRaw('
                platform,
                COUNT(*) as count
            ')
            ->groupBy('platform')
            ->get();

        // Most shared reviews
        $topSharedReviews = DB::table('tracks_reviews')
            ->join('tracks', 'tracks_reviews.track', '=', 'tracks.id')
            ->join('members', 'tracks_reviews.member', '=', 'members.id')
            ->whereIn('tracks_reviews.track', $trackIds)
            ->where('tracks_reviews.share_count', '>', 0)
            ->select([
                'tracks_reviews.*',
                'tracks.title as track_title',
                DB::raw("CONCAT(members.fname, ' ', members.lname) as dj_name")
            ])
            ->orderBy('tracks_reviews.share_count', 'DESC')
            ->limit(10)
            ->get();

        return view('client.review-share-stats', compact('reviewShareStats', 'topSharedReviews'));
    }
}
```

---

## 5. Custom Styling Examples

### Compact Share Buttons (Horizontal)

```html
<div class="share-buttons-compact">
    <span class="share-label">Share:</span>
    <button onclick="shareItem('facebook', 'track', {{ $track->id }})" class="btn-icon btn-facebook" title="Facebook">
        <i class="fab fa-facebook-f"></i>
    </button>
    <button onclick="shareItem('twitter', 'track', {{ $track->id }})" class="btn-icon btn-twitter" title="Twitter">
        <i class="fab fa-twitter"></i>
    </button>
    <button onclick="copyShareLink('track', {{ $track->id }})" class="btn-icon btn-copy" title="Copy Link">
        <i class="fas fa-link"></i>
    </button>
</div>

<style>
.share-buttons-compact {
    display: flex;
    align-items: center;
    gap: 8px;
}
.share-label {
    font-size: 14px;
    color: #666;
}
.btn-icon {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: none;
    color: white;
    cursor: pointer;
    transition: transform 0.2s;
}
.btn-icon:hover {
    transform: scale(1.1);
}
.btn-icon.btn-facebook { background: #1877f2; }
.btn-icon.btn-twitter { background: #1da1f2; }
.btn-icon.btn-copy { background: #6c757d; }
</style>
```

### Review Card with Share Highlight

```html
<div class="review-card-shareable">
    <div class="review-badge">
        <i class="fas fa-star"></i> 5-Star Review
    </div>
    <div class="review-stars">⭐⭐⭐⭐⭐</div>
    <blockquote class="review-quote">
        "{{ $review->comment }}"
    </blockquote>
    <div class="review-author">- {{ $review->dj_name }}</div>

    <div class="share-prompt">
        <i class="fas fa-bullhorn"></i>
        <span>Share this great feedback!</span>
    </div>

    <x-share-buttons type="review" :id="$review->id" />
</div>

<style>
.review-card-shareable {
    border: 2px solid #28a745;
    border-radius: 12px;
    padding: 20px;
    background: linear-gradient(to bottom, #fff 0%, #f0fff4 100%);
}
.review-badge {
    display: inline-block;
    background: #28a745;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: bold;
    margin-bottom: 10px;
}
.review-quote {
    font-size: 18px;
    font-style: italic;
    color: #1a1a1a;
    border-left: 4px solid #28a745;
    padding-left: 15px;
    margin: 15px 0;
}
.share-prompt {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #28a745;
    font-weight: bold;
    margin: 15px 0 10px;
}
</style>
```

---

## 6. Analytics Dashboard Examples

### Share Statistics Widget

```php
<!-- In client dashboard -->

<div class="stats-widget">
    <h3><i class="fas fa-chart-line"></i> Social Sharing Stats</h3>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $totalShares }}</div>
            <div class="stat-label">Total Shares</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ $reviewShares }}</div>
            <div class="stat-label">Review Shares</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ $trackShares }}</div>
            <div class="stat-label">Track Shares</div>
        </div>

        <div class="stat-card">
            <div class="stat-value">{{ $pageViews }}</div>
            <div class="stat-label">Public Page Views</div>
        </div>
    </div>

    <div class="platform-breakdown">
        <h4>Shares by Platform</h4>
        @foreach($platformStats as $platform => $count)
        <div class="platform-stat">
            <span class="platform-name">
                <i class="fab fa-{{ $platform }}"></i>
                {{ ucfirst($platform) }}
            </span>
            <div class="platform-bar">
                <div class="platform-fill" style="width: {{ ($count / $totalShares * 100) }}%"></div>
            </div>
            <span class="platform-count">{{ $count }}</span>
        </div>
        @endforeach
    </div>
</div>
```

---

## 7. Testing Checklist

After integration, test these scenarios:

### DJ Dashboard Tests
- [ ] Share buttons appear on track listings
- [ ] Share buttons appear on downloaded tracks page
- [ ] Click Facebook share - opens popup
- [ ] Click Twitter share - opens popup with text
- [ ] Click Copy Link - copies URL and shows notification
- [ ] Share tracking increments in database

### Artist Dashboard Tests
- [ ] Share buttons appear on 4-5 star reviews only
- [ ] Share buttons do NOT appear on 1-3 star reviews
- [ ] Share buttons work correctly for reviews
- [ ] Instagram share copies caption
- [ ] TikTok share copies caption
- [ ] Download image button works (for reviews)
- [ ] Review share tracking works

### Permission Tests
- [ ] Artists cannot share reviews below 4 stars
- [ ] Artists cannot share reviews from DJs who opted out
- [ ] Public pages load correctly
- [ ] 403 errors for unauthorized reviews
- [ ] Share counts increment correctly

---

## Need Help?

See `SOCIAL_SHARING_IMPLEMENTATION_GUIDE.md` for:
- Complete implementation guide
- View templates
- JavaScript documentation
- Troubleshooting
- Testing checklist

