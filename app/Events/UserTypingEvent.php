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
 * Event: User Typing Indicator
 *
 * Broadcast when a user is typing in a conversation.
 * Shows "User is typing..." to the conversation partner.
 *
 * @package App\Events
 */
class UserTypingEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $userType;
    public $userName;
    public $recipientId;
    public $recipientType;
    public $isTyping;

    /**
     * Create a new event instance.
     *
     * @param int $userId User who is typing
     * @param int $userType User type (1=Client, 2=Member)
     * @param string $userName User's display name
     * @param int $recipientId Recipient user ID
     * @param int $recipientType Recipient type
     * @param bool $isTyping True if typing, false if stopped
     * @return void
     */
    public function __construct($userId, $userType, $userName, $recipientId, $recipientType, $isTyping = true)
    {
        $this->userId = $userId;
        $this->userType = $userType;
        $this->userName = $userName;
        $this->recipientId = $recipientId;
        $this->recipientType = $recipientType;
        $this->isTyping = $isTyping;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to recipient's private channel
        $channelName = "chat.user.{$this->recipientType}.{$this->recipientId}";
        return new PrivateChannel($channelName);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'user.typing';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'userId' => $this->userId,
            'userType' => $this->userType,
            'userName' => $this->userName,
            'isTyping' => $this->isTyping,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
