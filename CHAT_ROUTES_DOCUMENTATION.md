# Chat Routes Documentation

## 📋 Overview

This document defines all API routes needed for the enhanced chat system. These routes should be added to your Laravel `routes/web.php` file.

---

## 🛣️ Routes Structure

### Member (DJ) Routes
Prefix: `/member/chat`

### Client Routes
Prefix: `/client/chat`

### Shared/API Routes
Prefix: `/api/chat`

---

## 📝 Complete Route Definitions

Add these to `/var/www/digiwaxx-app/routes/web.php`:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Members\MemberDashboardController;
use App\Http\Controllers\Clients\ClientDashboardController;

/*
|--------------------------------------------------------------------------
| Enhanced Chat Routes - Member/DJ Area
|--------------------------------------------------------------------------
*/

Route::prefix('member/chat')->middleware(['auth.member'])->group(function () {

    // Existing routes (keep these)
    Route::get('/messages', [MemberDashboardController::class, 'viewMemberMessages'])
        ->name('member.messages');

    Route::get('/messages/unread', [MemberDashboardController::class, 'viewMemberMessagesUnread'])
        ->name('member.messages.unread');

    Route::get('/messages/starred', [MemberDashboardController::class, 'viewMemberMessagesStarred'])
        ->name('member.messages.starred');

    Route::get('/messages/archived', [MemberDashboardController::class, 'viewMemberMessagesArchived'])
        ->name('member.messages.archived');

    Route::get('/conversation/{clientId}', [MemberDashboardController::class, 'Member_send_message'])
        ->name('member.conversation');

    Route::post('/send', [MemberDashboardController::class, 'Member_send_message'])
        ->name('member.message.send');

    // NEW ENHANCED ROUTES

    // Send message with attachment
    Route::post('/send-with-attachment', [MemberDashboardController::class, 'sendMessageWithAttachment'])
        ->name('member.message.send.attachment');

    // Mark message(s) as read
    Route::post('/mark-read/{messageId}', [MemberDashboardController::class, 'markMessageRead'])
        ->name('member.message.mark.read');

    Route::post('/mark-conversation-read/{clientId}', [MemberDashboardController::class, 'markConversationRead'])
        ->name('member.conversation.mark.read');

    // Typing indicator
    Route::post('/typing/{clientId}', [MemberDashboardController::class, 'updateTyping'])
        ->name('member.typing.update');

    // Search messages
    Route::get('/search', [MemberDashboardController::class, 'searchMessages'])
        ->name('member.messages.search');

    // Get unread count (for badge)
    Route::get('/unread-count', [MemberDashboardController::class, 'getUnreadCount'])
        ->name('member.messages.unread.count');

    // Online status
    Route::post('/status/online', [MemberDashboardController::class, 'setOnlineStatus'])
        ->name('member.status.online');

    Route::post('/status/offline', [MemberDashboardController::class, 'setOfflineStatus'])
        ->name('member.status.offline');

    Route::post('/heartbeat', [MemberDashboardController::class, 'heartbeat'])
        ->name('member.heartbeat');

    // Get user online status
    Route::get('/status/{clientId}', [MemberDashboardController::class, 'getClientOnlineStatus'])
        ->name('member.client.status');

    // File download
    Route::get('/download/{messageId}', [MemberDashboardController::class, 'downloadAttachment'])
        ->name('member.message.download');
});

/*
|--------------------------------------------------------------------------
| Enhanced Chat Routes - Client Area
|--------------------------------------------------------------------------
*/

