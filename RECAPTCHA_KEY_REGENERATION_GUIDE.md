# 🔐 RECAPTCHA KEY REGENERATION GUIDE
## URGENT: Exposed Secret Key Must Be Regenerated

**Status:** ⚠️ **CRITICAL - IMMEDIATE ACTION REQUIRED**
**Exposed Key:** `6Lcz58IkAAAAAMwf7LkqCEfemauHtcMkK-c0Mj8z`
**Location:** Previously hardcoded in `Http/Controllers/PagesController.php:945`
**Risk Level:** **HIGH - Key is publicly exposed in git history**

---

## ⚠️ WHY THIS IS URGENT

Your reCAPTCHA secret key was hardcoded in source code and committed to git. This means:

1. **Anyone with repository access** can see the secret key
2. **Git history preserves the key** even after removal
3. **Attackers can bypass CAPTCHA** protection using the exposed key
4. **Automated bots** can submit forms without verification
5. **Spam and abuse** are now possible on protected forms

**Impact:**
- Contact form spam
- Registration bot attacks
- Automated form submissions
- Resource exhaustion

---

## 🚀 REGENERATION STEPS (15 minutes)

### Step 1: Access Google reCAPTCHA Admin Console

1. Go to: **https://www.google.com/recaptcha/admin**
2. Sign in with your Google account
3. You should see your existing reCAPTCHA sites

### Step 2: Locate and Delete Exposed Key

1. Find the site that uses key `6Lcz58IkAAAAAMwf7LkqCEfemauHtcMkK-c0Mj8z`
2. Click the ⚙️ **Settings** icon for that site
3. Scroll to the bottom
4. Click **Delete this key**
5. Confirm deletion

**⚠️ CRITICAL:** Deleting the key immediately invalidates it everywhere.

### Step 3: Create New reCAPTCHA v2 Site

1. Click the ➕ **Add** button (top right)
2. Fill in the form:

**Label:**
```
Digiwaxx Production
```

**reCAPTCHA type:**
- ☑️ **reCAPTCHA v2**
- Select: **"I'm not a robot" Checkbox**

**Domains:**
```
your-production-domain.com
www.your-production-domain.com
localhost (for local testing)
127.0.0.1 (for local testing)
```

*Note: Add each domain on a separate line*

**Owners:**
- Leave as is (your Google account)
- Or add team members' Google accounts

**Accept reCAPTCHA Terms of Service:**
- ☑️ Check the box

3. Click **Submit**

### Step 4: Copy Your New Keys

You'll see two keys:

**Site Key (Public):**
```
6Lcxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```
**Copy this** - it goes in your frontend HTML

**Secret Key (Private):**
```
6Lczzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz
```
**Copy this** - it goes in your `.env` file

---

## 📝 UPDATE YOUR APPLICATION

### 1. Update .env File

Open your `.env` file and add/update:

```bash
# reCAPTCHA Configuration
RECAPTCHA_SECRET_KEY=6Lczzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz  # Your NEW secret key
RECAPTCHA_SITE_KEY=6Lcxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx      # Your NEW site key
```

**⚠️ IMPORTANT:**
- Use the **real keys** from Google, not the examples above
- The secret key starts with `6Lc` and is 40 characters
- **NEVER commit .env to git!**
- Verify `.env` is in `.gitignore`

### 2. Update Frontend Templates

Find all files that include the reCAPTCHA widget:

**Search for:**
```bash
grep -r "6Lcz58IkAAAAAMwf7LkqCEfemauHtcMkK" resources/views/
```

**Update locations (likely in):**
- `resources/views/pages/ContactUs.blade.php`
- `resources/views/pages/SponsorAdvertise.blade.php`
- `resources/views/members/registration.blade.php`
- `resources/views/clients/registration.blade.php`

**Replace the old site key with your new one:**

```html
<!-- BEFORE (Old exposed key): -->
<div class="g-recaptcha" data-sitekey="6Lcz58IkAAAAAMwf7LkqCEfemauHtcMkK"></div>

<!-- AFTER (New secure key): -->
<div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
```

**Or if using the script tag:**

```html
<!-- BEFORE: -->
<script src="https://www.google.com/recaptcha/api.js?render=6Lcz58IkAAAAAMwf7LkqCEfemauHtcMkK"></script>

<!-- AFTER: -->
<script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
```

### 3. Verify Controller Code

The controller has already been updated to use environment variables:

**File:** `Http/Controllers/PagesController.php:943-968`

```php
public function reCaptcha($recaptcha)
{
    // SECURITY FIX: Moved reCAPTCHA secret to environment variable
    $secret = env('RECAPTCHA_SECRET_KEY');

    if (empty($secret)) {
        \Log::error('reCAPTCHA secret key not configured in environment');
        return ['success' => false, 'error-codes' => ['missing-secret-key']];
    }

    // ... rest of validation code
}
```

✅ **No changes needed here** - it's already secure!

---

## 🧪 TESTING

### 1. Clear Application Cache

```bash
php artisan config:clear
php artisan cache:clear
```

### 2. Test Contact Form

