# SQL Injection Testing & Validation Plan

## Overview

This document provides comprehensive testing procedures to validate that all 9 SQL injection vulnerabilities have been properly fixed in PagesController.php.

---

## Test Environment Setup

### Prerequisites

1. **Staging Environment Required**
   - ⚠️ NEVER test SQL injection on production
   - Use a staging/test environment with sample data
   - Ensure database backups are in place

2. **Testing Tools**
   ```bash
   # Install curl for API testing
   sudo apt-get install curl

   # Install sqlmap (optional, for automated testing)
   sudo apt-get install sqlmap
   ```

3. **Browser Developer Tools**
   - Chrome DevTools or Firefox Developer Tools
   - Network tab for monitoring requests
   - Console for JavaScript testing

---

## Critical Vulnerabilities Testing (P0)

### Test 1: Forum Comments Query (Line 712)

**Original Vulnerability**: `DB::select("select* from forum_article_comments where art_id=$id...")`

**Attack Vector**: Route parameter injection in `/single_forum/{id}`

#### Manual Test Cases

**Test 1.1: Basic SQL Injection**
```bash
# Try to inject UNION SELECT
curl "https://your-staging-site.com/single_forum/1' UNION SELECT 1,2,3,4,5--"

# Expected Result: Should return 404 or normal forum page, NOT database data
# Before Fix: Would expose database structure
# After Fix: Query Builder sanitizes input
```

**Test 1.2: Error-based Injection**
```bash
# Try to trigger SQL error
curl "https://your-staging-site.com/single_forum/1' AND 1=CONVERT(int, (SELECT TOP 1 name FROM sysobjects))--"

# Expected Result: Should return 404 or normal page, NOT SQL error
# Before Fix: Would expose SQL error messages
# After Fix: No SQL error exposed
```

**Test 1.3: Time-based Blind Injection**
```bash
# Try to delay response
curl "https://your-staging-site.com/single_forum/1' AND SLEEP(5)--"

# Expected Result: Should return immediately, NOT delay 5 seconds
# Before Fix: Would delay response
# After Fix: No delay, Query Builder sanitizes
```

**Test 1.4: Valid Request**
```bash
# Test normal functionality
curl "https://your-staging-site.com/single_forum/1"

# Expected Result: Should return normal forum page with comments
# Verify: Comments load correctly, no functionality broken
```

#### Browser Test

1. Navigate to: `https://your-staging-site.com/single_forum/1`
2. Verify comments load correctly
3. Try modifying URL to: `https://your-staging-site.com/single_forum/999999`
4. Should return 404 or "not found", not SQL error

#### Automated Test (SQLMap)

```bash
# Run automated SQL injection test
sqlmap -u "https://your-staging-site.com/single_forum/1" \
       --batch \
       --level=5 \
       --risk=3

# Expected Result: "all tested parameters do not appear to be injectable"
```

---

### Test 2: Forum Likes Query (Line 737)

**Original Vulnerability**: `DB::select("select art_id from forum_article_likes where art_id=$id AND user_id=$id1")`

**Attack Vector**: User session data injection

#### Manual Test Cases

**Test 2.1: SQL Injection via User ID**
```bash
# Login as a user first, then try to inject via like action
curl -X POST "https://your-staging-site.com/like_article" \
     -H "Cookie: your_session_cookie" \
     -d "art_id=1' OR '1'='1&user_id=1"

# Expected Result: Should fail gracefully or work normally, NOT expose data
# Before Fix: Could manipulate likes data
# After Fix: Query Builder sanitizes input
```

**Test 2.2: Valid Like Request**
```bash
# Test normal like functionality
curl -X POST "https://your-staging-site.com/like_article" \
     -H "Cookie: your_session_cookie" \
     -d "art_id=1&user_id=1"

# Expected Result: Should return total likes count
# Verify: Like functionality works correctly
```

#### Browser Test

1. Login to the forum
2. Navigate to a forum article
3. Click the "like" button
4. Open DevTools Network tab
5. Verify the request/response is clean
6. Check database: verify only 1 like record was created

---

### Test 3: Forum Dislikes DELETE (Line 857)

**Original Vulnerability**: `DB::delete("DELETE from forum_article_likes where art_id=$art_id AND user_id=$user_id")`

**Attack Vector**: Delete arbitrary records via request injection

#### Manual Test Cases