Route::prefix('client/chat')->middleware(['auth.client'])->group(function () {

    // Existing routes (keep these)
    Route::get('/messages', [ClientDashboardController::class, 'Client_messages'])
        ->name('client.messages');

    Route::get('/messages/unread', [ClientDashboardController::class, 'Client_messages_unread'])
        ->name('client.messages.unread');

    Route::get('/messages/starred', [ClientDashboardController::class, 'Client_messages_starred'])
        ->name('client.messages.starred');

    Route::get('/messages/archived', [ClientDashboardController::class, 'Client_messages_archived'])
        ->name('client.messages.archived');

    Route::get('/members', [ClientDashboardController::class, 'Client_messages_members'])
        ->name('client.messages.members');

    Route::get('/conversation/{memberId}', [ClientDashboardController::class, 'Client_messages_conversation'])
        ->name('client.conversation');

    Route::post('/send', [ClientDashboardController::class, 'Client_messages_conversation'])
        ->name('client.message.send');

    // NEW ENHANCED ROUTES

    // Send message with attachment
    Route::post('/send-with-attachment', [ClientDashboardController::class, 'sendMessageWithAttachment'])
        ->name('client.message.send.attachment');

    // Mark message(s) as read
    Route::post('/mark-read/{messageId}', [ClientDashboardController::class, 'markMessageRead'])
        ->name('client.message.mark.read');

    Route::post('/mark-conversation-read/{memberId}', [ClientDashboardController::class, 'markConversationRead'])
        ->name('client.conversation.mark.read');

    // Typing indicator
    Route::post('/typing/{memberId}', [ClientDashboardController::class, 'updateTyping'])
        ->name('client.typing.update');

    // Search messages
    Route::get('/search', [ClientDashboardController::class, 'searchMessages'])
        ->name('client.messages.search');

    // Get unread count (for badge)
    Route::get('/unread-count', [ClientDashboardController::class, 'getUnreadCount'])
        ->name('client.messages.unread.count');

    // Online status
    Route::post('/status/online', [ClientDashboardController::class, 'setOnlineStatus'])
        ->name('client.status.online');

    Route::post('/status/offline', [ClientDashboardController::class, 'setOfflineStatus'])
        ->name('client.status.offline');

    Route::post('/heartbeat', [ClientDashboardController::class, 'heartbeat'])
        ->name('client.heartbeat');

    // Get user online status
    Route::get('/status/{memberId}', [ClientDashboardController::class, 'getMemberOnlineStatus'])
        ->name('client.member.status');

    // File download
    Route::get('/download/{messageId}', [ClientDashboardController::class, 'downloadAttachment'])
        ->name('client.message.download');
});

/*
|--------------------------------------------------------------------------
| Broadcasting Authentication Routes
|--------------------------------------------------------------------------
*/

// This enables channel authentication for private/presence channels
Broadcast::routes(['middleware' => ['web']]);
```

---

## 🔐 Middleware Requirements

Ensure these middleware exist in your app:

### 1. Member Authentication Middleware
**File: `app/Http/Middleware/AuthenticateMember.php`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class AuthenticateMember
{
    public function handle(Request $request, Closure $next)
    {
        if (empty(Session::get('memberId'))) {
            return redirect()->route('login')->with('error', 'Please log in to access this page');
        }

        return $next($request);
    }
}
```

### 2. Client Authentication Middleware
**File: `app/Http/Middleware/AuthenticateClient.php`**

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class AuthenticateClient
{
    public function handle(Request $request, Closure $next)
    {
        if (empty(Session::get('clientId'))) {
            return redirect()->route('login')->with('error', 'Please log in to access this page');
        }

        return $next($request);
    }
}
```

### 3. Register Middleware

**File: `app/Http/Kernel.php`**

```php
protected $routeMiddleware = [
    // ... existing middleware ...
    'auth.member' => \App\Http\Middleware\AuthenticateMember::class,
    'auth.client' => \App\Http\Middleware\AuthenticateClient::class,
];
```

---

## 📡 API Endpoints Reference

### Member/DJ Endpoints

#### 1. Send Message with Attachment
```
POST /member/chat/send-with-attachment
Content-Type: multipart/form-data

Parameters:
- clientId (int, required): Recipient client ID
- message (string, required): Message content
- attachment (file, optional): File to attach (max 10MB)

Response:
{
  "success": true,
  "messageId": 12345,
  "message": "Message sent successfully"
}
```

#### 2. Mark Message as Read
```
POST /member/chat/mark-read/{messageId}

Response:
{
  "success": true,
  "message": "Message marked as read"
}
```

#### 3. Mark Conversation as Read
```
POST /member/chat/mark-conversation-read/{clientId}

Response:
{
  "success": true,
  "count": 5,
  "message": "5 messages marked as read"
}
```

#### 4. Update Typing Indicator
```
POST /member/chat/typing/{clientId}

Parameters:
- isTyping (boolean, required): true if typing, false if stopped

Response:
{
  "success": true
}
```

#### 5. Search Messages
```
GET /member/chat/search?q={keyword}

Parameters:
- q (string, required): Search keyword
- limit (int, optional): Max results (default: 50)

Response:
{
  "success": true,
  "count": 10,
  "results": [
    {
      "messageId": 123,
      "message": "Message containing keyword...",
      "senderId": 1,
      "senderType": 2,
      "dateTime": "2025-01-21 10:30:00"
    },
    ...
  ]
}
```

#### 6. Get Unread Count
```
GET /member/chat/unread-count

Response:
{
  "success": true,
  "count": 12
}
```

#### 7. Set Online Status
```
POST /member/chat/status/online

