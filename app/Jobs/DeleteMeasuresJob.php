<?php

namespace App\Jobs;

use App\Events\NotifyEvent;
use App\Models\Measurement;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeleteMeasuresJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public int $sensor_id,
        public int $requester_id
    )
    {

    }

    public function handle(): void
    {
        Measurement::where('sensor_id', $this->sensor_id)->each(function ($measurement) {
            $measurement->delete();
        });

        NotifyEvent::dispatch("Toutes les mesures ont été supprimées",1,$this->requester_id);

    }
}
