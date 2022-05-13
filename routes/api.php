<?php

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


Route::group(["prefix" => "/api/v1/"],function (){


    Route::group(["prefix" => "/users"],function (){

        Route::get("/",[UserController::class,"index"])->name("users");

        Route::get("/{user}",[UserController::class,"show"])->name("users.show");

        Route::post("/create",[UserController::class,"store"])->name("users.create");

        Route::patch("/{user}/edit",[UserController::class,"update"])->name("users.update");

        Route::delete("/{user}/delete",[UserController::class,"destroy"])->name("users.delete");

    });

});
