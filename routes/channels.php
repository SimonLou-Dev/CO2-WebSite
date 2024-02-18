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


Broadcast::channel('User.'.env("APP_ENV").".{id}", function ($user, $id) {
    return true;
});

Broadcast::channel('Sensor.'.env("APP_ENV").".{sensorId}", function ($user) {
    return true;
});