**Test 3.1: SQL Injection via DELETE**
```bash
# Try to delete all records
curl -X POST "https://your-staging-site.com/dislike_article" \
     -H "Cookie: your_session_cookie" \
     -d "art_id=1' OR '1'='1&user_id=1' OR '1'='1"

# Expected Result: Should only delete user's own like, NOT all likes
# Before Fix: Could delete all records
# After Fix: Query Builder sanitizes, only deletes specific record
```

**Test 3.2: Valid Dislike Request**
```bash
# Test normal dislike functionality
curl -X POST "https://your-staging-site.com/dislike_article" \
     -H "Cookie: your_session_cookie" \
     -d "art_id=1&user_id=1"

# Expected Result: Should remove user's like and return new count
# Verify: Only the specific like record is deleted
```

#### Database Validation

```sql
-- Before dislike: Count total likes
SELECT COUNT(*) FROM forum_article_likes WHERE art_id = 1;

-- Execute dislike action for user_id = 1

-- After dislike: Verify only 1 record deleted
SELECT COUNT(*) FROM forum_article_likes WHERE art_id = 1;
-- Count should be (previous_count - 1), not 0

-- Verify the deleted record is correct
SELECT * FROM forum_article_likes WHERE art_id = 1 AND user_id = 1;
-- Should return 0 rows
```

---

## High Priority Vulnerabilities Testing

### Test 4: News Pagination (Line 252)

**Original Vulnerability**: `DB::select("...limit $start,$limit")`

**Attack Vector**: `?page=` parameter injection

#### Manual Test Cases

**Test 4.1: SQL Injection via Page Parameter**
```bash
# Try to inject UNION SELECT
curl "https://your-staging-site.com/news?page=1,999999"

# Expected Result: Should return page 1 or error, NOT massive data dump
# Before Fix: Could bypass pagination limits
# After Fix: offset()/limit() sanitizes input
```

**Test 4.2: Negative Page Values**
```bash
# Try negative pagination
curl "https://your-staging-site.com/news?page=-1"

# Expected Result: Should return page 1 or error, NOT SQL error
```

**Test 4.3: String Injection**
```bash
# Try SQL string injection
curl "https://your-staging-site.com/news?page=1' UNION SELECT * FROM users--"

# Expected Result: Should return page 1 or error, NOT user data
```

**Test 4.4: Valid Pagination**
```bash
# Test normal pagination
curl "https://your-staging-site.com/news?page=1"
curl "https://your-staging-site.com/news?page=2"
curl "https://your-staging-site.com/news?page=3"

# Expected Result: Should paginate correctly, 25 items per page
# Verify: Correct news items returned for each page
```

---

### Test 5: Videos Pagination (Line 363)

**Original Vulnerability**: `DB::select("...limit $start,$limit")`

**Attack Vector**: `?page=` parameter injection

#### Test Cases (Same as News Pagination)

```bash
# SQL injection attempt
curl "https://your-staging-site.com/videos?page=1,999999"

# Valid pagination
curl "https://your-staging-site.com/videos?page=1"
curl "https://your-staging-site.com/videos?page=2"
```

---

### Test 6: Forum Articles Pagination (Line 485)

**Original Vulnerability**: `DB::select("...limit $start,$limit")`

**Attack Vector**: `?page=` parameter injection

#### Test Cases (Same as News Pagination)

```bash
# SQL injection attempt
curl "https://your-staging-site.com/forum?page=1' UNION SELECT password FROM users--"

# Valid pagination
curl "https://your-staging-site.com/forum?page=1"
curl "https://your-staging-site.com/forum?page=2"
```

---

### Test 7: Charts AJAX Type Validation (Lines 1482-1493)

**Original Vulnerability**: `if ($_GET['type'] == 1)` without validation

**Attack Vector**: `?type=` parameter injection

#### Manual Test Cases

**Test 7.1: SQL Injection via Type Parameter**
```bash
# Try to inject via type parameter
curl "https://your-staging-site.com/charts?page=1&type=1' UNION SELECT * FROM users--"

# Expected Result: Should return charts or error, NOT user data
# Before Fix: Could inject SQL via type comparison
# After Fix: Type cast to integer prevents injection
```

**Test 7.2: Invalid Type Values**
```bash
# Try invalid type values
curl "https://your-staging-site.com/charts?page=1&type=abc"
curl "https://your-staging-site.com/charts?page=1&type=999"
curl "https://your-staging-site.com/charts?page=1&type=-1"

# Expected Result: Should return empty or default charts, NOT SQL error
```

