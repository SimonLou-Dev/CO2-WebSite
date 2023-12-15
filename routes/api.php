<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\UserController;
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
    Route::apiResource("messages", MessageController::class);


    Route::patch("/user/{role}", [ApiTokenController::class, 'editRole'])->name("users.editRole");

});

Route::get("/test", function() {

    event(new \App\Events\MessageSendedEvent());

    return response()->json([
        'message' => 'User created successfully',
        'user' => "test",
    ], 200);
})->name("test");



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register", [UserController::class, 'register'])->name("register");
Route::post("/login", [UserController::class, 'login'])->name("login");

Route::post("/tokens/create", [ApiTokenController::class, 'create'])->name("api-tokens.create");

Broadcast::routes(['middleware' => ['auth:sanctum']]);

