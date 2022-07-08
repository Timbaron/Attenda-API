<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\HomeController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/course', [CourseController::class, 'index']);
    Route::get('/course/create', [CourseController::class, 'store']);
    Route::get('/course/{id}', [CourseController::class, 'show']);
    Route::post('/course/update/{id}', [CourseController::class, 'update']);
    Route::post('/course/delete/{id}', [CourseController::class, 'destroy']);
});

Route::post('/register', [RegisterController::class,'create']);

Route::post('/login', [LoginController::class, 'login']);
