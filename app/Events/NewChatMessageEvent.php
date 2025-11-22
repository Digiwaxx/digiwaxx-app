<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event: New Chat Message Sent
 *
 * Broadcast when a new message is sent in a conversation.
 * Triggers real-time notification to the receiver.
 *
 * @package App\Events
 */
class NewChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $senderType;
    public $senderId;
    public $senderName;
    public $receiverType;
    public $receiverId;
    public $hasAttachment;
    public $attachmentType;

    /**
     * Create a new event instance.
     *
     * @param array $messageData Message information
     * @return void
     */
    public function __construct($messageData)
    {
        $this->message = $messageData['message'];
        $this->senderType = $messageData['senderType'];
        $this->senderId = $messageData['senderId'];
        $this->senderName = $messageData['senderName'] ?? 'Unknown';
        $this->receiverType = $messageData['receiverType'];
        $this->receiverId = $messageData['receiverId'];
        $this->hasAttachment = $messageData['hasAttachment'] ?? false;
        $this->attachmentType = $messageData['attachmentType'] ?? null;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to receiver's private channel
        $channelName = "chat.user.{$this->receiverType}.{$this->receiverId}";
        return new PrivateChannel($channelName);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'new.message';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'senderType' => $this->senderType,
            'senderId' => $this->senderId,
            'senderName' => $this->senderName,
            'hasAttachment' => $this->hasAttachment,
            'attachmentType' => $this->attachmentType,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
