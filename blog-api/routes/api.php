<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{slug}', [PostController::class, 'show']);
Route::get('/posts/filter/search', [PostController::class, 'filtered']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/authors', [PostController::class, 'getAuthors']);

// Authentication Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // User & Profile
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Posts Management
    Route::post('/admin/posts', [PostController::class, 'store']);
    Route::get('/admin/posts', [PostController::class, 'manage']);
    Route::get('/admin/posts/{id}', [PostController::class, 'show']); // For editing
    Route::put('/admin/posts/{id}', [PostController::class, 'update']);
    Route::delete('/admin/posts/{id}', [PostController::class, 'destroy']);

    // Users Management 
    Route::get('/admin/users', [UserController::class, 'index']);
    Route::post('/admin/users', [UserController::class, 'store']);
    Route::get('/admin/users/{id}', [UserController::class, 'show']);
    Route::put('/admin/users/{id}', [UserController::class, 'update']);
    Route::delete('/admin/users/{id}', [UserController::class, 'destroy']);
    Route::put('/admin/users/{id}/password', [UserController::class, 'updatePassword']);

    // Categories Management
    Route::post('/admin/categories', [CategoryController::class, 'store']);
    Route::put('/admin/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/admin/categories/{id}', [CategoryController::class, 'destroy']);

    // Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});