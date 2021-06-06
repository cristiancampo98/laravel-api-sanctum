<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Middleware\CanLogin;
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

Route::middleware('auth:sanctum')->get('profile', function (Request $request) {
    return $request->user();
});

Route::post('login', [LoginController::class, 'authenticate'])->middleware(CanLogin::class);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'register']);

Route::group( ['middleware' => ['auth:sanctum',CanLogin::class]], function() {
    Route::get('profile', [ProfileController::class, 'profile']);
    Route::post('change-password', [ProfileController::class, 'changePassword']);
    Route::put('suspend/{user}', [UserController::class, 'suspend']);
    Route::post('validate', [UserController::class, 'validateCovid']);
    Route::apiResources([
        'user' => UserController::class,
    ]);
});

Route::fallback(function(){
    return response()->json([
        'message' => 'Page Not Found. If error persists, contact info@website.com'], 404);
});

