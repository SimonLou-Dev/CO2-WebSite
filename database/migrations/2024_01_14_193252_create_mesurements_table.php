<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mesurements', function (Blueprint $table) {
            $table->id();
            $table->integer('sensor_id');
            $table->float('humidity');
            $table->float('temperature');
            $table->integer('ppm');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mesurements');
    }
};