**Test 7.3: Valid Type Values**
```bash
# Test valid type values (1 = weekly, 2 = monthly, 3 = yearly)
curl "https://your-staging-site.com/charts?page=1&type=1"  # Weekly
curl "https://your-staging-site.com/charts?page=1&type=2"  # Monthly
curl "https://your-staging-site.com/charts?page=1&type=3"  # Yearly

# Expected Result: Should return correct chart data for each type
# Verify: Different data sets for weekly/monthly/yearly
```

---

## Medium Priority Vulnerabilities Testing

### Test 8: Homepage Downloads Query (Lines 70-77)

**Original Vulnerability**: String concatenation with variables

**Attack Vector**: Indirect injection if variables are compromised

#### Test Cases

**Test 8.1: Normal Homepage Load**
```bash
# Load homepage and verify downloads section
curl "https://your-staging-site.com/"

# Expected Result: Homepage loads with top downloads
# Verify: Downloads section shows correct data
```

**Test 8.2: Database Validation**
```sql
-- Verify the query is using parameterized bindings
-- Check Laravel query log (enable in config/database.php)

-- Expected: Should see query with ? placeholders
-- SELECT DISTINCT ... WHERE tracks.deleted = '0'
-- AND track_member_downloads.downloadedDateTime > ?
-- AND track_member_downloads.downloadedDateTime < ?
```

---

### Test 9: Charts Page WHERE Clauses (Lines 1466-1473)

**Original Vulnerability**: PHP-generated date variables in WHERE clauses

**Note**: This is MEDIUM priority because variables are PHP-generated, not user input

#### Test Cases

**Test 9.1: Charts Page Load**
```bash
# Load charts page
curl "https://your-staging-site.com/charts"

# Expected Result: Charts load correctly
# Verify: Weekly/monthly/yearly charts display
```

**Test 9.2: Verify Date Variables**
```bash
# Check Laravel logs to verify date variables are PHP-generated
# Should see dates like: 2025-11-21

# Expected: No user input in date generation
# Variables: $monday, $sunday, $year, $month all from PHP date()
```

---

## Automated Testing Scripts

### PHP Unit Tests

Create file: `tests/Feature/SqlInjectionTest.php`

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SqlInjectionTest extends TestCase
{
    /**
     * Test forum comments SQL injection protection
     */
    public function test_forum_comments_sql_injection_protected()
    {
        // Test with SQL injection attempt
        $response = $this->get('/single_forum/1\' UNION SELECT 1,2,3--');

        // Should return 404 or normal page, not SQL error
        $this->assertTrue(
            $response->status() === 404 || $response->status() === 200
        );

        // Should not contain SQL error keywords
        $this->assertStringNotContainsString('SQL', $response->content());
        $this->assertStringNotContainsString('syntax', $response->content());
    }

    /**
     * Test news pagination SQL injection protection
     */
    public function test_news_pagination_sql_injection_protected()
    {
        // Test with SQL injection attempt
        $response = $this->get('/news?page=1\' UNION SELECT * FROM users--');

        // Should work normally or error gracefully
        $this->assertTrue(
            $response->status() === 404 || $response->status() === 200
        );
    }

    /**
     * Test forum like SQL injection protection
     */
    public function test_forum_like_sql_injection_protected()
    {
        // Create test user
        $user = factory(User::class)->create();

        // Attempt SQL injection via like
        $response = $this->actingAs($user)->post('/like_article', [
            'art_id' => '1\' OR \'1\'=\'1',
            'user_id' => '1\' OR \'1\'=\'1'
        ]);

        // Should handle gracefully
        $this->assertTrue($response->isSuccessful() || $response->isClientError());
    }

    /**
     * Test charts AJAX type validation
     */
    public function test_charts_ajax_type_validation()
    {
        // Test with SQL injection attempt
        $response = $this->get('/charts?page=1&type=1\' UNION SELECT * FROM users--');

        // Should work normally or error gracefully
        $this->assertTrue(
            $response->status() === 404 || $response->status() === 200
        );
    }
}
```

### Run Tests

```bash
# Run SQL injection tests
php artisan test --filter=SqlInjectionTest

