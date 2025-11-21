<?php

/**
 * SECURE ADMIN PASSWORD RESET METHODS
 *
 * Add these methods to AdminLoginController.php to replace the insecure password reset system.
 *
 * CRITICAL SECURITY FIX:
 * - Old system used admin ID in URL (anyone with ID could reset password)
 * - New system uses cryptographically secure random tokens
 * - Tokens are stored in forgot_password table with userType = 3 (admin)
 * - One-time use tokens (marked as used after reset)
 * - Token expiration (1 hour for admin accounts)
 *
 * DATABASE STRUCTURE:
 * - Table: forgot_password
 * - Columns: id, userId, userType, code, status, created_at, updated_at
 * - userType: 1 = Client, 2 = Member, 3 = Admin
 * - status: 0 = active, 1 = used
 *
 * USAGE:
 * 1. Copy these methods into Http/Controllers/Auth/AdminLoginController.php
 * 2. Add required imports at the top:
 *    use Illuminate\Support\Str;
 *    use Illuminate\Support\Facades\Mail;
 *    use Illuminate\Support\Facades\DB;
 * 3. Remove or comment out old methods:
 *    - AdminForgetNotification_function()
 *    - admin_reset_password_mail()
 *    - submit_reset_admin_password()
 * 4. Update routes to use new method names
 */

// ============================================
// METHOD 1: Show Forgot Password Form
// ============================================

/**
 * Display the admin forgot password form
 *
 * @param Request $request
 * @return \Illuminate\View\View
 */
public function showAdminForgotPassword(Request $request)
{
    return view('admin.forgot-password');
}

// ============================================
// METHOD 2: Send Password Reset Email
// ============================================

/**
 * Send password reset email to admin
 *
 * SECURITY IMPROVEMENTS:
 * - Uses cryptographically secure random tokens (32 characters)
 * - Tokens stored in database with one-time use flag
 * - Professional email template
 * - No email enumeration (same message for valid/invalid emails)
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function sendAdminPasswordResetEmail(Request $request)
{
    // Validate email input
    $request->validate([
        'email' => 'required|email'
    ]);

    $email = $request->input('email');

    // Find admin by email
    $admin = DB::table('admins')
        ->select('id', 'uname', 'email')
        ->where('email', '=', $email)
        ->first();

    // SECURITY: Always show success message (no email enumeration)
    // This prevents attackers from discovering valid admin emails
    if (!$admin) {
        return redirect('/admin/forgot-password?mailSent=1');
    }

    try {
        // Generate secure random token (32 characters)
        $token = Str::random(32);

        // Store token in database
        // userType = 3 for admin (1 = client, 2 = member, 3 = admin)
        DB::table('forgot_password')->insert([
            'userId' => $admin->id,
            'userType' => 3, // Admin
            'code' => $token,
            'status' => 0, // 0 = active, 1 = used
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Prepare email data
        $data = [
            'emailId' => $admin->email,
            'name' => $admin->uname ?: 'Administrator',
            'token' => $token
        ];

        // Send email with reset link
        Mail::send('mails.admin-password-reset', ['data' => $data], function ($message) use ($data) {
            $message->to($data['emailId']);
            $message->subject('Admin Password Reset - Digiwaxx');
            $message->from('business@digiwaxx.com', 'Digiwaxx Admin');
        });

        return redirect('/admin/forgot-password?mailSent=1');

    } catch (\Exception $e) {
        // Log error but don't expose details to user
        \Log::error('Admin password reset email failed: ' . $e->getMessage());
        return redirect('/admin/forgot-password?error=1');
    }
}

// ============================================
// METHOD 3: Show Reset Password Form
// ============================================

/**
 * Display admin reset password form with token validation
 *
 * SECURITY IMPROVEMENTS:
 * - Validates token exists and is not used
 * - Checks token expiration (1 hour for admin accounts)
 * - Does not reveal if token is invalid vs expired
 *
 * @param string $token
 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
 */
