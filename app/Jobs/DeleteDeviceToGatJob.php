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

class DeleteDeviceToGatJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $devEUI,
        private int $userId
    )
    {

        $deleteDeviceReqt = Http::acceptJson()
            ->withToken(Cache::get("CHIRPSTACK_API_KEY"))
            ->delete(env("CHIRPSTACK_API_URL")."/devices/".$this->devEUI);

        if ($deleteDeviceReqt->status() != 200){
            $this->fail("Request Error");
            NotifyEvent::dispatch("Error lors de la suppression dans Chirpstack",3,$this->userId);
        } else{
            NotifyEvent::dispatch("Capteur supprimé de Chirpstack",1,$this->userId);
        }

    }

    public function handle(): void
    {
    }
}
