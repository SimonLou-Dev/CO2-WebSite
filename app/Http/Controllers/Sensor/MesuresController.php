<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\Mesurement;
use App\Models\Sensor;
use Illuminate\Http\Request;

class MesuresController extends Controller
{
    public function getMesures(Request $request, Sensor $sensor)
    {
        if ($request->has("period")) $period = $request->get("period");
        else $period = "1h";

        if (!\Str::startsWith($period, "1")) {
            return response()->json([
                "message" => "Invalid period"
            ], 403);
        }
        $period = \Str::convertCase($period, "lower");

        $start = now();
        $end = $start->sub($period);

        return response()->json([
            "start" => $start,
            "end" => $end
        ], 200);







        $mesures = Mesurement::where("sensor_id", $sensor->id)->orderBy("created_at", "desc");
        $data = [];



    }
}
