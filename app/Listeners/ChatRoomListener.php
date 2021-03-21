<?php

namespace App\Listeners;

use App\Events\ChatRoomEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChatRoomListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ChatRoomEvent  $event
     * @return void
     */
    public function handle(ChatRoomEvent $event)
    {
        //
    }
}
