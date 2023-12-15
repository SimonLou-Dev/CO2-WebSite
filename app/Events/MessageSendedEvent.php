<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSendedEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct()
    {
        $this->broadcastToEveryone();
    }

    public function broadcastOn()
    {
        return [
            new PresenceChannel('test'),
            new PrivateChannel("moi")
        ];
    }

    public function broadcastAs(){
        return "message-send";
    }

    public function broadcastWith()
    {
        return ['message' => 'Hello, world!'];
    }


}
