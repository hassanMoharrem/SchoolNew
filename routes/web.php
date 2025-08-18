<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Route::get('/admin/{any?}', function () {
//     return file_get_contents(public_path('admin.html'));
// })->where('any', '.*');

// Route::get('/{any?}', function () {
//     return file_get_contents(public_path('indexSite.html'));
// })->where('any', '.*');
Route::get('/styled-export', [UserController::class, 'export']);
