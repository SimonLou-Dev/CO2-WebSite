<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\Facades\MQTT;
use PhpMqtt\Client\MqttClient;

class SubscribeToTopicCommand extends Command
{
    protected $signature = 'mqtt:subscribe';

    protected $description = 'Subscribe To MQTT topic';

    public function handle(): int
    {
        $server = env('MQTT_HOST');
        $port = '1883';
        $clientId = "laravel-mqtt-" . uniqid();
        $username = env('MQTT_AUTH_USERNAME');
        $password = env('MQTT_AUTH_PASSWORD');
        $clean_session = true;


        $connectionSettings = (new ConnectionSettings())
            ->setConnectTimeout(10)
            ->setUsername($username)
            ->setPassword($password)
            ->setUseTls(false)
            ->setTlsSelfSignedAllowed(false)
            ->setKeepAliveInterval(60);

        $mqtt = new MqttClient($server, $port, $clientId);
        $mqtt->connect($connectionSettings, $clean_session);

        $this->info("Connected to MQTT broker {$server}:{$port} as {$clientId}");


        $mqtt->subscribe('#', function (string $topic, string $message) {
            $this->info("Received message on topic [{$topic}]: {$message}");
        }, 0);

        $mqtt->loop(true);
        return Command::SUCCESS;

    }
}
