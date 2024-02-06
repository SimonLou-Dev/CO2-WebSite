<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ChirpstackController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Put(
     *     path="/chirpstack/keys",
     *     summary="update keys",
     *     tags={"chirpstack"},
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
     *          @OA\RequestBody(
     *         required=true,
     *         description="Sensor data",
     *          @OA\JsonContent(
     *              required={"app_id", "profile_id", "api_key"},
     *              @OA\Property(property="app_id", type="string", example="87674096-09e4-41e8-b114-a994a56e9e8b"),
     *              @OA\Property(property="profile_id", type="string", example="e4d9526a-d02d-440c-b467-61213cd55d00"),
     *              @OA\Property(property="api_key", type="string", example="e4d9526a-d02d-440c-b467-61213cd55d00"),
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
    public function setKeys(request $request)
    {
        $this->authorize("update_chirpstack_key", $request->user());

        $request->validate([
            "app_id"=>["required", "uuid"],
            "profile_id"=>["required", "uuid"],
            "api_key"=>["required", "string"]
        ]);

        if(Cache::store("redis")->has("CHIRPSTACK_API_KEY")) Cache::store("redis")->put("CHIRPSTACK_API_KEY", $request->api_key);
        else Cache::store("redis")->add("CHIRPSTACK_API_KEY", $request->api_key);

        if(Cache::store("redis")->has("CHIRPSTACK_DEVICE_PROFILE_ID")) Cache::store("redis")->put("CHIRPSTACK_DEVICE_PROFILE_ID", $request->profile_id);
        else Cache::store("redis")->add("CHIRPSTACK_DEVICE_PROFILE_ID", $request->profile_id);

        if(Cache::store("redis")->has("CHIRPSTACK_APPLICATION_ID")) Cache::store("redis")->put("CHIRPSTACK_APPLICATION_ID", $request->app_id);
        else Cache::store("redis")->add("CHIRPSTACK_APPLICATION_ID", $request->app_id);


        return response()->json();
    }
}
