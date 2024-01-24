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
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     * *      name="Authorization",
     * *      in="header",
     * *      required=true,
     * *      description="Bearer {access-token}",
     * *      @OA\Schema(
     * *          type="bearerAuth"
     * *      )
     * *     ),
     *          @OA\PathParameter (
     *         name="sensorId",
     *         description="Id Of selected sensor",
     *         required=true,
     *      ),
     *          @OA\RequestBody(
     *          required=false,
     *          description="Request Period",
     *           @OA\JsonContent(
     *               @OA\Property(property="period", type="string", example="1h | 1d | 1m | 1y | 1s
     *           )
     *       ),

     *
     *     @OA\Response(
     *          response=200,
     *          description="Sensor retrieve succesfully",
     *          @OA\JsonContent(
     *              allOf={
     *                  @OA\Schema (ref="#/components/schemas/PaginatedResult"),
     *                  @OA\Schema (
     *                      @OA\Property (property="data", type="array", @OA\Items(ref="#/components/schemas/Sensor")),
     *                  ),
     *              }
     *          ),
     *    ),
     *     @OA\Response(
     *           response=403,
     *           description="Not Allowed"
     *     )
     *)
     */
    public function getMesures(Request $request, Sensor $sensor)
    {
        if ($request->has("period")) $period = $request->get("period");
        else $period = "1h";

        if (!\Str::startsWith($period, "1")) {
            return response()->json([
                "message" => "Invalid period"
            ], 403);
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
            case "y":
                $start = now()->sub("year", 1);
                $start->setHour(0)->setMinute(0)->setSecond(0);
                break;
            case "h":
                $start = now()->sub("hour", 1)->setSecond(0);
                break;
            case "d":
                $start = now()->sub("day", 1)->setSecond(0);
                break;

        }

        $ppm = array();
        $humidity = array();
        $temperature = array();
        $created_at = array();

        if($period == "s" || $period == "m" || $period == "y"){
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
                $created = $mesure["measured_at"];
                $ppm[] = [$created => $mesure["ppm"]];
                $humidity[] = [$created => $mesure["humidity"]];
                $temperature[] = [$created => $mesure["temperature"]];
                $created_at[] = $mesure["measured_at"];
            }
        }else{
            $mesures = Measurement::where("sensor_id", $sensor->id)->whereBetween("measured_at", [$start, $end])->orderBy("measured_at", "desc")->get(["measured_at", "ppm", "humidity", "temperature"]);
            foreach($mesures as $mesure){
                $created = $mesure->measured_at->format("d-m H:i:s");
                $ppm[] = [$created =>  $mesure->ppm];
                $humidity[] = [$created => $mesure->humidity];
                $temperature[] = [$created => $mesure->temperature];
                $created_at[] = $mesure->measured_at->format("d-m H:i:s");
            }
        }

        $lastMesure = Measurement::where("sensor_id", $sensor->id)->orderBy("measured_at", "desc")->first();


        return response()->json([
            "socket_path" => "sensor." . $sensor->id,
            "period" => $period,
            "from"=> $start->format("Y-m-d H:i:s"),
            "to"=> $end->format("Y-m-d H:i:s"),
            "last_mesure" => [
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
