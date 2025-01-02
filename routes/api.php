<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);



Route::get('/profile/{id}', [ProfileController::class, 'getProfile']);
//Route::put('/profile-update/{id}', [ProfileController::class, 'updateProfile'])->middleware('auth:sanctum');

Route::get('/profile-fetch/{id}', [ProfileController::class, 'getProfileforEdit']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/profile-update/{id}', [ProfileController::class, 'updateProfile']);
    Route::post('/postsp', [PostController::class, 'store']);
    Route::get('/posts', [PostController::class, 'index']);
});
