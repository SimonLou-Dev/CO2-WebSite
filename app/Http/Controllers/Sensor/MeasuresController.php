<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Sensor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use function PHPUnit\Framework\isNull;

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
     *             @OA\Property(property="quality_threshold", type="object", example={"low": 400, "medium": 800, "high": 1200}),
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
            $sensor = Measurement::orderBy("id", "desc")->first()->getSensor;
        } else {
            $sensor = Sensor::where("id", $sensor)->firstOrFail();
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

        $mesures = null;
        if($period == "s" || $period == "m"){
            $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [$start, $end])->orderBy("measured_at", "desc")->get(["measured_at", "ppm", "humidity", "temperature"])->groupBy(function ($item) {
                return $item->measured_at->format('Y-m-d');
            })->map(function ($item) {

                return [
                    "ppm" => round($item->avg("ppm"), 0),
                    "humidity" => round($item->avg("humidity"),1),
                    "temperature" => round($item->avg("temperature"),1),
                    "measured_at" => $item->first()->measured_at->timestamp * 1000,
                ];
            });


        }elseif ($period == "a") {
            $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [$start, $end])->orderBy("measured_at", "desc")->get(["measured_at", "ppm", "humidity", "temperature"])->groupBy(function ($item) {
                return $item->measured_at->format('Y-m');
            })->map(function ($item) {

                return [
                    "ppm" => round($item->avg("ppm"), 0),
                    "humidity" => round($item->avg("humidity"),1),
                    "temperature" => round($item->avg("temperature"),1),
                    "measured_at" => $item->first()->measured_at->timestamp * 1000,
                ];
            });

        }else{
            $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [$start, $end])->orderBy("measured_at", "desc")->get(["measured_at", "ppm", "humidity", "temperature"]);

        }

        foreach($mesures as $mesure){


            $ppm[] = $mesure["ppm"];

            $humidity[] = $mesure["humidity"];
            $temperature[] = $mesure["temperature"];
            if($period == "a")
                $created_at[] = date("Y-m", ($mesure["measured_at"]/1000));
            else if ($period == "s" || $period == "m")
                $created_at[] = date("Y-m-d", ($mesure["measured_at"]/1000));
            else
                $created_at[] = Carbon::createFromTimeString($mesure["measured_at"])->timestamp * 1000;
        }



        $lastMesure = Measurement::where("sensor_id", $sensor->id)->orderBy("measured_at", "desc")->first();
        $interval = 0;
        switch ($period){
            case "h":
                $interval = 10 * 60;
                break;
            case "j":
                $interval = 3600;
                break;
            case "s":
                $interval = 3600 * 24;
                break;
            case "m":
                $interval = 3600 * 24 * 2;
                break;
            case "a":
                $interval = 3600 * 24 * 30;
                break;
        }

        return response()->json([
            "socket_path" => "sensor." . $sensor->id,
            "period" => $period,
            "from"=> $start->timestamp * 1000,
            "to"=> $end->timestamp * 1000,
            "room" => $sensor->getRoom,
            "sensor"=>$sensor,
            "interval" => $interval * 1000,
            "quality_threshold" => [
                "low" => Cache::get("CONCENTRATION_THRESHOLD_LOW", "400"),
                "medium" => Cache::get("CONCENTRATION_THRESHOLD_MEDIUM", "800"),
                "high" => Cache::get("CONCENTRATION_THRESHOLD_HIGH", "1200")
            ],
            "last_measure" => [
                "ppm" => (is_null($lastMesure) ? null  :  $lastMesure->ppm),
                "humidity" => (is_null($lastMesure) ? null  :  $lastMesure->humidity),
                "temperature" => (is_null($lastMesure) ? null  : $lastMesure->temperature),
            ],
            "data" => [
                "dates" => array_reverse($created_at),
                "ppm" => array_reverse($ppm),
                "humidity" => array_reverse($humidity),
                "temperature" => array_reverse($temperature),
            ]

        ]);



    }

    public function getHeatmap(Request $request, string $sensor)
    {
        if ($sensor == "0") {
            $sensor = Sensor::first();
        } else {
            $sensor = Sensor::where("id", $sensor)->firstOrFail();
        }

        Carbon::setLocale('fr');
        setlocale(LC_TIME, 'fr_FR.utf8', 'fra');
        $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [now()->sub("day", 7), now()])->orderBy("measured_at", "desc")->get(["measured_at", "ppm"]);
        $mesures = $mesures->groupBy(function ($item) {
            return $item->measured_at->format('Y-m-d H');
        })->map(function ($item) {
            return [
                "ppm" => round($item->avg("ppm"), 0),
                "x" => $item->first()->measured_at->translatedFormat("H"),
                "y" => $item->first()->measured_at->translatedFormat("l")
            ];
        });

        $data = new Collection();
        $days = [];
        for ($j = 6; $j >= 0; $j--) {

            $key = Carbon::now()->sub("day", $j);

            for ($h = 6; $h < 20; $h++) {
                $key->setHour($h);
                $date = $key->format("Y-m-d H");

                $data->add([
                    "ppm" => isset($mesures[$date]) ? $mesures[$key->format("Y-m-d H")]["ppm"] : null,
                    "x" => $h,
                    "y" =>  $j
                ]);
            }
        }

        $current = Carbon::now();
        for ($i = 6; $i >= 0; $i--) {
            $days[] = $current->translatedFormat("l");
            $current = $current->sub("day", 1);
        }



        return response()->json([
            "data" => $data->toArray(),
            "quality_threshold" => [
                "low" => Cache::get("CONCENTRATION_THRESHOLD_LOW", "400"),
                "medium" => Cache::get("CONCENTRATION_THRESHOLD_MEDIUM", "800"),
                "high" => Cache::get("CONCENTRATION_THRESHOLD_HIGH", "1200")
            ],
            "days"=> $days

        ]);

    }

}
