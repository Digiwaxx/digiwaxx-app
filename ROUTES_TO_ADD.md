# Routes to Add - Email Notifications System

## Quick Integration

Add these routes to your main Laravel routes file (`web.php` or `routes.php`):

### Option 1: Include the routes file

```php
// Add this line to your main routes file
require __DIR__.'/email_notifications.php';
```

### Option 2: Copy routes directly

If you prefer, copy the routes from `routes/email_notifications.php` directly into your main routes file.

---

## Routes Added

### Email Notification Unsubscribe (Public - No Auth Required)

```php
GET  /unsubscribe-reviews/{token}          → Show unsubscribe confirmation page
POST /unsubscribe-reviews/confirm/{token}  → Process unsubscribe
GET  /resubscribe-reviews/{token}          → Resubscribe to notifications
```

**Usage**: These are called from email links, so no authentication required.

---

### Track Report Downloads (Mixed Auth)

```php
GET    /track/report/download/{token}      → Download report (no auth - uses token)
GET    /track/{id}/generate-report         → Generate new report (auth required)
GET    /track/{id}/report-options          → Show report options modal (auth required)
GET    /reports/history                    → View report history (auth required)
DELETE /reports/{id}                       → Delete old report (auth required)
```

**Usage**: Download uses secure token (shareable). Generation requires login.

---

### Email Preferences (Auth Required)

```php
GET  /settings/email-preferences           → Show preferences page
POST /settings/email-preferences           → Update preferences
```

**Usage**: Authenticated users can manage their email notification preferences.

---

### Admin Email Management (Admin Auth Required)

```php
GET  /admin/email-settings                 → Email settings page
POST /admin/email-settings                 → Update settings
GET  /admin/email-statistics               → Statistics dashboard
GET  /admin/email-logs                     → View email logs
POST /admin/email-test                     → Send test email
POST /admin/email-resend/{id}              → Resend failed email
```

**Usage**: Admin-only routes for managing the email notification system.

---

## Controllers Needed

Make sure these controllers exist:

- ✅ `ReviewNotificationsController` (already created)
- ⏳ `TrackReportController` (needs to be created)
- ⏳ `EmailPreferencesController` (needs to be created)
- ⏳ `Admin\AdminEmailController` (needs to be created)

---

## Middleware Used

- `auth` - Requires user to be logged in
- `admin` - Requires admin privileges (you may need to create this middleware)

If `admin` middleware doesn't exist, you can:
1. Create it, or
2. Replace with your existing admin check middleware

---

## Testing Routes

After adding routes, test that they're registered:

```bash
php artisan route:list | grep -E "unsubscribe|report|email-preferences"
```

You should see all the routes listed above.

---

## Next Steps

1. ✅ Add routes to your routes file
2. ⏳ Create missing controllers (TrackReportController, etc.)
3. ⏳ Create admin middleware if needed
4. ⏳ Test route access
