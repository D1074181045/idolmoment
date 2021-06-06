<?php

namespace App\Events;

use App\Http\Other\UserNameCrypto;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnlockCharacterEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels, UserNameCrypto;

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
        return new PrivateChannel('unlock-character-channel-' . $this->UserNameEncrypt2($this->username));
    }

    public function broadcastAs()
    {
        return 'unlock-character-event';
    }
}
