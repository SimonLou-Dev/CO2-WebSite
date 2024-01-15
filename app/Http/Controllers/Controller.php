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
 * @OA\Schema (
 *  schema="PaginatedResult",
 *  title="Pagination exemple",
 *     @OA\Property (property="total", type="integer"),
 *     @OA\Property (property="current_page", type="integer"),
 *     @OA\Property (property="per_page", type="integer"),
 *     @OA\Property (property="next_page_url", type="string"),
 *     @OA\Property (property="prev_page_url", type="string"),
 *     @OA\Property (property="data", type="array", @OA\Items()),
 * )
 *
 *
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
