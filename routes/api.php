<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\SubjectController;
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
Route::get('subject', [SubjectController::class, 'index']);


/* AUTHENTICATED USERS ONLY */
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user/delete', [UserController::class, 'delete']);

    /* ADMIN ONLY */
    Route::middleware('is:admin')->group(function () {
        Route::group(['prefix' => 'subject'], function () {
            Route::post('/', [SubjectController::class, 'store']);
            Route::post('/update', [SubjectController::class, 'update']);
            Route::post('/delete', [SubjectController::class, 'destroy']);
        });
    });
});

Route::group(['prefix' => 'user'], function () {
    Route::get('/', [UserController::class, 'index']);
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::group(['prefix' => 'group'], function () {
    Route::get('/', [GroupController::class, 'index']);
    Route::post('/', [GroupController::class, 'store']);
});
