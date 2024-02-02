<?php

namespace App\Console\Commands;

use App\Models\Measurement;
use App\Models\Sensor;
use Illuminate\Console\Command;

class FillMeasurementCommand extends Command
{
    protected $signature = 'measurement:fill';

    protected $description = 'Command description';

    public function handle(): void
    {
        $sensor = new Sensor();
        $sensor->room_id = 1;
        $sensor->created_by = 1;
        $sensor->device_addr= "00:00:00:00:00:00";
        $sensor->save();
        $sensor->refresh();

        $this->info("Sensor created with id {$sensor->id}");

        $start = now()->subDays(30)->setHour(0)->setMinute(0)->setSecond(0);
        $cursor = $start;
        $end = now()->setHour(23)->setMinute(59)->setSecond(59);

        $this->info("Faking measures between: {$start->format('Y-m-d H:i:s')} & {$end->format('Y-m-d H:i:s')}");

        $data = [];

        $pmmMoy = [];
        $humidityMoy = [];
        $temperatureMoy = [];

        for ($day = 0; $day <= 30; $day++){
            $this->info("Faking measures for {$cursor->format('Y-m-d')}");
            for ($hour = 0; $hour <= 23; $hour++){
                for ($minute = 0; $minute <= 59; $minute+=10){
                    $cursor->addMinutes(10);
                    $pmmMoy[$minute % 2 ] = rand(250, 1500);
                    $humidityMoy[$minute % 2 ] = rand(30, 80);
                    $temperatureMoy[$minute % 2 ] = rand(18, 25);

                    $data[] = [
                        "ppm" => array_sum($pmmMoy) / count($pmmMoy),
                        "humidity" => array_sum($humidityMoy) / count($humidityMoy),
                        "temperature" => array_sum($temperatureMoy) / count($temperatureMoy),
                        "measured_at" => $cursor->format("Y-m-d H:i:s"),
                        "sensor_id" => $sensor->id,
                        "created_at" => $cursor->format("Y-m-d H:i:s"),
                        "updated_at" => $cursor->format("Y-m-d H:i:s"),
                    ];


                }
            }
        }



        $this->info("Faking measures done");


        $this->info("Preparing to insert " .  count($data) . " measures");

        $this->withProgressBar($data, function ($item) {
            Measurement::insert($item);
        });

        $this->info("Measures inserted successfully");









    }
}
