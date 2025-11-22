<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Events\NewChatMessageEvent;
use App\Events\MessageReadEvent;
use App\Events\UserTypingEvent;
use App\Events\UserOnlineStatusEvent;

/**
 * Trait: Enhanced Chat Functionality
 *
 * Provides advanced chat features:
 * - File attachments
 * - Read receipts
 * - Typing indicators
 * - Online status tracking
 * - Message search
 * - Real-time broadcasting
 *
 * @package App\Models\Traits
 */
trait EnhancedChatTrait
{
    /**
     * Send message with optional file attachment and broadcast event
     *
     * @param int $senderId Sender user ID
     * @param int $senderType Sender type (1=Client, 2=Member)
     * @param int $receiverId Receiver user ID
     * @param int $receiverType Receiver type (1=Client, 2=Member)
     * @param string $message Message content
     * @param array|null $attachment File attachment data
     * @return int Message ID
     */
    public function sendMessageWithAttachment($senderId, $senderType, $receiverId, $receiverType, $message, $attachment = null)
    {
        // Update latest flag for conversation
        DB::table('chat_messages')
            ->where(function($query) use ($senderId, $senderType, $receiverId, $receiverType) {
                $query->where('senderType', $senderType)
                      ->where('senderId', $senderId)
                      ->where('receiverType', $receiverType)
                      ->where('receiverId', $receiverId);
            })
            ->orWhere(function($query) use ($senderId, $senderType, $receiverId, $receiverType) {
                $query->where('senderType', $receiverType)
                      ->where('senderId', $receiverId)
                      ->where('receiverType', $senderType)
                      ->where('receiverId', $senderId);
            })
            ->update(['latest' => 0]);

        // Prepare insert data
        $insert_data = [
            'senderType' => $senderType,
            'senderId' => $senderId,
            'receiverType' => $receiverType,
            'receiverId' => $receiverId,
            'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
            'dateTime' => now(),
            'latest' => 1,
            'unread' => 0,
            'delivered_at' => now(),
        ];

        // Add attachment data if present
        if ($attachment && !empty($attachment['path'])) {
            $insert_data['attachment_type'] = $attachment['type'] ?? 'other';
            $insert_data['attachment_path'] = $attachment['path'];
            $insert_data['attachment_name'] = $attachment['name'] ?? basename($attachment['path']);
            $insert_data['attachment_size'] = $attachment['size'] ?? 0;
            $insert_data['attachment_mime'] = $attachment['mime'] ?? 'application/octet-stream';
        }

        // Insert message
        $messageId = DB::table('chat_messages')->insertGetId($insert_data);

        // Broadcast new message event
        $this->broadcastNewMessage($messageId, $senderId, $senderType, $receiverId, $receiverType, $message, $attachment);

        return $messageId;
    }

    /**
     * Mark message as read and send read receipt
     *
     * @param int $messageId Message ID
     * @param int $readerId User who read the message
     * @param int $readerType Reader type (1=Client, 2=Member)
     * @return bool
     */
    public function markMessageAsRead($messageId, $readerId, $readerType)
    {
        // Get message details
        $message = DB::table('chat_messages')
            ->where('messageId', $messageId)
            ->first();

        if (!$message) {
            return false;
        }

        // Only mark as read if the reader is the receiver
        if ($message->receiverId != $readerId || $message->receiverType != $readerType) {
            return false;
        }

        // Update message
        DB::table('chat_messages')
            ->where('messageId', $messageId)
            ->update([
                'unread' => 1,
                'read_at' => now()
            ]);

        // Broadcast read receipt to sender
        event(new MessageReadEvent(
            $messageId,
            $message->senderId,
            $message->senderType,
            $readerId,
            $readerType
        ));

        return true;
    }

    /**
     * Mark all messages in a conversation as read
     *
     * @param int $userId Current user ID
     * @param int $userType Current user type
     * @param int $partnerId Conversation partner ID
     * @param int $partnerType Conversation partner type
     * @return int Number of messages marked as read
     */
    public function markConversationAsRead($userId, $userType, $partnerId, $partnerType)
    {
        $updated = DB::table('chat_messages')
            ->where('receiverId', $userId)
            ->where('receiverType', $userType)
            ->where('senderId', $partnerId)
            ->where('senderType', $partnerType)
            ->where('unread', 0)
            ->update([
                'unread' => 1,
                'read_at' => now()
            ]);

        // Broadcast read receipts for each message
        if ($updated > 0) {
            $messages = DB::table('chat_messages')
                ->where('receiverId', $userId)
                ->where('receiverType', $userType)
                ->where('senderId', $partnerId)
                ->where('senderType', $partnerType)
                ->whereNotNull('read_at')
                ->get();

            foreach ($messages as $message) {
                event(new MessageReadEvent(
                    $message->messageId,
                    $message->senderId,
                    $message->senderType,
                    $userId,
                    $userType
                ));
            }
        }

        return $updated;
    }

    /**
     * Search messages by keyword
     *
     * @param int $userId User ID
     * @param int $userType User type
     * @param string $keyword Search keyword
     * @param int $limit Result limit
     * @return array
     */
    public function searchMessages($userId, $userType, $keyword, $limit = 50)
    {
        $results = DB::table('chat_messages')
            ->where(function($query) use ($userId, $userType) {
                $query->where(function($q) use ($userId, $userType) {
                    $q->where('senderId', $userId)
                      ->where('senderType', $userType);
                })
                ->orWhere(function($q) use ($userId, $userType) {
                    $q->where('receiverId', $userId)
                      ->where('receiverType', $userType);
                });
            })
            ->whereRaw('MATCH(message) AGAINST(? IN NATURAL LANGUAGE MODE)', [$keyword])
            ->orderBy('dateTime', 'desc')
            ->limit($limit)
            ->get();

        return [
            'numRows' => count($results),
            'data' => $results
        ];
    }

