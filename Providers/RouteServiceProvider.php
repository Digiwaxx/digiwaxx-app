<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * This is used by Laravel authentication to redirect users after login.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * The controller namespace for the application.
     *
     * When present, controller route declarations will automatically be prefixed with this namespace.
     *
     * @var string|null
     */
    // protected $namespace = 'App\\Http\\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        // API rate limiting - 60 requests per minute
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });

        // Authentication rate limiting - 5 attempts per minute (brute force protection)
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // File upload rate limiting - 10 uploads per hour (storage abuse protection)
        RateLimiter::for('uploads', function (Request $request) {
            return Limit::perHour(10)->by(optional($request->user())->id ?: $request->ip());
        });

        // Messaging rate limiting - 10 messages per minute (spam protection)
        RateLimiter::for('messages', function (Request $request) {
            return Limit::perMinute(10)->by(optional($request->user())->id ?: $request->ip());
        });

        // Review rate limiting - 20 reviews per minute (review bombing protection)
        RateLimiter::for('reviews', function (Request $request) {
            return Limit::perMinute(20)->by(optional($request->user())->id ?: $request->ip());
        });

        // Payment rate limiting - 3 attempts per minute (fraud protection)
        RateLimiter::for('payments', function (Request $request) {
            return Limit::perMinute(3)->by(optional($request->user())->id ?: $request->ip());
        });

        // Registration rate limiting - 10 registrations per minute (bot protection)
        RateLimiter::for('registration', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip());
        });

        // Global rate limiting - 200 requests per minute per IP (DDoS protection)
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(200)->by($request->ip());
        });
    }
}
