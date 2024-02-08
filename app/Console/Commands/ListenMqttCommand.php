<?php

namespace App\Console\Commands;

use App\Exceptions\SensorNotFoundException;
use App\Jobs\SaveSensorDataJob;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
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
        $this->info("Subscribing to application/+/device/+/event/up");


        $app_id = Cache::get('CHIRPSTACK_APPLICATION_ID', "Unknown");
        $this->info("Process only request from app n°${app_id}");

        $mqtt->subscribe("application/+/device/+/event/up", function (string $topic, string $message) {

            $topicExploded = explode('/', $topic);

            if($topicExploded[1] != $app_id = Cache::get('CHIRPSTACK_APPLICATION_ID', "Unknown")){
                $this->warn("Receive request on ${topicExploded[1]} but current app id is ${app_id} ! skipping...");
                return;
            }



            if(sizeof($topicExploded) == 6 && Str::isJson($message)){

                SaveSensorDataJob::dispatch($topicExploded[3], $message);

                $this->info("Receiving data from sensor ${topicExploded[3]} successfully at " . Carbon::now()->format("d/m/Y H:i:s"));

            }else{
                $this->warn("Error while reading message [${topic}] \n\t ${message}");
            }



        }, 0);


        $mqtt->loop(true);
        return Command::SUCCESS;
    }
}
