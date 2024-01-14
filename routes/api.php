<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Room\RoomController;
use App\Http\Controllers\Sensor\SensorController;
use App\Http\Controllers\User\ApiTokenController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function() {

    Route::patch("/user/{role}", [ApiTokenController::class, 'editRole'])->name("users.editRole");

});

Route::apiResource("/sensors", SensorController::class);
Route::apiResource('/rooms', RoomController::class);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register", [UserController::class, 'register'])->name("register");
Route::post("/login", [UserController::class, 'login'])->name("login");

Route::post("/tokens/create", [ApiTokenController::class, 'create'])->name("api-tokens.create");

Route::get("/health", [HomeController::class, 'getHealth'])->name("api-health");
Route::get('/test-mqtt', [HomeController::class, 'testMqtt'])->name("test-mqtt");

Broadcast::routes(['middleware' => ['auth:sanctum']]);

