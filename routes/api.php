<?php

use App\Http\Controllers\V1\AlbumController;
use App\Http\Controllers\V1\ImageResizeController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('v1')->group(function () {
        Route::apiResource('album', AlbumController::class);
        Route::get('image', [ImageResizeController::class, 'index']);
        Route::get('image/by-album/{album}', [ImageResizeController::class, 'byAlbum']);
        Route::get('image/{image}', [ImageResizeController::class, 'show']);
        Route::post('image/resize', [ImageResizeController::class, 'resize']);
        Route::delete('image/{image}', [ImageResizeController::class, 'destroy']);

    });
});


