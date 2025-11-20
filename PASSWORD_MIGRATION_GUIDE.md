# Password Migration Guide: MD5 to Bcrypt

## ⚠️ CRITICAL SECURITY ISSUE

Your application currently stores passwords using MD5, which is:
- **Cryptographically broken** since 2008
- **Vulnerable to rainbow table attacks**
- **Extremely fast to crack** with modern GPUs
- **Non-compliant** with security standards (OWASP, PCI-DSS)

**All passwords MUST be migrated to bcrypt immediately.**

---

## Migration Strategy

### Option 1: Forced Password Reset (RECOMMENDED)

**Pros:**
- Most secure approach
- All users get strong passwords
- Clean migration
- Opportunity to enforce password complexity

**Cons:**
- Users must reset passwords
- Some users may not complete process

**Steps:**

1. **Add password reset flag to database:**
```sql
-- For clients table
ALTER TABLE clients ADD COLUMN password_reset_required TINYINT(1) DEFAULT 1;
ALTER TABLE clients ADD COLUMN password_reset_token VARCHAR(100) NULL;
ALTER TABLE clients ADD COLUMN password_reset_expires DATETIME NULL;

-- For members table
ALTER TABLE members ADD COLUMN password_reset_required TINYINT(1) DEFAULT 1;
ALTER TABLE members ADD COLUMN password_reset_token VARCHAR(100) NULL;
ALTER TABLE members ADD COLUMN password_reset_expires DATETIME NULL;

-- For admins table
ALTER TABLE admins ADD COLUMN password_reset_required TINYINT(1) DEFAULT 1;
ALTER TABLE admins ADD COLUMN password_reset_token VARCHAR(100) NULL;
ALTER TABLE admins ADD COLUMN password_reset_expires DATETIME NULL;
```

2. **Mark all existing users for password reset:**
```sql
UPDATE clients SET password_reset_required = 1 WHERE pword IS NOT NULL;
UPDATE members SET password_reset_required = 1 WHERE pword IS NOT NULL;
UPDATE admins SET password_reset_required = 1 WHERE password IS NOT NULL;
```

3. **Send password reset emails to all users**
4. **Update authentication to check reset flag**
5. **Force password reset on next login**

### Option 2: Gradual Migration (LESS SECURE)

**Pros:**
- No user disruption
- Transparent to users

**Cons:**
- Leaves some passwords in MD5 temporarily
- Requires dual-mode authentication
- Takes longer to complete

**Steps:**

1. **Add new password field:**
```sql
ALTER TABLE clients ADD COLUMN password_bcrypt VARCHAR(255) NULL;
ALTER TABLE members ADD COLUMN password_bcrypt VARCHAR(255) NULL;
ALTER TABLE admins ADD COLUMN password_new VARCHAR(255) NULL;
```

2. **Modify login to check both:**
   - Try bcrypt first
   - Fall back to MD5 if bcrypt is null
   - If MD5 succeeds, immediately convert to bcrypt

3. **Monitor until all passwords converted**

---

## Implementation

### Step 1: Create Password Migration Command

File: `app/Console/Commands/MigratePasswords.php`

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class MigratePasswords extends Command
{
    protected $signature = 'passwords:migrate {--send-emails : Send reset emails to users}';
    protected $description = 'Migrate MD5 passwords to bcrypt';

    public function handle()
    {
        $this->info('Starting password migration...');

        // Mark all users for password reset
        $this->markUsersForReset();

        // Optionally send reset emails
        if ($this->option('send-emails')) {
            $this->sendResetEmails();
        }

        $this->info('Password migration completed!');
    }

    private function markUsersForReset()
    {
        DB::table('clients')->update(['password_reset_required' => 1]);
        DB::table('members')->update(['password_reset_required' => 1]);
        DB::table('admins')->update(['password_reset_required' => 1]);

        $this->info('All users marked for password reset.');
    }

    private function sendResetEmails()
    {
        // Send to clients
        $clients = DB::table('clients')
            ->where('active', 1)
            ->where('deleted', 0)
            ->select('id', 'email', 'name')
            ->get();

        foreach ($clients as $client) {
            $this->sendResetEmail($client, 'client');
        }

        // Send to members
        $members = DB::table('members')
            ->where('active', 1)
            ->where('deleted', 0)
            ->select('id', 'email', 'fname as name')
            ->get();

        foreach ($members as $member) {
            $this->sendResetEmail($member, 'member');
        }

        $this->info('Reset emails sent to all active users.');
    }

    private function sendResetEmail($user, $type)
    {
        $token = Str::random(64);
        $expires = now()->addHours(24);

        // Save token
        DB::table($type . 's')->where('id', $user->id)->update([
            'password_reset_token' => $token,
            'password_reset_expires' => $expires,
        ]);

        // Send email (implement based on your mail system)
        // Mail::to($user->email)->send(new PasswordResetMail($token, $user->name));
    }
}
```

### Step 2: Update LoginController

Add this method to check reset flag:

```php
private function checkPasswordResetRequired($user, $userType)
{
    $table = $userType === 'client' ? 'clients' : 'members';

    $resetRequired = DB::table($table)
        ->where('id', $user->id)
        ->value('password_reset_required');

    if ($resetRequired) {
        Session::put('password_reset_user_id', $user->id);
        Session::put('password_reset_user_type', $userType);
        return redirect('password/reset/required');
    }

    return null;
}
```

Call after successful authentication:

```php
// After verifying credentials
$resetCheck = $this->checkPasswordResetRequired($result['data'][0], $membertype);
if ($resetCheck) {
    return $resetCheck;
}

