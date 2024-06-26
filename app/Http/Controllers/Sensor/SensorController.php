<?php

namespace App\Http\Controllers\Sensor;

use App\Events\NotifyEvent;
use App\Http\Controllers\Controller;
use App\Jobs\AddNewDeviceToGatJob;
use App\Jobs\DeleteDeviceToGatJob;
use App\Jobs\DeleteMeasuresJob;
use App\Models\Room;
use App\Models\Sensor;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public function index(Request $request)
    {
        $this->authorize('viewAny', Sensor::class);

        $sensors = Sensor::orderBy('id', 'asc')->paginate(10);

        foreach ($sensors as $sensor){
            $sensor->getRoom;
        }

        return $sensors;
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
     *              @OA\Property (property="rooms", type="array", @OA\Items(ref="#/components/schemas/Room")),
     *              @OA\Property (property="sensor", type="object", ref="#/components/schemas/Sensor"),
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
            'room_id' => ['required', 'integer', 'min:1'],
            'device_addr' => ["required"]
        ]);
        $data["created_by"] = 1;
        $data["device_addr"] = Str::replace(['-', ' ', ':'],'', $data["device_addr"]);



        $sensor = Sensor::create($data);
        if(!Cache::has("CHIRPSTACK_DEVICE_PROFILE_ID") && !Cache::has("CHIRPSTACK_API_KEY") && !Cache::has("CHIRPSTACK_APPLICATION_ID")){
            NotifyEvent::dispatch("Le capteur ne sera pas ajouté à chirpstack", 3, $request->user()->id);
        }else{
            AddNewDeviceToGatJob::dispatch($request->device_addr, "sensor_".$sensor->id, $sensor->created_by);
        }

        return response()->json($sensor, 201);
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

        $sensors = Sensor::all();

        $rooms = Room::withTrashed()->get();

        foreach ($rooms as $key => $room){
            if($sensors->has($room->id) && $room->id != $sensor->room_id) $rooms->forget($key);
        }



        return response()->json([
            "sensor"=> $sensor,
            "rooms"=> array_values($rooms->toArray())
        ]);
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
    public function destroy(Request $request, Sensor $sensor)
    {
        $this->authorize('delete', $sensor);


        if(!Cache::has("CHIRPSTACK_API_KEY")){
            return response()->json([
                "API_KEY"=>"You must set API KEY"
            ],500);
        }

        if(!Cache::has("CHIRPSTACK_DEVICE_PROFILE_ID")){
            return response()->json([
                "DEVICE_PROFIL"=>"You must set DEVICE PROFIL ID"
            ],500);
        }
        if(!Cache::has("CHIRPSTACK_APPLICATION_ID")){
            return response()->json([
                "APPLICATION_ID"=>"You must set APPLICATION ID"
            ],500);
        }

        DeleteDeviceToGatJob::dispatch($sensor->device_addr, $request->user()->id);

        if($sensor->getRoom->deleted_at != null){
            $sensor->getRoom->forceDelete();
        }

        DeleteMeasuresJob::dispatch($sensor->id, $request->user()->id) ;

        $sensor->delete();

        return response()->json();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/sensors/{sensorId}/qrcode",
     *     summary="Get Code code",
     *     tags={"sensors"},
     *     @OA\PathParameter (
     *        name="sensorId",
     *        description="Id Of selected sensor",
     *        required=true,
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="get sensor qrCode succesfully (return png images)"
     *    ),
     *      @OA\Response(
     *            response=403,
     *            description="Not Allowed"
     *      )
     *
     *)
     */
    public function getQrCode(Sensor $sensor){
        $path = Storage::get('public\images\LogoQrCode.png');
        $savePath = Storage::path('public/QrCodes/sensor_'.dechex($sensor->id).'.png');
        if(Storage::exists($savePath)) return response()->file($savePath);
        $url = env("APP_URL") . '?id='.$sensor->id;


        \CodeQr::size(500)
            ->format("png")
            ->mergeString($path,.3)
            ->encoding("UTF-8")
            ->errorCorrection('H')
            ->eye("circle")
            ->style('round')
            ->generate($url, $savePath);

        return response()->file($savePath);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/sensors/code",
     *     summary="Get code",
     *     tags={"sensors"},

     *
     *     @OA\Response(
     *          response=200,
     *          description="Dowload file",
     *          @OA\JsonContent(
     *
     *
     *          ),
     *    ),
     *      @OA\Response(
     *            response=403,
     *            description="Not Allowed"
     *      )
     *
     *)
     */
        public function getCode()
        {

            $file = Storage::path('public/code.ino');

            return response()->download($file, 'code.ino', ['Content-Type' => 'application/octet-stream']);

        }

}
