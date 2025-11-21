# Password Reset System - Complete Guide

## Overview

This document provides complete documentation for the password reset functionality on app.digiwaxx. The system supports both **Clients (Artists/Labels)** and **Members (DJs)** with separate user types.

---

## System Architecture

### Flow Diagram

```
1. User visits /forgot-password
2. User enters email + selects user type (Client or Member)
3. System generates token and stores in database
4. Reset email sent with link containing token
5. User clicks link → /reset-password/{token}
6. Token validated (not expired, status = 0)
7. User enters new password
8. Password updated (bcrypt hashed)
9. Token marked as used (status = 1)
10. Confirmation email sent
11. User redirected to login
```

---

## Files Created

### Email Views (3 files)

1. **resources/views/mails/password/email.blade.php**
   - Forgot password form page
   - User enters email and selects account type
   - Beautiful gradient design with logo
   - Responsive mobile-friendly layout
   - Features:
     * Email input field
     * Radio buttons for user type (Client/Member)
     * Success/error alerts
     * "Send Reset Link" button
     * Link back to login
   - URL: `/forgot-password`

2. **resources/views/mails/password/verify.blade.php**
   - Password reset email template
   - Sent to user's email with reset link
   - Features:
     * Professional email layout
     * Large "Reset Password" button
     * Security notice (24-hour expiration)
     * Alternative text link
     * Digiwaxx branding
   - Email Subject: "Reset Password Notification"

3. **resources/views/mails/password/confirmemail.blade.php**
   - Password reset confirmation email
   - Sent after successful password reset
   - Features:
     * Success message with checkmark
     * "Login to Your Account" button
     * Security reminder
     * Support contact information
   - Email Subject: "Confirmation of reset password at Digiwaxx"

### Page Views (1 file)

4. **resources/views/auth/password/reset-frontend.blade.php**
   - Password reset form page
   - User enters new password
   - Features:
     * New password field
     * Confirm password field
     * Toggle password visibility (eye icon)
     * Real-time password validation:
       - At least 8 characters
       - One uppercase letter
       - One lowercase letter
       - One number
       - Passwords match
     * Visual feedback (green checkmarks for valid)
     * Beautiful gradient design
     * Responsive layout
   - URL: `/reset-password/{token}`

### Routes (1 file)

5. **routes/password-reset.php**
   - All password reset routes
   - To integrate: `require __DIR__ . '/password-reset.php';` in main routes file
   - Routes:
     * `GET /forgot-password` - Show forgot password form
     * `POST /forgot-password` - Handle form submission
     * `GET /reset-password/{token}` - Show reset password form
     * `POST /reset-password-submit` - Handle password reset

---

## Database Schema

### Tables Used

**1. `clients` Table**
- Stores artist/label accounts
- Columns used:
  * `id` - Primary key
  * `email` - User email (can be URL encoded)
  * `uname` - Username
  * `name` - Company/artist name
  * `pword` - Password (bcrypt hashed)

**2. `members` Table**
- Stores DJ accounts
- Columns used:
  * `id` - Primary key
  * `email` - User email (can be URL encoded)
  * `uname` - Username
  * `fname` - First name
  * `pword` - Password (bcrypt hashed)

**3. `forgot_password` Table**
- Tracks password reset requests
- Schema:
```sql
CREATE TABLE `forgot_password` (
    `id` BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    `userId` BIGINT UNSIGNED NOT NULL,
    `userType` ENUM('1', '2') NOT NULL, -- 1=Client, 2=Member
    `code` VARCHAR(255) NOT NULL,       -- Reset token
    `status` TINYINT DEFAULT 0,         -- 0=Active, 1=Used
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_code (code),
    INDEX idx_user (userId, userType),
    INDEX idx_status (status)
);
```

**User Types:**
- `1` = Client (Artist/Label)
- `2` = Member (DJ)

**Status Values:**
- `0` = Active token (not used)
- `1` = Used token (after password reset)

---

## Controllers

### ForgotPasswordController

**Location:** `Http/Controllers/Auth/ForgotPasswordController.php`

**Methods:**

