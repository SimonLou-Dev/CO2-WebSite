<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThresholdController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/theshold",
     *     summary="Get theshold",
     *     tags={"theshold"},
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
     *     @OA\Response(
     *          response=200,
     *          description="Threshold set succesfully",
     *          @OA\JsonContent(
     *               required={"low", "medium"},
     *               @OA\Property(property="low", type="int", example="1200"),
     *               @OA\Property(property="medium", type="int", example="800"),
     *           )
     *    ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error"
     *    ),
     *      @OA\Response(
     *            response=403,
     *            description="Not Allowed"
     *      )
     *)
     */
    public function getThreshold()
    {

        return response()->json([
            "low" => Cache::get("CONCENTRATION_THRESHOLD_LOW", "400"),
            "medium" => Cache::get("CONCENTRATION_THRESHOLD_MEDIUM", "800"),
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Put(
     *     path="/threshlod",
     *     summary="update threshold",
     *     tags={"theshold"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     * *      name="Authorization",
     * *      in="header",
     * *      required=true,
     * *      description="Bearer {access-token}",
     * *      @OA\Schema(
     * *          type="bearerAuth"
     * *      )hold
     * *     ),
     *          @OA\RequestBody(
     *         required=true,
     *         description="Thres data",
     *          @OA\JsonContent(
     *              required={"low", "medium"},
     *              @OA\Property(property="low", type="int", example="1200"),
     *              @OA\Property(property="medium", type="int", example="800"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Keys set succesfully",
     *    ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error"
     *    ),
     *      @OA\Response(
     *            response=403,
     *            description="Not Allowed"
     *      )
     *)
     */
    public function updateThreshold(Request $request)
    {
        $request->validate([
            "low" => ["required", "numeric"],
            "medium" => ["required", "numeric"],
            "high" => ["required", "numeric"]
        ]);

        if(Cache::store("redis")->has("CONCENTRATION_THRESHOLD_LOW")) Cache::store("redis")->put("CONCENTRATION_THRESHOLD_LOW", $request->low);
        else Cache::store("redis")->add("CONCENTRATION_THRESHOLD_LOW", $request->low);

        if(Cache::store("redis")->has("CONCENTRATION_THRESHOLD_MEDIUM")) Cache::store("redis")->put("CONCENTRATION_THRESHOLD_MEDIUM", $request->medium);
        else Cache::store("redis")->add("CONCENTRATION_THRESHOLD_MEDIUM",$request->medium);


    }
}
