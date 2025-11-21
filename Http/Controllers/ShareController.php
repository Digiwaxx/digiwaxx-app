<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception;

/**
 * ShareController
 *
 * Handles social media sharing functionality:
 * - Public track pages (for DJs sharing tracks)
 * - Public review pages (for artists sharing DJ feedback)
 * - Share tracking and analytics
 * - Shareable image generation
 */
class ShareController extends Controller
{
    /**
     * Show public track page (for DJ sharing)
     *
     * @param string $slug Track share slug
     * @return \Illuminate\View\View
     */
    public function showTrack($slug)
    {
        // Get track by share slug
        $track = DB::table('tracks')
            ->where('share_slug', $slug)
            ->where('is_public', true)
            ->first();

        if (!$track) {
            abort(404, 'Track not found or not available for sharing');
        }

        // Get track MP3s/versions
        $mp3s = DB::table('tracks_mp3s')
            ->where('track', $track->id)
            ->orderByRaw('preview DESC')
            ->get();

        // Get review stats
        $reviewStats = DB::table('tracks_reviews')
            ->where('track', $track->id)
            ->selectRaw('
                COUNT(*) as total_reviews,
                COALESCE(AVG(CAST(whatrate AS DECIMAL(3,2))), 0) as avg_rating,
                SUM(CASE WHEN willplay = 1 THEN 1 ELSE 0 END) as will_play_count
            ')
            ->first();

        // Get client/artist info
        $client = DB::table('clients')->where('id', $track->client)->first();

        // Track page view
        $this->trackPageView('track', $track->id, request());

        // Increment page views
        DB::table('tracks')
            ->where('id', $track->id)
            ->increment('public_page_views');

        return view('public.track', compact('track', 'mp3s', 'reviewStats', 'client'));
    }

    /**
     * Show public review page (for artist sharing DJ feedback)
     *
     * @param string $slug Track share slug
     * @param int $reviewId Review ID
     * @return \Illuminate\View\View
     */
    public function showReview($slug, $reviewId)
    {
        // Get track
        $track = DB::table('tracks')
            ->where('share_slug', $slug)
            ->where('is_public', true)
            ->first();

        if (!$track) {
            abort(404, 'Track not found');
        }

        // Get review
        $review = DB::table('tracks_reviews')
            ->where('id', $reviewId)
            ->where('track', $track->id)
            ->first();

        if (!$review) {
            abort(404, 'Review not found');
        }

        // Check if review is shareable
        if (!$review->is_shareable || $review->whatrate < 4) {
            abort(403, 'This review cannot be shared');
        }

        // Get DJ/member info (check privacy setting)
        $member = DB::table('members')
            ->where('id', $review->member)
            ->first();

        if ($member && !$member->allow_review_sharing) {
            abort(403, 'This DJ has disabled review sharing');
        }

        // Get client info
        $client = DB::table('clients')->where('id', $track->client)->first();

        // Track page view
        $this->trackPageView('review', $reviewId, request());

        return view('public.review', compact('track', 'review', 'member', 'client'));
    }

    /**
     * Get track share data (API endpoint for JavaScript)
     *
     * @param int $id Track ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTrackShareData($id)
    {
        $track = DB::table('tracks')
            ->where('id', $id)
            ->where('is_public', true)
            ->first();

        if (!$track) {
            return response()->json(['error' => 'Track not found'], 404);
        }

        // Generate share URL
        $publicUrl = route('track.public', $track->share_slug);

        return response()->json([
            'public_url' => $publicUrl,
            'title' => $track->title,
            'artist' => $track->artist,
            'artwork_url' => $track->imgpage ?? $track->img ?? asset('images/default-artwork.jpg'),
            'genre' => $track->genre ?? 'Electronic',
            'bpm' => $track->bpm,
            'label' => $track->label,
            // Social share texts
            'facebook_text' => "🔥 Just downloaded '{$track->title}' by {$track->artist} on Digiwaxx - this track is fire! 🎧",
            'twitter_text' => "Supporting '{$track->title}' by {$track->artist} 🎵 Get it on @Digiwaxx",
            'instagram_caption' => "🎧 New in my sets: {$track->title} by {$track->artist}\n\nLink: {$publicUrl}\n\n#DJ #NewMusic #Digiwaxx",
            'tiktok_caption' => "🔥 This track tho! {$track->title} by {$track->artist}\n\nFull track: {$publicUrl}\n\n#DJ #NewMusic #FYP"
        ]);
    }

    /**
     * Get review share data (API endpoint for JavaScript)
     *
     * @param int $id Review ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReviewShareData($id)
    {
        $review = DB::table('tracks_reviews')
            ->where('id', $id)
            ->first();

        if (!$review) {
            return response()->json(['error' => 'Review not found'], 404);
        }

        // Check if shareable
        if (!$review->is_shareable || $review->whatrate < 4) {
            return response()->json(['error' => 'This review cannot be shared'], 403);
        }

        // Get track
        $track = DB::table('tracks')->where('id', $review->track)->first();

        if (!$track || !$track->is_public) {
            return response()->json(['error' => 'Track not found'], 404);
        }

        // Get member (DJ) info
        $member = DB::table('members')->where('id', $review->member)->first();

        if (!$member || !$member->allow_review_sharing) {
            return response()->json(['error' => 'DJ has disabled review sharing'], 403);
        }

        // Generate public URL
        $publicUrl = route('review.public', [
            'slug' => $track->share_slug,
            'reviewId' => $review->id
        ]);

        // Get DJ display name
        $djName = $member->display_name ?? $member->fname . ' ' . $member->lname ?? 'Anonymous DJ';

        // Get review excerpt
        $comment = urldecode($review->additionalcomments ?? '');
        $commentExcerpt = Str::limit($comment, 100, '...');

        $stars = str_repeat('⭐', $review->whatrate);

        return response()->json([
            'public_url' => $publicUrl,
            'dj_name' => $djName,
            'dj_location' => $member->city ?? null,
            'rating' => $review->whatrate,
            'stars' => $stars,
            'comment' => $comment,
            'comment_excerpt' => $commentExcerpt,
            'track_title' => $track->title,
            'track_artist' => $track->artist,
            'track_artwork' => $track->imgpage ?? $track->img,
            // Social share texts
            'facebook_text' => "{$stars} DJ {$djName} gave my track '{$track->title}' {$review->whatrate} stars! '{$commentExcerpt}'",
            'twitter_text' => "💯 DJ {$djName}: '{$commentExcerpt}' on my track '{$track->title}' {$stars}",
            'instagram_caption' => "🙏 DJ support! \n\n{$stars} from DJ {$djName}\n\n'{$commentExcerpt}'\n\nTrack: {$track->title}\n\n#DJ #NewMusic #DJApproved",
            'tiktok_caption' => "When DJs show love 🔥\n\n{$stars} from DJ {$djName}\n\nTrack: {$track->title}\n\n#NewMusic #DJApproved #FYP"
        ]);
    }

    /**
     * Track share action (API endpoint)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function trackShare(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:track,review',
            'id' => 'required|integer',
            'platform' => 'required|in:facebook,twitter,instagram,tiktok,linkedin,whatsapp,copy_link,download_image',
            'share_text' => 'nullable|string'
        ]);

        try {
            // Insert share record
            DB::table('track_shares')->insert([
                'shareable_type' => $validated['type'],
                'shareable_id' => $validated['id'],
                'platform' => $validated['platform'],
                'user_id' => session('memberId') ?? session('clientId') ?? null,
                'user_type' => session('memberId') ? 'member' : (session('clientId') ? 'client' : null),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer' => $request->header('referer'),
                'share_text' => $validated['share_text'] ?? null,
                'shared_at' => now()
            ]);

            // Increment share count
            if ($validated['type'] === 'track') {
                DB::table('tracks')
                    ->where('id', $validated['id'])
                    ->increment('share_count');

                DB::table('tracks')
                    ->where('id', $validated['id'])
                    ->update(['last_shared_at' => now()]);
            } else {
                DB::table('tracks_reviews')
                    ->where('id', $validated['id'])
                    ->increment('share_count');

                DB::table('tracks_reviews')
                    ->where('id', $validated['id'])
                    ->update(['last_shared_at' => now()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Share tracked successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'error' => 'Failed to track share',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate shareable image for review
     *
     * @param int $id Review ID
     * @return \Illuminate\Http\Response
     */
    public function generateReviewImage($id)
    {
        $review = DB::table('tracks_reviews')
            ->where('id', $id)
            ->where('is_shareable', true)
            ->where('whatrate', '>=', 4)
            ->first();

        if (!$review) {
            abort(404, 'Review not found or not shareable');
        }

        // Get track and member info
        $track = DB::table('tracks')->where('id', $review->track)->first();
        $member = DB::table('members')->where('id', $review->member)->first();

        if (!$member || !$member->allow_review_sharing) {
            abort(403, 'DJ has disabled review sharing');
        }

        // Generate image (placeholder - implement with Intervention Image)
        $imageData = $this->createReviewShareImage($review, $track, $member);

        return response($imageData)->header('Content-Type', 'image/png');
    }

    /**
     * Create shareable review image
     *
     * @param object $review
     * @param object $track
     * @param object $member
     * @return string Image binary data
     */
    protected function createReviewShareImage($review, $track, $member)
    {
        // TODO: Implement with Intervention Image library
        // For now, return placeholder

        // This would create a 1080x1080 image with:
        // - Blurred background (track artwork)
        // - White card overlay with review text
        // - Star rating visual
        // - DJ name and location
        // - Track info at bottom
        // - Digiwaxx branding

        // Placeholder: return empty PNG
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNk+M9QDwADhgGAWjR9awAAAABJRU5ErkJggg==');
    }

    /**
     * Track page view
     *
     * @param string $type 'track' or 'review'
     * @param int $id ID of track or review
     * @param Request $request
     * @return void
     */
    protected function trackPageView($type, $id, Request $request)
    {
        DB::table('share_page_views')->insert([
            'viewable_type' => $type,
            'viewable_id' => $id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referrer' => $request->header('referer'),
            'utm_source' => $request->query('utm_source'),
            'utm_medium' => $request->query('utm_medium'),
            'utm_campaign' => $request->query('utm_campaign'),
            'device_type' => $this->detectDeviceType($request->userAgent()),
            'viewed_at' => now()
        ]);
    }

    /**
     * Detect device type from user agent
     *
     * @param string $userAgent
     * @return string
     */
    protected function detectDeviceType($userAgent)
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', $userAgent)) {
            return 'tablet';
        }
        if (preg_match('/(mobile|iphone|android|blackberry|mini|windows\sce|palm)/i', $userAgent)) {
            return 'mobile';
        }
        return 'desktop';
    }
}
