<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminstratorController;
use App\Http\Controllers\AuthController;




Route::middleware(['auth:sanctum'])->group( function () {
    //admin route
    Route::get("admin", [AdminstratorController::class, "index"])->middleware(["role:admin"]);
    Route::post("admin/user/update", [AdminstratorController::class, "update"])->middleware(["role:admin,coach"]);
    Route::apiResource("users", UserController::class)->middleware(["role:user,admin,coach"]);

    //coach route
    Route::get("coach", [CoachController::class, "index"])->middleware("role:admin,coach");

});

//Registration route
Route::post("user/reg" , [UserController::class, "store"]);
Route::post("coach/reg", [CoachController::class, "store"]);
Route::post("admin/reg", [AdminstratorController::class, "store"]);

//Login route

Route::post("login/{role}", [AuthController::class, "login"])->where("role", "user|admin|coach|.*");
