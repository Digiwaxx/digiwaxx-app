# Email Notification System - Testing Guide

## Step 7: Test End-to-End Email Delivery

---

## Pre-Testing Checklist

Before testing, verify:

- ✅ **STEP 1**: PDF library installed (`composer.json` has `barryvdh/laravel-dompdf`)
- ✅ **STEP 2**: Database migrations run successfully
- ✅ **STEP 3**: Routes added to `web.php` or routes file
- ✅ **STEP 4**: Email code integrated in `Models/MemberAllDB.php` (after line 1479)
- ✅ **STEP 5**: Download button component created
- ✅ **STEP 6**: New database tables exist (`generated_reports`, `email_notification_logs`)

---

## Configure Mail Server (CRITICAL)

### Option A: Use MailHog for Testing (Current Setup)

**MailHog** is currently configured in `.env` - perfect for local testing!

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

**Access MailHog inbox:**
- Open browser: `http://localhost:8025`
- All test emails will appear here
- No emails actually sent to real addresses

**✅ Recommended for initial testing**

### Option B: Use Real SMTP for Production

**For production**, update `.env` with real email service:

#### SendGrid Example:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your_api_key_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@digiwaxx.com"
MAIL_FROM_NAME="Digiwaxx"
```

#### Gmail Example (for testing only - not for production):
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="your-email@gmail.com"
MAIL_FROM_NAME="Digiwaxx"
```

#### Mailgun Example:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=postmaster@yourdomain.com
MAIL_PASSWORD=your_mailgun_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@yourdomain.com"
MAIL_FROM_NAME="Digiwaxx"
```

**After updating `.env`:**
```bash
php artisan config:clear
```

---

## Test 1: Submit a Review (End-to-End)

### Step-by-Step Test

1. **Set up test data:**

```sql
-- Ensure test client has email notifications enabled
UPDATE clients
SET
    email = 'test-client@example.com',
    trackReviewEmailsActivated = 1
WHERE id = 1;

-- Ensure test track exists and belongs to client
UPDATE tracks
SET client = 1
WHERE id = 1;

-- Ensure test member (DJ) exists
SELECT * FROM members WHERE id = 1;
```

2. **Submit a review as DJ:**

Login as a DJ (member) and submit a review for a track, OR run directly:

```php
// In tinker or a test script
use App\Models\MemberAllDB;

$memberAllDB = new MemberAllDB();

$reviewData = [
    'whatRate' => 5,
    'comments' => 'This track is amazing! Played it in my last set and the crowd went wild!'
];

$trackId = 1;
$countryName = 'United States';
$countryCode = 'US';

// Simulate DJ session
Session::put('memberId', 1);

// Submit review (this should trigger email)
$result = $memberAllDB->addReview($reviewData, $trackId, $countryName, $countryCode);

echo "Review ID: " . $result;
```

3. **Check for email:**

**If using MailHog:**
- Open `http://localhost:8025`
- Look for email to `test-client@example.com`
- Subject should be: "New 5-Star Review on [Track Title] from DJ [Name]"

**If using real SMTP:**
- Check the inbox of the client email address
- Check spam folder if not in inbox

4. **Verify email content:**

Email should contain:
- ✅ DJ name or "Anonymous DJ"
- ✅ Star rating (⭐⭐⭐⭐⭐)
- ✅ Review comment
- ✅ Track title and artist
- ✅ Date/time of review
- ✅ Link to track analytics
- ✅ "Download Full Report (PDF)" button
- ✅ Unsubscribe link at bottom

5. **Check database logs:**

```sql
-- Verify email was logged
SELECT *
FROM email_notification_logs
ORDER BY id DESC
LIMIT 5;

-- Should see:
-- notification_type = 'review'
-- status = 'sent' (or 'failed' if there was an error)
-- recipient_email = client's email
-- sent_at = timestamp
```

---

## Test 2: Verify Email Links Work

### Test Unsubscribe Link

1. **Get unsubscribe token:**

```sql
SELECT review_notification_token
FROM clients
WHERE id = 1;
```

2. **Visit unsubscribe URL:**

```
http://your-domain.com/unsubscribe-reviews/[TOKEN_HERE]
```

