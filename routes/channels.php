<?php

use Illuminate\Support\Facades\Broadcast;
use App\Other\UserNameCrypto;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('unlock-character-channel-{name}', function ($user, $name) {
    return $user->name === UserNameCrypto::UserNameDecrypt($name);
});

Broadcast::channel('danger-channel-{name}', function ($user, $name) {
    return $user->name === UserNameCrypto::UserNameDecrypt($name);
});
