<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\API\V1\Auth\RegisterController;
use App\Domains\Users\PhoneVerification\VerifyPhoneController;
use App\Domains\Users\Controllers\Auth\LoginController;
use App\Domains\Users\Controllers\Password\PasswordController;
use App\Domains\Freight\FreightController;
use App\Domains\Users\Controllers\Auth\RegisterController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'auth'], function () {
    Route::post('/signup', [RegisterController::class, 'signUp']);
    Route::post('/verify',[VerifyPhoneController::class ,'verifyPhone']);
    Route::post('/resend', [VerifyPhoneController::class, 'reSendVerificationCode']);

    Route::post('/signin', [LoginController::class, 'signIn']);
    Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

    Route::post('/refresh-token', [LoginController::class, 'refreshToken'])->middleware('auth:sanctum');
    
    Route::post('/forgot-password', [PasswordController::class, 'forgotPassword']);
    Route::post('/reset-password', [PasswordController::class, 'resetPassword']);
    Route::post('/change-password', [PasswordController::class, 'changePassword'])->middleware('auth:sanctum');
    Route::post('/verify-reset-password', [PasswordController::class, 'verifyForResetPassword']);
});

Route::group(['prefix' => 'freights'], function () { 
    Route::post('/', [FreightController::class, 'store'])->middleware('auth:sanctum');
    Route::put('/{uuid}', [FreightController::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{uuid}', [FreightController::class, 'destroy'])->middleware('auth:sanctum');
    Route::get('/{uuid}', [FreightController::class, 'show']);
    Route::get('/', [FreightController::class, 'index']);
    Route::get('/search', [FreightController::class, 'searchFreight']);
});
