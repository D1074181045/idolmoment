<?php

namespace App\Events;

use App\Http\Controllers\Controller;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class PromptEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $name;
    public $type;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param $name
     * @param $type
     * @param $message
     */
    public function __construct($name, $type, $message)
    {
        $this->name = $name;
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('danger-channel-' . $this->name);
    }

    public function broadcastAs()
    {
        return 'danger-event';
    }
}
