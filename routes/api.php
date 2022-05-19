<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\SubcateoryController;
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

    Route::group(["prefix" => "/categories","middleware" => "auth:sanctum"],function (){

        Route::get("/",[CategoryController::class,"index"])
            ->middleware("is_admin")->name("categories");

        Route::get("/{category}",[CategoryController::class,"show"])
            ->middleware("is_admin")->name("categories.show");

        Route::post("/create",[CategoryController::class,"store"])
            ->middleware("is_admin")->name("categories.create");

        Route::patch("/{category}/edit",[CategoryController::class,"update"])
            ->middleware("is_admin")->name("categories.update");

        Route::delete("/{category}/delete",[CategoryController::class,"destroy"])
            ->middleware("is_admin")->name("categories.delete");
    });

    Route::group(["prefix" => "/sub_categories","middleware" => "auth:sanctum"],function (){
         Route::get("/",[SubcateoryController::class,"index"])
             ->middleware("is_admin")->name("sub_categories");

         Route::get("/{subcategory}",[SubcateoryController::class,"show"])
             ->middleware("is_admin")->name("sub_categories.show");

         Route::post("/create",[SubcateoryController::class,"store"])
             ->middleware("is_admin")->name("sub_categories.create");

         Route::patch("/{subcategory}/edit",[SubcateoryController::class,"update"])
             ->middleware("is_admin")->name("sub_categories.update");

         Route::delete("/{subcategory}/delete",[SubcateoryController::class,"destroy"])
             ->middleware("is_admin")->name("sub_categories.delete");
    });


    Route::middleware(["auth:sanctum","is_admin"])
        ->resource("posts", PostsController::class);
});
