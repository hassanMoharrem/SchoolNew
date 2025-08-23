<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
});
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/create', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/export/data', [UserController::class, 'export']);

    });
    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
        Route::post('/create', [TeacherController::class, 'store']);
        Route::put('/{id}', [TeacherController::class, 'update']);
        Route::get('/{id}', [TeacherController::class, 'show']);
        Route::delete('/{id}', [TeacherController::class, 'destroy']);
        Route::get('/export/data', [TeacherController::class, 'export']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});




Route::prefix('teacher')->group(function() {
    Route::post('/login', [TeacherAuthController::class, 'login']);
});
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->prefix('teacher')->group(function () {
    
    Route::post('/logout', [TeacherAuthController::class, 'logout']);
    //
});


Route::prefix('user')->group(function() {
    Route::post('/login', [UserAuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout']);
    //
});