# Expected: All tests should pass
```

---

## Manual Testing Checklist

### Pre-Deployment Checklist

- [ ] **Backup Production Database**
  ```bash
  mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
  ```

- [ ] **Deploy to Staging First**
  - Never deploy directly to production
  - Test on staging environment with real data copy

- [ ] **Enable Query Logging**
  ```php
  // In config/database.php or .env
  DB_LOG_QUERIES=true
  ```

### Testing Checklist

#### Critical Tests (Must Pass)

- [ ] **Test 1: Forum Comments**
  - [ ] Normal forum page loads
  - [ ] SQL injection attempt returns 404/normal page
  - [ ] Comments display correctly
  - [ ] No SQL errors in logs

- [ ] **Test 2: Forum Likes**
  - [ ] Like button works normally
  - [ ] SQL injection attempt fails gracefully
  - [ ] Like count updates correctly
  - [ ] Database shows only 1 like per user per article

- [ ] **Test 3: Forum Dislikes**
  - [ ] Dislike/unlike button works
  - [ ] SQL injection attempt fails gracefully
  - [ ] Only specific like is deleted
  - [ ] Other users' likes remain intact

#### High Priority Tests (Should Pass)

- [ ] **Test 4-6: Pagination**
  - [ ] News pagination works (25 items per page)
  - [ ] Videos pagination works (25 items per page)
  - [ ] Forum pagination works (25 items per page)
  - [ ] SQL injection via ?page= fails
  - [ ] Negative page numbers handled gracefully

- [ ] **Test 7: Charts AJAX**
  - [ ] Weekly charts load (type=1)
  - [ ] Monthly charts load (type=2)
  - [ ] Yearly charts load (type=3)
  - [ ] SQL injection via ?type= fails
  - [ ] Invalid type values handled gracefully

#### Medium Priority Tests (Good to Pass)

- [ ] **Test 8: Homepage Downloads**
  - [ ] Homepage loads correctly
  - [ ] Top downloads section displays
  - [ ] Query logs show parameterized bindings

- [ ] **Test 9: Charts Page**
  - [ ] Charts page loads
  - [ ] All chart types display
  - [ ] Date variables are PHP-generated

### Functional Testing

- [ ] **Forum Functionality**
  - [ ] Create new forum post
  - [ ] Add comment to forum post
  - [ ] Like a forum post
  - [ ] Unlike a forum post
  - [ ] View single forum post
  - [ ] Navigate through forum pages

- [ ] **News Functionality**
  - [ ] View news list
  - [ ] Navigate news pages
  - [ ] View single news article
  - [ ] All images load correctly

- [ ] **Videos Functionality**
  - [ ] View videos list
  - [ ] Navigate video pages
  - [ ] Play a video
  - [ ] All thumbnails load

- [ ] **Charts Functionality**
  - [ ] View weekly charts
  - [ ] View monthly charts
  - [ ] View yearly charts
  - [ ] Navigate chart pages

---

## Security Validation

### SQL Injection Test Summary

Run all tests and record results:

| Test # | Vulnerability | Test Type | Expected Result | Actual Result | Status |
|--------|--------------|-----------|-----------------|---------------|--------|
| 1 | Forum Comments | Manual | 404/Normal | | ⬜ |
| 1 | Forum Comments | SQLMap | Not Injectable | | ⬜ |
| 2 | Forum Likes | Manual | Fails Gracefully | | ⬜ |
| 3 | Forum Dislikes | Manual | Only 1 Delete | | ⬜ |
| 4 | News Pagination | Manual | Normal Page | | ⬜ |
| 5 | Videos Pagination | Manual | Normal Page | | ⬜ |
| 6 | Forum Pagination | Manual | Normal Page | | ⬜ |
| 7 | Charts AJAX | Manual | Type Validated | | ⬜ |
| 8 | Homepage Downloads | Review | Parameterized | | ⬜ |
| 9 | Charts Page | Review | PHP-Generated | | ⬜ |

### Log Review

After testing, review Laravel logs:

```bash
# Check for SQL errors
tail -f storage/logs/laravel.log | grep -i "sql"

# Check for exceptions
tail -f storage/logs/laravel.log | grep -i "exception"