3. **Expected behavior:**
- Shows confirmation page: "Are you sure you want to unsubscribe?"
- Has two buttons: "Yes, Unsubscribe Me" and "Cancel"

4. **Click "Yes, Unsubscribe Me":**

```sql
-- Verify database updated
SELECT trackReviewEmailsActivated
FROM clients
WHERE id = 1;
-- Should now be 0
```

5. **Test success page appears:**
- Shows: "You've Been Unsubscribed"
- Has "Resubscribe" button

### Test Resubscribe Link

1. **Click resubscribe button or visit:**

```
http://your-domain.com/resubscribe-reviews/[TOKEN_HERE]
```

2. **Verify resubscribed:**

```sql
SELECT trackReviewEmailsActivated
FROM clients
WHERE id = 1;
-- Should now be 1
```

---

## Test 3: Verify PDF Report Download

### Test Report Generation

1. **Get report download link from email:**

From the email you received, click "Download Full Report (PDF)" button.

**Or manually test:**

```php
// Generate download token
$trackId = 1;
$token = base64_encode($trackId . '|' . time());
echo "Download URL: /track/report/download/" . $token;
```

2. **Visit download URL:**

```
http://your-domain.com/track/report/download/[TOKEN_HERE]
```

3. **Expected behavior:**
- PDF download starts automatically
- Filename: `Track_Report_[Artist]_[Title]_[Date].pdf`
- PDF contains:
  - Track information
  - Review statistics
  - Rating distribution chart
  - DJ feedback/comments
  - Download statistics
  - Geographic data

4. **Verify download logged:**

```sql
SELECT *
FROM email_notification_logs
WHERE notification_type = 'report_download'
ORDER BY id DESC
LIMIT 1;
```

---

## Test 4: Verify Email NOT Sent to Unsubscribed Clients

1. **Unsubscribe a client:**

```sql
UPDATE clients
SET trackReviewEmailsActivated = 0
WHERE id = 1;
```

2. **Submit another review for that client's track:**

(Follow Test 1 steps again)

3. **Expected result:**
- ✅ Review saves to database successfully
- ❌ NO email sent to client
- ✅ No entry in `email_notification_logs` for this review

4. **Verify:**

```sql
-- Check review was saved
SELECT * FROM tracks_reviews
ORDER BY id DESC LIMIT 1;

-- Check NO email was sent
SELECT * FROM email_notification_logs
WHERE review_id = [ID_FROM_ABOVE]
-- Should return 0 rows
```

---

## Test 5: Test Error Handling

### Test Invalid Email Address

1. **Set client email to invalid:**

```sql
UPDATE clients
SET email = 'invalid-email'
WHERE id = 1;
```

2. **Submit review:**

(Follow Test 1 steps)

3. **Expected behavior:**
- Review still saves successfully
- Email fails but doesn't break review submission
- Error logged

4. **Check error log:**

```sql
SELECT *
FROM email_notification_logs
WHERE status = 'failed'
ORDER BY id DESC LIMIT 1;

-- Should see:
-- status = 'failed'
-- error_message = [error details]
```

**Also check Laravel logs:**

```bash
tail -f storage/logs/laravel.log | grep "Failed to send review notification"
```

---

## Test 6: Verify Email Template Rendering

### Test HTML Email

Send yourself a test email:

```php
// In tinker or test script
use App\Mail\TrackReviewNotification;
use Illuminate\Support\Facades\Mail;

$track = (object)[
    'id' => 1,
    'title' => 'Test Track',
    'artist' => 'Test Artist',
    'album' => 'Test Album',
    'label' => 'Test Label'
];

$review = (object)[
    'whatrate' => 5,
    'additionalcomments' => 'This is a test review with lots of great feedback!',
    'added' => now()
];

$djName = 'Test DJ';
$clientName = 'Test Client';
$unsubscribeToken = 'test-token-123';
$reportDownloadUrl = url('/track/report/download/test');

Mail::to('your-actual-email@example.com')->send(
    new TrackReviewNotification(
        $track,
        $review,
        $djName,
        $clientName,
        $unsubscribeToken,
        $reportDownloadUrl
    )
);

echo "Test email sent!";
```