1. Open your contact form in a browser
2. Fill out the form
3. **Complete the reCAPTCHA** (check "I'm not a robot")
4. Submit the form
5. Verify submission succeeds

### 3. Test Without CAPTCHA

1. Fill out the form
2. **Do NOT complete the reCAPTCHA**
3. Submit the form
4. Verify submission fails with CAPTCHA error

### 4. Check Logs

```bash
tail -f storage/logs/laravel.log
```

Look for:
- ✅ Successful reCAPTCHA validation
- ❌ Failed validation when CAPTCHA not completed
- ❌ No errors about missing secret key

### 5. Monitor Google Admin Console

1. Go back to **https://www.google.com/recaptcha/admin**
2. Click on your new site
3. View the **Analytics** dashboard
4. Verify you see:
   - Requests coming through
   - Success/failure rates
   - No suspicious activity

---

## 🔒 SECURITY BEST PRACTICES

### 1. Protect Your Secret Key

**DO:**
- ✅ Store in environment variables (.env)
- ✅ Use different keys for dev/staging/production
- ✅ Rotate keys if compromised
- ✅ Limit access to .env file
- ✅ Use server-side validation only

**DON'T:**
- ❌ Hardcode in source code
- ❌ Commit to version control
- ❌ Share via email/chat
- ❌ Use same key across multiple sites
- ❌ Expose in client-side JavaScript

### 2. Monitor for Abuse

Set up alerts in Google reCAPTCHA Admin:

1. Go to your site settings
2. Enable **"Email alerts"**
3. Set threshold for suspicious activity
4. Add team email addresses

### 3. Additional Security Measures

Consider implementing:

- **Rate limiting** on form submissions
- **IP-based throttling** for repeated failures
- **Honeypot fields** (hidden fields bots fill out)
- **Email verification** for registrations
- **CAPTCHA on multiple attempts** only

---

## 📋 VERIFICATION CHECKLIST

Before considering this complete:

- [ ] Old key deleted from Google Console
- [ ] New reCAPTCHA v2 site created
- [ ] New secret key added to `.env`
- [ ] New site key added to `.env`
- [ ] `.env` is in `.gitignore`
- [ ] All frontend templates updated
- [ ] Application cache cleared
- [ ] Contact form tested successfully
- [ ] CAPTCHA validation working
- [ ] No errors in logs
- [ ] Google Analytics showing requests
- [ ] Different keys for dev/staging/production
- [ ] Team members have access to Google Console

---

## 🆘 TROUBLESHOOTING

### Issue: "ERROR for site owner: Invalid site key"

**Cause:** Site key doesn't match the domain

**Fix:**
1. Check domain in Google Console matches your actual domain
2. Add `localhost` and `127.0.0.1` for local testing
3. Verify site key is correct in template

### Issue: "ERROR for site owner: Invalid domain for site key"

**Cause:** Domain not registered in Google Console

**Fix:**
1. Go to Google Console
2. Add the missing domain to your site settings
3. Wait 5 minutes for changes to propagate

### Issue: reCAPTCHA not loading

**Cause:** Script not included or wrong version

**Fix:**
```html
<!-- Add before </head> -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Add where CAPTCHA should appear -->
<div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
```

### Issue: Validation always fails

**Cause:** Secret key not configured or incorrect

**Fix:**
1. Verify `.env` has `RECAPTCHA_SECRET_KEY=...`
2. Run `php artisan config:clear`
3. Check secret key is correct (40 characters, starts with `6Lc`)
4. Verify controller is reading from environment

### Issue: "Missing 'q' parameter" or similar

**Cause:** Wrong key being used

**Fix:**
- Double-check you're using the **secret key** in `.env`
- Double-check you're using the **site key** in templates
- Don't mix them up!

---

## 📞 SUPPORT

If you need help:

**Google reCAPTCHA Support:**
- Documentation: https://developers.google.com/recaptcha/docs/display
- FAQ: https://developers.google.com/recaptcha/docs/faq
- Community: https://groups.google.com/g/recaptcha

**Laravel Integration:**
- Package: https://github.com/google/recaptcha (official PHP library)
- Docs: https://laravel.com/docs/validation#rule-recaptcha

---

## 📊 POST-REGENERATION MONITORING

### Week 1: Monitor Daily

- Check Google Analytics for request volume
- Monitor failed validation rate
- Watch for spam submissions
- Review server logs for errors

### Week 2-4: Monitor Weekly

- Verify no bots bypassing CAPTCHA
- Check for unusual patterns
- Review success/failure rates
- Adjust if needed

### Monthly: Review and Optimize

- Analyze CAPTCHA solve rates
- Consider v3 (invisible) if solve rate is low
- Review and update domains
- Rotate keys if suspicious activity

---

## ✅ COMPLETION

Once you've completed all steps:

1. **Update this file** with completion date
2. **Document the new keys** securely (password manager)
3. **Train team members** on new process
4. **Set calendar reminder** to review quarterly

**Completed on:** _________________
**Completed by:** _________________
**New keys secured in:** _________________

---

**Remember:** Security is an ongoing process. Regular key rotation and monitoring are essential for maintaining protection against evolving threats.

For questions or issues, refer to the documentation links above or contact your development team.
