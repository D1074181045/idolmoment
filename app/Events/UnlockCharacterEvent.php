<?php

namespace App\Events;

use App\Models\ChatRoom;
use App\Http\Controllers\Controller;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class UnlockCharacterEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $Character;
    public $username;

    /**
     * Create a new event instance.
     *
     * @param $Character
     * @param $username
     */
    public function __construct($Character, $username)
    {
        $this->Character = $Character;
        $this->username = $username;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('unlock-character-channel-' . Controller::UserNameEncrypt2($this->username));
    }

    public function broadcastAs()
    {
        return 'unlock-character-event';
    }
}
