# Routes Rate Limiting Configuration

## IMPORTANT: Apply These Changes to Your routes/web.php File

This file contains the rate limiting configuration that should be applied to your Laravel routes file.

Since the routes directory is located in your Laravel project root (not in the app directory), you'll need to manually apply these changes.

---

## Location

File: `routes/web.php` (in your Laravel project root)

---

## Rate Limiting Configuration

Add rate limiting to protect against brute force attacks and DoS:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\StripeDigiPaymentController;

/*
|--------------------------------------------------------------------------
| SECURITY: Rate Limited Routes
|--------------------------------------------------------------------------
|
| Critical routes with rate limiting applied to prevent:
| - Brute force login attacks
| - Password reset abuse
| - Payment fraud
| - API abuse
|
*/

// Authentication Routes - CRITICAL: Rate limit login attempts
Route::post('/login', [LoginController::class, 'authenticate'])
    ->middleware('throttle:5,1') // 5 attempts per minute
    ->name('login.authenticate');

Route::post('/authenticate', [LoginController::class, 'authenticate'])
    ->middleware('throttle:5,1');

// Admin Login - CRITICAL: Stricter rate limiting for admin access
Route::post('/admin/login', [AdminLoginController::class, 'login'])
    ->middleware('throttle:3,1') // 3 attempts per minute
    ->name('admin.login');

// Registration - Prevent spam account creation
Route::post('/register', [RegisterController::class, 'storeUser'])
    ->middleware('throttle:3,10') // 3 attempts per 10 minutes
    ->name('register.store');

Route::post('/Client_register', [Clients\ClientRegisterController::class, 'store'])
    ->middleware('throttle:3,10');

Route::post('/Member_register', [Members\MemberRegisterController::class, 'store'])
    ->middleware('throttle:3,10');

// Password Reset - Prevent reset link abuse
Route::post('/password/email', [Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->middleware('throttle:3,60') // 3 attempts per hour
    ->name('password.email');

Route::post('/password/reset', [Auth\ResetPasswordController::class, 'reset'])
    ->middleware('throttle:5,60') // 5 attempts per hour
    ->name('password.update');

// Payment Routes - CRITICAL: Prevent payment fraud
Route::post('/stripe/checkout', [StripeDigiPaymentController::class, 'stripePostCheckout'])
    ->middleware(['throttle:10,60', 'auth']) // 10 payment attempts per hour, must be authenticated
    ->name('stripe.checkout');

Route::post('/package_payment', [StripeDigiPaymentController::class, 'package_payment'])
    ->middleware(['throttle:10,60']);

Route::post('/package_payment_client', [StripeDigiPaymentController::class, 'package_payment_client'])
    ->middleware(['throttle:10,60']);

Route::post('/upgrade_package_payment_member', [StripeDigiPaymentController::class, 'upgrade_package_payment_member'])
    ->middleware(['throttle:10,60', 'auth']);

// File Upload Routes - Prevent upload abuse
Route::post('/admin/tracks/store', [AdminAddTracksController::class, 'save_admin_add_track_new'])
    ->middleware(['auth:admin', 'throttle:20,60']) // 20 uploads per hour
    ->name('tracks.store');

Route::post('/admin/tracks/upload', [AdminAddTracksController::class, 'upload'])
    ->middleware(['auth:admin', 'throttle:50,60']) // 50 file uploads per hour
    ->name('tracks.upload');

// API Routes - General API rate limiting
Route::prefix('api')->middleware('throttle:60,1')->group(function () {
    // API routes with 60 requests per minute
    // Add your API routes here
});

/*
|--------------------------------------------------------------------------
| Non-Critical Routes
|--------------------------------------------------------------------------
|
| Other routes with more lenient rate limiting
|
*/

// General authenticated routes
Route::middleware(['auth', 'throttle:100,1'])->group(function () {
    // 100 requests per minute for authenticated users
    // Add your authenticated routes here
});

// Public routes (view-only)
Route::middleware('throttle:60,1')->group(function () {
    // 60 requests per minute for public access
    // Add your public view routes here
});
```

---

## Rate Limiting Explained

### Format: `throttle:attempts,minutes`

- **throttle:5,1** = 5 attempts per 1 minute
- **throttle:3,10** = 3 attempts per 10 minutes
- **throttle:10,60** = 10 attempts per 60 minutes (1 hour)

### Recommended Limits by Route Type

| Route Type | Limit | Reasoning |
|------------|-------|-----------|
| Login | 5 per minute | Prevent brute force, allow legitimate retries |
| Admin Login | 3 per minute | Stricter protection for admin access |
| Registration | 3 per 10 min | Prevent spam account creation |
| Password Reset | 3 per hour | Prevent reset abuse while allowing legitimate requests |
| Payments | 10 per hour | Prevent fraud while allowing multiple purchases |
| File Uploads | 20-50 per hour | Prevent abuse, depends on file size |
| API | 60 per minute | Balance between usability and protection |
| Public Views | 60 per minute | Prevent scraping without blocking legitimate users |

---

## Custom Rate Limiting

### Create Custom Rate Limiters

In `app/Providers/RouteServiceProvider.php`:

```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

public function boot()
{
    // Custom rate limiter for API
    RateLimiter::for('api', function (Request $request) {
        return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
    });

    // Custom rate limiter for login
    RateLimiter::for('login', function (Request $request) {
        $email = $request->input('email');
        $key = 'login:' . $email . $request->ip();

        return Limit::perMinute(5)->by($key)->response(function () {
            return response()->json([
                'message' => 'Too many login attempts. Please try again in 60 seconds.'
            ], 429);
        });
    });

    // Custom rate limiter for payments
    RateLimiter::for('payments', function (Request $request) {
        return [
            Limit::perMinute(2)->by($request->user()->id),
            Limit::perHour(10)->by($request->user()->id),
        ];
    });
}
```

### Use Custom Rate Limiters

```php
Route::post('/login')->middleware('throttle:login');
Route::post('/stripe/checkout')->middleware('throttle:payments');
```

---

## Response Headers

When rate limit is hit, Laravel returns:

```
HTTP/1.1 429 Too Many Requests
X-RateLimit-Limit: 5
X-RateLimit-Remaining: 0
Retry-After: 60
```

---

## Testing Rate Limiting

### Test Login Rate Limiting

```bash
# Make 6 rapid login attempts (should block on 6th)
for i in {1..6}; do
  curl -X POST http://localhost/login \
    -d "email=test@test.com&password=wrong&membertype=client"
  echo "\nAttempt $i completed"
  sleep 1
done
```

### Test Payment Rate Limiting

```bash
# Make multiple payment requests
for i in {1..12}; do
  curl -X POST http://localhost/stripe/checkout \
    -H "Authorization: Bearer YOUR_TOKEN" \
    -d "amount=1000"
  echo "\nAttempt $i completed"
done
```

---

## Monitoring Rate Limiting

### Log Rate Limit Hits

Add to `app/Http/Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... existing middleware
        function ($request, $next) {
            $response = $next($request);

            if ($response->getStatusCode() === 429) {
                Log::warning('Rate limit exceeded', [
                    'ip' => $request->ip(),
                    'url' => $request->fullUrl(),
                    'user_id' => auth()->id(),
                ]);
            }

            return $response;
        },
    ],
];
```

### Check Rate Limit Stats

```php
// In a controller or command
use Illuminate\Support\Facades\Cache;

