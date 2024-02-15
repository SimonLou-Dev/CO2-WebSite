<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearFrontManifestCommand extends Command
{
    protected $signature = 'front:clear';

    protected $description = 'Command description';

    public function handle(): void
    {
        if(Cache::has("vite_manifest")){
            Cache::forget("vite_manifest");
            $this->info("Front manifest cache cleared");
        } else {
            $this->info("Front manifest cache not found");
        }
    }
}
