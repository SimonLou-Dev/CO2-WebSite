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
    //Route::apiResource("messages", MessageController::class);
    Route::post("/messages", [MessageController::class, 'store'])->name("messages.store");
    Route::get("/messages", [MessageController::class, 'index'])->name("messages.index");
    Route::get("/messages/{message}", [MessageController::class, 'show'])->name("messages.show");
    Route::patch("/messages/{message}", [MessageController::class, 'update'])->name("messages.update");
    Route::delete("/messages/{message}", [MessageController::class, 'destroy'])->name("messages.destroy");




    Route::patch("/user/{role}", [ApiTokenController::class, 'editRole'])->name("users.editRole");

});

Route::get("/test", function() {

    event(new \App\Events\MessageSendedEvent());

    return "Hello, world!";
})->name("test");



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("/register", [UserController::class, 'register'])->name("register");
Route::post("/login", [UserController::class, 'login'])->name("login");

Route::post("/tokens/create", [ApiTokenController::class, 'create'])->name("api-tokens.create");

Broadcast::routes(['middleware' => ['auth:sanctum']]);

