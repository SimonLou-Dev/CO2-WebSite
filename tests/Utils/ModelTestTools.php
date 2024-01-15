<?php

namespace Tests\Utils;

use App\Models\Room;
use App\Models\Sensor;

class ModelTestTools
{

    public static function createRoom(): Room
    {
        $room = Room::factory()->make();
        $room->save();

        return Room::where("name", $room->name)->first();

    }

    public static function createSensor(): Sensor
    {
        $room = ModelTestTools::createRoom();

        $sensor = Sensor::factory()->make([
            "room_id"=>$room->id
        ]);
        $sensor->save();
        return Sensor::where("room_id", $room->id)->first();

    }

}
