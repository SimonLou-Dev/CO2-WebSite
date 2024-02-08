<?php

namespace App\Jobs;

use App\Exceptions\SensorNotFoundException;
use App\Models\Measurement;
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
        private string $devId,
        private string $jsonString,
    )
    {
    }

    public function handle(): void
    {


        if(Sensor::where("device_addr", $this->devId)->count() == 1){
            $messageDecoded = json_decode($this->jsonString);
            $data = $messageDecoded->data;
            $data = base64_decode($data);
            $dataExploded = explode('_', $data);

            $sensor = Sensor::where("device_addr",$this->devId)->first();
            $sensor->last_message = Carbon::now()->format("Y-m-d H:i:s");
            $sensor->save();

            $mesurement = new Measurement();
            $mesurement->sensor_id = $sensor->id;
            $mesurement->humidity = \Str::replace("_", "", $dataExploded[0]);
            $mesurement->temperature = \Str::replace("_", "", $dataExploded[1]);
            $mesurement->ppm = \Str::replace("_", "", $dataExploded[2]);
            $mesurement->measured_at = Carbon::now()->format("d/m/Y H:i:s");
            $mesurement->save();


        }else{
            $this->fail(SensorNotFoundException::class);
        }


    }
}