### Check Email Rendering:

1. **HTML version:**
   - Styled properly
   - Stars display correctly (⭐⭐⭐⭐⭐)
   - All links clickable
   - Responsive on mobile

2. **Plain text version:**
   - Readable without HTML
   - All information present
   - Links display as URLs

---

## Test 7: Volume Test (Multiple Reviews)

Test system handles multiple reviews quickly:

```php
// Submit 10 reviews rapidly
for ($i = 0; $i < 10; $i++) {
    $memberAllDB->addReview([
        'whatRate' => rand(3, 5),
        'comments' => 'Test review #' . $i
    ], 1, 'USA', 'US');

    echo "Review {$i} submitted\n";
}
```

**Verify:**
- All emails sent successfully
- No errors in logs
- Database not overwhelmed

---

## Troubleshooting Guide

### Problem: No Email Received

**Check:**

1. **Is mail configured?**
```bash
grep MAIL_ .env
```

2. **Is trackReviewEmailsActivated = 1?**
```sql
SELECT trackReviewEmailsActivated FROM clients WHERE id = 1;
```

3. **Check error logs:**
```sql
SELECT * FROM email_notification_logs WHERE status = 'failed' ORDER BY id DESC LIMIT 5;
```

4. **Check Laravel logs:**
```bash
tail -100 storage/logs/laravel.log | grep -i "mail\|email"
```

5. **Test mail config:**
```bash
php artisan tinker
Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Problem: Email Goes to Spam

**Solutions:**
- Set up SPF records for your domain
- Set up DKIM signing
- Use reputable email service (SendGrid, Mailgun, etc.)
- Avoid spam trigger words in subject/body

### Problem: Email Not Styled

**Check:**
- Email client supports HTML emails
- Check plain text version renders correctly
- Verify blade templates exist

### Problem: Links Don't Work

**Check:**
- `APP_URL` set correctly in `.env`
- Routes are added properly
- Controllers exist

### Problem: PDF Won't Generate

**Check:**
- `barryvdh/laravel-dompdf` installed
- Storage directory writable
- Track data exists in database

---

## Success Criteria

✅ **All tests should pass:**

1. ✅ Review submitted → Email sent to track owner
2. ✅ Email contains all required information
3. ✅ Email renders properly (HTML and plain text)
4. ✅ Unsubscribe link works (one-click)
5. ✅ Resubscribe link works
6. ✅ PDF report downloads correctly
7. ✅ Report contains accurate data
8. ✅ Unsubscribed clients don't receive emails
9. ✅ Errors logged but don't break functionality
10. ✅ Email logs track all sent emails

---

## Production Deployment Checklist

Before going live:

- [ ] Configure production SMTP server
- [ ] Update `MAIL_FROM_ADDRESS` to real domain email
- [ ] Set up SPF/DKIM records
- [ ] Test with real email addresses
- [ ] Monitor email delivery rates
- [ ] Set up email bounce handling
- [ ] Configure rate limiting (if needed)
- [ ] Test unsubscribe compliance
- [ ] Review email templates for branding
- [ ] Set up email analytics (opens, clicks)

---

## Monitoring in Production

### Daily Checks

```sql
-- Check email success rate
SELECT
    status,
    COUNT(*) as count,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM email_notification_logs), 2) as percentage
FROM email_notification_logs
WHERE DATE(created_at) = CURDATE()
GROUP BY status;
```

### Weekly Reports

```sql
-- Email performance last 7 days
SELECT
    DATE(created_at) as date,
    COUNT(*) as total_sent,
    SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as successful,
    SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed
FROM email_notification_logs
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY DATE(created_at)
ORDER BY date DESC;
```

---

## Next Steps After Testing

1. ✅ All tests passing → Deploy to production
2. ❌ Tests failing → Review error logs and fix issues
3. ✅ Production deployed → Monitor email delivery
4. ✅ System stable → Add enhancements (weekly digests, etc.)

---

**Testing Complete?** Commit and push your changes!

```bash
git add -A
git commit -m "FEATURE: Email notification system fully functional"
git push
```

**Need help?** Check the audit report: `EMAIL_NOTIFICATION_AUDIT_REPORT.md`
