<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\AdminController;
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

Route::prefix('v1')->group(function () {
    Route::prefix('auth')->group(function () {
        // Below mention routes are public, user can access those without any restriction.
        // Create New User
        Route::post('register', [AuthController::class, 'register']);
        // Login User
        Route::post('login', [AuthController::class, 'login']);
        
        // Refresh the JWT Token
        Route::get('refresh', [AuthController::class, 'refresh']);
        
        // Below mention routes are available only for the authenticated users.
        Route::middleware('auth:api')->group(function () {
            // Get user info
            Route::get('user', [AuthController::class, 'user']);
            // Logout user from application
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware('auth:api')->prefix('gallery')->group(function () {
        Route::post('user/genres', [GalleryController::class, 'updateUserGenres']);
        Route::get('user/feed', [GalleryController::class, 'getUserFeed']);
        Route::get('images', [GalleryController::class, 'getImages']);
    });
});

Route::prefix('v2')->group(function () {
    Route::prefix('auth')->group(function () {
        // Below mention routes are public, user can access those without any restriction.
        // Create New User
        Route::post('register', [AuthController::class, 'register']);
        // Login User
        Route::post('login', [AuthController::class, 'loginv2']);
        
        // Refresh the JWT Token
        Route::get('refresh', [AuthController::class, 'refresh']);
        
        // Below mention routes are available only for the authenticated users.
        Route::middleware('auth:api')->group(function () {
            // Get user info
            Route::get('user', [AuthController::class, 'user']);
            // Logout user from application
            Route::post('logout', [AuthController::class, 'logout']);
        });
    });

    Route::middleware(['auth:api', 'isadmin'])->prefix('admin')->group(function () {
        Route::get('users', [AdminController::class, 'getUsers']);
        Route::post('image/modify', [AdminController::class, 'modifyImage']);
        Route::get('image/{id}', [AdminController::class, 'getImage']);
    });

    Route::middleware('auth:api')->prefix('gallery')->group(function () {
        Route::post('user/genres', [GalleryController::class, 'updateUserGenres']);
        Route::get('user/feed', [GalleryController::class, 'getUserFeed']);
        Route::get('images', [GalleryController::class, 'getImages']);
    });
});
