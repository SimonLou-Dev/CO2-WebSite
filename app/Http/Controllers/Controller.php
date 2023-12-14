<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
/**
 *
 * @OA\Info(
 *       version="1.0.0",
 *       title="Laravel API",
 *       description="Laravel API OpenApi description"
 *  )
 * @OA\Server(
 *      url="http://localhost:8000/api",
 *      description="Laravel API Server"
 *  )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     in="header",
 *     name="Authorization",
 * ),
 *
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
