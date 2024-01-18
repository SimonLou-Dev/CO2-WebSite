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

class SetOTAAOnDevicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $devEUI,
        private int $userId
    )
    {
    }

    public function handle(): void
    {

        $postDevicesOTAA = Http::acceptJson()
            ->withToken(Cache::get("CHIRPSTACK_API_KEY"))
            ->post(env("CHIRPSTACK_API_URL")."/devices/".$this->devEUI."/keys", [
                "deviceKeys"=>[
                    "appKey"=>"2B7E151628AED2A6ABF7158809CF4F3C",
                    "nwkKey"=> "2B7E151628AED2A6ABF7158809CF4F3C",
                ]
            ]);

        if ($postDevicesOTAA->status() != 200){
            $this->fail("Request Error");
            NotifyEvent::dispatch("Error lors de la configuration OTAA",3,$this->userId);
        } else {
            NotifyEvent::dispatch("Configuration OTAA terminée",1,$this->userId);
        }
    }
}
