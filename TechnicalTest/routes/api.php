<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:api')->group(function () {
    Route::post('/tasks', [TaskController::class, 'store'])->middleware('role:manager');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->middleware('role:manager');
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::post('/task/dependency/{task}', [TaskController::class, 'addDependency'])->middleware('role:manager');
});