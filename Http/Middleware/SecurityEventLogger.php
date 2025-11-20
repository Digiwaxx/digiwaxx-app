<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

/**
 * Security Event Logging Middleware
 *
 * Logs security-relevant events for monitoring and incident response
 */
class SecurityEventLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Log failed authentication attempts
        if ($this->isAuthenticationAttempt($request) && $response->getStatusCode() >= 400) {
            $this->logFailedAuthentication($request);
        }

        // Log successful authentication
        if ($this->isAuthenticationAttempt($request) && $response->getStatusCode() < 400) {
            $this->logSuccessfulAuthentication($request);
        }

        // Log file uploads
        if ($request->hasFile('pageImage') || $request->hasFile('files') || $request->hasFile('logoImage')) {
            $this->logFileUpload($request);
        }

        // Log admin actions
        if ($request->is('admin/*') && in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $this->logAdminAction($request, $response);
        }

        // Log payment transactions
        if ($this->isPaymentTransaction($request)) {
            $this->logPaymentTransaction($request, $response);
        }

        return $response;
    }

    /**
     * Check if this is an authentication attempt
     */
    private function isAuthenticationAttempt(Request $request): bool
    {
        return $request->is('login') ||
               $request->is('authenticate') ||
               $request->is('admin/login');
    }

    /**
     * Check if this is a payment transaction
     */
    private function isPaymentTransaction(Request $request): bool
    {
        return $request->is('stripe/*') ||
               $request->is('payment/*') ||
               $request->is('*payment*');
    }

    /**
     * Log failed authentication attempt
     */
    private function logFailedAuthentication(Request $request): void
    {
        Log::warning('Failed login attempt', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'email' => $request->input('email'),
            'membertype' => $request->input('membertype'),
            'timestamp' => now()->toDateTimeString(),
            'url' => $request->fullUrl(),
        ]);
    }

    /**
     * Log successful authentication
     */
    private function logSuccessfulAuthentication(Request $request): void
    {
        Log::info('Successful login', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'email' => $request->input('email'),
            'membertype' => $request->input('membertype'),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Log file upload
     */
    private function logFileUpload(Request $request): void
    {
        $files = [];
        foreach ($request->allFiles() as $key => $file) {
            if (is_array($file)) {
                foreach ($file as $f) {
                    $files[] = [
                        'name' => $f->getClientOriginalName(),
                        'size' => $f->getSize(),
                        'mime' => $f->getMimeType(),
                    ];
                }
            } else {
                $files[] = [
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime' => $file->getMimeType(),
                ];
            }
        }

        Log::info('File upload', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'files' => $files,
            'timestamp' => now()->toDateTimeString(),
            'url' => $request->fullUrl(),
        ]);
    }

    /**
     * Log admin action
     */
    private function logAdminAction(Request $request, $response): void
    {
        Log::info('Admin action', [
            'admin_id' => Auth::guard('admin')->id(),
            'action' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
            'status_code' => $response->getStatusCode(),
            'timestamp' => now()->toDateTimeString(),
        ]);
    }

    /**
     * Log payment transaction
     */
    private function logPaymentTransaction(Request $request, $response): void
    {
        Log::info('Payment transaction', [
            'user_id' => Auth::id(),
            'ip' => $request->ip(),
            'amount' => $request->input('amount'),
            'status_code' => $response->getStatusCode(),
            'timestamp' => now()->toDateTimeString(),
            'success' => $response->getStatusCode() < 400,
        ]);
    }
}
