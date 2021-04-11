<?php

namespace App\Listeners;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class LoginSuccessful
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
     * @return void
     */
    public function handle()
    {
        //
        $user = Auth::user();
        $user->logged_at = Carbon::now();
        $user->save();
    }
}
