<?php

namespace App\Http\Controllers;

use App\Jobs\SendMQTTMessageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use OpenApi\Annotations\OpenApi as OA;
use PhpMqtt\Client\Facades\MQTT;

class HomeController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/health",
     *     summary="Check API status",
     *     tags={"health"},
     *     @OA\Response(
     *          response=200,
     *          description="API is alive",
     *          @OA\JsonContent(
     *              @OA\Property(property="api_name", type="string", example="laravel_CO2"),
     *          ),
     *    ),
     *     @OA\Response(
     *          response=404,
     *          description="request lost"
     *    ),
     *      @OA\Response(
     *           response=500,
     *           description="api error"
     *     )
     *)
     *
     *
     *
     */
    public function getHealth()
    {
        return response()->json([
            "api_name" => "laravel_CO2"
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/test-mqtt",
     *     summary="Check API status",
     *     tags={"health"},
     *     @OA\Response(
     *          response=200,
     *          description="API is alive",
     *          @OA\JsonContent(
     *              @OA\Property(property="topic", type="string", example="/test"),
     *          ),
     *    ),
     *     @OA\Response(
     *          response=404,
     *          description="request lost"
     *    ),
     *      @OA\Response(
     *           response=500,
     *           description="api error"
     *     )
     *)
     *
     *
     *
     */
    public function testMqtt()
    {
        $date = Date::now();
        $message = ["date"=>$date->format("H:i:s d-m-Y"), "app"=> env("app_name")];

        SendMQTTMessageJob::dispatch("/test", json_encode($message));


        return response()->json([
            "topic" => "/test",
            "content" => $message
        ]);
    }

    public function getIndex(string $a =null){
        return view("home");
    }

}