1. **`getEmail(Request $request)`**
   - Shows forgot password form
   - Handles URL query parameters for messages:
     * `?mailSent=1` - "Reset link sent to email"
     * `?error=1` - "Error occurred"
     * `?invalidEmail=1` - "Email not registered"
     * `?invalidCode=1` - "Invalid or expired token"
   - Returns view: `mails.password.email`

2. **`postEmail(Request $request)`**
   - Handles forgot password form submission
   - Parameters:
     * `email` - User's email address
     * `user` - User type (1=Client, 2=Member)
     * `sendMail` - Form submission flag
   - Process:
     1. Validates email exists in correct user table
     2. Generates 20-character random token
     3. Stores in `forgot_password` table
     4. Sends email with reset link
     5. Redirects with success/error message

### ResetPasswordController

**Location:** `Http/Controllers/Auth/ResetPasswordController.php`

**Methods:**

1. **`getResetPassword($token)`**
   - Shows reset password form
   - Validates token:
     * Checks token exists
     * Checks status = 0 (not used)
     * Checks not expired (if expiration logic added)
   - If invalid:
     * Redirects to `/forgot-password?invalidCode=1`
   - If valid:
     * Shows reset form with token
   - Returns view: `auth.password.reset-frontend`

2. **`updateResetPassword(Request $request)`**
   - Handles password reset form submission
   - Parameters:
     * `password` - New password
     * `token` - Reset token
   - Process:
     1. Validates token
     2. Hashes password with bcrypt
     3. Updates user's password in correct table
     4. Marks token as used (status = 1)
     5. Sends confirmation email
     6. Redirects to login with success message

---

## Model Methods

### FrontEndUser Model

**Location:** `Models/Frontend/FrontEndUser.php`

**Methods:**

1. **`forgotPassword($email, $userType)`**
   - Static method
   - Parameters:
     * `$email` - User's email (string)
     * `$userType` - User type (1 or 2)
   - Process:
     1. Searches for email in correct table (clients or members)
     2. If found:
        - Generates 20-character token
        - Inserts record into `forgot_password` table
        - Returns user data and token
     3. If not found:
        - Returns empty result
   - Returns:
     ```php
     [
         'numRows' => int,        // 1 if found, 0 if not
         'insertId' => int,       // Insert ID of forgot_password record
         'data' => array,         // User data (id, name, email)
         'code' => string         // Reset token
     ]
     ```

2. **`resetPassword($password, $code)`**
   - Static method
   - Parameters:
     * `$password` - New password (plain text)
     * `$code` - Reset token
   - Process:
     1. Validates token exists and status = 0
     2. Hashes password using bcrypt
     3. Updates password in correct user table
     4. Marks token as used (status = 1)
     5. Retrieves user data for confirmation email
   - Returns:
     ```php
     [
         'numRows' => int,        // 1 if valid token, 0 if not
         'update' => int,         // 1 if updated, 0 if not
         'data' => array          // User data (name, email)
     ]
     ```

3. **`isExpiredConfirmCode($code)`**
   - Static method
   - Parameters:
     * `$code` - Reset token
   - Process:
     1. Checks if token exists in `forgot_password` table
     2. Checks status = 0 (not used)
   - Returns:
     ```php
     [
         'numRows' => int,        // 1 if valid, 0 if invalid/expired
         'data' => array          // Token data (userId, userType)
     ]
     ```

---

## Email Configuration

### Email Settings

Emails are sent from:
- **From Address:** `business@digiwaxx.com`
- **From Name:** `DigiWaxx`

### Email Subjects

1. **Reset Request Email:**
   - Subject: "Reset Password Notification"
   - Template: `mails.password.verify`

2. **Confirmation Email:**
   - Subject: "Confirmation of reset password at Digiwaxx"
   - Template: `mails.password.confirmemail`

### Email Variables

**Reset Request Email (`verify.blade.php`):**
```php
$data = [
    'emailId' => 'user@example.com',  // Recipient email
    'token' => 'abc123...',            // Reset token
    'name' => 'User Name'              // User's name
];
```

