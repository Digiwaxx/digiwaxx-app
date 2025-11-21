<?php

/**
 * Admin Password Reset Routes
 *
 * These routes handle the forgot password and reset password functionality
 * specifically for administrator accounts.
 *
 * SECURITY: Uses secure random tokens instead of admin IDs
 *
 * To integrate: Include this file in your main routes file with:
 * require __DIR__ . '/admin-password-reset.php';
 */

use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

// ============================================
// ADMIN FORGOT PASSWORD ROUTES
// ============================================

// Show admin forgot password form
Route::get('/admin/forgot-password', [AdminLoginController::class, 'showAdminForgotPassword'])
    ->name('admin.password.request');

// Handle admin forgot password form submission
Route::post('/admin/forgot-password', [AdminLoginController::class, 'sendAdminPasswordResetEmail'])
    ->name('admin.password.email');

// ============================================
// ADMIN RESET PASSWORD ROUTES
// ============================================

// Show admin reset password form (with secure token)
Route::get('/admin/reset-password/{token}', [AdminLoginController::class, 'showAdminResetPassword'])
    ->name('admin.password.reset');

// Handle admin reset password form submission
Route::post('/admin/reset-password-submit', [AdminLoginController::class, 'updateAdminPassword'])
    ->name('admin.password.update');
