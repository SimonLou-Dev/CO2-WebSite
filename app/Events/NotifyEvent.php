<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifyEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $text,
        public int $type,
        public int $userId
    )
    {
    }



    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('User.'.env("APP_ENV").'.'.$this->userId)
        ];
    }

    public function broadcastAs()
    {
        return 'notify';
    }
    public function broadcastWith()
    {
        return [
            "message"=>$this->text,
            "type"=>$this->type
        ];
    }
}
