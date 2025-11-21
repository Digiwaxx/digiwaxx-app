<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\StripeWebhookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

/*
|--------------------------------------------------------------------------
| Subscription & Pricing Routes
|--------------------------------------------------------------------------
*/

// Public pricing page - shows all tiers with monthly/annual toggle
Route::get('/pricing', [SubscriptionController::class, 'pricing'])->name('pricing');

// Subscription checkout routes (requires authentication)
Route::middleware(['auth'])->group(function () {
    // Initiate subscription checkout
    Route::get('/subscribe/{tier}/{billing}', [SubscriptionController::class, 'checkout'])
        ->name('subscribe.checkout')
        ->where(['tier' => 'free|artist|label', 'billing' => 'monthly|annual']);

    // Stripe Checkout success callback
    Route::get('/subscribe/success', [SubscriptionController::class, 'success'])->name('subscribe.success');

    // Stripe Checkout cancel callback
    Route::get('/subscribe/cancel', [SubscriptionController::class, 'cancel'])->name('subscribe.cancel');

    // Current subscription management page
    Route::get('/subscription', [SubscriptionController::class, 'manage'])->name('subscription.manage');

    // Upgrade subscription
    Route::post('/subscription/upgrade', [SubscriptionController::class, 'upgrade'])->name('subscription.upgrade');

    // Cancel subscription
    Route::post('/subscription/cancel', [SubscriptionController::class, 'cancelSubscription'])->name('subscription.cancel');

    // Resume canceled subscription (if within grace period)
    Route::post('/subscription/resume', [SubscriptionController::class, 'resume'])->name('subscription.resume');

    // Stripe Customer Portal - manage payment methods, invoices
    Route::get('/billing-portal', [SubscriptionController::class, 'billingPortal'])->name('billing.portal');

    // Get current upload usage (AJAX endpoint)
    Route::get('/subscription/upload-usage', [SubscriptionController::class, 'uploadUsage'])->name('subscription.upload-usage');
});

/*
|--------------------------------------------------------------------------
| Stripe Webhook Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming webhooks from Stripe.
| IMPORTANT: Exclude these from CSRF protection in VerifyCsrfToken middleware.
|
*/

Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook'])
    ->name('stripe.webhook')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| API Routes for Upload Limit Checking
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->prefix('api')->group(function () {
    // Check if user can upload (returns remaining uploads)
    Route::get('/can-upload', [SubscriptionController::class, 'canUpload'])->name('api.can-upload');

    // Get subscription tier info
    Route::get('/subscription-info', [SubscriptionController::class, 'subscriptionInfo'])->name('api.subscription-info');
});
