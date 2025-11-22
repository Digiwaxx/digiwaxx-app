<?php
namespace App\Http\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * SECURITY: Keep this array as empty as possible.
     * Only exclude endpoints that:
     * 1. Are webhooks from trusted third parties (with signature verification)
     * 2. Are truly read-only GET requests (but those don't need CSRF anyway)
     *
     * @var array
     */
    protected $except = [
        // SECURITY FIX: Re-enabled CSRF protection on /ai/ask
        // This endpoint makes external AI API calls which could incur costs
        // and should be protected against CSRF attacks
        // '/ai/ask',  // REMOVED - CSRF protection now required
    ];
}