<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\StageController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Teacher\AttachmentController;
use App\Http\Controllers\Teacher\AuthController as TeacherAuthController;
use App\Http\Controllers\Teacher\StageController as TeacherStageController;
use App\Http\Controllers\Teacher\WeekController;
use App\Http\Controllers\User\AuthController as UserAuthController;
use App\Models\StageSubjectTeacher;
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
    });

    Route::prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index']);
        Route::post('/create', [TeacherController::class, 'store']);
        Route::put('/{id}', [TeacherController::class, 'update']);
        Route::get('/{id}', [TeacherController::class, 'show']);
        Route::delete('/{id}', [TeacherController::class, 'destroy']);
    });

    Route::prefix('stages')->group(function () {
        Route::get('/', [StageController::class, 'index']);
        Route::post('/create', [StageController::class, 'store']);
        Route::put('/{id}', [StageController::class, 'update']);
        Route::get('/{id}', [StageController::class, 'show']);
        Route::delete('/{id}', [StageController::class, 'destroy']);
    });
    Route::prefix('subjects')->group(function () {
        Route::get('/', [SubjectController::class, 'index']);
        Route::post('/create', [SubjectController::class, 'store']);
        Route::put('/{id}', [SubjectController::class, 'update']);
        Route::get('/{id}', [SubjectController::class, 'show']);
        Route::delete('/{id}', [SubjectController::class, 'destroy']);
        Route::get('/stages/list', [SubjectController::class, 'showStages']);
    });
    Route::post('/logout', [AuthController::class, 'logout']);
});




Route::prefix('teacher')->group(function() {
    Route::post('/login', [TeacherAuthController::class, 'login']);
    Route::post('/register', [TeacherAuthController::class, 'register']);

});
Route::middleware('auth:sanctum')->prefix('teacher')->group(function () {
    Route::post('/logout', [TeacherAuthController::class, 'logout']);
    Route::prefix('stages')->group(function () {
        Route::get('/', [TeacherStageController::class, 'index']);
        Route::get('/{id}/subjects', [TeacherStageController::class, 'getSubjects']);
    });

    Route::prefix('stage-subject-teacher')->group(function () {
        Route::get('/', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'index']);
        Route::post('/create', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'store']);
        Route::get('/{id}', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'show']);
        Route::get('/show/{id}', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'showSubject']);
        Route::put('/{id}', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'update']);
        Route::delete('/delete', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'destroy']);
        Route::delete('/{id}', [\App\Http\Controllers\Teacher\StageSubjectTeacherController::class, 'destroySubscribe']);
    });

    Route::prefix('weeks')->group(function () {
        Route::get('/', [WeekController::class, 'index']);
        Route::post('/create', [WeekController::class, 'store']);
        Route::put('/{id}', [WeekController::class, 'update']);
        Route::get('/{id}', [WeekController::class, 'show']);
        Route::delete('/{id}', [WeekController::class, 'destroy']);
    });

    Route::prefix('attachments')->group(function () {
        Route::get('/', [AttachmentController::class, 'index']);
        Route::post('/create', [AttachmentController::class, 'store']);
        Route::put('/{id}', [AttachmentController::class, 'update']);
        Route::get('/{id}', [AttachmentController::class, 'show']);
        Route::delete('/{id}', [AttachmentController::class, 'destroy']);
    });

});


Route::prefix('user')->group(function() {
    Route::post('/login', [UserAuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->prefix('user')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout']);
    //
});
