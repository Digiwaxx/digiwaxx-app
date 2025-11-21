# Chat Controller Enhancements Documentation

## 📋 Overview

This document provides the exact controller methods to add to your existing controllers for enhanced chat functionality.

---

## 🎯 Controllers to Update

1. **MemberDashboardController.php** - DJ/Member chat functionality
2. **ClientDashboardController.php** - Client chat functionality

---

## 📝 Member Dashboard Controller Methods

Add these methods to: `/var/www/digiwaxx-app/app/Http/Controllers/Members/MemberDashboardController.php`

### 1. Send Message with Attachment

```php
/**
 * Send message with optional file attachment
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function sendMessageWithAttachment(Request $request)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    // Validate request
    $request->validate([
        'clientId' => 'required|integer',
        'message' => 'required|string|max:5000',
        'attachment' => 'nullable|file|max:10240' // 10MB max
    ]);

    $clientId = $request->input('clientId');
    $message = $request->input('message');

    try {
        // Handle file upload if present
        $attachment = null;
        if ($request->hasFile('attachment')) {
            $attachment = $this->memberAllDB_model->handleChatFileUpload($request->file('attachment'));
        }

        // Send message using enhanced trait method
        $messageId = $this->memberAllDB_model->sendMessageWithAttachment(
            $memberId,
            2,  // Member type
            $clientId,
            1,  // Client type
            $message,
            $attachment
        );

        return response()->json([
            'success' => true,
            'messageId' => $messageId,
            'message' => 'Message sent successfully',
            'hasAttachment' => !empty($attachment)
        ]);

    } catch (\Exception $e) {
        \Log::error('Message send error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to send message: ' . $e->getMessage()
        ], 500);
    }
}
```

### 2. Mark Message as Read

```php
/**
 * Mark single message as read
 *
 * @param Request $request
 * @param int $messageId
 * @return \Illuminate\Http\JsonResponse
 */
public function markMessageRead(Request $request, $messageId)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        $result = $this->memberAllDB_model->markMessageAsRead($messageId, $memberId, 2);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => 'Message marked as read'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark message as read'
            ], 400);
        }

    } catch (\Exception $e) {
        \Log::error('Mark read error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 3. Mark Conversation as Read

```php
/**
 * Mark all messages in conversation as read
 *
 * @param Request $request
 * @param int $clientId
 * @return \Illuminate\Http\JsonResponse
 */
