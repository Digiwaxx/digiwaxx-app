# Tier 2 Remaining Fixes - Review Validation

## Status: Code Ready, Needs Manual Application

Due to whitespace formatting issues in the codebase, the following fixes need to be manually applied to `Models/MemberAllDB.php`

---

## Fix Location: Models/MemberAllDB.php - addReview() function (Line 1282)

### Current Code (Line 1311-1318):
```php
$member_session_id = Session::get('memberId');

$insertData = array(
    'version' => 2,
    'track' => $tid,
    'member' => $member_session_id,
    'whatrate' => $data['whatRate'],
    'additionalcomments' => urlencode($data['comments']),
    'added' => NOW(),
    'countryName' => $countryName,
    'countryCode' => $countryCode,
);
```

### Required Fix:
```php
$member_session_id = Session::get('memberId');

// SECURITY FIX: Prevent duplicate reviews
$existingReview = DB::table('tracks_reviews')
    ->where('track', $tid)
    ->where('member', $member_session_id)
    ->first();

if ($existingReview) {
    return -1; // Already reviewed
}

// SECURITY FIX: Prevent self-reviews (DJs rating their own tracks)
$track = DB::table('tracks')->where('id', $tid)->first();
if (!$track) {
    return -2; // Track not found
}

// Check if this member is the track owner (client)
$member = DB::table('members')->where('id', $member_session_id)->first();
if ($member && $member->client_id && $member->client_id == $track->client) {
    return -3; // Cannot review your own track
}

// SECURITY FIX: Validate input
if (!isset($data['whatRate']) || !is_numeric($data['whatRate'])) {
    return -4; // Invalid rating
}

$rating = (int)$data['whatRate'];
if ($rating < 1 || $rating > 5) {
    return -5; // Rating must be 1-5
}

if (!isset($data['comments'])) {
    $data['comments'] = '';
}

$comments = trim($data['comments']);
if (strlen($comments) > 5000) {
    return -6; // Comment too long
}

$insertData = array(
    'version' => 2,
    'track' => $tid,
    'member' => $member_session_id,
    'whatrate' => $rating, // Validated rating
    'additionalcomments' => htmlspecialchars($comments, ENT_QUOTES, 'UTF-8'), // XSS protection
    'added' => NOW(),
    'countryName' => $countryName,
    'countryCode' => $countryCode,
);
```

---

## What This Fixes

### 1. **Duplicate Review Prevention** ✅
**Before:** Users could submit unlimited reviews for the same track
**After:** One review per member per track

**Return Code:** -1 if duplicate detected

---

### 2. **Self-Review Prevention** ✅
**Before:** DJs could rate their own tracks
**After:** Track owner cannot review their own music

**How it works:**
- Checks if member's `client_id` matches track's `client`
- Prevents rating manipulation

**Return Code:** -3 if self-review attempt

---

### 3. **Input Validation** ✅

#### Rating Validation:
- **Before:** Could submit rating of 9999 or "abc"
- **After:** Rating must be numeric, 1-5 only

**Return Codes:**
- -4: Invalid rating format
- -5: Rating out of range (not 1-5)

#### Comment Validation:
- **Before:** No length limits, no sanitization
- **After:**
  - Max 5000 characters
  - HTML entities escaped (XSS protection)
  - Uses `htmlspecialchars()` instead of `urlencode()`

**Return Code:** -6 if comment too long

---

### 4. **XSS Protection** ✅
**Before:**
```php
'additionalcomments' => urlencode($data['comments'])
```

**After:**
```php
'additionalcomments' => htmlspecialchars($comments, ENT_QUOTES, 'UTF-8')
```

**Impact:** Prevents JavaScript injection in review comments

---

## Controller Updates Required

### Update error handling in controllers:

**File:** `Http/Controllers/Members/MemberDashboardController.php` (Lines 6867, 12819)

**Add after addReview() call:**
```php
$result = $this->memberAllDB_model->addReview($_POST, $trackId, $countryName, $countryCode);

if ($result == -1) {
    return redirect()->back()->with('error', 'You have already reviewed this track');
}
if ($result == -2) {
    return redirect()->back()->with('error', 'Track not found');
}
if ($result == -3) {
    return redirect()->back()->with('error', 'You cannot review your own track');
}
if ($result == -4 || $result == -5) {
    return redirect()->back()->with('error', 'Invalid rating value');
}
if ($result == -6) {
    return redirect()->back()->with('error', 'Comment is too long (max 5000 characters)');
}

if ($result > 0) {
    // Success - continue with existing code
}
```

---

## Testing

### Test Duplicate Prevention:
1. Submit a review for a track
2. Try to submit another review for the same track
3. Should see: "You have already reviewed this track"

### Test Self-Review Prevention:
1. Login as a client/DJ
2. Try to review your own track
3. Should see: "You cannot review your own track"

### Test Rating Validation:
1. Submit review with rating < 1 or > 5
2. Should see: "Invalid rating value"

### Test Comment Length:
1. Submit review with 5001+ character comment
2. Should see: "Comment is too long"

### Test XSS Protection:
1. Submit review with: `<script>alert('XSS')</script>`
2. Comment should display as text, not execute

---

## Database Migration (Optional but Recommended)

Add unique constraint to prevent duplicates at database level:

```sql
ALTER TABLE tracks_reviews
ADD UNIQUE KEY unique_member_track (member, track);
```

---

## Return Code Reference

| Code | Meaning | User Message |
|------|---------|--------------|
| > 0 | Success | Review submitted successfully |
| -1 | Duplicate review | You have already reviewed this track |
| -2 | Track not found | Track not found |
| -3 | Self-review attempt | You cannot review your own track |
| -4 | Invalid rating format | Invalid rating value |
| -5 | Rating out of range | Invalid rating value (must be 1-5) |
| -6 | Comment too long | Comment is too long (max 5000 characters) |

---

## Impact

- **Prevents review manipulation** (self-reviews, duplicates)
- **Prevents spam** (duplicate submissions)
- **Prevents XSS attacks** (sanitized comments)
- **Improves data quality** (validated ratings)

---

**Priority:** HIGH
**Estimated Time:** 15 minutes manual application
**Dependencies:** None
**Testing Required:** Yes

---

*Created: 2025-11-20*
*Reason: Whitespace formatting issues in MemberAllDB.php preventing automated fixes*
