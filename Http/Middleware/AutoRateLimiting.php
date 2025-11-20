<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Str;

/**
 * Automatically apply rate limiting to routes based on patterns
 *
 * This middleware detects the type of request and automatically applies
 * the appropriate rate limiter without needing to modify route files.
 */
class AutoRateLimiting
{
    protected $throttle;

    public function __construct(ThrottleRequests $throttle)
    {
        $this->throttle = $throttle;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();
        $method = $request->method();

        // Authentication routes - 5/min (brute force protection)
        if ($this->isAuthRoute($path, $method)) {
            return $this->throttle->handle($request, $next, 5, 1);
        }

        // Registration routes - 10/min (bot protection)
        if ($this->isRegistrationRoute($path, $method)) {
            return $this->throttle->handle($request, $next, 10, 1);
        }

        // Upload routes - 10/hour (storage abuse protection)
        if ($this->isUploadRoute($path, $method)) {
            return $this->throttle->handle($request, $next, 10, 60);
        }

        // Messaging routes - 10/min (spam protection)
        if ($this->isMessagingRoute($path, $method)) {
            return $this->throttle->handle($request, $next, 10, 1);
        }

        // Review routes - 20/min (review bombing protection)
        if ($this->isReviewRoute($path, $method)) {
            return $this->throttle->handle($request, $next, 20, 1);
        }

        // Payment routes - 3/min (fraud protection)
        if ($this->isPaymentRoute($path, $method)) {
            return $this->throttle->handle($request, $next, 3, 1);
        }

        // Global rate limiting for all other routes - 200/min (DDoS protection)
        return $this->throttle->handle($request, $next, 200, 1);
    }

    /**
     * Check if this is an authentication route
     */
    protected function isAuthRoute(string $path, string $method): bool
    {
        if ($method !== 'POST') {
            return false;
        }

        return Str::contains($path, [
            '/login',
            '/authenticate',
            '/admin/login',
            '/member/login',
            '/client/login',
            'password/email',
            'password/reset',
        ]);
    }

    /**
     * Check if this is a registration route
     */
    protected function isRegistrationRoute(string $path, string $method): bool
    {
        if ($method !== 'POST') {
            return false;
        }

        return Str::contains($path, [
            '/register',
            '/signup',
            'registration',
        ]);
    }

    /**
     * Check if this is an upload route
     */
    protected function isUploadRoute(string $path, string $method): bool
    {
        if ($method !== 'POST') {
            return false;
        }

        return Str::contains($path, [
            '/upload',
            'tracks/upload',
            'images/upload',
            'file/upload',
            'logo/upload',
        ]);
    }

    /**
     * Check if this is a messaging route
     */
    protected function isMessagingRoute(string $path, string $method): bool
    {
        if ($method !== 'POST') {
            return false;
        }

        return Str::contains($path, [
            'message/send',
            'send/message',
            'sendmessage',
        ]);
    }

    /**
     * Check if this is a review route
     */
    protected function isReviewRoute(string $path, string $method): bool
    {
        if (!in_array($method, ['POST', 'PUT', 'PATCH'])) {
            return false;
        }

        return Str::contains($path, [
            'review/submit',
            'submit/review',
            'review/add',
            'add/review',
            'submitReview',
        ]);
    }

    /**
     * Check if this is a payment route
     */
    protected function isPaymentRoute(string $path, string $method): bool
    {
        if ($method !== 'POST') {
            return false;
        }

        return Str::contains($path, [
            '/payment',
            '/stripe',
            '/checkout',
            '/subscription',
        ]);
    }
}
