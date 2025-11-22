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
 * Event: Message Read (Read Receipt)
 *
 * Broadcast when a message is read by the receiver.
 * Notifies the sender with a read receipt (double checkmark).
 *
 * @package App\Events
 */
class MessageReadEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $messageId;
    public $senderId;
    public $senderType;
    public $readerId;
    public $readerType;
    public $readAt;

    /**
     * Create a new event instance.
     *
     * @param int $messageId Message ID that was read
     * @param int $senderId Original sender ID
     * @param int $senderType Original sender type
     * @param int $readerId Reader ID (who read the message)
     * @param int $readerType Reader type
     * @return void
     */
    public function __construct($messageId, $senderId, $senderType, $readerId, $readerType)
    {
        $this->messageId = $messageId;
        $this->senderId = $senderId;
        $this->senderType = $senderType;
        $this->readerId = $readerId;
        $this->readerType = $readerType;
        $this->readAt = now();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        // Broadcast to sender's private channel (they get the read receipt)
        $channelName = "chat.user.{$this->senderType}.{$this->senderId}";
        return new PrivateChannel($channelName);
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'message.read';
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'messageId' => $this->messageId,
            'readerId' => $this->readerId,
            'readerType' => $this->readerType,
            'readAt' => $this->readAt->toIso8601String()
        ];
    }
}
