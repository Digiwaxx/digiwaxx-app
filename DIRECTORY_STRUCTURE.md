# Digiwaxx App Directory Structure

**Verified:** 2025-11-22
**Actual Root Path:** `/home/user/digiwaxx-app/`

> **Note:** The believed path `/home/app.digiwaxx/public_html/` does not exist in this environment.
> The repository root is at `/home/user/digiwaxx-app/`.

---

## Overview

This is a **Laravel-based** web application for music distribution/management. The structure follows Laravel conventions with some custom additions.

---

## Main Directory Structure

```
/home/user/digiwaxx-app/
├── app/                    # Application logic (Models, Controllers, Services)
├── config/                 # Configuration files
├── database/               # Migrations, seeders
├── public/                 # Web root (publicly accessible files)
├── resources/              # Views, assets, language files
├── routes/                 # Route definitions
├── .env                    # Environment configuration (exists)
├── .env.example            # Example environment file
├── composer.json           # PHP dependencies
├── composer.lock           # Locked dependency versions
└── [documentation files]   # Various .md files for security, deployment, etc.
```

---

## Detailed Directory Breakdown

### `/app/` - Application Logic

```
app/
├── Console/                # Artisan commands
├── Events/                 # Event classes
├── Exceptions/             # Exception handlers
├── Helpers/                # Custom helper functions
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Authentication controllers
│   │   ├── Clients/        # Client-specific controllers
│   │   ├── Members/        # Member-specific controllers
│   │   ├── AdminController.php
│   │   ├── AdminAddTracksController.php
│   │   ├── AIController.php
│   │   ├── AlbumsController.php
│   │   ├── HomeController.php
│   │   ├── MailController.php
│   │   ├── NewsController.php
│   │   ├── PagesController.php
│   │   ├── ReviewNotificationsController.php
│   │   ├── StripeDigiPaymentController.php
│   │   ├── StripeWebhookController.php
│   │   ├── SubscriptionController.php
│   │   ├── SubscriptionErrorTestController.php
│   │   └── TrackReportController.php
│   ├── Middleware/
│   │   ├── Authenticate.php
│   │   ├── EncryptCookies.php
│   │   ├── PreventRequestsDuringMaintenance.php
│   │   ├── RedirectIfAuthenticated.php
│   │   ├── SecurityEventLogger.php      # Custom
│   │   ├── SecurityHeaders.php          # Custom
│   │   ├── SetLocale.php
│   │   ├── TrimStrings.php
│   │   ├── TrustHosts.php
│   │   ├── TrustProxies.php
│   │   └── VerifyCsrfToken.php
│   └── Requests/           # Form request validation classes
├── Mail/
│   ├── AdminForgetNotification.php
│   ├── DemoEmail.php
│   ├── MailtrapExample.php
│   └── TrackReviewNotification.php
├── Models/
│   ├── Frontend/           # Frontend-specific models
│   ├── Traits/             # Model traits
│   ├── Admin.php
│   ├── Albums.php
│   ├── ClientAllDB.php
│   ├── Gallery.php
│   ├── MemberAllDB.php
│   ├── Tracks.php
│   ├── TracksSubmitted.php
│   └── User.php
├── Policies/               # Authorization policies
├── Providers/              # Service providers
└── Services/
    ├── GitHubAI.php
    ├── TrackReportGenerator.php
    └── UploadLimitService.php
```

### `/resources/` - Views & Assets

```
resources/
├── css/                    # CSS source files
├── js/                     # JavaScript source files
├── lang/
│   └── en/                 # English language files
└── views/
    ├── admin/
    │   └── includes/
    │       └── partials/
    ├── auth/
    │   └── password/       # Password reset views
    ├── clients/
    │   └── dashboard/
    │       └── includes/
    ├── components/         # Blade components
    ├── emails/             # Email templates
    ├── forums/             # Forum views
    ├── layouts/
    │   └── include/        # Layout partials
    ├── mails/
    │   ├── clients/
    │   ├── forums/
    │   ├── members/
    │   ├── package/
    │   ├── password/
    │   └── templates/
    │       ├── Product/
    │       └── newsletter/
    ├── members/
    │   └── dashboard/
    ├── news/               # News views
    ├── pages/              # Static page templates
    └── subscription/       # Subscription-related views
```

### `/routes/` - Route Definitions

```
routes/
├── api.php                 # API routes
├── channels.php            # Broadcasting channels
├── console.php             # Console commands
├── email_notifications.php # Email notification routes (custom)
└── web.php                 # Web routes (main - 62KB)
```

### `/config/` - Configuration

```
config/
├── app.php                 # Main application config
├── localization.php        # Localization settings
└── stripe.php              # Stripe payment configuration
```

### `/database/` - Database