Response:
{
  "success": true,
  "status": "online"
}
```

#### 8. Heartbeat (Keep-Alive)
```
POST /member/chat/heartbeat

Response:
{
  "success": true,
  "timestamp": "2025-01-21T10:30:00.000Z"
}
```

#### 9. Get Client Online Status
```
GET /member/chat/status/{clientId}

Response:
{
  "success": true,
  "userId": 123,
  "userType": 1,
  "isOnline": true,
  "lastSeenAt": "2025-01-21T10:25:00.000Z"
}
```

#### 10. Download Attachment
```
GET /member/chat/download/{messageId}

Response: File download
```

### Client Endpoints

All client endpoints mirror the member endpoints but with `/client/chat` prefix instead of `/member/chat`.

---

## 🔒 Security Features

### CSRF Protection
All POST routes automatically include CSRF protection via Laravel's middleware.

**Include in all forms:**
```html
<form method="POST" action="{{ route('member.message.send') }}">
    @csrf
    <!-- form fields -->
</form>
```

**Include in AJAX requests:**
```javascript
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
```

### Rate Limiting (Recommended)

Add rate limiting to prevent spam:

**File: `app/Http/Kernel.php`**
```php
protected $middlewareGroups = [
    'api' => [
        'throttle:60,1', // 60 requests per minute
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
    ],
];
```

**Apply to chat routes:**
```php
Route::post('/send-with-attachment', [MemberDashboardController::class, 'sendMessageWithAttachment'])
    ->middleware('throttle:30,1') // 30 messages per minute
    ->name('member.message.send.attachment');

Route::post('/typing/{clientId}', [MemberDashboardController::class, 'updateTyping'])
    ->middleware('throttle:60,1') // 60 typing updates per minute
    ->name('member.typing.update');
```

---

## 🧪 Testing Routes

### Using Postman/Insomnia

1. **Get CSRF Token:**
   - Visit your app in browser
   - Open DevTools → Console
   - Run: `document.querySelector('meta[name="csrf-token"]').content`

2. **Test Endpoints:**
   - Set headers: `X-CSRF-TOKEN: {token}`
   - Set cookies from browser session

### Using Laravel Tinker

```bash
php artisan tinker

# Test send message
use App\Models\MemberAllDB;
$model = new MemberAllDB();
$messageId = $model->sendMessageWithAttachment(1, 2, 5, 1, 'Test message', null);

# Test mark as read
$model->markMessageAsRead($messageId, 5, 1);

# Test search
$results = $model->searchMessages(1, 2, 'test', 10);
print_r($results);
```

---

## 🐛 Troubleshooting

### 404 Not Found
- Verify routes are added to `routes/web.php`
- Clear route cache: `php artisan route:clear`
- Check middleware is registered

### 419 Page Expired (CSRF Error)
- Ensure `@csrf` token is included in forms
- Check AJAX requests include `X-CSRF-TOKEN` header
- Verify session is working

### 403 Forbidden
- Check middleware is allowing access
- Verify user is authenticated (check Session)
- Review channel authorization in `routes/channels.php`

### Route Not Accessible
```bash
# List all routes
php artisan route:list

# Search for specific route
php artisan route:list --name=member.message

# Clear and cache routes
php artisan route:clear
php artisan route:cache
```

---

## ✅ Testing Checklist

Before deploying:

- [ ] All routes added to `routes/web.php`
- [ ] Middleware registered and working
- [ ] CSRF protection tested
- [ ] Rate limiting configured
- [ ] Channel authorization tested
- [ ] File upload routes work
- [ ] Download routes work
- [ ] Typing indicator endpoints functional
- [ ] Online status endpoints functional
- [ ] Search endpoint returns results
- [ ] Unread count endpoint accurate

---

## 📚 Related Documentation

- **CHAT_CONTROLLER_ENHANCEMENTS.md** - Controller methods for these routes
- **CHAT_JAVASCRIPT_DOCUMENTATION.md** - Frontend AJAX calls to these routes
- **CHAT_ENHANCEMENT_IMPLEMENTATION_GUIDE.md** - Main implementation guide

---

## 🎯 Next Steps

After adding these routes:
1. Implement controller methods (see CHAT_CONTROLLER_ENHANCEMENTS.md)
2. Update views to call these routes (see CHAT_VIEWS_DOCUMENTATION.md)
3. Add JavaScript for AJAX calls (see CHAT_JAVASCRIPT_DOCUMENTATION.md)
4. Test each endpoint thoroughly

---

**Questions?** Refer to the main CHAT_ENHANCEMENT_IMPLEMENTATION_GUIDE.md
