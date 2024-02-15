<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Room\RoomController;

use App\Http\Controllers\Sensor\MeasuresController;
use App\Http\Controllers\Sensor\MesuresController;
use App\Http\Controllers\Sensor\ChirpstackController;
use App\Http\Controllers\Sensor\SensorController;
use App\Http\Controllers\User\ApiTokenController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
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

//Authed routes
Route::middleware('auth:sanctum')->group(function() {

    /* Key management */
    Route::put('/chirpstack/keys', [ChirpstackController::class, "setKeys"]);


    /* User Auth */
    Route::patch("/logout", [UserController::class, "logout"]);

    /* User management */
    Route::delete("/users/{user}", [UserController::class, "destroy"]);
    Route::get("/users", [UserController::class, "getAllUsers"]);
    Route::get("/user", [UserController::class, "showUser"]);
    Route::patch("/users/{user}/role/{role}", [UserController::class, "setRoles"]);


    /* Room management */
    Route::get("/rooms/{room}", [RoomController::class, "show"]);
    Route::post("/rooms", [RoomController::class, "store"]);
    Route::put("/rooms/{room}", [RoomController::class, "update"]);
    Route::delete("/rooms/{room}", [RoomController::class, "destroy"]);

    /* Sensor management */
    Route::get("/sensors", [SensorController::class, "index"]);
    Route::post("/sensors", [SensorController::class, "store"]);
    Route::put("/sensors/{sensor}", [SensorController::class, "update"]);
    Route::delete("/sensors/{sensor}", [SensorController::class, "destroy"]);
});





/* Public routes */

/* Index and show room */
Route::get("/rooms", [RoomController::class, "index"]);
Route::get("/sensors/{sensor}", [SensorController::class, "show"]);

/* Retrieve qrCode, measures & heatmap */
Route::get("/sensors/{sensor}/qrcode", [SensorController::class, "getQrCode"]);
Route::get("/sensors/{sensor}/measures", [MeasuresController::class, "getMesures"]);
Route::get("/sensors/{sensor}/heatmap", [MeasuresController::class, "getHeatmap"]);

/* Auth routes */
Route::post("/register", [UserController::class, 'register'])->name("register");
Route::post("/login", [UserController::class, 'login'])->name("login");

/* Token & csrf */
Route::get("/csrf", [CsrfCookieController::class, "show"])->name("custom-csrf");

//Health
Route::get("/health", [HomeController::class, 'getHealth'])->name("api-health");

Route::get('/test', function (Request $request) {
    \App\Events\UpdateGraphEvent::dispatch(2);

    return "Send";
});

