<?php

namespace Database\Factories;

use App\Models\Mesurement;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class MesurementFactory extends Factory
{
    protected $model = Mesurement::class;

    public function definition(): array
    {
        return [
            'sensor_id' => $this->faker->randomNumber(),
            'humidity' => $this->faker->randomFloat(),
            'temperature' => $this->faker->randomFloat(),
            'ppm' => $this->faker->randomNumber(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
