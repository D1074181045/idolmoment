<?php

namespace App\Providers;

use App\Events\ChatRoomEvent;
use App\Events\PromptEvent;
use App\Events\UnlockCharacterEvent;
use App\Listeners\ChatRoomListener;
use App\Listeners\LoginSuccessful;
use App\Listeners\PromptListener;
use App\Listeners\UnlockCharacterListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ChatRoomEvent::class => [
            ChatRoomListener::class,
        ],
        UnlockCharacterEvent::class => [
            UnlockCharacterListener::class,
        ],
        PromptEvent::class => [
            PromptListener::class,
        ],
        Login::class => [
            LoginSuccessful::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
