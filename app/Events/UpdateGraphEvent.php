<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateGraphEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        private int $sensorId
    )
    {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('Sensor.'.env("APP_ENV").'.'.$this->sensorId)
        ];
    }

    public function broadcastAs()
    {
        return 'updateGraphEvent';
    }
}
