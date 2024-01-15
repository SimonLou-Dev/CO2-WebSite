<?php

namespace App\Http\Controllers\Room;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Sensor;
use Illuminate\Http\Request;

class RoomController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/rooms",
     *     summary="Get All room",
     *     tags={"rooms"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *      name="Authorization",
     *      in="header",
     *      required=true,
     *      description="Bearer {access-token}",
     *      @OA\Schema(
     *          type="bearerAuth"
     *      )
     *     ),
     *     @OA\Parameter (
     *         name="search",
     *         in="query",
     *         required=false,
     *         allowEmptyValue=true,
     *         example="L222"
     *     ),
     *     @OA\Parameter (
     *          name="page",
     *          in="query",
     *          required=false,
     *          allowEmptyValue=true,
     *          example="1"
     *      ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Room retrieve succesfully",
     *          @OA\JsonContent(
     *              @OA\Property (title="data", type="array", @OA\Items(ref="#/components/schemas/Room")),
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
        //$this->authorize('viewAny', Room::class);

        return Room::all();
    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Post(
     *     path="/rooms",
     *     summary="Add new Room",
     *     tags={"rooms"},
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
     *         description="Room data",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="L222"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="Sensor created succesfully",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Room",
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
        $this->authorize('create', Room::class);

        $data = $request->validate([
            'name' => ['string','unique:rooms'],
        ]);


        return Room::create($data);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Get(
     *     path="/rooms/{roomId}",
     *     summary="Update Room",
     *     tags={"rooms"},
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
     *        name="roomId",
     *        description="Id Of selected room",
     *        required=true,
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Room updated succesfully",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Room",
     *          ),
     *    ),
     *
     *       @OA\Response(
     *             response=403,
     *             description="Not Allowed"
     *       )
     *
     *)
     */
    public function show(Room $room)
    {
        $this->authorize('view', $room);

        return $room;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Put(
     *     path="/rooms/{roomId}",
     *     summary="Update Room",
     *     tags={"rooms"},
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
     *        name="roomId",
     *        description="Id Of selected room",
     *        required=true,
     *     ),
     *
     *     @OA\RequestBody(
     *          required=true,
     *          description="Room data",
     *           @OA\JsonContent(
     *               required={"name"},
     *               @OA\Property(property="name", type="string", example="L222"),
     *           )
     *       ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Room updated succesfully",
     *          @OA\JsonContent(
     *              ref="#/components/schemas/Room",
     *          ),
     *    ),
     *     @OA\Response(
     *           response=422,
     *           description="Validation error"
     *     ),
     *       @OA\Response(
     *             response=403,
     *             description="Not Allowed"
     *       )
     *
     *
     *
     *)
     */
    public function update(Request $request, Room $room)
    {
        $this->authorize('update', $room);

        $data = $request->validate([
            'name' => ['string', 'unique:rooms'],
        ]);

        $room->update($data);

        return $room;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Delete (
     *     path="/rooms/{roomId}",
     *     summary="Update Room",
     *     tags={"rooms"},
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
     *        name="roomId",
     *        description="Id Of selected room",
     *        required=true,
     *     ),
     *
     *     @OA\Response(
     *          response=200,
     *          description="Room deleted succesfully",
     *    ),
     *       @OA\Response(
     *             response=403,
     *             description="Not Allowed"
     *       )
     *
     *)
     */
    public function destroy(Room $room)
    {
        $this->authorize('delete', $room);

        if(Sensor::where("room_id", $room->id)->count() == 0){
            $room->forceDelete();
        }else {
            $room->delete();
        }

        return response()->json();
    }
}
