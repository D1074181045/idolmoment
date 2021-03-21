<?php

namespace App\Listeners;

use App\Events\DangerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class DangerListener
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
     * @param  DangerEvent  $event
     * @return void
     */
    public function handle(DangerEvent $event)
    {
        //
    }
}
