<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel("moi", function ($user) {
    Log::info("je suis moi");
    return $user;
});

Broadcast::channel("private-moi", function ($user) {
    Log::info("je suis moi");
    return $user;
});

Broadcast::channel("test", function () {
    Log::info("je suis test");
    return true;
});

Broadcast::channel("precense-test", function () {
    Log::info("je suis precense-test");
    return true;
});
