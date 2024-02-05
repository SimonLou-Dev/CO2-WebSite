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
            "api_name" => env("APP_NAME")
        ]);
    }


    public function getIndex(string $a =null){
        return view("home");
    }

}