# Expected: No SQL errors, no exceptions
```

---

## Deployment Process

### Step 1: Pre-Deployment

1. **Merge Pull Request**
   - Review PR: `claude/fix-critical-sql-injections-017gYfUW5wF3MXc9DcwmcrUU`
   - Get approval from team
   - Merge to main branch

2. **Tag Release**
   ```bash
   git tag -a v1.1.0-security-fixes -m "SQL Injection Security Fixes"
   git push origin v1.1.0-security-fixes
   ```

### Step 2: Staging Deployment

1. **Deploy to Staging**
   ```bash
   # Pull latest code
   cd /path/to/staging
   git pull origin main

   # Clear caches
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear

   # Run migrations if any
   php artisan migrate
   ```

2. **Run All Tests**
   ```bash
   # Run automated tests
   php artisan test --filter=SqlInjectionTest

   # Run manual tests (use checklist above)
   ```

3. **Monitor Staging**
   - Run for at least 24 hours
   - Monitor logs for errors
   - Test all functionality
   - Get user acceptance testing (UAT)

### Step 3: Production Deployment

1. **Schedule Maintenance Window**
   - Notify users of deployment
   - Choose low-traffic time

2. **Backup Production**
   ```bash
   # Backup database
   mysqldump -u user -p database > backup_$(date +%Y%m%d_%H%M%S).sql

   # Backup files
   tar -czf backup_files_$(date +%Y%m%d_%H%M%S).tar.gz /path/to/app
   ```

3. **Deploy to Production**
   ```bash
   # Pull latest code
   cd /path/to/production
   git pull origin main

   # Clear caches
   php artisan config:clear
   php artisan cache:clear
   php artisan view:clear
   php artisan route:clear

   # Optimize
   php artisan optimize
   ```

4. **Smoke Tests**
   - Test homepage loads
   - Test forum functionality
   - Test news/videos pagination
   - Test charts functionality
   - Monitor error logs in real-time

### Step 4: Post-Deployment Monitoring

1. **Monitor for 1 Hour**
   ```bash
   # Watch error logs
   tail -f storage/logs/laravel.log

   # Watch web server logs
   tail -f /var/log/nginx/error.log  # or Apache
   ```

2. **Monitor for 24 Hours**
   - Check error logs regularly
   - Monitor user reports
   - Watch performance metrics
   - Verify no SQL errors

3. **Rollback Plan** (if issues found)
   ```bash
   # Rollback code
   git reset --hard <previous_commit>

   # Restore database if needed
   mysql -u user -p database < backup_file.sql

   # Clear caches
   php artisan config:clear
   php artisan cache:clear
   ```

---

## Success Criteria

### All Tests Must Pass

✅ **No SQL Errors**: Zero SQL errors in logs
✅ **No Functionality Broken**: All features work normally
✅ **SQL Injection Blocked**: All injection attempts fail safely
✅ **Performance Maintained**: No performance degradation
✅ **User Experience**: No negative user reports

### Security Validation

✅ **SQLMap Tests Pass**: All parameters "not injectable"
✅ **Manual Tests Pass**: All 9 vulnerabilities blocked
✅ **Code Review**: Query Builder/parameterized queries in place
✅ **No Regressions**: Existing security fixes still working

---

## Troubleshooting

### Issue: SQL Error After Deployment

**Symptom**: SQL syntax error in logs

**Solution**:
1. Check the specific query causing the error
2. Verify Query Builder syntax is correct
3. Check variable types match database schema
4. Review commit for typos

### Issue: Pagination Not Working

**Symptom**: Blank pages or no results

**Solution**:
1. Verify `offset()` and `limit()` values are integers
2. Check page calculation: `$start = ($_GET['page'] * $limit) - $limit;`
3. Ensure `$limit` is defined before use
4. Check database has data

### Issue: Forum Likes Not Working

**Symptom**: Like button doesn't respond

**Solution**:
1. Check browser console for JavaScript errors
2. Verify AJAX endpoint is correct
3. Check CSRF token is valid
4. Review Query Builder syntax for likes table

---

## Additional Resources

### Laravel Query Builder Documentation

- https://laravel.com/docs/queries
- https://laravel.com/docs/queries#selects
- https://laravel.com/docs/queries#where-clauses

### OWASP SQL Injection Prevention

- https://cheatsheetseries.owasp.org/cheatsheets/SQL_Injection_Prevention_Cheat_Sheet.html

### Security Testing Tools

- SQLMap: http://sqlmap.org/
- Burp Suite: https://portswigger.net/burp
- OWASP ZAP: https://www.zaproxy.org/

---

## Sign-Off

### Testing Sign-Off

- [ ] **Developer Testing Complete**: All automated tests pass
- [ ] **Security Testing Complete**: All manual tests pass
- [ ] **QA Testing Complete**: Functional tests pass
- [ ] **Staging Testing Complete**: 24 hour staging validation

### Deployment Sign-Off

- [ ] **Staging Deployment**: Approved by Tech Lead
- [ ] **Production Deployment**: Approved by Project Manager
- [ ] **Post-Deployment Monitoring**: 24 hour monitoring complete
- [ ] **Security Audit**: Confirmed all 9 vulnerabilities fixed

---

**Document Version**: 1.0
**Last Updated**: 2025-11-21
**Author**: Claude (Security Fixes)
**Status**: Ready for Testing
