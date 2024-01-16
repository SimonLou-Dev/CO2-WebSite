<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use SimpleSoftwareIO\QrCode\QrCodeServiceProvider;


class SensorController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/sensors",
     *     summary="Get All sensor",
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
     *     @OA\Parameter (
     *         name="search",
     *         in="query",
     *         required=false,
     *         allowEmptyValue=true,
     *         example="L222"
     *     ),
     *     @OA\Parameter (
     *           name="page",
     *           in="query",
     *           required=false,
     *           allowEmptyValue=true,
     *           example="1"
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
    public function index()
    {
        $this->authorize('viewAny', Sensor::class);

        return Sensor::paginate();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Post(
     *     path="/sensors",
     *     summary="Add new sensor",
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
     *          @OA\RequestBody(
     *         required=true,
     *         description="Sensor data",
     *          @OA\JsonContent(
     *              required={"room_id", "device_addr"},
     *              @OA\Property(property="device_addr", type="string", example="fe:80:70:40:70"),
     *              @OA\Property(property="room_id", type="string", example="1"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Sensor created succesfully",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Sensor",
     *          ),
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
    public function store(Request $request)
    {
        $this->authorize('create', Sensor::class);

        $data = $request->validate([
            'room_id' => ['required', 'integer','exists:rooms,id','unique:sensors'],
            'device_addr' => ["required"]
        ]);
        $data["created_by"] = 1;

        return Sensor::create($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/sensors/{sensorId}",
     *     summary="Update sensor",
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
     *     @OA\PathParameter (
     *        name="sensorId",
     *        description="Id Of selected sensor",
     *        required=true,
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Sensor created succesfully",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Sensor",
     *          ),
     *    ),
     *      @OA\Response(
     *            response=403,
     *            description="Not Allowed"
     *      )
     *
     *)
     */
    public function show(Sensor $sensor)
    {
        $this->authorize('view', $sensor);

        return $sensor;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Put(
     *     path="/sensors/{sensorId}",
     *     summary="Update sensor",
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
     *     @OA\PathParameter (
     *        name="sensorId",
     *        description="Id Of selected sensor",
     *        required=true,
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Sensor data",
     *          @OA\JsonContent(
     *              required={"room_id", "device_addr"},
     *              @OA\Property(property="device_addr", type="string", example="fe:80:70:40:70"),
     *              @OA\Property(property="room_id", type="string", example="1"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Sensor created succesfully",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Sensor"
     *          ),
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
    public function update(Request $request, Sensor $sensor)
    {
        $this->authorize('update', $sensor);

        $data = $request->validate([
            'room_id' => ['required', 'integer'],
        ]);

        $sensor->update($data);

        return $sensor;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Delete(
     *     path="/sensors/{sensorId}",
     *     summary="Update sensor",
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
     *     @OA\PathParameter (
     *        name="sensorId",
     *        description="Id Of selected sensor",
     *        required=true,
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Sensor deleted succesfully",
     *          @OA\JsonContent(
     *          ),
     *    ),
     *      @OA\Response(
     *            response=403,
     *            description="Not Allowed"
     *      )
     *
     *)
     */
    public function destroy(Sensor $sensor)
    {
        $this->authorize('delete', $sensor);

        $sensor->delete();

        return response()->json();
    }

    public function getQrCode(string $sensor){
        

    }
}
