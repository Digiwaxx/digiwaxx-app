<?php

/**
 * Social Sharing Routes
 *
 * These routes enable social media sharing of tracks and DJ reviews.
 *
 * To integrate: Include this file in your main routes file with:
 * require __DIR__ . '/social-sharing.php';
 */

use App\Http\Controllers\ShareController;
use Illuminate\Support\Facades\Route;

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
    ->name('api.track.share-data');

// Get review share data (for JavaScript share buttons)
Route::get('/api/reviews/{id}/share-data', [ShareController::class, 'getReviewShareData'])
    ->name('api.review.share-data');

// Track share action (analytics)
Route::post('/api/share-tracking', [ShareController::class, 'trackShare'])
    ->name('api.share.track');

// Generate shareable review image (for Instagram/TikTok)
Route::get('/api/reviews/{id}/shareable-image', [ShareController::class, 'generateReviewImage'])
    ->name('api.review.shareable-image');
