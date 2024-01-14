<?php

namespace App\Jobs;

use App\Exceptions\SensorNotFoundException;
use App\Models\Mesurement;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use function Ramsey\Uuid\v1;

class SaveSensorDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private int $id,
        private string $jsonString,
    )
    {
    }

    public function handle(): void
    {


        if(Sensor::where("id", $this->id)->count() == 1){
            $messageDecoded = json_decode($this->jsonString);
            $mesurement = new Mesurement();
            $mesurement->sensor_id = $this->id;
            $mesurement->humidity = $messageDecoded->humidity;
            $mesurement->temperature = $messageDecoded->temperature;
            $mesurement->ppm = $messageDecoded->ppm;
            $mesurement->save();

            $sensor = Sensor::where("id",$this->id)->first();
            $sensor->last_message = Carbon::now()->format("H:i:s Y-m-d");
            $sensor->save();


        }else{
            $this->fail(SensorNotFoundException::class);
        }


    }
}
