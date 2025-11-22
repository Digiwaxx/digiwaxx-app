# Rate Limiting Implementation Guide

## Overview
This guide provides complete rate limiting configuration for the Digiwaxx application to prevent brute force attacks, API abuse, and excessive resource consumption.

---

## Quick Implementation (5 minutes)

Add this to your `routes/web.php` file:

```php
use Illuminate\Support\Facades\Route;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

// Define rate limiters
RateLimiter::for('auth', function (Request $request) {
    return Limit::perMinute(5)->by($request->ip());
});

RateLimiter::for('api', function (Request $request) {
    return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
});

RateLimiter::for('uploads', function (Request $request) {
    return Limit::perHour(10)->by(optional($request->user())->id ?: $request->ip());
});

RateLimiter::for('messages', function (Request $request) {
    return Limit::perMinute(10)->by(optional($request->user())->id ?: $request->ip());
});
```

---

## Complete Route Protection

### 1. Authentication Routes
**Purpose:** Prevent brute force login attacks

```php
// Login routes
Route::post('/login', [LoginController::class, 'authenticate'])
    ->middleware('throttle:auth')
    ->name('login.authenticate');

Route::post('/admin/login', [AdminLoginController::class, 'authenticate'])
    ->middleware('throttle:auth')
    ->name('admin.login.authenticate');

// Password reset routes
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:auth')
    ->name('password.email');

Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
    ->middleware('throttle:auth')
    ->name('password.update');
```

**Limits:**
- **5 attempts per minute** per IP address
- After 5 failed attempts: 60 second wait
- Protects against: Brute force attacks, credential stuffing

---

### 2. Registration Routes
**Purpose:** Prevent automated account creation

```php
// Member registration
Route::post('/register/member', [MemberRegisterController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('member.register');

// Client registration
Route::post('/register/client', [ClientRegisterController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('client.register');
```

**Limits:**
- **10 registrations per minute** per IP
- Protects against: Spam accounts, bot registration

---

### 3. File Upload Routes
**Purpose:** Prevent storage abuse and server overload

```php
// Track uploads
Route::post('/client/tracks/upload', [ClientsTrackController::class, 'upload'])
    ->middleware(['auth:client', 'throttle:uploads'])
    ->name('client.tracks.upload');

// Image uploads
Route::post('/client/images/upload', [ClientsTrackController::class, 'uploadImage'])
    ->middleware(['auth:client', 'throttle:uploads'])
    ->name('client.images.upload');
```

**Limits:**
- **10 uploads per hour** per user
- **50 MB per file** (configure in validation)
- Protects against: Storage abuse, bandwidth exhaustion

---

### 4. Messaging Routes
**Purpose:** Prevent message spam

```php
// Send message
Route::post('/member/message/send', [MemberDashboardController::class, 'sendMessage'])
    ->middleware(['auth:member', 'throttle:messages'])
    ->name('member.message.send');

Route::post('/client/message/send', [ClientDashboardController::class, 'sendMessage'])
    ->middleware(['auth:client', 'throttle:messages'])
    ->name('client.message.send');
```

**Limits:**
- **10 messages per minute** per user
- Protects against: Message spam, harassment

---

### 5. Review Submission Routes
**Purpose:** Prevent review manipulation

```php
// Submit review
Route::post('/member/review/submit', [MemberDashboardController::class, 'submitReview'])
    ->middleware(['auth:member', 'throttle:20,1'])
    ->name('member.review.submit');

// Update review
Route::put('/member/review/{id}', [MemberDashboardController::class, 'updateReview'])
    ->middleware(['auth:member', 'throttle:20,1'])
    ->name('member.review.update');
```

**Limits:**
- **20 reviews per minute** per user
- Protects against: Review bombing, spam reviews

---

### 6. API Routes
**Purpose:** Prevent API abuse

```php
// API endpoints
Route::middleware(['auth:api', 'throttle:api'])->group(function () {
    Route::get('/api/tracks', [ApiController::class, 'getTracks']);
    Route::get('/api/track/{id}', [ApiController::class, 'getTrack']);
    Route::post('/api/track/play', [ApiController::class, 'recordPlay']);
});
```

**Limits:**
- **60 requests per minute** per user/IP
- Protects against: API abuse, scraping

---

### 7. Payment Routes
**Purpose:** Prevent payment fraud attempts

```php
// Stripe payments
Route::post('/payment/process', [StripeDigiPaymentController::class, 'processPayment'])
    ->middleware(['auth', 'throttle:3,1'])
    ->name('payment.process');

Route::post('/payment/subscription', [StripeDigiPaymentController::class, 'createSubscription'])
    ->middleware(['auth', 'throttle:3,1'])
    ->name('payment.subscription');
```

**Limits:**
- **3 payment attempts per minute** per user
- Protects against: Card testing, fraud attempts

---

### 8. Admin Routes
**Purpose:** Protect administrative functions

```php
// Admin operations
Route::middleware(['auth:admin', 'throttle:100,1'])->prefix('admin')->group(function () {
    Route::post('/track/approve', [AdminController::class, 'approveTrack']);
    Route::delete('/track/{id}', [AdminController::class, 'deleteTrack']);
    Route::put('/user/{id}/ban', [AdminController::class, 'banUser']);
});
```

**Limits:**
- **100 requests per minute** per admin
- Higher limit for legitimate administrative work
- Still protects against: Compromised admin accounts

---

## Advanced Configuration

### 1. Custom Rate Limiter with Dynamic Limits