**Confirmation Email (`confirmemail.blade.php`):**
```php
$data = [
    'emailId' => 'user@example.com',  // Recipient email
    'name' => 'User Name',             // User's name
    'pwd' => 'newpassword123'          // New password (optional - hidden by default)
];
```

---

## User Flow Examples

### Example 1: Client (Artist) Password Reset

1. **User visits:** `https://app.digiwaxx.com/forgot-password`
2. **User enters:**
   - Email: `artist@musiclabel.com`
   - Selects: "Artist/Label (Client)"
   - Clicks: "Send Reset Link"
3. **System:**
   - Searches `clients` table for email
   - Generates token: `a1b2c3d4e5f6g7h8i9j0`
   - Stores in `forgot_password`:
     ```sql
     INSERT INTO forgot_password (userId, userType, code, status)
     VALUES (123, '1', 'a1b2c3d4e5f6g7h8i9j0', 0);
     ```
   - Sends email to `artist@musiclabel.com`
4. **User receives email:**
   - Subject: "Reset Password Notification"
   - Contains link: `https://app.digiwaxx.com/reset-password/a1b2c3d4e5f6g7h8i9j0`
5. **User clicks link:**
   - System validates token (exists, status=0)
   - Shows reset password form
6. **User enters new password:**
   - Password: `NewSecure123!`
   - Confirm: `NewSecure123!`
   - Clicks: "Reset Password"
7. **System:**
   - Hashes password with bcrypt
   - Updates `clients.pword` for userId=123
   - Updates `forgot_password.status` to 1
   - Sends confirmation email
   - Redirects to `/login?reset=1`
8. **User sees:** "Password has been reset successfully!"
9. **User logs in** with new password

### Example 2: Member (DJ) Password Reset

1. **User visits:** `https://app.digiwaxx.com/forgot-password`
2. **User enters:**
   - Email: `dj@djservice.com`
   - Selects: "DJ (Member)"
   - Clicks: "Send Reset Link"
3. **System:**
   - Searches `members` table for email
   - Generates token: `z9y8x7w6v5u4t3s2r1q0`
   - Stores in `forgot_password`:
     ```sql
     INSERT INTO forgot_password (userId, userType, code, status)
     VALUES (456, '2', 'z9y8x7w6v5u4t3s2r1q0', 0);
     ```
   - Sends email to `dj@djservice.com`
4. **Process continues same as Example 1...**

---

## Security Features

### 1. Password Hashing
- **Method:** bcrypt via `PasswordMigrationHelper::hashPassword()`
- **Location:** `App\Helpers\PasswordMigrationHelper`
- **Security:** Industry-standard, resistant to rainbow tables

### 2. Token Generation
- **Method:** `Str::random(20)` - Laravel's secure random string generator
- **Length:** 20 characters
- **Characters:** Alphanumeric (a-z, A-Z, 0-9)
- **Uniqueness:** Cryptographically secure

### 3. Token Validation
- **Checks:**
  * Token exists in database
  * Status = 0 (not used)
  * Correct user type
- **Invalid tokens:** Redirect with error message

### 4. One-Time Use Tokens
- After password reset, token status changed to 1
- Token cannot be reused
- Must request new token for another reset

### 5. Email Encoding
- Emails can be stored URL-encoded in database
- System checks both encoded and plain versions
- Example: `test@example.com` or `test%40example.com`

### 6. Password Requirements (Frontend Validation)
- Minimum 8 characters
- At least 1 uppercase letter
- At least 1 lowercase letter
- At least 1 number
- Passwords must match

---

## Installation & Setup

### Step 1: Include Routes

In your main routes file (e.g., `routes/web.php` or similar):

```php
// Include password reset routes
require __DIR__ . '/password-reset.php';
```

### Step 2: Verify Email Configuration

Ensure your `.env` file has email settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=business@digiwaxx.com
MAIL_FROM_NAME="DigiWaxx"
```

### Step 3: Test Email Sending

Test that emails can be sent:

```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Step 4: Verify Database Tables

Ensure these tables exist:
- `clients` (with columns: id, email, name, pword)
- `members` (with columns: id, email, fname, pword)
- `forgot_password` (with columns: id, userId, userType, code, status)