```
database/
└── migrations/
    ├── 2025_01_21_000001_add_file_attachments_to_chat_messages.php
    ├── 2025_01_21_000002_add_read_receipts_to_chat_messages.php
    ├── 2025_01_21_000003_create_chat_typing_indicators_table.php
    ├── 2025_01_21_000004_create_user_online_status_table.php
    ├── 2025_01_21_000005_add_search_index_to_chat_messages.php
    ├── 2025_11_21_000001_add_email_preferences_and_notifications.php
    ├── 2025_11_21_000001_add_subscription_tier_fields_to_clients.php
    ├── 2025_11_21_000002_create_monthly_uploads_table.php
    └── 2025_11_21_000003_create_subscription_payments_table.php
```

### `/public/` - Web Root

```
public/
└── sitemap.xml             # Site map for SEO
```

> **Note:** This appears to be a minimal public directory in the repo.
> On production, this would contain compiled assets, images, etc.

---

## Key Files in Root

| File | Purpose |
|------|---------|
| `.env` | Environment configuration (DB, mail, API keys) |
| `.env.example` | Template for environment setup |
| `composer.json` | PHP dependencies definition |
| `composer.lock` | Locked dependency versions |
| `.gitignore` | Git ignore rules |

---

## Documentation Files (Root Level)

The project includes extensive documentation:

- **Security Reports:** `COMPLETE_SECURITY_REPORT.md`, `CRITICAL_SQL_INJECTION_ALERT.md`, `FINAL_SECURITY_SUMMARY.md`, `SQL_INJECTION_FIXES_FINAL_REPORT.md`, `SECURITY_AUDIT_FULL.md`
- **Deployment:** `DEPLOYMENT_GUIDE.md`, `DEPLOYMENT_CHECKLIST.md`, `DEPLOYMENT_SECURITY_CHECKLIST.md`
- **Features:** `CHAT_*.md` (multiple chat system docs), `PLAY_TRACKING_IMPLEMENTATION.md`, `RATE_LIMITING_IMPLEMENTATION.md`
- **Testing:** `TESTING_QUICKSTART.md`, `SQL_INJECTION_TESTING_PLAN.md`, `EMAIL_TESTING_GUIDE.md`
- **Other:** `BROKEN_FEATURES_REPORT.md`, `PASSWORD_MIGRATION_GUIDE.md`, `RECAPTCHA_IMPLEMENTATION.md`

---

## Utility Scripts (Root Level)

| Script | Purpose |
|--------|---------|
| `check_chat_tables.php` | Verify chat database tables |
| `fix_sql_injection.php` | SQL injection fix utility |
| `fix_sql_injection.py` | Python SQL injection fixer |
| `fix_remaining_sql_injections.py` | Additional SQL fixes |
| `rehash_passwords.php` | Password rehashing utility |
| `run_migration.php` | Migration runner |
| `test_sql_injection_fixes.sh` | Security test script |
| `DATABASE_INDEXES.sql` | Database index definitions |

---

## Custom/Project-Specific Directories

These directories are **custom to this project** (not standard Laravel):

1. **`app/Helpers/`** - Custom helper functions
2. **`app/Services/`** - Service classes (GitHubAI, TrackReportGenerator, UploadLimitService)
3. **`app/Models/Frontend/`** - Frontend-specific models
4. **`app/Models/Traits/`** - Model traits
5. **`app/Http/Controllers/Clients/`** - Client user controllers
6. **`app/Http/Controllers/Members/`** - Member user controllers
7. **`resources/views/clients/`** - Client dashboard views
8. **`resources/views/members/`** - Member dashboard views
9. **`resources/views/forums/`** - Forum system views
10. **`resources/views/mails/`** - Detailed mail templates
11. **`routes/email_notifications.php`** - Custom email notification routes

---

## Missing Standard Laravel Directories

These standard Laravel directories are **NOT present** in this repo:

- `bootstrap/` - Laravel bootstrap files
- `storage/` - Logs, cache, uploaded files
- `tests/` - Test files
- `vendor/` - Composer dependencies (typically gitignored)
- `artisan` - Laravel CLI tool

> These may exist in production but are excluded from the repository.

---

## User Roles

The application supports multiple user types:

1. **Admin** - Full system access (`app/Http/Controllers/AdminController.php`)
2. **Clients** - Client dashboard (`app/Http/Controllers/Clients/`)
3. **Members** - Member dashboard (`app/Http/Controllers/Members/`)

---

## Quick Reference Paths

```bash
# Controllers
/home/user/digiwaxx-app/app/Http/Controllers/

# Models
/home/user/digiwaxx-app/app/Models/

# Views
/home/user/digiwaxx-app/resources/views/

# Routes
/home/user/digiwaxx-app/routes/web.php

# Config
/home/user/digiwaxx-app/config/

# Migrations
/home/user/digiwaxx-app/database/migrations/
```