```php
// In RouteServiceProvider.php or routes/web.php
RateLimiter::for('dynamic', function (Request $request) {
    $user = $request->user();

    // Premium members get higher limits
    if ($user && $user->isPremium()) {
        return Limit::perMinute(100)->by($user->id);
    }

    // Free members get standard limits
    if ($user) {
        return Limit::perMinute(60)->by($user->id);
    }

    // Unauthenticated users get low limits
    return Limit::perMinute(20)->by($request->ip());
});
```

### 2. Per-User Limits with Redis

```php
// Requires Redis configured in .env
RateLimiter::for('per-user', function (Request $request) {
    $key = $request->user() ? 'user:' . $request->user()->id : 'ip:' . $request->ip();

    return Limit::perMinute(60)
        ->by($key)
        ->response(function () {
            return response('Too many requests. Please slow down.', 429);
        });
});
```

### 3. Global Rate Limiter

```php
// Apply to all routes
Route::middleware(['throttle:global'])->group(function () {
    // All your routes
});

RateLimiter::for('global', function (Request $request) {
    return Limit::perMinute(200)->by($request->ip());
});
```

---

## Response Handling

### Custom 429 (Too Many Requests) Response

Create `app/Exceptions/Handler.php` method:

```php
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

public function render($request, Throwable $exception)
{
    if ($exception instanceof TooManyRequestsHttpException) {
        $retryAfter = $exception->getHeaders()['Retry-After'] ?? 60;

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Too many requests',
                'message' => 'Please wait ' . $retryAfter . ' seconds before trying again.',
                'retry_after' => $retryAfter
            ], 429);
        }

        return redirect()->back()->with('error',
            'Too many requests. Please wait ' . $retryAfter . ' seconds before trying again.'
        );
    }

    return parent::render($request, $exception);
}
```

---

## Testing Rate Limits

### Test Login Rate Limiting

```bash
# Send 6 login attempts rapidly
for i in {1..6}; do
    curl -X POST http://localhost/login \
        -d "email=test@test.com&password=wrong" \
        -H "Content-Type: application/x-www-form-urlencoded"
    echo "Attempt $i"
done
```

**Expected:** First 5 succeed (fail login), 6th returns 429

### Test Upload Rate Limiting

```bash
# Send 11 uploads in one hour
for i in {1..11}; do
    curl -X POST http://localhost/client/tracks/upload \
        -H "Authorization: Bearer $TOKEN" \
        -F "file=@test.mp3"
    echo "Upload $i"
    sleep 360  # Wait 6 minutes between uploads
done
```

**Expected:** First 10 succeed, 11th returns 429

---

## Monitoring

### Log Rate Limit Hits

Add to `app/Http/Middleware/ThrottleRequests.php`:

```php
protected function buildException($key, $maxAttempts)
{
    Log::warning('Rate limit exceeded', [
        'key' => $key,
        'ip' => request()->ip(),
        'user' => auth()->id(),
        'route' => request()->path(),
        'max_attempts' => $maxAttempts
    ]);

    return parent::buildException($key, $maxAttempts);
}
```

### View Rate Limit Status

```php
// Check remaining attempts
Route::get('/api/rate-limit-status', function (Request $request) {
    $limiter = RateLimiter::for('api', $request);

    return response()->json([
        'remaining' => $limiter->remaining(),
        'retry_after' => $limiter->availableIn(),
    ]);
});
```

---

## Bypass for Testing

### Temporarily Disable Rate Limiting

In `.env`:
```env
RATE_LIMIT_ENABLED=false
```

In `routes/web.php`:
```php
if (config('app.rate_limit_enabled', true)) {
    // Apply rate limiting
} else {
    // Skip rate limiting
}
```

---

## Production Checklist

- [ ] Configure Redis for better performance (optional but recommended)
- [ ] Set up rate limit monitoring/alerts
- [ ] Test all rate-limited routes
- [ ] Document limits in API documentation
- [ ] Configure custom 429 error pages
- [ ] Set up log monitoring for rate limit violations
- [ ] Review limits after 1 week of production use
- [ ] Adjust limits based on legitimate usage patterns

---

## Rate Limit Summary Table

| Route Type | Limit | Window | Identifier |
|------------|-------|--------|------------|
| Login/Auth | 5 | 1 minute | IP |
| Registration | 10 | 1 minute | IP |
| File Uploads | 10 | 1 hour | User ID |
| Messages | 10 | 1 minute | User ID |
| Reviews | 20 | 1 minute | User ID |
| API Calls | 60 | 1 minute | User ID/IP |
| Payments | 3 | 1 minute | User ID |
| Admin Actions | 100 | 1 minute | Admin ID |

---

## Troubleshooting

### "Rate limit not working"
- Check that routes have `->middleware('throttle:X,Y')`
- Verify cache driver is configured (`config/cache.php`)
- Clear cache: `php artisan cache:clear`

### "Rate limit too strict"
- Increase limits gradually
- Use per-user limits instead of per-IP for authenticated routes
- Consider premium user tiers with higher limits

### "Rate limit bypassed"
- Check for CDN/proxy headers (`X-Forwarded-For`)
- Configure `TrustProxies` middleware correctly
- Use Redis for distributed rate limiting

---

**Implementation Time:** 10-15 minutes
**Testing Time:** 5-10 minutes
**Priority:** HIGH
**Impact:** Prevents abuse, protects infrastructure

---

*Created: 2025-11-20*
*Purpose: Complete rate limiting implementation for Digiwaxx platform*
