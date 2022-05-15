<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::group(["prefix" => "/v1"],function (){

    Route::post("/login", LoginController::class)->name("login");


    Route::group(["prefix" => "/users"],function (){

        Route::get("/",[UserController::class,"index"])
            ->middleware("auth:sanctum")
            ->name("users");

        Route::get("/{user}",[UserController::class,"show"])
            ->middleware("auth:sanctum")->name("users.show");

        Route::post("/create",[UserController::class,"store"])
            ->middleware(["auth:sanctum","is_admin"])->name("users.create");

        Route::patch("/{user}/edit",[UserController::class,"update"])
            ->middleware(["auth:sanctum"])->name("users.update");

        Route::delete("/{user}/delete",[UserController::class,"destroy"])
            ->middleware(["auth:sanctum","is_admin"])->name("users.delete");

    });

    Route::group(["prefix" => "/categories","middleware" => "auth:sanctum"],function (){

        Route::get("/",[CategoryController::class,"index"])->name("categories");

        Route::get("/{category}",[CategoryController::class,"show"])->name("categories.show");

        Route::post("/create",[CategoryController::class,"store"])->name("categories.create");

        Route::patch("/{category}/edit",[CategoryController::class,"update"])->name("categories.update");

        Route::delete("/{category}/delete",[CategoryController::class,"destroy"])->name("categories.delete");
    });

});
