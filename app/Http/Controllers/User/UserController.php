<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Sensor;
use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Annotations\OpenApi as OA;
use Psy\Util\Str;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
        if(!empty($request->user()->id)) return response()->redirectTo("/");


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

        $user->assignRole("user");




        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $user->createToken($request->device_name)->plainTextToken
        ], 201);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
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
        if(!empty($request->user()->id)) return response()->redirectTo("/");

        $request->validate([
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            throw ValidationException::withMessages([
                'password' => ['The provided credentials are incorrect']
            ]);
        }

        foreach ($user->tokens as $token){
            if ($token->name == $request->device_name) $token->delete();
        }

        $user->updated_at = now();

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $user->createToken($request->device_name)->plainTextToken
        ], 200);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     *
     *
     * @OA\Get(
     *     path="/api/user",
     *     summary="Get curent user",
     *     tags={"USER"},
     *     @OA\Response(
     *          response=200,
     *          description="User is logged",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *          ),
     *    ),
     *     @OA\Response(
     *          response=302,
     *          description="Not Authed"
     *    )
     *)
     *
     *
     *
     */
    public function showUser(Request $request){
        $user = User::where("id", $request->user()->id)->firstOrFail();
        $user->updated_at = now();
        return response()->json([
            'user' => $user,
        ], 200);

    }


    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Patch(
     *     path="/logout",
     *     summary="logout a user",
     *     tags={"USER"},
     *          security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *   *      name="Authorization",
     *   *      in="header",
     *   *      required=true,
     *   *      description="Bearer {access-token}",
     *   *      @OA\Schema(
     *   *          type="bearerAuth"
     *   *      )
     *   *     ),
     *     @OA\Response(
     *          response=200,
     *          description="User logged out successfully",
     *    ),
     *     @OA\Response(
     *          response=403,
     *          description="Auth error"
     *    )
     *)
     *
     *
     *
     */
    public function logout(Request $request){

        $request->user()->currentAccessToken()->delete();

        return response()->json();
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Patch(
     *     path="/logout",
     *     summary="logout a user",
     *     tags={"USER"},
     *          security={{"bearerAuth":{}}},
     *       @OA\Parameter(
     *   *      name="Authorization",
     *   *      in="header",
     *   *      required=true,
     *   *      description="Bearer {access-token}",
     *   *      @OA\Schema(
     *   *          type="bearerAuth"
     *   *      )
     *   *     ),
     *     @OA\Response(
     *          response=200,
     *          description="User logged out successfully",
     *    ),
     *     @OA\Response(
     *          response=403,
     *          description="Auth error"
     *    )
     *)
     *
     *
     *
     */

    /**
     * @param Request $request
     *
     *
     * @OA\Patch(
     *     path="/users/{userId}/role/{roleId}",
     *     summary="get all users paginated",
     *     tags={"USER"},
     *     security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *  *      name="Authorization",
     *  *      in="header",
     *  *      required=true,
     *  *      description="Bearer {access-token}",
     *  *      @OA\Schema(
     *  *          type="bearerAuth"
     *  *      )
     *  *     ),
     *
     *      @OA\PathParameter (
     *         name="userId",
     *         description="Id Of selected user",
     *         required=true,
     *      ),
     *      @OA\PathParameter (
     *         name="roleId",
     *         description="Id Of selected role",
     *         required=true,
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="Role changed succesfully",
     *    ),
     *     @OA\Response(
     *          response=403,
     *          description="Auth error"
     *    )
     *)
     */
    public function setRoles(Request $request, User $user, Role $role){

        $this->authorize('user_viewAll', $request->user());


        $user->removeRole($user->roles()->first()->name);
        $user->assignRole($role->name);


        return response()->json();

    }


    /**
     * @param Request $request
     *
     *
     * @OA\Get(
     *     path="/users",
     *     summary="get all users paginated",
     *     tags={"USER"},
     *     security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *  *      name="Authorization",
     *  *      in="header",
     *  *      required=true,
     *  *      description="Bearer {access-token}",
     *  *      @OA\Schema(
     *  *          type="bearerAuth"
     *  *      )
     *  *     ),
     *      @OA\Parameter (
     *          name="search",
     *          in="query",
     *          required=false,
     *          allowEmptyValue=true,
     *          example="michel"
     *      ),
     *     @OA\Response(
     *          response=200,
     *          description="User logged out successfully",
     *          @OA\JsonContent(
     *           allOf={
     *               @OA\Schema (ref="#/components/schemas/PaginatedResult"),
     *               @OA\Schema (
     *                   @OA\Property (property="data", type="array", @OA\Items(ref="#/components/schemas/User")),
     *               ),
     *            }
     *        ),
     *    ),
     *     @OA\Response(
     *          response=403,
     *          description="Auth error"
     *    )
     *)
     */
    public function getAllUsers(Request $request){

        $this->authorize('user_viewAll', $request->user());
        $roles = Role::all();
        $search = $request->get("search");
        $users = User::where("name","LIKE", "%".$search."%")->orWhere("email","LIKE", "%".$search."%")->paginate();

        foreach ($users as $user) {
            if ($user->roles->count() > 0){
                $user->role_id = $user->roles->first()->id;
            }else{
                $user->role_id = 0;
            }


        }


        return response()->json([
            "users" => $users,
            "roles" => $roles
        ]);

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     *
     *
     * @OA\Delete(
     *     path="/users/{userId}",
     *     summary="Delete user",
     *     tags={"USER"},
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
     *        name="userId",
     *        description="Id Of selected user",
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
    public function destroy(User $user)
    {
        $this->authorize('user_delete', $user);

        $user->delete();

        return response()->json();
    }

}
