# Database Migration Guide

## Step 6: Run Database Migrations

### Prerequisites

Make sure you have:
- ✅ Database connection configured in `.env`
- ✅ Database user has CREATE TABLE permissions
- ✅ Backup of database (just in case)

---

## Option 1: Run Migrations via Laravel Artisan (Recommended)

### Preview Changes First (Dry Run)

```bash
php artisan migrate --pretend
```

This shows you what SQL will be executed WITHOUT making any changes.

### Run Migrations

```bash
php artisan migrate
```

This will create:
- ✅ `review_notification_token` column in `clients` table
- ✅ `email_weekly_digest`, `email_milestones`, `email_newsletter` columns in `clients`
- ✅ `email_preferences_updated_at` column in `clients`
- ✅ Email index on `clients.email`
- ✅ `generated_reports` table
- ✅ `email_notification_logs` table

### Verify Migrations

```bash
php artisan migrate:status
```

Should show the new migration as "Ran".

---

## Option 2: Run SQL Manually (If Artisan Not Available)

If Laravel's artisan command doesn't work, run this SQL directly:

```sql
-- =================================================================
-- EMAIL PREFERENCES AND NOTIFICATION SYSTEM
-- Migration: 2025_11_21_000001_add_email_preferences_and_notifications
-- =================================================================

-- Add columns to clients table
ALTER TABLE `clients`
ADD COLUMN `review_notification_token` VARCHAR(64) UNIQUE NULL AFTER `trackReviewEmailsActivated`,
ADD COLUMN `email_weekly_digest` TINYINT(1) DEFAULT 1 AFTER `review_notification_token`,
ADD COLUMN `email_milestones` TINYINT(1) DEFAULT 1 AFTER `email_weekly_digest`,
ADD COLUMN `email_newsletter` TINYINT(1) DEFAULT 1 AFTER `email_milestones`,
ADD COLUMN `email_preferences_updated_at` TIMESTAMP NULL AFTER `email_newsletter`,
ADD INDEX `clients_email_index` (`email`);

-- Create generated_reports table
CREATE TABLE IF NOT EXISTS `generated_reports` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `client_id` BIGINT UNSIGNED NOT NULL,
    `track_id` BIGINT UNSIGNED NULL,
    `report_type` VARCHAR(50) NOT NULL,
    `format` VARCHAR(10) NOT NULL,
    `date_range_start` DATE NULL,
    `date_range_end` DATE NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `generated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    `download_count` INT DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_client_id` (`client_id`),
    INDEX `idx_track_id` (`track_id`),
    INDEX `idx_generated_at` (`generated_at`),
    INDEX `idx_expires_at` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create email_notification_logs table
CREATE TABLE IF NOT EXISTS `email_notification_logs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `client_id` BIGINT UNSIGNED NOT NULL,
    `track_id` BIGINT UNSIGNED NULL,
    `review_id` BIGINT UNSIGNED NULL,
    `notification_type` VARCHAR(50) NOT NULL,
    `status` VARCHAR(20) NOT NULL,
    `recipient_email` VARCHAR(255) NOT NULL,
    `error_message` TEXT NULL,
    `sent_at` TIMESTAMP NULL,
    `opened_at` TIMESTAMP NULL,
    `clicked_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_client_id` (`client_id`),
    INDEX `idx_track_id` (`track_id`),
    INDEX `idx_review_id` (`review_id`),
    INDEX `idx_notification_type` (`notification_type`),
    INDEX `idx_status` (`status`),
    INDEX `idx_sent_at` (`sent_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Verify Tables Were Created

```sql
-- Check clients table has new columns
DESCRIBE clients;

-- Should see:
-- review_notification_token (varchar 64, nullable)
-- email_weekly_digest (tinyint 1, default 1)
-- email_milestones (tinyint 1, default 1)
-- email_newsletter (tinyint 1, default 1)
-- email_preferences_updated_at (timestamp, nullable)

-- Check new tables exist
SHOW TABLES LIKE 'generated_reports';
SHOW TABLES LIKE 'email_notification_logs';

-- Check table structures
DESCRIBE generated_reports;
DESCRIBE email_notification_logs;
```

---

## What Each Column/Table Does

### Clients Table - New Columns

| Column | Purpose |
|--------|---------|
| `review_notification_token` | Secure token for unsubscribe links (one per client) |
| `email_weekly_digest` | Toggle weekly review summary emails (1=on, 0=off) |
| `email_milestones` | Toggle milestone notifications (100 downloads, etc.) |
| `email_newsletter` | Toggle general newsletter/updates (1=on, 0=off) |
| `email_preferences_updated_at` | When user last changed email preferences |

### Generated Reports Table

Stores history of all generated reports (PDF/CSV).

**Key columns:**
- `client_id` - Who generated it
- `track_id` - Which track (NULL for multi-track reports)
- `report_type` - validation/demand/regional/format/full
- `format` - pdf or csv
- `file_path` - Where the file is stored
- `expires_at` - When to auto-delete (default: 30 days)
- `download_count` - How many times downloaded

### Email Notification Logs Table

Tracks every email sent by the system.

**Key columns:**
- `client_id` - Who received it
- `track_id` - Related track (if applicable)
- `review_id` - Related review (if applicable)
- `notification_type` - review/weekly_digest/milestone/newsletter
- `status` - sent/failed/bounced/opened/clicked
- `recipient_email` - Where it was sent
- `error_message` - Error details if failed
- `sent_at` - When sent
- `opened_at` - When opened (if tracking enabled)
- `clicked_at` - When link clicked (if tracking enabled)

---

## Rollback (If Something Goes Wrong)

### Via Laravel Artisan

```bash
php artisan migrate:rollback
```

### Manual SQL Rollback

```sql
-- Remove columns from clients table
ALTER TABLE `clients`
DROP COLUMN `review_notification_token`,
DROP COLUMN `email_weekly_digest`,
DROP COLUMN `email_milestones`,
DROP COLUMN `email_newsletter`,
DROP COLUMN `email_preferences_updated_at`,
DROP INDEX `clients_email_index`;

-- Drop new tables
DROP TABLE IF EXISTS `generated_reports`;
DROP TABLE IF EXISTS `email_notification_logs`;
```

---

## Common Issues & Solutions

### Issue: "Column already exists"

**Cause**: Migration was run before, or column exists from previous version

**Solution**: Skip that column or modify migration to check if exists first

```sql
-- Check if column exists before adding
SELECT COUNT(*)
FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = 'your_database_name'
  AND TABLE_NAME = 'clients'
  AND COLUMN_NAME = 'review_notification_token';
-- If returns 1, column already exists, skip it
```

### Issue: "Table 'generated_reports' already exists"

**Cause**: Table was created before

**Solution**: Check if it has the right structure:

```sql
DESCRIBE generated_reports;
```

If structure matches, you're good. If not, drop and recreate:

```sql
DROP TABLE generated_reports;
-- Then run CREATE TABLE again
```

### Issue: "Access denied" or "Permission denied"

**Cause**: Database user doesn't have CREATE/ALTER permissions

**Solution**: Grant permissions:

```sql
GRANT CREATE, ALTER, DROP ON database_name.* TO 'username'@'localhost';
FLUSH PRIVILEGES;
```

---

## After Migration - Verification Checklist

Run these queries to verify everything worked:

```sql
-- 1. Check clients table
SELECT
    review_notification_token,
    email_weekly_digest,
    email_milestones,
    email_newsletter
FROM clients
LIMIT 1;
-- Should return columns with NULL or default values

-- 2. Check generated_reports table
SELECT COUNT(*) FROM generated_reports;
-- Should return 0 (empty table)

-- 3. Check email_notification_logs table
SELECT COUNT(*) FROM email_notification_logs;
-- Should return 0 (empty table) unless emails already sent

-- 4. Check indexes
SHOW INDEX FROM clients WHERE Key_name = 'clients_email_index';
-- Should return 1 row

SHOW INDEX FROM generated_reports;
-- Should return 5 indexes (PRIMARY + 4 others)

SHOW INDEX FROM email_notification_logs;
-- Should return 7 indexes (PRIMARY + 6 others)
```

---

## Next Steps After Migration

1. ✅ Verify all tables created successfully
2. ✅ Test email sending (Step 7)
3. ✅ Check logs in `email_notification_logs` table
4. ✅ Generate a test report
5. ✅ Check `generated_reports` table populated

---

**Migration File Location:**
`database/migrations/2025_11_21_000001_add_email_preferences_and_notifications.php`

**Need to rollback?** See "Rollback" section above.

**Migrations successful?** Proceed to Step 7 - Testing!