    /**
     * Update typing indicator
     *
     * @param int $userId User who is typing
     * @param int $userType User type
     * @param int $recipientId Recipient ID
     * @param int $recipientType Recipient type
     * @param bool $isTyping True if typing, false if stopped
     * @return void
     */
    public function updateTypingIndicator($userId, $userType, $recipientId, $recipientType, $isTyping = true)
    {
        if ($isTyping) {
            // Insert or update typing indicator
            DB::table('chat_typing_indicators')->updateOrInsert(
                [
                    'userType' => $userType,
                    'userId' => $userId,
                    'recipientType' => $recipientType,
                    'recipientId' => $recipientId
                ],
                [
                    'typing_at' => now(),
                    'expires_at' => now()->addSeconds(5)
                ]
            );
        } else {
            // Remove typing indicator
            DB::table('chat_typing_indicators')
                ->where('userType', $userType)
                ->where('userId', $userId)
                ->where('recipientType', $recipientType)
                ->where('recipientId', $recipientId)
                ->delete();
        }

        // Get user name
        $userName = $this->getUserName($userId, $userType);

        // Broadcast typing event
        event(new UserTypingEvent(
            $userId,
            $userType,
            $userName,
            $recipientId,
            $recipientType,
            $isTyping
        ));
    }

    /**
     * Update user online status
     *
     * @param int $userId User ID
     * @param int $userType User type
     * @param bool $isOnline Online status
     * @return void
     */
    public function updateOnlineStatus($userId, $userType, $isOnline = true)
    {
        $data = [
            'is_online' => $isOnline,
            'last_activity_at' => now()
        ];

        if ($isOnline) {
            $data['session_id'] = session()->getId();
            $data['ip_address'] = request()->ip();
            $data['user_agent'] = request()->userAgent();
        } else {
            $data['last_seen_at'] = now();
        }

        DB::table('user_online_status')->updateOrInsert(
            [
                'userType' => $userType,
                'userId' => $userId
            ],
            $data
        );

        // Get user name
        $userName = $this->getUserName($userId, $userType);

        // Broadcast status change
        event(new UserOnlineStatusEvent(
            $userId,
            $userType,
            $userName,
            $isOnline,
            $data['last_seen_at'] ?? null
        ));
    }

    /**
     * Get unread message count for a user
     *
     * @param int $userId User ID
     * @param int $userType User type
     * @return int
     */
    public function getUnreadCount($userId, $userType)
    {
        return DB::table('chat_messages')
            ->where('receiverId', $userId)
            ->where('receiverType', $userType)
            ->where('unread', 0)
            ->count();
    }

    /**
     * Get online status for a user
     *
     * @param int $userId User ID
     * @param int $userType User type
     * @return array
     */
    public function getOnlineStatus($userId, $userType)
    {
        $status = DB::table('user_online_status')
            ->where('userType', $userType)
            ->where('userId', $userId)
            ->first();

        if (!$status) {
            return [
                'is_online' => false,
                'last_seen_at' => null
            ];
        }

        // Consider user offline if last activity was more than 5 minutes ago
        $isOnline = $status->is_online &&
                    $status->last_activity_at &&
                    now()->diffInMinutes($status->last_activity_at) < 5;

        return [
            'is_online' => $isOnline,
            'last_seen_at' => $status->last_seen_at
        ];
    }

    /**
     * Handle file upload for chat attachment
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array|null
     */
    public function handleChatFileUpload($file)
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Validate file size (max 10MB)
        if ($file->getSize() > 10 * 1024 * 1024) {
            throw new \Exception('File size exceeds 10MB limit');
        }

        // Determine file type
        $mime = $file->getMimeType();
        $type = 'other';

        if (str_starts_with($mime, 'image/')) {
            $type = 'image';
        } elseif (str_starts_with($mime, 'audio/')) {
            $type = 'audio';
        } elseif (str_starts_with($mime, 'video/')) {
            $type = 'video';
        } elseif (in_array($mime, ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])) {
            $type = 'document';
        }

        // Store file
        $path = $file->store('chat-attachments', 'public');

        return [
            'type' => $type,
            'path' => $path,
            'name' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'mime' => $mime
        ];
    }

    /**
     * Get user name by ID and type
     *
     * @param int $userId
     * @param int $userType
     * @return string
     */
    protected function getUserName($userId, $userType)
    {
        if ($userType == 1) {
            // Client
            $user = DB::table('clients')->where('id', $userId)->first();
            return $user->uname ?? 'Unknown Client';
        } else {
            // Member/DJ
            $user = DB::table('members')->where('id', $userId)->first();
            return $user->stagename ?? $user->fname ?? 'Unknown DJ';
        }
    }

    /**
     * Broadcast new message event
     *
     * @param int $messageId
     * @param int $senderId
     * @param int $senderType
     * @param int $receiverId
     * @param int $receiverType
     * @param string $message
     * @param array|null $attachment
     * @return void
     */
    protected function broadcastNewMessage($messageId, $senderId, $senderType, $receiverId, $receiverType, $message, $attachment = null)
    {
        $senderName = $this->getUserName($senderId, $senderType);

        event(new NewChatMessageEvent([
            'messageId' => $messageId,
            'message' => $message,
            'senderType' => $senderType,
            'senderId' => $senderId,
            'senderName' => $senderName,
            'receiverType' => $receiverType,
            'receiverId' => $receiverId,
            'hasAttachment' => !empty($attachment),
            'attachmentType' => $attachment['type'] ?? null
        ]));
    }
}
