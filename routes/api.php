<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminstratorController;
use App\Http\Controllers\AuthController;
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

Route::middleware(['auth:sanctum', "role:admin,coach"])->group( function () {
    Route::apiResource("users", UserController::class);
});

//Registration route
Route::post("user/reg" , [UserController::class, "store"]);
Route::post("coach/reg", [CoachController::class, "store"]);
Route::post("admin/reg", [AdminstratorController::class, "store"]);

//Login route

Route::post("login/{role}", [AuthController::class, "login"])->where("role", "user|admin|coach|.*");
