<?php

namespace App\Listeners;

use App\Events\UnlockCharacterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UnlockCharacterListener
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
     * @param  UnlockCharacterEvent  $event
     * @return void
     */
    public function handle(UnlockCharacterEvent $event)
    {
        //
    }
}
