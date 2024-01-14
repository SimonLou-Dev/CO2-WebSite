<?php

namespace App\Console\Commands;

use App\Exceptions\SensorNotFoundException;
use App\Jobs\SaveSensorDataJob;
use App\Models\Sensor;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use PhpMqtt\Client\ConnectionSettings;
use PhpMqtt\Client\MqttClient;

class ListenMqttCommand extends Command
{
    protected $signature = 'mqtt:listen';

    protected $description = 'Command description';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $server = env('MQTT_HOST');
        $port = '1883';
        $clientId = "laravel-mqtt-listener-" . uniqid();
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


        $mqtt->subscribe('/sensor/+', function (string $topic, string $message) {

            $topicExploded = explode('/', $topic);

            if(sizeof($topicExploded) == 3 && ctype_xdigit($topicExploded[2]) && Str::isJson($message)){

                SaveSensorDataJob::dispatch(hexdec($topicExploded[2]), $message);

                $this->info("Receiving sensor ${topicExploded[2]} data successfully");

            }else{
                $this->warn("Error while reading message [${topic}] ${message}");
            }

        }, 0);

        $mqtt->loop(true);
        return Command::SUCCESS;
    }
}
