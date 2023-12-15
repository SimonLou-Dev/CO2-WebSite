<?php

namespace App\Http\Controllers;

use App\Events\MessageSendedEvent;
use App\Models\Message;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class MessageController extends Controller
{

    /**
     * @return Message[]|\Illuminate\Database\Eloquent\Collection|\LaravelIdea\Helper\App\Models\_IH_Message_C
     *
     * @OA\Get(
     *     path="/messages",
     *     summary="Get all messages",
     *     tags={"MESSAGE"},
     *     security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer {access-token}",
     *          @OA\Schema(
     *               type="bearerAuth"
     *          )
     *       ),
     *     @OA\Response(
     *     response=200,
     *     description="List of messages",
     *     @OA\JsonContent(
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Message")
     *    )
     *  )
     * )
     *
     *
     */
    public function index()
    {
        $this->authorize('viewAny', Message::class);

        return Message::all();
    }

    /**
     * @param Request $request
     * @return Message
     *
     * @OA\Post(
     *     path="/messages",
     *     summary="Create a new message",
     *     tags={"MESSAGE"},
     *     security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer {access-token}",
     *          @OA\Schema(
     *               type="bearerAuth"
     *          )
     *       ),
     *     @OA\RequestBody(
     *     required=true,
     *     description="Message data",
     *     @OA\JsonContent(
     *      required={"content", "title"},
     *     @OA\Property(property="messsage", type="string", example="Hello World"),
     *     @OA\Property(property="title", type="string", example="Hello World"),
     *     )
     *    ),
     *     @OA\Response(
     *     response=201,
     *     description="Message created successfully",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="object", ref="#/components/schemas/Message"),
     *     )
     *   ),
     * )
     *
     *
     */
    public function store(Request $request)
    {

        $this->authorize('create', Message::class);

        $request->validate([
            'message' => ['required'],
            'title' => ['required'],
        ]);

        $message = new Message();
        $message->content = $request->message;
        $message->title = $request->title;
        $message->sender_id = auth()->user()->id;
        $message->save();

        MessageSendedEvent::broadcast();

        return response()->json([
            'message' => $message,
        ], 201);

    }


    /**
     * @param Message $message
     * @return Message
     *
     * @OA\Get(
     *     path="/messages/{message}",
     *     summary="Get a message",
     *     tags={"MESSAGE"},
     *     security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer {access-token}",
     *          @OA\Schema(
     *               type="bearerAuth"
     *          )
     *       ),
     *     @OA\Parameter(
     *     name="message",
     *     in="path",
     *     description="Message id",
     *     required=true,
     *     @OA\Schema(
     *     type="integer",
     *     )
     *    ),
     *     @OA\Response(
     *     response=200,
     *     description="Message",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="object", ref="#/components/schemas/Message"),
     *     )
     *   ),
     * )
     *
     *
     */
    public function show(Message $message)
    {

        $this->authorize('view', $message);

        return $message;
    }

    /**
     * @param Request $request
     * @param Message $message
     * @return Message
     *
     * @OA\Put(
     *     path="/messages/{message}",
     *     summary="Update a message",
     *     tags={"MESSAGE"},
     *     security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer {access-token}",
     *          @OA\Schema(
     *               type="bearerAuth"
     *          )
     *       ),
     *     @OA\Parameter(
     *     name="message",
     *     in="path",
     *     description="Message id",
     *     required=true,
     *     @OA\Schema(
     *     type="integer",
     *     )
     *   ),
     *     @OA\RequestBody(
     *     required=true,
     *     description="Message data",
     *     @OA\JsonContent(
     *     required={"message", "title"},
     *     @OA\Property(property="messsage", type="string", example="Hello World"),
     *     @OA\Property(property="title", type="string", example="Hello World"),
     *     )
     *   ),
     *     @OA\Response(
     *     response=200,
     *     description="Message updated successfully",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="object", ref="#/components/schemas/Message"),
     *     )
     *  ),
     *     @OA\Response(
     *     response=422,
     *     description="Validation error"
     * )
     * )
     *
     */

    public function update(Request $request, Message $message)
    {

        $this->authorize('update', $message);

        $request->validate([
            'message' => ['required'],
            'title' => ['required'],
        ]);

        $message = Message::where('id', $message->id)->firstOrFail();
        $message->content = $request->message;
        $message->title = $request->title;
        $message->save();


        return $message;
    }


    /**
     * @param Message $message
     * @return \Illuminate\Http\JsonResponse
     *
     * @OA\Delete(
     *     path="/messages/{message}",
     *     summary="delete a message",
     *     tags={"MESSAGE"},
     *     security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *          name="Authorization",
     *          in="header",
     *          required=true,
     *          description="Bearer {access-token}",
     *          @OA\Schema(
     *               type="bearerAuth"
     *          )
     *       ),
     *     @OA\Parameter(
     *     name="message",
     *     in="path",
     *     description="Message id",
     *     required=true,
     *     @OA\Schema(
     *     type="integer",
     *     )
     *    ),
     *     @OA\Response(
     *     response=200,
     *     description="Message",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="object", ref="#/components/schemas/Message"),
     *     )
     *   ),
     * )
     *
     *
     */
    public function destroy(Message $message)
    {
        $this->authorize('delete', $message);

        $message->delete();

        return response()->json();
    }
}
