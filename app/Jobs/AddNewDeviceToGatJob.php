<?php

namespace App\Jobs;

use App\Events\NotifyEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class AddNewDeviceToGatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $devEUI,
        private string $name,
        private int $userId
    )
    {

    }

    public function handle(): void
    {


        $postDevicesReqt = Http::acceptJson()
            ->withToken(Cache::get("CHIRPSTACK_API_KEY"))
            ->post(env("CHIRPSTACK_API_URL")."/devices", [
                "device"=>[
                    "applicationId"=>Cache::get("CHIRPSTACK_APPLICATION_ID"),
                    "devEui"=> $this->devEUI,
                    "deviceProfileId"=> Cache::get("CHIRPSTACK_DEVICE_PROFILE_ID"),
                    "isDisabled"=>false,
                    "name"=>$this->name
                ]
            ]);
        $this->fail("Pute");

        if ($postDevicesReqt->status() != 200){
            $this->fail("Request Error");
            NotifyEvent::dispatch("Error lors de l'ajout à Chirpstack",3,$this->userId);
        } else{
            SetOTAAOnDevicesJob::dispatch($this->devEUI, $this->userId);
            NotifyEvent::dispatch("Capteur ajouté à Chirpstack",1,$this->userId);
        }


    }
}
