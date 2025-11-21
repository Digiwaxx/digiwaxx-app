<?php

/**
 * Password Reset Routes
 *
 * These routes handle the forgot password and reset password functionality
 * for both clients (artists/labels) and members (DJs).
 *
 * To integrate: Include this file in your main routes file with:
 * require __DIR__ . '/password-reset.php';
 */

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Route;

// ============================================
// FORGOT PASSWORD ROUTES
// ============================================

// Show forgot password form
Route::get('/forgot-password', [ForgotPasswordController::class, 'getEmail'])
    ->name('password.request');

// Handle forgot password form submission
Route::post('/forgot-password', [ForgotPasswordController::class, 'postEmail'])
    ->name('password.email');

// ============================================
// RESET PASSWORD ROUTES
// ============================================

// Show reset password form (with token)
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'getResetPassword'])
    ->name('password.reset');

// Handle reset password form submission
Route::post('/reset-password-submit', [ResetPasswordController::class, 'updateResetPassword'])
    ->name('password.update');
