<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations as OA;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Post(
     *     path="/register",
     *     summary="Register a new user",
     *     tags={"USER"},
 *          @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *          @OA\JsonContent(
     *              required={"name", "email", "password, password_confirmation"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="a@b.c"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *              @OA\Property(property="password_confirmation", type="string", example="123456"),
     *              @OA\Property(property="device_name", type="string", example="johns-iphone"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=201,
     *          description="User created successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User created successfully"),
     *              @OA\Property(property="token", type="string", example="token"),
     *              @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *          ),
     *    ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error"
     *    )
     *)
     *
     *
     *
     */

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'device_name' => 'required',
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password= Hash::make($request->password);
        $user->save();

        $user->assignRole('user');




        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken($request->device_name)->plainTextToken
        ], 201);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Post(
     *     path="/login",
     *     summary="Login a user",
     *     tags={"USER"},
     *          @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *          @OA\JsonContent(
     *              required={"email", "password, password_confirmation"},
     *              @OA\Property(property="email", type="string", example="a@b.c"),
     *              @OA\Property(property="password", type="string", example="123456"),
     *              @OA\Property(property="device_name", type="string", example="johns-iphone"),
     *          )
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="User logged successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User logged in successfully"),
     *              @OA\Property(property="token", type="string", example="token"),
     *              @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *          ),
     *    ),
     *     @OA\Response(
     *          response=422,
     *          description="Validation error"
     *    )
     *)
     *
     *
     *
     */
    public function login(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect']
            ]);
        }

        foreach ($user->tokens as $token){
            if ($token->name == $request->device_name) $token->delete();
        }

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $user->createToken($request->device_name)->plainTextToken
        ], 200);

    }

}
