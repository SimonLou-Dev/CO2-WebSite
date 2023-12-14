<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\Facades\MQTT;

class SendMqttTestCommand extends Command
{
    protected $signature = 'mqtt:test';

    protected $description = 'Command description';

    public function handle(): void
    {
        MQTT::connection()->publish('/test', 'Hello World!', 0, false);
    }
}
