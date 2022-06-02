<?php

use App\Http\Controllers\AlbumsController;
use App\Http\Controllers\ArtistsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\SubcateoryController;
use App\Http\Controllers\TagsController;
use App\Http\Controllers\UserController;
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

Route::group(["prefix" => "/v1"],function (){

    Route::post("/login", LoginController::class)->name("login");


    Route::group(["prefix" => "/users"],function (){

        Route::get("/",[UserController::class,"index"])
            ->middleware("auth:sanctum")
            ->name("users");

        Route::get("/{user}",[UserController::class,"show"])
            ->middleware("auth:sanctum")->name("users.show");

        Route::post("/create",[UserController::class,"store"])
            ->name("users.create");

        Route::patch("/{user}/edit",[UserController::class,"update"])
            ->middleware(["auth:sanctum"])->name("users.update");

        Route::delete("/{user}/delete",[UserController::class,"destroy"])
            ->middleware(["auth:sanctum","is_admin","not_be_empty"])->name("users.delete");

    });




    Route::middleware(["auth:sanctum","is_admin"])
        ->resource("albums", AlbumsController::class);

    Route::middleware(["auth:sanctum","is_admin"])
        ->resource("artists", ArtistsController::class);

    Route::middleware(["auth:sanctum","is_admin"])
        ->resource("posts", PostsController::class);

    Route::middleware(["auth:sanctum","is_admin"])
        ->resource("tags", TagsController::class);

    Route::resource("comments",CommentsController::class);

});