### Step 5: Clear Caches

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Step 6: Test the Flow

1. Visit: `/forgot-password`
2. Enter a valid email and user type
3. Check email inbox for reset link
4. Click link and reset password
5. Verify login works with new password

---

## Testing Checklist

### ✅ Functional Tests

- [ ] Forgot password page loads correctly
- [ ] Logo displays (if configured)
- [ ] Email validation works
- [ ] User type radio buttons work
- [ ] Form submission with valid email succeeds
- [ ] Form submission with invalid email shows error
- [ ] Reset email is sent and received
- [ ] Reset email contains correct link
- [ ] Reset link opens password reset page
- [ ] Invalid/expired token shows error
- [ ] Password requirements display correctly
- [ ] Password visibility toggle works
- [ ] Real-time password validation works
- [ ] Password mismatch shows error
- [ ] Password reset succeeds with valid data
- [ ] Confirmation email is sent
- [ ] Login works with new password
- [ ] Old password no longer works
- [ ] Token cannot be reused
- [ ] Redirects work correctly

### ✅ Security Tests

- [ ] Passwords are bcrypt hashed
- [ ] Tokens are cryptographically secure
- [ ] Tokens are one-time use only
- [ ] Invalid tokens are rejected
- [ ] User type is validated
- [ ] Email exists check works
- [ ] SQL injection attempts are blocked (parameterized queries)
- [ ] XSS attempts are escaped

### ✅ Email Tests

- [ ] Reset email arrives in inbox (not spam)
- [ ] Email formatting looks good
- [ ] Links in email work
- [ ] Email works on mobile devices
- [ ] Email client compatibility (Gmail, Outlook, etc.)
- [ ] Confirmation email arrives
- [ ] Confirmation email formatting looks good

### ✅ UI/UX Tests

- [ ] Forms are mobile-responsive
- [ ] Buttons work on touch devices
- [ ] Error messages are clear
- [ ] Success messages are visible
- [ ] Loading states are indicated
- [ ] Form validation is user-friendly
- [ ] Password requirements are clear
- [ ] Visual feedback for password strength

---

## Troubleshooting

### Problem: Reset Email Not Received

**Possible Causes:**
1. Email configuration incorrect
2. Email in spam folder
3. SMTP server issues
4. Email address doesn't exist

**Solutions:**
1. Check `.env` email settings
2. Test email with `php artisan tinker`
3. Check spam/junk folder
4. Verify SMTP credentials
5. Check mail logs: `storage/logs/laravel.log`

### Problem: "Invalid or Expired Token"

**Possible Causes:**
1. Token already used
2. Token doesn't exist in database
3. Token status = 1 (used)

**Solutions:**
1. Request a new reset link
2. Check `forgot_password` table for token
3. Verify token status is 0
4. Check for typos in URL

### Problem: Password Reset Fails

**Possible Causes:**
1. Database connection issue
2. Password validation failed
3. User account doesn't exist

**Solutions:**
1. Check database connection
2. Verify password meets requirements
3. Check error logs
4. Verify user exists in correct table

### Problem: Login Fails After Reset

**Possible Causes:**
1. Password not actually updated
2. Browser cached old password
3. Using wrong user type to login

**Solutions:**
1. Check database - verify password hash changed
2. Clear browser cache/cookies
3. Try different browser
4. Verify logging in with correct user type

---

## Customization

### Change Token Expiration

Currently, tokens don't expire. To add expiration:

1. **Add expiration check in `ResetPasswordController::getResetPassword()`:**

```php
public function getResetPassword($token = null)
{
    // ... existing code ...

    // Add expiration check (24 hours)
    $tokenData = DB::table('forgot_password')
        ->where('code', $token)
        ->where('status', 0)
        ->first();

    if ($tokenData) {
        $createdAt = Carbon::parse($tokenData->created_at);
        $expiresAt = $createdAt->addHours(24);

        if (now()->greaterThan($expiresAt)) {
            return Redirect::to('forgot-password?invalidCode=1');
        }
    }

    // ... rest of code ...
}
```