public function markConversationRead(Request $request, $clientId)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        $count = $this->memberAllDB_model->markConversationAsRead($memberId, 2, $clientId, 1);

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "$count messages marked as read"
        ]);

    } catch (\Exception $e) {
        \Log::error('Mark conversation read error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 4. Update Typing Indicator

```php
/**
 * Update typing indicator
 *
 * @param Request $request
 * @param int $clientId
 * @return \Illuminate\Http\JsonResponse
 */
public function updateTyping(Request $request, $clientId)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    $request->validate([
        'isTyping' => 'required|boolean'
    ]);

    $isTyping = $request->input('isTyping');

    try {
        $this->memberAllDB_model->updateTypingIndicator($memberId, 2, $clientId, 1, $isTyping);

        return response()->json([
            'success' => true
        ]);

    } catch (\Exception $e) {
        \Log::error('Typing indicator error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 5. Search Messages

```php
/**
 * Search messages
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function searchMessages(Request $request)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    $request->validate([
        'q' => 'required|string|min:2',
        'limit' => 'nullable|integer|max:100'
    ]);

    $keyword = $request->input('q');
    $limit = $request->input('limit', 50);

    try {
        $results = $this->memberAllDB_model->searchMessages($memberId, 2, $keyword, $limit);

        return response()->json([
            'success' => true,
            'count' => $results['numRows'],
            'results' => $results['data']
        ]);

    } catch (\Exception $e) {
        \Log::error('Search error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 6. Get Unread Count

```php
/**
 * Get unread message count
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getUnreadCount(Request $request)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        $count = $this->memberAllDB_model->getUnreadCount($memberId, 2);

        return response()->json([
            'success' => true,
            'count' => $count
        ]);

    } catch (\Exception $e) {
        \Log::error('Unread count error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 7. Set Online Status

```php
/**
 * Set user as online
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function setOnlineStatus(Request $request)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        $this->memberAllDB_model->updateOnlineStatus($memberId, 2, true);

        return response()->json([
            'success' => true,
            'status' => 'online'
        ]);

    } catch (\Exception $e) {
        \Log::error('Online status error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 8. Set Offline Status

```php
/**
 * Set user as offline
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function setOfflineStatus(Request $request)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        $this->memberAllDB_model->updateOnlineStatus($memberId, 2, false);

        return response()->json([
            'success' => true,
            'status' => 'offline'
        ]);

    } catch (\Exception $e) {
        \Log::error('Offline status error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 9. Heartbeat (Keep-Alive)

```php
/**
 * Heartbeat to keep user online
 *
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function heartbeat(Request $request)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        // Update last activity
        $this->memberAllDB_model->updateOnlineStatus($memberId, 2, true);

        // Get unread count while we're at it
        $unreadCount = $this->memberAllDB_model->getUnreadCount($memberId, 2);

        return response()->json([
            'success' => true,
            'timestamp' => now()->toIso8601String(),
            'unreadCount' => $unreadCount
        ]);

    } catch (\Exception $e) {
        \Log::error('Heartbeat error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 10. Get Client Online Status

```php
/**
 * Get client's online status
 *
 * @param Request $request
 * @param int $clientId
 * @return \Illuminate\Http\JsonResponse
 */
public function getClientOnlineStatus(Request $request, $clientId)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    try {
        $status = $this->memberAllDB_model->getOnlineStatus($clientId, 1);

        return response()->json([
            'success' => true,
            'userId' => $clientId,
            'userType' => 1,
            'isOnline' => $status['is_online'],
            'lastSeenAt' => $status['last_seen_at']
        ]);

    } catch (\Exception $e) {
        \Log::error('Get status error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

### 11. Download Attachment

```php
/**
 * Download message attachment
 *
 * @param Request $request
 * @param int $messageId
 * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\JsonResponse
 */
public function downloadAttachment(Request $request, $messageId)
{
    if (empty(Session::get('memberId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $memberId = Session::get('memberId');

    try {
        // Get message and verify access
        $message = DB::table('chat_messages')
            ->where('messageId', $messageId)
            ->where(function($query) use ($memberId) {
                $query->where(function($q) use ($memberId) {
                    $q->where('senderId', $memberId)->where('senderType', 2);
                })
                ->orWhere(function($q) use ($memberId) {
                    $q->where('receiverId', $memberId)->where('receiverType', 2);
                });
            })
            ->first();

        if (!$message || empty($message->attachment_path)) {
            return response()->json([
                'success' => false,
                'message' => 'Attachment not found'
            ], 404);
        }

        $filePath = storage_path('app/public/' . $message->attachment_path);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found on server'
            ], 404);
        }

        return response()->download($filePath, $message->attachment_name);

    } catch (\Exception $e) {
        \Log::error('Download error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
}
```

---

## 📝 Client Dashboard Controller Methods

Add these methods to: `/var/www/digiwaxx-app/app/Http/Controllers/Clients/ClientDashboardController.php`

**All methods are identical to Member methods, but with these changes:**
- Use `Session::get('clientId')` instead of `Session::get('memberId')`
- Use user type `1` (Client) instead of `2` (Member)
- Replace `$memberId` with `$clientId` throughout
- Replace `$clientId` with `$memberId` for conversation partners
- Use `$this->clientAllDB_model` instead of `$this->memberAllDB_model`

### Complete Client Controller Methods

```php
/**
 * Send message with optional file attachment
 */
public function sendMessageWithAttachment(Request $request)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    $request->validate([
        'memberId' => 'required|integer',
        'message' => 'required|string|max:5000',
        'attachment' => 'nullable|file|max:10240'
    ]);

    $memberId = $request->input('memberId');
    $message = $request->input('message');

    try {
        $attachment = null;
        if ($request->hasFile('attachment')) {
            $attachment = $this->clientAllDB_model->handleChatFileUpload($request->file('attachment'));
        }

        $messageId = $this->clientAllDB_model->sendMessageWithAttachment(
            $clientId,
            1,  // Client type
            $memberId,
            2,  // Member type
            $message,
            $attachment
        );

        return response()->json([
            'success' => true,
            'messageId' => $messageId,
            'message' => 'Message sent successfully',
            'hasAttachment' => !empty($attachment)
        ]);

    } catch (\Exception $e) {
        \Log::error('Message send error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Failed to send message: ' . $e->getMessage()
        ], 500);
    }
}

/**
 * Mark single message as read
 */
public function markMessageRead(Request $request, $messageId)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $result = $this->clientAllDB_model->markMessageAsRead($messageId, $clientId, 1);

        if ($result) {
            return response()->json(['success' => true, 'message' => 'Message marked as read']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to mark message as read'], 400);
        }

    } catch (\Exception $e) {
        \Log::error('Mark read error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Mark all messages in conversation as read
 */
public function markConversationRead(Request $request, $memberId)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $count = $this->clientAllDB_model->markConversationAsRead($clientId, 1, $memberId, 2);

        return response()->json([
            'success' => true,
            'count' => $count,
            'message' => "$count messages marked as read"
        ]);

    } catch (\Exception $e) {
        \Log::error('Mark conversation read error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Update typing indicator
 */
public function updateTyping(Request $request, $memberId)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    $request->validate(['isTyping' => 'required|boolean']);
    $isTyping = $request->input('isTyping');

    try {
        $this->clientAllDB_model->updateTypingIndicator($clientId, 1, $memberId, 2, $isTyping);
        return response()->json(['success' => true]);

    } catch (\Exception $e) {
        \Log::error('Typing indicator error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Search messages
 */
public function searchMessages(Request $request)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    $request->validate([
        'q' => 'required|string|min:2',
        'limit' => 'nullable|integer|max:100'
    ]);

    $keyword = $request->input('q');
    $limit = $request->input('limit', 50);

    try {
        $results = $this->clientAllDB_model->searchMessages($clientId, 1, $keyword, $limit);

        return response()->json([
            'success' => true,
            'count' => $results['numRows'],
            'results' => $results['data']
        ]);

    } catch (\Exception $e) {
        \Log::error('Search error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Get unread message count
 */
public function getUnreadCount(Request $request)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $count = $this->clientAllDB_model->getUnreadCount($clientId, 1);
        return response()->json(['success' => true, 'count' => $count]);

    } catch (\Exception $e) {
        \Log::error('Unread count error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Set user as online
 */
public function setOnlineStatus(Request $request)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $this->clientAllDB_model->updateOnlineStatus($clientId, 1, true);
        return response()->json(['success' => true, 'status' => 'online']);

    } catch (\Exception $e) {
        \Log::error('Online status error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Set user as offline
 */
public function setOfflineStatus(Request $request)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $this->clientAllDB_model->updateOnlineStatus($clientId, 1, false);
        return response()->json(['success' => true, 'status' => 'offline']);

    } catch (\Exception $e) {
        \Log::error('Offline status error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Heartbeat to keep user online
 */
public function heartbeat(Request $request)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $this->clientAllDB_model->updateOnlineStatus($clientId, 1, true);
        $unreadCount = $this->clientAllDB_model->getUnreadCount($clientId, 1);

        return response()->json([
            'success' => true,
            'timestamp' => now()->toIso8601String(),
            'unreadCount' => $unreadCount
        ]);

    } catch (\Exception $e) {
        \Log::error('Heartbeat error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Get member's online status
 */
public function getMemberOnlineStatus(Request $request, $memberId)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    try {
        $status = $this->clientAllDB_model->getOnlineStatus($memberId, 2);

        return response()->json([
            'success' => true,
            'userId' => $memberId,
            'userType' => 2,
            'isOnline' => $status['is_online'],
            'lastSeenAt' => $status['last_seen_at']
        ]);

    } catch (\Exception $e) {
        \Log::error('Get status error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}

/**
 * Download message attachment
 */
public function downloadAttachment(Request $request, $messageId)
{
    if (empty(Session::get('clientId'))) {
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }

    $clientId = Session::get('clientId');

    try {
        $message = DB::table('chat_messages')
            ->where('messageId', $messageId)
            ->where(function($query) use ($clientId) {
                $query->where(function($q) use ($clientId) {
                    $q->where('senderId', $clientId)->where('senderType', 1);
                })
                ->orWhere(function($q) use ($clientId) {
                    $q->where('receiverId', $clientId)->where('receiverType', 1);
                });
            })
            ->first();

        if (!$message || empty($message->attachment_path)) {
            return response()->json(['success' => false, 'message' => 'Attachment not found'], 404);
        }

        $filePath = storage_path('app/public/' . $message->attachment_path);

        if (!file_exists($filePath)) {
            return response()->json(['success' => false, 'message' => 'File not found on server'], 404);
        }

        return response()->download($filePath, $message->attachment_name);

    } catch (\Exception $e) {
        \Log::error('Download error: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
    }
}
```

---

## 🔧 Integration with Existing Methods

### Update Existing Send Message Method

In `MemberDashboardController.php`, update the existing `Member_send_message()` method to use the new trait:

```php
public function Member_send_message(Request $request)
{
    // ... existing authentication and view logic ...

    // If POST request (sending message)
    if ($request->isMethod('post')) {
        $memberId = Session::get('memberId');
        $clientId = $request->input('receiverId');
        $message = $request->input('message');

        // Use new method instead of old one
        $messageId = $this->memberAllDB_model->sendMessageWithAttachment(
            $memberId,
            2,
            $clientId,
            1,
            $message,
            null  // No attachment for simple form
        );

        // ... rest of existing logic ...
    }
}
```

Similar update for `ClientDashboardController.php` → `Client_messages_conversation()` method.

---

## ✅ Testing Checklist

- [ ] All methods added to both controllers
- [ ] Trait imported in both models
- [ ] Authentication checks working
- [ ] File upload validation working
- [ ] Messages send successfully
- [ ] Read receipts update
- [ ] Typing indicators broadcast
- [ ] Online status tracks correctly
- [ ] Search returns results
- [ ] Unread count accurate
- [ ] Heartbeat updates status
- [ ] File downloads work
- [ ] Error logging functional

---

## 🐛 Troubleshooting

### Trait Method Not Found
- Ensure `use EnhancedChatTrait;` is added to both models
- Clear cache: `php artisan cache:clear`

### Broadcasts Not Firing
- Check events are being dispatched in trait methods
- Verify Pusher credentials in `.env`
- Check `BroadcastServiceProvider` is enabled

### File Uploads Failing
- Check `storage/app/public/` permissions: `chmod -R 775 storage`
- Verify storage link exists: `php artisan storage:link`
- Check `upload_max_filesize` in `php.ini`

---

## 📚 Related Documentation

- **CHAT_ROUTES_DOCUMENTATION.md** - Routes for these methods
- **CHAT_JAVASCRIPT_DOCUMENTATION.md** - Frontend calls to these endpoints
- **CHAT_ENHANCEMENT_IMPLEMENTATION_GUIDE.md** - Main guide

---

**Next Steps:** After adding these controller methods, update your views and JavaScript to call these new endpoints.
