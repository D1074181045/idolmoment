<?php

namespace App\Events;

use App\Models\ChatRoom;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatRoomEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $name;
    public $nickname;
    public $message;
    public $chat_created_at;

    /**
     * Create a new event instance.
     *
     * @param $name
     * @param $nickname
     * @param $message
     * @param $chat_created_at
     * @throws \Exception
     */
    public function __construct($name, $nickname, $message, $chat_created_at)
    {
//        if (ChatRoom::all()->count() > 100)
//            ChatRoom::query()->orderBy('created_at')->first()->delete();

        $this->name = $name;
        $this->nickname = $nickname;
        $this->message = $message;
        $this->chat_created_at = $chat_created_at;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return ['public-chat-channel'];
//        return new PrivateChannel('channel-name');
    }

    public function broadcastAs()
    {
        return 'public-chat-event';
    }
}
