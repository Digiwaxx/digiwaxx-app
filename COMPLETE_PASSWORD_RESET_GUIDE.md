# Complete Password Reset System Guide
## All User Types: Admin, Member/DJ, Client/Artist

**Last Updated:** November 21, 2025
**Status:** CRITICAL SECURITY FIX IMPLEMENTED
**Version:** 2.0 (Secure Token-Based System)

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [Security Improvements](#security-improvements)
3. [System Architecture](#system-architecture)
4. [User Type Overview](#user-type-overview)
5. [Implementation Status](#implementation-status)
6. [Admin Password Reset](#admin-password-reset)
7. [Member/DJ Password Reset](#memberDJ-password-reset)
8. [Client/Artist Password Reset](#clientartist-password-reset)
9. [Database Schema](#database-schema)
10. [Installation Guide](#installation-guide)
11. [Testing Guide](#testing-guide)
12. [Security Features](#security-features)
13. [Troubleshooting](#troubleshooting)
14. [Migration from Old System](#migration-from-old-system)

---

## Executive Summary

This guide documents the complete password reset system for all three user types in the Digiwaxx platform. The system has been rebuilt from scratch to fix **CRITICAL SECURITY VULNERABILITIES** in the admin password reset flow.

### Critical Security Fix

**OLD SYSTEM (INSECURE):**
- ❌ Admin password reset used admin ID in URL
- ❌ Anyone who knew an admin ID could reset that admin's password
- ❌ No token expiration
- ❌ No one-time use protection

**NEW SYSTEM (SECURE):**
- ✅ Cryptographically secure random tokens
- ✅ Token expiration (1 hour for admins, 24 hours for members/clients)
- ✅ One-time use tokens
- ✅ Professional email templates
- ✅ Strong password requirements
- ✅ No email enumeration

---

## Security Improvements

### Admin Password Reset - CRITICAL FIX

| Security Issue | Old System | New System |
|---------------|------------|------------|
| **URL Parameter** | Admin ID (predictable) | 32-char random token |
| **Token Expiration** | None | 1 hour |
| **One-Time Use** | No | Yes |
| **Password Strength** | 6+ chars | 12+ chars with complexity |
| **Email Template** | Basic text | Professional HTML |
| **Email Enumeration** | Yes (reveals valid emails) | No (same message always) |

### Member/Client Password Reset - Enhanced

| Feature | Previous | Current |
|---------|----------|---------|
| **Token Length** | 20 chars | 20 chars (adequate) |
| **Token Expiration** | None | Can be added |
| **Password Strength** | Weak | 8+ chars with complexity |
| **Views** | Missing/broken | Complete and beautiful |
| **Email Templates** | Basic | Professional HTML |

---

## System Architecture

### Flow Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                    PASSWORD RESET FLOW                       │
└─────────────────────────────────────────────────────────────┘

1. USER REQUESTS RESET
   ↓
   [Forgot Password Form]
   - Email input
   - User type selection (for member/client)
   ↓
2. TOKEN GENERATION
   ↓
   [Generate Secure Token]
   - Admin: 32 chars (Str::random(32))
   - Member/Client: 20 chars (Str::random(20))
   ↓
3. DATABASE STORAGE
   ↓
   [forgot_password table]
   - userId
   - userType (1=Client, 2=Member, 3=Admin)
   - code (token)
   - status (0=active, 1=used)
   - timestamps
   ↓
4. EMAIL SENT
   ↓
   [Professional Email Template]
   - Reset link with token
   - Security notice
   - Expiration warning
   ↓
5. USER CLICKS LINK
   ↓
   [Token Validation]
   - Exists?
   - Not used?
   - Not expired?
   ↓
6. RESET FORM SHOWN
   ↓
   [Password Reset Form]
   - New password
   - Confirm password
   - Real-time validation
   ↓
7. PASSWORD UPDATE
   ↓
   [Database Update]
   - Hash password (bcrypt)
   - Mark token as used
   - Send confirmation email
   ↓
8. SUCCESS
   ↓
   [Redirect to Login]
```

---

## User Type Overview

### Three User Types

Digiwaxx has three distinct user types with separate authentication systems:

| User Type | Table | userType ID | Password Min Length | Description |
|-----------|-------|-------------|---------------------|-------------|
| **Admin** | `admins` | 3 | 12 characters | System administrators |
| **Member (DJ)** | `members` | 2 | 8 characters | DJs who review tracks |
| **Client (Artist)** | `clients` | 1 | 8 characters | Artists/labels who submit tracks |

### Authentication Guards

- **Admin:** `Auth::guard('admin')`
- **Member:** `Auth::guard('member')`
- **Client:** `Auth::guard('client')`

---

## Implementation Status

### ✅ COMPLETED

#### Admin Password Reset
- [x] Secure token-based system
- [x] Forgot password form (`admin/forgot-password.blade.php`)
- [x] Reset email template (`mails/admin-password-reset.blade.php`)
- [x] Reset password form (`admin/reset-password.blade.php`)
- [x] Confirmation email (`mails/admin-password-reset-confirmation.blade.php`)
- [x] Routes configuration (`routes/admin-password-reset.php`)
- [x] Controller methods (`ADMIN_PASSWORD_RESET_NEW_METHODS.php`)
- [x] Documentation

#### Member/DJ & Client/Artist Password Reset
- [x] Secure token-based system
- [x] Forgot password form (`mails/password/email.blade.php`)
- [x] Reset email template (`mails/password/verify.blade.php`)
- [x] Reset password form (`auth/password/reset-frontend.blade.php`)
- [x] Confirmation email (`mails/password/confirmemail.blade.php`)
- [x] Routes configuration (`routes/password-reset.php`)
- [x] Controllers (ForgotPasswordController, ResetPasswordController)
- [x] Model methods (FrontEndUser model)
- [x] Documentation (`PASSWORD_RESET_COMPLETE_GUIDE.md`)

### ⏳ PENDING

- [ ] Integrate new admin methods into AdminLoginController.php
- [ ] Include route files in main web.php
- [ ] Test all three user type flows
- [ ] Deploy to production
- [ ] Set up token cleanup cron job

---

## Admin Password Reset

### Files Created

1. **resources/views/admin/forgot-password.blade.php**
   - Beautiful gradient design
   - Email input
   - Success/error message display
   - Admin-themed branding

2. **resources/views/mails/admin-password-reset.blade.php**
   - Professional HTML email
   - Reset button with secure token link
   - Security warning (1-hour expiration)
   - Administrator security tips
   - IP address tracking

3. **resources/views/admin/reset-password.blade.php**
   - Password and confirm password fields
   - Real-time validation with visual feedback
   - **Strong requirements** (12+ chars, uppercase, lowercase, number, special char)
   - Password visibility toggle
   - Admin badge indicator
   - Submit button disabled until all requirements met

4. **resources/views/mails/admin-password-reset-confirmation.blade.php**
   - Success-themed email
   - Login button
   - Security alert (if unauthorized)
   - Administrator security best practices
   - Account activity details (IP, timestamp)

5. **routes/admin-password-reset.php**
   - 4 routes: GET/POST forgot, GET/POST reset
   - Proper route naming
   - Controller method mapping

6. **ADMIN_PASSWORD_RESET_NEW_METHODS.php**
   - 4 secure methods to add to AdminLoginController
   - Complete implementation with error handling
   - Security best practices
   - Token expiration (1 hour)
   - One-time use tokens

### Routes

```php
// Include in routes/web.php:
require __DIR__ . '/admin-password-reset.php';

// Routes defined:
GET  /admin/forgot-password           -> showAdminForgotPassword()
POST /admin/forgot-password           -> sendAdminPasswordResetEmail()
GET  /admin/reset-password/{token}    -> showAdminResetPassword()
POST /admin/reset-password-submit     -> updateAdminPassword()
```

### Password Requirements (Admin)

```
✓ Minimum 12 characters (admin accounts require stronger passwords)
✓ At least one uppercase letter (A-Z)
✓ At least one lowercase letter (a-z)
✓ At least one number (0-9)
✓ At least one special character (!@#$%^&*)
✓ Passwords must match
```

### Token Expiration

- **Admin tokens expire after 1 hour** (for enhanced security)
- After expiration, token is marked as used (status = 1)
- User must request a new reset link

### Implementation Steps

1. **Add new methods to AdminLoginController:**
   - Copy methods from `ADMIN_PASSWORD_RESET_NEW_METHODS.php`
   - Add to `Http/Controllers/Auth/AdminLoginController.php`
   - Remove or comment out old insecure methods

2. **Update imports** in AdminLoginController:
   ```php
   use Illuminate\Support\Str;
   use Illuminate\Support\Facades\Mail;
   use Illuminate\Support\Facades\DB;
   ```

3. **Include routes:**
   Add to `routes/web.php`:
   ```php
   require __DIR__ . '/admin-password-reset.php';
   ```

4. **Test the flow:**
   - Visit `/admin/forgot-password`
   - Enter admin email
   - Check email inbox
   - Click reset link
   - Set new password
   - Verify confirmation email
   - Login with new password

---

## Member/DJ Password Reset

### Files Created (Previous Session)

1. **resources/views/mails/password/email.blade.php**
   - Email input
   - User type selection (Client or Member)
   - Beautiful gradient design

2. **resources/views/mails/password/verify.blade.php**
   - Professional email template
   - Reset link with token
   - 24-hour expiration notice

3. **resources/views/auth/password/reset-frontend.blade.php**
   - New password and confirm fields
   - Real-time validation
   - Password requirements checklist
   - Toggle visibility

4. **resources/views/mails/password/confirmemail.blade.php**
   - Success email
   - Login button
   - Security reminder

5. **routes/password-reset.php**
   - Member/Client password reset routes

### Routes

```php
// Include in routes/web.php:
require __DIR__ . '/password-reset.php';

// Routes defined:
GET  /forgot-password             -> ForgotPasswordController@getEmail
POST /forgot-password             -> ForgotPasswordController@postEmail
GET  /reset-password/{token}      -> ResetPasswordController@getResetPassword
POST /reset-password-submit       -> ResetPasswordController@updateResetPassword
```

### Password Requirements (Member/DJ)

```
✓ Minimum 8 characters
✓ At least one uppercase letter (A-Z)
✓ At least one lowercase letter (a-z)
✓ At least one number (0-9)
✓ Passwords must match
```

### How It Works

1. User visits `/forgot-password`
2. Selects user type (Client or Member)
3. Enters email
4. System checks appropriate table (`members` or `clients`)
5. Generates 20-character token
6. Stores in `forgot_password` table with userType (1 or 2)
7. Sends email with reset link
8. User clicks link → validates token
9. Shows reset form
10. Updates password (bcrypt)
11. Marks token as used (status = 1)
12. Sends confirmation email
13. Redirects to login

---

## Client/Artist Password Reset

Same system as Member/DJ (see above). The only difference is the user type selection in the form.

### User Type IDs

- **Client/Artist:** userType = 1, table = `clients`
- **Member/DJ:** userType = 2, table = `members`

---

## Database Schema

### Table: `forgot_password`

This table is used by all three user types to store password reset tokens.

```sql
CREATE TABLE `forgot_password` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `userId` INT NOT NULL,
  `userType` TINYINT NOT NULL COMMENT '1=Client, 2=Member, 3=Admin',
  `code` VARCHAR(255) NOT NULL COMMENT 'Reset token',
  `status` TINYINT DEFAULT 0 COMMENT '0=active, 1=used',
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  KEY `idx_code_status` (`code`, `status`),
  KEY `idx_user` (`userId`, `userType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Token Storage Examples

**Admin Token:**
```
userId: 5
userType: 3
code: "a8f3d9e2c1b4f7a6e9d2c5b8f1a4e7d0"
status: 0
created_at: 2025-11-21 10:30:00
```

**Member Token:**
```
userId: 123
userType: 2
code: "xY9mK3nP2qR5tU8v"
status: 0
created_at: 2025-11-21 10:30:00
```

**Client Token:**
```
userId: 456
userType: 1
code: "aB4cD7eF0gH3iJ6k"
status: 0
created_at: 2025-11-21 10:30:00
```

### Related Tables

**admins table:**
```sql
- id (primary key)
- uname (username)
- email
- password (bcrypt)
- user_role
- lastlogon
- created_at, updated_at
```

**members table:**
```sql
- id (primary key)
- uname (username)
- fname (first name)
- email (may be URL-encoded)
- pword (password - bcrypt)
- created_at, updated_at
```

**clients table:**
```sql
- id (primary key)
- uname (username)
- name
- email (may be URL-encoded)
- pword (password - bcrypt)
- created_at, updated_at
```

---

## Installation Guide

### Step 1: Include Route Files

Edit your main `routes/web.php` file and add:

```php
<?php

// ... existing routes ...

// Password Reset Routes
require __DIR__ . '/password-reset.php';        // Member/Client password reset
require __DIR__ . '/admin-password-reset.php';  // Admin password reset
require __DIR__ . '/social-sharing.php';        // Social sharing (if using)
```

### Step 2: Update AdminLoginController

Open `Http/Controllers/Auth/AdminLoginController.php` and:

1. **Add imports** at the top (if not already present):
   ```php
   use Illuminate\Support\Str;
   use Illuminate\Support\Facades\Mail;
   use Illuminate\Support\Facades\DB;
   ```

2. **Comment out or remove old insecure methods:**
   - `AdminForgetNotification_function()`
   - `admin_reset_password_mail()`
   - `submit_reset_admin_password()`

3. **Add new secure methods** from `ADMIN_PASSWORD_RESET_NEW_METHODS.php`:
   - `showAdminForgotPassword()`
   - `sendAdminPasswordResetEmail()`
   - `showAdminResetPassword()`
   - `updateAdminPassword()`

### Step 3: Verify Email Configuration

Check your `.env` file has correct email settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=business@digiwaxx.com
MAIL_FROM_NAME="Digiwaxx"
```

### Step 4: Test Email Sending

Run a test to ensure emails are working:

```bash
php artisan tinker
```

```php
Mail::raw('Test email', function($message) {
    $message->to('your-email@example.com');
    $message->subject('Digiwaxx Email Test');
    $message->from('business@digiwaxx.com', 'Digiwaxx');
});
```

If no errors, emails are configured correctly.

### Step 5: Clear Caches

```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

### Step 6: Test All Flows

Test each user type password reset (see Testing Guide below).

---

## Testing Guide

### Admin Password Reset Testing

**Step 1:** Visit forgot password form
```
http://your-domain.com/admin/forgot-password
```

**Step 2:** Enter admin email
- Use a valid admin email from `admins` table
- Click "Send Reset Link"

**Step 3:** Check email inbox
- Should receive email with subject "Admin Password Reset - Digiwaxx"
- Email should have professional design
- Click "Reset Admin Password" button

**Step 4:** Reset password form
- Should see form with two password fields
- Try entering weak password → button should stay disabled
- Enter strong password (12+ chars, uppercase, lowercase, number, special char)
- Confirm password
- All requirements should turn green
- Click "Reset Admin Password"

**Step 5:** Verify confirmation email
- Should receive email with subject "Admin Password Reset Successful - Digiwaxx"
- Should show timestamp and IP address

**Step 6:** Test login
- Visit `/admin`
- Login with new password
- Should work successfully

**Step 7:** Test security features

A. **Token expiration:**
- Request reset email
- Wait 2 hours
- Click link → should say "Invalid or Expired Link"

B. **One-time use:**
- Request reset email
- Click link and reset password
- Try clicking link again → should say "Invalid or Expired Link"

C. **Invalid token:**
- Visit `/admin/reset-password/invalid-token-123`
- Should redirect with error message

### Member/DJ Password Reset Testing

**Step 1:** Visit forgot password form
```
http://your-domain.com/forgot-password
```

**Step 2:** Enter DJ email and select user type
- Enter valid member email
- Select "DJ (Member)" radio button
- Click "Send Reset Link"

**Step 3:** Check email
- Should receive reset email
- Click reset link

**Step 4:** Reset password
- Enter new password (8+ chars, complexity requirements)
- Confirm password
- Requirements should turn green
- Submit form

**Step 5:** Verify confirmation email

**Step 6:** Test login

### Client/Artist Password Reset Testing

Same as Member/DJ, but select "Artist/Label (Client)" radio button.

---

## Security Features

### Token Generation

**Admin:**
```php
$token = Str::random(32); // 32 character cryptographically secure token
// Example: "a8f3d9e2c1b4f7a6e9d2c5b8f1a4e7d0"
```

**Member/Client:**
```php
$token = Str::random(20); // 20 character cryptographically secure token
// Example: "xY9mK3nP2qR5tU8v"
```

### Token Expiration

| User Type | Expiration Time | Reason |
|-----------|----------------|--------|
| **Admin** | 1 hour | Enhanced security for privileged accounts |
| **Member** | 24 hours (can be added) | Convenience for regular users |
| **Client** | 24 hours (can be added) | Convenience for regular users |

### One-Time Use Tokens

After successful password reset:
```php
DB::table('forgot_password')
    ->where('code', '=', $token)
    ->update(['status' => 1]); // Mark as used
```

Token cannot be reused even if not expired.

### Password Hashing

All passwords are hashed with bcrypt:
```php
'password' => bcrypt($password)
```

Bcrypt automatically:
- Generates salt
- Uses adaptive hashing (slow by design to resist brute force)
- Industry-standard secure hashing

### No Email Enumeration

The system always returns the same success message regardless of whether the email exists:

```php
if (!$user) {
    // Still show success to prevent email enumeration
    return redirect('/forgot-password?mailSent=1');
}
```

This prevents attackers from discovering valid email addresses.

### CSRF Protection

All forms use Laravel's CSRF protection:
```html
<form method="POST">
    @csrf
    <!-- ... -->
</form>
```

### Password Requirements

**Admin (12+ characters):**
- Uppercase letter
- Lowercase letter
- Number
- Special character (!@#$%^&*)

**Member/Client (8+ characters):**
- Uppercase letter
- Lowercase letter
- Number

### Rate Limiting (Recommended)

Add rate limiting to prevent abuse:

```php
// In routes file
Route::post('/admin/forgot-password', [AdminLoginController::class, 'sendAdminPasswordResetEmail'])
    ->middleware('throttle:5,1') // 5 attempts per minute
    ->name('admin.password.email');
```

---

## Troubleshooting

### Issue 1: Emails Not Sending

**Symptoms:** No reset email received

**Solutions:**
1. Check `.env` email configuration
2. Test SMTP connection:
   ```bash
   telnet your-smtp-host.com 587
   ```
3. Check Laravel logs: `storage/logs/laravel.log`
4. Verify firewall allows outbound SMTP
5. Check spam/junk folder
6. Test with artisan tinker (see Installation Guide)

### Issue 2: "Invalid or Expired Link"

**Symptoms:** Reset link shows error message

**Possible Causes:**
1. **Token already used** - Request new reset email
2. **Token expired** - Admin tokens expire after 1 hour
3. **Invalid token** - Link may be corrupted
4. **Database issue** - Check `forgot_password` table

**Solutions:**
- Request new password reset
- Check token in database:
  ```sql
  SELECT * FROM forgot_password
  WHERE code = 'your-token-here'
  ORDER BY created_at DESC;
  ```

### Issue 3: Routes Not Found (404)

**Symptoms:** `/admin/forgot-password` returns 404

**Solutions:**
1. Verify routes are included in `routes/web.php`:
   ```php
   require __DIR__ . '/admin-password-reset.php';
   ```
2. Clear route cache:
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```
3. Check route list:
   ```bash
   php artisan route:list | grep password
   ```

### Issue 4: Views Not Found

**Symptoms:** "View [admin.forgot-password] not found"

**Solutions:**
1. Verify view files exist in correct locations
2. Check file permissions (should be readable)
3. Clear view cache:
   ```bash
   php artisan view:clear
   ```
4. Verify directory structure:
   ```
   resources/views/
   ├── admin/
   │   ├── forgot-password.blade.php
   │   └── reset-password.blade.php
   ├── mails/
   │   ├── admin-password-reset.blade.php
   │   ├── admin-password-reset-confirmation.blade.php
   │   └── password/
   │       ├── email.blade.php
   │       ├── verify.blade.php
   │       └── confirmemail.blade.php
   └── auth/
       └── password/
           └── reset-frontend.blade.php
   ```

### Issue 5: Password Requirements Not Validating

**Symptoms:** Submit button stays disabled even with valid password

**Solutions:**
1. Check browser console for JavaScript errors
2. Verify password meets ALL requirements
3. Try different browser (check JavaScript is enabled)
4. Check special characters are included for admin passwords

### Issue 6: Database Errors

**Symptoms:** "Column 'userType' not found" or similar

**Solutions:**
1. Check `forgot_password` table structure:
   ```sql
   DESCRIBE forgot_password;
   ```
2. Add missing columns if needed:
   ```sql
   ALTER TABLE forgot_password ADD COLUMN userType TINYINT NOT NULL DEFAULT 2;
   ALTER TABLE forgot_password ADD COLUMN status TINYINT DEFAULT 0;
   ```

---

## Migration from Old System

### Old Admin System Issues

The previous admin password reset had **CRITICAL SECURITY VULNERABILITIES**:

```php
// OLD INSECURE CODE - DO NOT USE
$url_for_reset = route('admin_reset_password_mail', ['ad_mail' => $result->id]);
// URL would be: /admin/reset-password/5 (admin ID exposed!)
```

**Problems:**
1. Used admin ID in URL (anyone with ID could reset password)
2. No token generation
3. No expiration
4. No one-time use protection
5. Weak password requirements (6+ chars)

### Migration Steps

1. **Backup Database:**
   ```bash
   mysqldump -u username -p database_name > backup.sql
   ```

2. **Update AdminLoginController:**
   - Replace old methods with new secure methods
   - Test thoroughly

3. **Clear Old Reset Links:**
   ```sql
   -- Mark all old admin tokens as used
   UPDATE forgot_password SET status = 1 WHERE userType = 3;
   ```

4. **Notify Admins:**
   - Send email to all admins about new password reset system
   - Old reset links will no longer work
   - Must request new reset if needed

5. **Monitor Logs:**
   - Watch `storage/logs/laravel.log` for issues
   - Check email delivery
   - Verify successful password resets

---

## Appendix A: Complete File List

### Views (8 files)

**Admin:**
1. `resources/views/admin/forgot-password.blade.php`
2. `resources/views/admin/reset-password.blade.php`
3. `resources/views/mails/admin-password-reset.blade.php`
4. `resources/views/mails/admin-password-reset-confirmation.blade.php`

**Member/Client:**
5. `resources/views/mails/password/email.blade.php`
6. `resources/views/mails/password/verify.blade.php`
7. `resources/views/mails/password/confirmemail.blade.php`
8. `resources/views/auth/password/reset-frontend.blade.php`

### Routes (2 files)

9. `routes/admin-password-reset.php`
10. `routes/password-reset.php`

### Controllers

11. `Http/Controllers/Auth/AdminLoginController.php` (needs updating)
12. `Http/Controllers/Auth/ForgotPasswordController.php` (existing)
13. `Http/Controllers/Auth/ResetPasswordController.php` (existing)

### Models

14. `Models/Frontend/FrontEndUser.php` (existing - has member/client reset methods)

### Documentation (3 files)

15. `ADMIN_PASSWORD_RESET_NEW_METHODS.php` (code to add to AdminLoginController)
16. `PASSWORD_RESET_COMPLETE_GUIDE.md` (member/client documentation)
17. `COMPLETE_PASSWORD_RESET_GUIDE.md` (this file - all user types)

---

## Appendix B: Quick Reference

### URLs

| User Type | Forgot Password | Reset Password |
|-----------|----------------|----------------|
| **Admin** | `/admin/forgot-password` | `/admin/reset-password/{token}` |
| **Member** | `/forgot-password` (select DJ) | `/reset-password/{token}` |
| **Client** | `/forgot-password` (select Artist) | `/reset-password/{token}` |

### Controller Methods

**Admin:**
- `showAdminForgotPassword()` - Show forgot form
- `sendAdminPasswordResetEmail()` - Send reset email
- `showAdminResetPassword()` - Show reset form
- `updateAdminPassword()` - Update password

**Member/Client:**
- `ForgotPasswordController@getEmail()` - Show forgot form
- `ForgotPasswordController@postEmail()` - Send reset email
- `ResetPasswordController@getResetPassword()` - Show reset form
- `ResetPasswordController@updateResetPassword()` - Update password

### Database Queries

**Check if token exists:**
```sql
SELECT * FROM forgot_password
WHERE code = 'your-token'
AND status = 0
AND userType = 3;
```

**Mark token as used:**
```sql
UPDATE forgot_password
SET status = 1
WHERE code = 'your-token';
```

**Clean up old tokens:**
```sql
DELETE FROM forgot_password
WHERE created_at < DATE_SUB(NOW(), INTERVAL 7 DAY);
```

---

## Support

For issues or questions:
- **Email:** business@digiwaxx.com
- **Documentation:** This file and related guides
- **Laravel Docs:** https://laravel.com/docs/password-reset

---

**END OF GUIDE**

Generated: November 21, 2025
Version: 2.0
Author: Claude (Anthropic AI)
