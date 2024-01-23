<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Room\RoomController;
use App\Http\Controllers\Sensor\MesuresController;
use App\Http\Controllers\Sensor\SensorController;
use App\Http\Controllers\User\ApiTokenController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

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

Broadcast::routes(['middleware' => ['auth:sanctum']]);
Route::middleware('auth:sanctum')->group(function() {

    Route::patch("/logout", [UserController::class, "logout"]);
    Route::get("/users", [UserController::class, "getAllUsers"]);
    Route::delete("/users/{user}", [UserController::class, "destroy"]);

    Route::apiResource("/sensors", SensorController::class);
    Route::apiResource('/rooms', RoomController::class);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get("/sensors/{sensor}/mesures", [MesuresController::class, "getMesures"]);

});



Route::get("/sensors/{sensor}/qrcode", [SensorController::class, "getQrCode"]);

//Auth
Route::post("/register", [UserController::class, 'register'])->name("register");
Route::post("/login", [UserController::class, 'login'])->name("login");

Route::get("/csrf", [CsrfCookieController::class, "show"])->name("custom-csrf");

Route::post("/tokens/create", [ApiTokenController::class, 'create'])->name("api-tokens.create");

//Health
Route::get("/health", [HomeController::class, 'getHealth'])->name("api-health");
Route::get('/test-mqtt', [HomeController::class, 'testMqtt'])->name("test-mqtt");



