<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Annotations\OpenApi as OA;
class ApiTokenController extends Controller
{



    /**
     * @param Request $request
     * @return array
     *
     * @OA\Post(
     *     path="/tokens/create",
     *     tags={"API"},
     *     @OA\Response(
     *         response="200",
     *         description="Token created successfully",
     *     )
     *
     * )
     *
     */
    public function create(Request $request)
    {
        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken];
    }
}
