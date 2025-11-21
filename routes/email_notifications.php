<?php

/*
|--------------------------------------------------------------------------
| Email Notification & Review Routes
|--------------------------------------------------------------------------
|
| These routes handle email notification preferences, unsubscribe functionality,
| and track report downloads.
|
| Add these routes to your main routes file (web.php or routes.php)
|
*/

use App\Http\Controllers\ReviewNotificationsController;
use App\Http\Controllers\TrackReportController;
use App\Http\Controllers\EmailPreferencesController;

// ============================================================================
// Email Notification Unsubscribe Routes
// ============================================================================

// Unsubscribe from review notifications (one-click from email)
Route::get('/unsubscribe-reviews/{token}', [ReviewNotificationsController::class, 'unsubscribe'])
    ->name('reviews.unsubscribe');

// Confirm unsubscribe action
Route::post('/unsubscribe-reviews/confirm/{token}', [ReviewNotificationsController::class, 'confirmUnsubscribe'])
    ->name('reviews.unsubscribe.confirm');

// Resubscribe to review notifications
Route::get('/resubscribe-reviews/{token}', [ReviewNotificationsController::class, 'resubscribe'])
    ->name('reviews.resubscribe');

// ============================================================================
// Track Report Download Routes
// ============================================================================

// Download generated report (PDF or CSV)
Route::get('/track/report/download/{token}', [TrackReportController::class, 'download'])
    ->name('track.report.download');

// Generate new report for a track (requires authentication)
Route::get('/track/{id}/generate-report', [TrackReportController::class, 'generate'])
    ->middleware('auth')
    ->name('track.report.generate');

// Show report generation modal/page (requires authentication)
Route::get('/track/{id}/report-options', [TrackReportController::class, 'showOptions'])
    ->middleware('auth')
    ->name('track.report.options');

// Report history page (requires authentication)
Route::get('/reports/history', [TrackReportController::class, 'history'])
    ->middleware('auth')
    ->name('reports.history');

// Delete old report (requires authentication)
Route::delete('/reports/{id}', [TrackReportController::class, 'delete'])
    ->middleware('auth')
    ->name('reports.delete');

// ============================================================================
// Email Preferences Routes (requires authentication)
// ============================================================================

// Show email preferences page
Route::get('/settings/email-preferences', [EmailPreferencesController::class, 'show'])
    ->middleware('auth')
    ->name('email.preferences');

// Update email preferences
Route::post('/settings/email-preferences', [EmailPreferencesController::class, 'update'])
    ->middleware('auth')
    ->name('email.preferences.update');

// ============================================================================
// Admin Email Management Routes (requires admin authentication)
// ============================================================================

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Email settings page
    Route::get('/email-settings', [App\Http\Controllers\Admin\AdminEmailController::class, 'settings'])
        ->name('admin.email.settings');

    // Update email settings
    Route::post('/email-settings', [App\Http\Controllers\Admin\AdminEmailController::class, 'updateSettings'])
        ->name('admin.email.settings.update');

    // Email statistics dashboard
    Route::get('/email-statistics', [App\Http\Controllers\Admin\AdminEmailController::class, 'statistics'])
        ->name('admin.email.statistics');

    // View email logs
    Route::get('/email-logs', [App\Http\Controllers\Admin\AdminEmailController::class, 'logs'])
        ->name('admin.email.logs');

    // Send test email
    Route::post('/email-test', [App\Http\Controllers\Admin\AdminEmailController::class, 'sendTest'])
        ->name('admin.email.test');

    // Resend failed emails
    Route::post('/email-resend/{id}', [App\Http\Controllers\Admin\AdminEmailController::class, 'resend'])
        ->name('admin.email.resend');
});
