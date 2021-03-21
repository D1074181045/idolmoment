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
    public bool $Back;

    /**
     * Create a new event instance.
     *
     * @param $Character
     * @param bool $Back
     */
    public function __construct($Character, bool $Back = false)
    {
        $this->Character = $Character;
        $this->Back = $Back;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        $self_name =  Controller::UserNameEncrypt2(Auth::user()->name);

        return new PrivateChannel('unlock-character-channel-' . $self_name);
    }

    public function broadcastAs()
    {
        return 'unlock-character-event';
    }
}