// Continue with normal login
```

### Step 3: Create Password Reset Views

Create forced password reset form at `resources/views/auth/passwords/reset-required.blade.php`

### Step 4: Update Password Reset Logic

```php
public function resetPassword(Request $request)
{
    $request->validate([
        'password' => [
            'required',
            'string',
            'min:8',
            'confirmed',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
        ],
    ], [
        'password.regex' => 'Password must contain: uppercase, lowercase, number, and special character.',
    ]);

    $userId = Session::get('password_reset_user_id');
    $userType = Session::get('password_reset_user_type');

    if (!$userId || !$userType) {
        return redirect('login')->with('error', 'Invalid reset session');
    }

    $table = $userType === 'client' ? 'clients' : 'members';

    // Update with bcrypt password
    DB::table($table)->where('id', $userId)->update([
        'pword' => bcrypt($request->password),
        'password_reset_required' => 0,
        'password_reset_token' => null,
        'password_reset_expires' => null,
    ]);

    Session::forget(['password_reset_user_id', 'password_reset_user_type']);

    return redirect('login')->with('success', 'Password updated successfully. Please login.');
}
```

---

## Password Policy Requirements

### New Password Requirements

All new passwords MUST meet these criteria:

- **Minimum 8 characters**
- **At least 1 uppercase letter** (A-Z)
- **At least 1 lowercase letter** (a-z)
- **At least 1 number** (0-9)
- **At least 1 special character** (@$!%*?&)
- **No common passwords** (check against list)

### Validation Regex

```php
'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
```

---

## Email Templates

### Password Reset Required Email

```html
Subject: Important: Password Reset Required - Digiwaxx

Dear {name},

As part of our ongoing commitment to security, we've upgraded our password
encryption system to protect your account.

You must reset your password to continue accessing your account.

Reset your password here:
{reset_link}

This link will expire in 24 hours.

If you don't reset your password within 7 days, your account will be
temporarily locked for security.

Why are we doing this?
- We've upgraded from MD5 to bcrypt encryption
- This makes your password significantly more secure
- Industry best practice requires this change

Questions? Contact support@digiwaxx.com

Best regards,
Digiwaxx Security Team
```

---

## Timeline

### Recommended Migration Timeline

**Week 1:**
- [ ] Add database columns
- [ ] Update authentication code
- [ ] Test password reset flow
- [ ] Prepare email templates

**Week 2:**
- [ ] Send announcement email to all users
- [ ] Enable password reset requirement
- [ ] Send reset emails in batches
- [ ] Monitor completion rate

**Week 3:**
- [ ] Send reminder emails
- [ ] Provide support for users with issues
- [ ] Lock accounts that haven't reset (optional)

**Week 4:**
- [ ] Review completion rate
- [ ] Handle edge cases
- [ ] Remove old MD5 password column

---

## Monitoring

Track migration progress:

```sql
-- Check how many users have migrated
SELECT
    COUNT(*) as total_users,
    SUM(CASE WHEN password_reset_required = 0 THEN 1 ELSE 0 END) as migrated,
    SUM(CASE WHEN password_reset_required = 1 THEN 1 ELSE 0 END) as pending
FROM clients;

SELECT
    COUNT(*) as total_users,
    SUM(CASE WHEN password_reset_required = 0 THEN 1 ELSE 0 END) as migrated,
    SUM(CASE WHEN password_reset_required = 1 THEN 1 ELSE 0 END) as pending
FROM members;
```

---

## Testing Checklist

Before rolling out:

- [ ] Test password reset flow (client)
- [ ] Test password reset flow (member)
- [ ] Test password reset flow (admin)
- [ ] Test password validation rules
- [ ] Test email delivery
- [ ] Test reset token expiration
- [ ] Test login with old password (should fail)
- [ ] Test login with new password (should work)
- [ ] Test password reset twice (should work)
- [ ] Test expired reset link (should fail)
- [ ] Load test email sending

---

## Rollback Plan

If issues occur:

1. **Disable password reset requirement:**
```sql
UPDATE clients SET password_reset_required = 0;
UPDATE members SET password_reset_required = 0;
UPDATE admins SET password_reset_required = 0;
```

2. **Keep both authentication methods active temporarily**

3. **Investigate and fix issues**

4. **Resume migration**

---

## Support

### Common Issues

**Issue:** User didn't receive reset email
**Solution:** Check spam folder, resend email, provide manual reset option

**Issue:** Reset link expired
**Solution:** Generate new reset link, extend expiration to 48 hours

**Issue:** User forgot which email they used
**Solution:** Search by username, provide account recovery process

**Issue:** User can't access email account
**Solution:** Verify identity through other means, manual password reset

---

## Post-Migration

After 100% migration:

1. **Remove old password column:**
```sql
ALTER TABLE clients DROP COLUMN pword;
ALTER TABLE members DROP COLUMN pword;
-- Keep admins.password as it may already be bcrypt
```

2. **Remove reset tracking columns:**
```sql
ALTER TABLE clients DROP COLUMN password_reset_required;
ALTER TABLE members DROP COLUMN password_reset_required;
```

3. **Update authentication code to only use bcrypt**

4. **Document new password policy in user documentation**

---

## Compliance Notes

This migration ensures compliance with:

- **OWASP Password Storage Cheat Sheet**
- **NIST SP 800-63B Digital Identity Guidelines**
- **PCI DSS Requirement 8.2.1** (if handling payments)
- **GDPR** (proper data protection)

---

## Questions?

Contact: security@digiwaxx.com

Last Updated: 2025-11-20
