<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserMobileController;
use App\Http\Controllers\ViolencesController;
use App\Http\Controllers\NatureController;
use App\Http\Controllers\CollecteController;
use Illuminate\Support\Facades\Mail;

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

Route::get('mail', function(Request $request){
    Mail::raw('Hello test', function ($msg) {
        $msg->to('devronaldjunior@gmail.com')
            ->subject('Quick Test');
    });
});


Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/violence', [ViolencesController::class, 'storeApi']);
    Route::put('/violence/{code}', [ViolencesController::class, 'updateApi']);
    Route::get('/violence', [ViolencesController::class, 'listApi']);
    
    Route::get('/nature', [NatureController::class, 'listApi']);
    Route::get('/collecte', [CollecteController::class, 'listApi']);
    Route::post('/auth/update-profile', [UserMobileController::class,'updateProfile']);
});

Route::get('/mobile/users', [UserMobileController::class, 'getUsersMobiles']);        // Liste des utilisateurs
Route::post('/auth/send-otp', [UserMobileController::class,'sendOtp']);
Route::post('/auth/verify-otp', [UserMobileController::class,'verifyOtp']);
