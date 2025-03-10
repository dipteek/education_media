<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\YouTubeVideo;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::get('/profile/{id}', [ProfileController::class, 'getProfile']);
//Route::put('/profile-update/{id}', [ProfileController::class, 'updateProfile'])->middleware('auth:sanctum');

Route::get('/profile-fetch/{id}', [ProfileController::class, 'getProfileforEdit']);
Route::get('/courses', [CourseController::class, 'index']); // 
Route::get('/courses/by/user/{user_id}', [CourseController::class, 'getByUsers']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store']);
    Route::get('/courses/{course_id}/videos', [VideoController::class, 'index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile-update/{id}', [ProfileController::class, 'updateProfile']);
    Route::post('/postsp', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'index']);
    

    

    Route::post('/videos', [VideoController::class, 'store']);
    

    
});

Route::get('/youtube', [YouTubeVideo::class, 'getEducationalVideos']);