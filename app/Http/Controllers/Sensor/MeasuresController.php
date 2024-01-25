<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Sensor;
use Illuminate\Http\Request;

class MeasuresController extends Controller
{



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/sensors{sensorId}/mesures",
     *     summary="Get sensor mesures",
     *     tags={"sensors"},
     *     @OA\Parameter (
     *          name="period",
     *          in="query",
     *          required=false,
     *          allowEmptyValue=true,
     *          example="1h|1j|1s|1m|1a",
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Sensor mesures",
     *         @OA\JsonContent(
     *             @OA\Property(property="socket_path", type="string", example="sensor.1"),
     *             @OA\Property(property="period", type="string", example="1h"),
     *             @OA\Property(property="from", type="string", example="2024-01-01 00:00:00"),
     *             @OA\Property(property="to", type="string", example="2024-01-01 00:20:00"),
     *             @OA\Property(property="last_measure", type="object", example={"ppm": 100, "humidity": 50, "temperature": 20}),
     *             @OA\Property(property="data", type="object", example={"dates": {"2024-01-01 00:00:00", "2024-01-01 00:20:00"}, "ppm": {100, 100}, "humidity": {50, 50}, "temperature": {20, 20}}),
     *         ),
     *     ),
     *     @OA\Response(
     *            response=422,
     *            description="validation error",
     *      ),
     * )
     *
     */
    public function getMesures(Request $request, string $sensor)
    {
        if ($sensor == "0") {
            $sensor = Sensor::first();
        } else {
            $sensor = Sensor::where("id", $sensor)->first();
        }




        if ($request->has("period")) $period = $request->get("period");
        else $period = "1j";

        if (!\Str::startsWith($period, "1")) {
            return response()->json([
                "message" => "Invalid period"
            ], 405);
        }
        $period = \Str::convertCase($period, MB_CASE_LOWER);
        $period = \Str::replaceFirst("1", "", $period);

        $end = now()->add("second", 1);
        $start = now()->sub("second", 1);

        switch ($period){
            case "s":
                $start = now()->sub("day", 6);
                $start->setHour(0)->setMinute(0)->setSecond(0);
                break;
            case "m":
                $start = now()->sub("month", 1);
                $start->setHour(0)->setMinute(0)->setSecond(0);
                break;
            case "a":
                $start = now()->sub("year", 1);
                $start->setHour(0)->setMinute(0)->setSecond(0);
                break;
            case "h":
                $start = now()->sub("hour", 1)->setSecond(0);
                break;
            case "j":
                $start = now()->sub("day", 1)->setSecond(0);
                break;

        }

        $ppm = array();
        $humidity = array();
        $temperature = array();
        $created_at = array();

        if($period == "s" || $period == "m" || $period == "a"){
            $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [$start, $end])->orderBy("measured_at", "desc")->get(["measured_at", "ppm", "humidity", "temperature"])->groupBy(function ($item) {
                return $item->measured_at->format('Y-m-d');
            })->map(function ($item) {

                return [
                    "ppm" => round($item->avg("ppm"), 0),
                    "humidity" => round($item->avg("humidity"),1),
                    "temperature" => round($item->avg("temperature"),1),
                    "measured_at" => $item->first()->measured_at->format("Y-m-d")
                ];
            });

            foreach($mesures as $mesure){
                $ppm[] = $mesure["ppm"];
                $humidity[] = $mesure["humidity"];
                $temperature[] = $mesure["temperature"];
                $created_at[] = $mesure["measured_at"];
            }
        }else{
            $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [$start, $end])->orderBy("measured_at", "desc")->get(["measured_at", "ppm", "humidity", "temperature"]);
            foreach($mesures as $mesure){

                $ppm[] = $mesure->ppm;
                $humidity[] = $mesure->humidity;
                $temperature[] = $mesure->temperature;
                $created_at[] = $mesure->measured_at->format("d H:i");
            }
        }

        $lastMesure = Measurement::where("sensor_id", $sensor->id)->orderBy("measured_at", "desc")->first();


        return response()->json([
            "socket_path" => "sensor." . $sensor->id,
            "period" => $period,
            "from"=> $start->format("Y-m-d H:i:s"),
            "to"=> $end->format("Y-m-d H:i:s"),
            "last_measure" => [
                "ppm" => $lastMesure->ppm,
                "humidity" => $lastMesure->humidity,
                "temperature" => $lastMesure->temperature,
            ],
            "data" => [
                "dates" => $created_at,
                "ppm" => $ppm,
                "humidity" => $humidity,
                "temperature" => $temperature,
            ]

        ]);



    }
}
