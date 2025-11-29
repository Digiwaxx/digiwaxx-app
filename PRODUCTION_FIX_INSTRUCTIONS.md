# Production Server Fix Instructions

## Problem Summary
The login route was not being registered properly, causing a "Route [login] not defined" error. Manual `sed` commands were used to replace `route('login')` with `url('/')`, which broke the login form POST functionality.

## Root Cause
The issue appears to be related to route caching on the production server. The routes are properly defined in `routes/web.php`, but they weren't being recognized after cache clearing.

## Solution

### Step 1: Pull the Latest Code
On the production server at `/home/appdigiwaxx/public_html`:

```bash
cd /home/appdigiwaxx/public_html
git fetch origin
git checkout main  # or your main branch
git pull origin main
```

### Step 2: Revert Manual sed Changes
The `sed` command modified blade templates, replacing `route('login')` with `url('/')`. We need to restore the original code by pulling from git:

```bash
# Check which files were modified
git status

# Restore all blade template files to their git state
git checkout -- resources/views/

# Specifically restore these files if they were changed:
git checkout -- resources/views/layouts/app.blade.php
git checkout -- resources/views/auth/login.blade.php
git checkout -- resources/views/forums/single_forum.blade.php
git checkout -- resources/views/mails/members/addMember.blade.php
git checkout -- resources/views/mails/members/approveMember.blade.php
git checkout -- resources/views/mails/clients/addClient.blade.php
git checkout -- resources/views/mails/clients/approveClient.blade.php
```

### Step 3: Clear ALL Caches Thoroughly
```bash
cd /home/appdigiwaxx/public_html

# Clear route cache
php artisan route:clear

# Clear config cache
php artisan config:clear

# Clear application cache
php artisan cache:clear

# Clear compiled views
php artisan view:clear

# Clear all cached data
php artisan optimize:clear

# IMPORTANT: Rebuild the optimized class loader
composer dump-autoload

# Rebuild route cache (optional, but recommended for production)
php artisan route:cache

# Rebuild config cache (optional, but recommended for production)
php artisan config:cache
```

### Step 4: Verify Routes are Registered
```bash
# Check that the login route appears
php artisan route:list | grep -i login

# Expected output should include:
# GET|HEAD  login   -> LoginController@login
# POST      login   -> LoginController@authenticate
```

### Step 5: Restart Web Server
```bash
systemctl restart httpd
```

### Step 6: Test the Fix
```bash
# Test the login page loads (should return 200)
curl -I https://app.digiwaxx.com/login

# Expected: HTTP/1.1 200 OK
```

## Alternative Fix: If Route Cache is Corrupted

If route caching continues to cause issues, you can disable route caching in production:

```bash
cd /home/appdigiwaxx/public_html

# Remove route cache file
rm -f bootstrap/cache/routes-v7.php

# Clear all caches
php artisan optimize:clear

# Do NOT run route:cache - let routes load dynamically
```

**Note:** This will have a minor performance impact, but ensures routes are always fresh.

## Verification Checklist

- [ ] Git status shows no uncommitted changes in `resources/views/`
- [ ] `php artisan route:list` shows the `login` route
- [ ] `https://app.digiwaxx.com/login` returns HTTP 200
- [ ] Login page displays without errors
- [ ] Login form submits successfully
- [ ] No "Route [login] not defined" errors in `storage/logs/laravel.log`

## Prevention

To prevent this issue in the future:

1. **Never use `sed` to modify code directly on production servers** - always make changes in git and deploy
2. **Always clear route cache after pulling code**: `php artisan route:clear && php artisan route:cache`
3. **Monitor Laravel logs** for routing errors: `tail -f storage/logs/laravel.log`
4. **Use proper deployment process**: Pull code → Clear caches → Restart services

## Rollback Plan

If the above steps don't work, you can manually restore the routes by ensuring `/home/appdigiwaxx/public_html/routes/web.php` contains:

```php
// Login routes - GET shows form, POST processes authentication
Route::get('login', 'App\Http\Controllers\Auth\LoginController@login')->name('login');
Route::post('login', 'App\Http\Controllers\Auth\LoginController@authenticate')->name('login.authenticate');
```

Then run:
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
systemctl restart httpd
```

## Support

If issues persist after following these steps, check:
- File permissions on `bootstrap/cache/` (should be writable by web server)
- Composer autoload issues: `composer dump-autoload`
- PHP opcache: `systemctl restart php-fpm` (if applicable)
