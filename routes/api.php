<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CoachController;
use App\Http\Controllers\AdminstratorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TreningProgramController;




Route::middleware(['auth:sanctum'])->group( function () {
    //admin routes
    Route::apiResource("admin", AdminstratorController::class)->middleware(["role:admin"]);

    //user routes
    Route::apiResource("users", UserController::class)->middleware(["role:user,admin,coach"]);

    //coach routes
    Route::get("coach", [CoachController::class, "index"])->middleware("role:admin,coach,user");
    Route::get("coach/{id}", [CoachController::class, "show"])->middleware("role:admin,coach");
    Route::put("coach/update/{id}", [CoachController::class, "update"])->middleware("role:admin,coach");

    //trening program
    Route::get("trening_program", [TreningProgramController::class, "index"])->middleware("role:admin,coach,user");

});

//Registration routes
Route::post("user/reg" , [UserController::class, "store"]);
Route::post("coach/reg", [CoachController::class, "store"]);
Route::post("admin/reg", [AdminstratorController::class, "store"]);

//Login route

Route::post("login/{role}", [AuthController::class, "login"])->where("role", "user|admin|coach|.*");