$key = 'throttle:' . $request->ip();
$attempts = Cache::get($key, 0);
$availableAt = Cache::get($key . ':timer');

echo "Attempts: $attempts\n";
echo "Reset at: " . date('Y-m-d H:i:s', $availableAt);
```

---

## Bypassing Rate Limits (For Testing Only!)

### Option 1: Disable in .env

```env
RATE_LIMITING_ENABLED=false
```

Then in routes:
```php
if (env('RATE_LIMITING_ENABLED', true)) {
    Route::middleware('throttle:5,1')->group(function () {
        // ...
    });
} else {
    Route::group(function () {
        // ...
    });
}
```

### Option 2: Whitelist IPs

```php
RateLimiter::for('login', function (Request $request) {
    // Whitelist specific IPs (for testing/monitoring services)
    $whitelistedIps = ['127.0.0.1', '::1'];

    if (in_array($request->ip(), $whitelistedIps)) {
        return Limit::none();
    }

    return Limit::perMinute(5);
});
```

---

## Best Practices

1. **Always rate limit authentication endpoints**
   - Prevents brute force attacks
   - Protects user accounts

2. **Use stricter limits for sensitive operations**
   - Admin access
   - Password resets
   - Payment processing

3. **Rate limit by both IP and user**
   - Prevents single user from multiple IPs
   - Prevents single IP from attacking multiple accounts

4. **Return helpful error messages**
   - Tell users when they can retry
   - Don't reveal security information

5. **Monitor rate limit hits**
   - Log excessive attempts
   - Alert on suspicious patterns
   - Block persistent attackers

6. **Adjust limits based on usage patterns**
   - Monitor legitimate user behavior
   - Adjust if too restrictive
   - Tighten if abuse detected

---

## Integration with Existing Code

### Update LoginController

```php
// In app/Http/Controllers/Auth/LoginController.php
use Illuminate\Support\Facades\RateLimiter;

public function authenticate(Request $request)
{
    // Check rate limit manually if needed
    $key = 'login:' . $request->input('email') . $request->ip();

    if (RateLimiter::tooManyAttempts($key, 5)) {
        $seconds = RateLimiter::availableIn($key);

        return back()->withErrors([
            'email' => "Too many login attempts. Please try again in $seconds seconds."
        ]);
    }

    // Attempt authentication
    // ...

    // On failed attempt
    RateLimiter::hit($key, 60); // Lock for 60 seconds after 5 attempts

    // On successful attempt
    RateLimiter::clear($key);
}
```

---

## Troubleshooting

### Rate Limit Not Working

1. Check cache is configured:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   ```

2. Verify middleware is registered in `Kernel.php`

3. Check route is using correct middleware:
   ```bash
   php artisan route:list --columns=uri,middleware
   ```

### Rate Limit Too Restrictive

1. Increase limits temporarily
2. Check logs for legitimate users being blocked
3. Consider per-user instead of per-IP limits
4. Implement exponential backoff instead of hard limits

---

## Security Considerations

- **Don't disable rate limiting in production**
- **Monitor rate limit logs for attack patterns**
- **Combine with other security measures** (CAPTCHA, 2FA)
- **Use Redis for distributed rate limiting** in multi-server setups
- **Consider geo-blocking** for persistent attackers
- **Implement account lockout** after multiple failed attempts

---

Last Updated: 2025-11-20
