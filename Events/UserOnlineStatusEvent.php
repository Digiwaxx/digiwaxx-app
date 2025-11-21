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
 * Event: User Online Status Change
 *
 * Broadcast when a user comes online or goes offline.
 * Updates online/offline indicators in real-time.
 *
 * @package App\Events
 */
class UserOnlineStatusEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $userId;
    public $userType;
    public $userName;
    public $isOnline;
    public $lastSeenAt;

    /**
     * Create a new event instance.
     *
     * @param int $userId User ID
     * @param int $userType User type (1=Client, 2=Member)
     * @param string $userName User's display name
     * @param bool $isOnline True if online, false if offline
     * @param string|null $lastSeenAt Last seen timestamp
     * @return void
     */
    public function __construct($userId, $userType, $userName, $isOnline, $lastSeenAt = null)
    {
        $this->userId = $userId;
        $this->userType = $userType;
        $this->userName = $userName;
        $this->isOnline = $isOnline;
        $this->lastSeenAt = $lastSeenAt ?? now()->toIso8601String();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to global presence channel
        // All users listening to chat will receive this
        return new PresenceChannel('chat.presence');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'user.status';
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
            'isOnline' => $this->isOnline,
            'lastSeenAt' => $this->lastSeenAt,
            'timestamp' => now()->toIso8601String()
        ];
    }
}
