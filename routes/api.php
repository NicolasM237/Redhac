<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserMobileController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/mobile/users', [UserMobileController::class, 'getUsersMobiles']);        // Liste des utilisateurs
Route::post('/auth/send-otp', [UserMobileController::class,'sendOtp']);
Route::post('/auth/verify-otp', [UserMobileController::class,'verifyOtp']);
Route::post('/auth/update-profile', [UserMobileController::class,'updateProfile']);
