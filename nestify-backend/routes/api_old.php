<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\AdminController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public property routes (no authentication needed)
Route::get('/properties', [PropertyController::class, 'index']);
Route::get('/properties/search', [PropertyController::class, 'search']);
Route::get('/properties/filter', [PropertyController::class, 'filter']);
Route::get('/properties/suggestions', [PropertyController::class, 'searchSuggestions']);
Route::get('/properties/locations', [PropertyController::class, 'getLocations']);
Route::get('/properties/filter-options', [PropertyController::class, 'getFilterOptions']);
Route::get('/properties/statistics', [PropertyController::class, 'getStatistics']);
Route::get('/properties/{id}', [PropertyController::class, 'show']);

// Protected routes - require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    
    // Client routes (favorites)
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::post('/favorites/{property}/toggle', [FavoriteController::class, 'toggle']);
    Route::get('/favorites/{property}/check', [FavoriteController::class, 'check']);
    Route::delete('/favorites/{property}', [FavoriteController::class, 'destroy']);
    
    // Agency/Admin routes (property management)
    Route::middleware('role:agency,admin')->group(function () {
        Route::get('/my-properties', [PropertyController::class, 'myProperties']);
        Route::post('/properties', [PropertyController::class, 'store']);
        Route::put('/properties/{id}', [PropertyController::class, 'update']);
        Route::delete('/properties/{id}', [PropertyController::class, 'destroy']);
        Route::post('/properties/upload-image', [PropertyController::class, 'uploadImages']);
    });
    
    // Admin-only routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/users', [AdminController::class, 'getUsers']);
        Route::get('/properties', [AdminController::class, 'getProperties']);
        Route::patch('/properties/{id}/toggle-status', [AdminController::class, 'togglePropertyStatus']);
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
        Route::delete('/properties/{id}', [AdminController::class, 'deleteProperty']);
    });
});
