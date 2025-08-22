<?php

use App\Http\Controllers\Admin\AuthController;
// use App\Http\Controllers\Admin\MaterialController;
// use App\Http\Controllers\Admin\StudyMaterialController;
use App\Http\Controllers\Admin\UserController;
// use App\Http\Controllers\AuthController as ControllersAuthController;
// use App\Http\Controllers\Site\HomeController;
// use App\Http\Controllers\Site\MaterialController as SiteMaterialController;
// use App\Http\Controllers\User\UserController as UserUserController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




// Route::get('/', [HomeController::class, 'index']);
Route::prefix('admin')->group(function() {
    Route::post('/login', [AuthController::class, 'login']);
});
// Route::post('/login', [ControllersAuthController::class, 'login']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::prefix('users')->group(function() {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/create', [UserController::class, 'store']);
        Route::put('/{id}', [UserController::class, 'update']);
        Route::get('/{id}', [UserController::class, 'show']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
        Route::get('/export/data', [UserController::class, 'export']);

    });
    // Route::prefix('materials')->group(function() {
    //     Route::get('/', [MaterialController::class, 'index']);
    //     Route::post('/create', [MaterialController::class, 'store']);
    //     Route::put('/{id}', [MaterialController::class, 'update']);
    //     Route::get('/{id}', [MaterialController::class, 'show']);
    //     Route::delete('/{id}', [MaterialController::class, 'destroy']);

    // });
    // Route::prefix('studyMaterials')->group(function() {
    //     Route::get('/', [StudyMaterialController::class, 'index']);
    //     Route::get('/materials', [StudyMaterialController::class, 'materials']);
    //     Route::post('/create', [StudyMaterialController::class, 'store']);
    //     Route::put('/{id}', [StudyMaterialController::class, 'update']);
    //     Route::get('/{id}', [StudyMaterialController::class, 'show']);
    //     Route::delete('/{id}', [StudyMaterialController::class, 'destroy']);
    // });
    Route::post('/logout', [AuthController::class, 'logout']);
});
// Route::middleware('auth:sanctum')->prefix('user')->group(function () {
//     Route::get('/profile', [UserUserController::class, 'profile']);
//     Route::put('/profile', [UserUserController::class, 'update']);
// });
