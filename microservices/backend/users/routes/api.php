<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware(['auth:api'])->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::put('users/info', [AuthController::class, 'updateInfo']);
    Route::put('users/password', [AuthController::class, 'updatePassword']);

    Route::apiResource('users', UserController::class);

    Route::get('admin', [AuthController::class, 'authenticated'])->middleware('scope:admin');
    Route::get('influencer', [AuthController::class, 'authenticated'])->middleware('scope:influencer');
});

//Route::prefix('influencer')->group(function() {
//
//    Route::post('login', [AuthController::class, 'login']);
//    Route::post('register', [AuthController::class, 'register']);
//    Route::post('logout', [AuthController::class, 'logout']);
//
//    Route::middleware(['auth:api', 'scope:influencer'])->group(function() {
//        Route::get('user', [AuthController::class, 'user']);
//        Route::put('users/info', [AuthController::class, 'updateInfo']);
//        Route::put('users/password', [AuthController::class, 'updatePassword']);
//    });
//});