public function showAdminResetPassword($token)
{
    if (empty($token)) {
        return redirect('/admin/forgot-password?invalidCode=1');
    }

    // Validate token
    $resetToken = DB::table('forgot_password')
        ->where('code', '=', $token)
        ->where('userType', '=', 3) // Admin only
        ->where('status', '=', 0) // Not used
        ->first();

    // Check if token exists and is not used
    if (!$resetToken) {
        return redirect('/admin/forgot-password?invalidCode=1');
    }

    // Check token expiration (1 hour for admin accounts)
    $tokenAge = now()->diffInHours($resetToken->created_at);
    if ($tokenAge > 1) {
        // Mark token as used (expired)
        DB::table('forgot_password')
            ->where('code', '=', $token)
            ->update(['status' => 1]);

        return redirect('/admin/forgot-password?invalidCode=1');
    }

    // Token is valid, show reset form
    return view('admin.reset-password', ['token' => $token]);
}

// ============================================
// METHOD 4: Update Admin Password
// ============================================

/**
 * Update admin password with new password
 *
 * SECURITY IMPROVEMENTS:
 * - Strong password requirements (12+ chars, uppercase, lowercase, number, special)
 * - Password confirmation required
 * - Token validation and one-time use
 * - Bcrypt hashing
 * - Confirmation email sent
 *
 * @param Request $request
 * @return \Illuminate\Http\RedirectResponse
 */
public function updateAdminPassword(Request $request)
{
    // Validate input with STRONG password requirements for admin
    $request->validate([
        'token' => 'required',
        'password' => [
            'required',
            'min:12', // Admin accounts require longer passwords
            'confirmed',
            'regex:/[a-z]/', // Lowercase
            'regex:/[A-Z]/', // Uppercase
            'regex:/[0-9]/', // Number
            'regex:/[@$!%*#?&]/' // Special character
        ]
    ], [
        'password.min' => 'Admin password must be at least 12 characters.',
        'password.regex' => 'Password must contain uppercase, lowercase, number, and special character.'
    ]);

    $token = $request->input('token');
    $password = $request->input('password');

    // Validate token again
    $resetToken = DB::table('forgot_password')
        ->where('code', '=', $token)
        ->where('userType', '=', 3) // Admin only
        ->where('status', '=', 0) // Not used
        ->first();

    if (!$resetToken) {
        return redirect('/admin/forgot-password?invalidCode=1');
    }

    // Check token expiration
    $tokenAge = now()->diffInHours($resetToken->created_at);
    if ($tokenAge > 1) {
        DB::table('forgot_password')
            ->where('code', '=', $token)
            ->update(['status' => 1]);

        return redirect('/admin/forgot-password?invalidCode=1');
    }

    try {
        // Get admin details
        $admin = DB::table('admins')
            ->where('id', '=', $resetToken->userId)
            ->first();

        if (!$admin) {
            return redirect('/admin/forgot-password?error=1');
        }

        // Update password with bcrypt
        DB::table('admins')
            ->where('id', '=', $resetToken->userId)
            ->update([
                'password' => bcrypt($password),
                'updated_at' => now()
            ]);

        // Mark token as used (one-time use)
        DB::table('forgot_password')
            ->where('code', '=', $token)
            ->update([
                'status' => 1,
                'updated_at' => now()
            ]);

        // Send confirmation email
        $data = [
            'emailId' => $admin->email,
            'name' => $admin->uname ?: 'Administrator'
        ];

        Mail::send('mails.admin-password-reset-confirmation', ['data' => $data], function ($message) use ($data) {
            $message->to($data['emailId']);
            $message->subject('Admin Password Reset Successful - Digiwaxx');
            $message->from('business@digiwaxx.com', 'Digiwaxx Admin');
        });

        // Redirect to admin login with success message
        return redirect('/admin')->with('password_changed', 'Your administrator password has been reset successfully. Please login with your new password.');

    } catch (\Exception $e) {
        \Log::error('Admin password update failed: ' . $e->getMessage());
        return redirect('/admin/forgot-password?error=1');
    }
}

// ============================================
// OPTIONAL: Clean up expired tokens
// ============================================

/**
 * Clean up expired admin password reset tokens
 *
 * This can be run as a scheduled task to keep the database clean.
 * Run daily or weekly to remove old tokens.
 *
 * @return int Number of tokens deleted
 */
public function cleanupExpiredAdminTokens()
{
    $deleted = DB::table('forgot_password')
        ->where('userType', '=', 3) // Admin only
        ->where('created_at', '<', now()->subHours(24))
        ->delete();

    return $deleted;
}
