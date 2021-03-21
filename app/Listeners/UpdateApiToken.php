<?php

namespace App\Listeners;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UpdateApiToken
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
        $api_token = Str::random(60);

        Auth::user()->api_token = sha1($api_token . Auth::user()->name);
        Auth::user()->logged_at = Carbon::now();
        Auth::user()->save();
    }
}
