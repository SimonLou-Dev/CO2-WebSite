<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $casts = [
        "created_at" => "datetime:Y-m-d H:i:s",
        "updated_at" => "datetime:Y-m-d H:i:s",
        "measured_at" => "datetime:Y-m-d H:i:s",
        "ppm" => "integer",
        "humidity" => "float",
        "temperature" => "float",
    ];

    public function getSensor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Sensor::class, 'sensor_id', 'id');
    }


}