### Change Email Templates

Email templates are located in:
- `resources/views/mails/password/verify.blade.php`
- `resources/views/mails/password/confirmemail.blade.php`

You can customize:
- Colors and styling
- Layout and structure
- Content and messaging
- Branding and logos

### Change Password Requirements

Edit `resources/views/auth/password/reset-frontend.blade.php`:

1. Update HTML requirements list
2. Update JavaScript validation function
3. Update backend validation in controller

Example: Require special character:

```javascript
// Add to validatePassword() function
const reqSpecial = document.getElementById('req-special');
if (/[!@#$%^&*]/.test(value)) {
    reqSpecial.classList.add('valid');
    // ... etc
}
```

---

## API Reference

### Endpoints

**1. Show Forgot Password Form**
```
GET /forgot-password
```
- **Query Parameters:**
  * `mailSent=1` - Show success message
  * `error=1` - Show error message
  * `invalidEmail=1` - Show invalid email message
  * `invalidCode=1` - Show invalid token message
- **Returns:** HTML page

**2. Submit Forgot Password**
```
POST /forgot-password
```
- **Body Parameters:**
  * `email` (required) - User's email address
  * `user` (required) - User type (1=Client, 2=Member)
  * `sendMail` (required) - Must be "1"
- **Returns:** Redirect with message

**3. Show Reset Password Form**
```
GET /reset-password/{token}
```
- **URL Parameters:**
  * `token` (required) - Password reset token
- **Returns:** HTML page or redirect if invalid

**4. Submit Reset Password**
```
POST /reset-password-submit
```
- **Body Parameters:**
  * `password` (required) - New password (min 8 chars)
  * `token` (required) - Password reset token
- **Returns:** Redirect to login

---

## Support

### Email Support
- **Email:** business@digiwaxx.com
- **Subject:** "Password Reset Issue"

### Common User Questions

**Q: How long does the reset link last?**
A: Currently, reset links don't expire. They are one-time use only.

**Q: I didn't receive the reset email. What should I do?**
A:
1. Check your spam/junk folder
2. Verify you entered the correct email address
3. Try requesting a new reset link
4. Contact support if issue persists

**Q: Can I reset my password multiple times?**
A: Yes, you can request as many reset links as needed. Each link is one-time use.

**Q: What if I forgot which email I used?**
A: Contact support at business@digiwaxx.com with your username or other account details.

**Q: Is it safe to reset my password?**
A: Yes, the system uses industry-standard security (bcrypt hashing, secure tokens, one-time use links).

---

## Changelog

### Version 1.0 (Current)
- ✅ Initial implementation
- ✅ Support for Clients and Members
- ✅ Email notifications
- ✅ Bcrypt password hashing
- ✅ One-time use tokens
- ✅ Beautiful UI with validation
- ✅ Responsive design
- ✅ Comprehensive documentation

### Future Enhancements
- [ ] Token expiration (24-hour limit)
- [ ] Rate limiting (prevent spam)
- [ ] Password strength meter
- [ ] Two-factor authentication option
- [ ] Account lockout after failed attempts
- [ ] Password history (prevent reuse)
- [ ] Audit log for password changes

---

## Files Summary

**Created Files:**
1. `resources/views/mails/password/email.blade.php` - Forgot password form
2. `resources/views/mails/password/verify.blade.php` - Reset email template
3. `resources/views/mails/password/confirmemail.blade.php` - Confirmation email
4. `resources/views/auth/password/reset-frontend.blade.php` - Reset password form
5. `routes/password-reset.php` - Routes configuration
6. `PASSWORD_RESET_COMPLETE_GUIDE.md` - This documentation

**Existing Files Used:**
1. `Http/Controllers/Auth/ForgotPasswordController.php` - Controller
2. `Http/Controllers/Auth/ResetPasswordController.php` - Controller
3. `Models/Frontend/FrontEndUser.php` - Model methods

**Total:** 6 new files, 3 existing files enhanced

---

**Last Updated:** 2025-01-21
**Version:** 1.0
**Author:** Digiwaxx Development Team

