<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\JobApplicationController;
use App\Http\Controllers\Api\JobController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/verify-code',  [AuthController::class, 'verifyCode'])->name('verify-code');
    Route::post('/resend-verification-email', [AuthController::class, 'resendVerificationEmail']);
});

Route::middleware('auth:api')->group(function () {
    Route::apiResource('jobs', JobController::class)->middleware('can:is-admin');
    Route::post('/job-applications', [JobApplicationController::class, 'store']);
});
