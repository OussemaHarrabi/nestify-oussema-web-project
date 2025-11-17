<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PromoterController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\PropertyController;
use App\Http\Controllers\Api\LeadController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PublicController;

/*
|--------------------------------------------------------------------------
| API Routes - Nestify Platform  
|--------------------------------------------------------------------------
*/

// ========================================
// Authentication Routes
// ========================================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/profile-picture', [AuthController::class, 'updateProfilePicture']);
});

// ========================================
// Promoter Routes
// ========================================
Route::prefix('promoter')->middleware(['auth:sanctum', 'user.type:promoter'])->group(function () {
    // Profile
    Route::get('/profile', [PromoterController::class, 'profile']);
    Route::put('/profile', [PromoterController::class, 'updateProfile']);
    Route::post('/logo', [PromoterController::class, 'uploadLogo']);
    Route::get('/dashboard', [PromoterController::class, 'dashboard']);
    Route::get('/stats', [PromoterController::class, 'dashboard']); // Alias for dashboard
    
    // Projects - MUST BE BEFORE /{id} routes to avoid conflicts
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    
    // Project with ID routes - Order matters!
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/projects/{id}', [ProjectController::class, 'update'])->where('id', '[0-9]+'); // Use POST for update with files
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->where('id', '[0-9]+');
    Route::patch('/projects/{id}/publish', [ProjectController::class, 'togglePublish'])->where('id', '[0-9]+');
    Route::post('/projects/{id}/upload-brochure', [ProjectController::class, 'uploadBrochure'])->where('id', '[0-9]+');
    Route::post('/projects/{id}/upload-gallery', [ProjectController::class, 'uploadGallery'])->where('id', '[0-9]+');
    Route::post('/projects/{id}/upload-floor-plans', [ProjectController::class, 'uploadFloorPlans'])->where('id', '[0-9]+');
    Route::delete('/projects/{id}/images/{imageUrl}', [ProjectController::class, 'deleteImage']);
    
    // Properties
    Route::get('/properties', [PropertyController::class, 'index']);
    Route::post('/projects/{projectId}/properties', [PropertyController::class, 'store'])->where('projectId', '[0-9]+');
    Route::get('/properties/{id}', [PropertyController::class, 'show'])->where('id', '[0-9]+');
    Route::post('/properties/{id}', [PropertyController::class, 'update'])->where('id', '[0-9]+'); // Use POST for update with files
    Route::delete('/properties/{id}', [PropertyController::class, 'destroy'])->where('id', '[0-9]+');
    Route::patch('/properties/{id}/availability', [PropertyController::class, 'updateAvailability'])->where('id', '[0-9]+');
    Route::post('/properties/{id}/upload-images', [PropertyController::class, 'uploadImages'])->where('id', '[0-9]+');
    Route::delete('/properties/{id}/images/{imageUrl}', [PropertyController::class, 'deleteImage']);
    
    // Leads
    Route::get('/leads', [LeadController::class, 'index']);
    Route::get('/leads/stats', [LeadController::class, 'stats']); // MUST BE BEFORE /{id}
    Route::get('/leads/{id}', [LeadController::class, 'show'])->where('id', '[0-9]+');
    Route::patch('/leads/{id}/status', [LeadController::class, 'updateStatus'])->where('id', '[0-9]+');
    Route::patch('/leads/{id}/priority', [LeadController::class, 'updatePriority'])->where('id', '[0-9]+');
    Route::put('/leads/{id}/notes', [LeadController::class, 'updateNotes'])->where('id', '[0-9]+');
    Route::delete('/leads/{id}', [LeadController::class, 'destroy'])->where('id', '[0-9]+');
});

// ========================================
// Lead Submission (PUBLIC)
// ========================================
Route::post('/leads', [LeadController::class, 'store']);

// ========================================
// Admin Routes
// ========================================
Route::prefix('admin')->middleware(['auth:sanctum', 'user.type:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard']);
    
    // Promoters
    Route::get('/promoters', [AdminController::class, 'promoters']);
    Route::get('/promoters/{id}', [AdminController::class, 'promoterDetails'])->where('id', '[0-9]+');
    Route::patch('/promoters/{id}/verify', [AdminController::class, 'verifyPromoter'])->where('id', '[0-9]+');
    
    // Projects
    Route::get('/projects', [AdminController::class, 'projects']);
    Route::get('/projects/{id}', [AdminController::class, 'projectDetails'])->where('id', '[0-9]+');
    Route::patch('/projects/{id}/publish', [AdminController::class, 'publishProject'])->where('id', '[0-9]+');
    Route::patch('/projects/{id}/unpublish', [AdminController::class, 'unpublishProject'])->where('id', '[0-9]+');
    
    // Properties
    Route::get('/properties', [AdminController::class, 'properties']);
    Route::patch('/properties/{id}/validate', [AdminController::class, 'validateProperty'])->where('id', '[0-9]+');
});

// ========================================
// Public Routes
// ========================================

// Projects
Route::get('/projects', [PublicController::class, 'projects']);
Route::get('/projects/{id}', [PublicController::class, 'projectById'])->where('id', '[0-9]+'); // By ID (must be before slug route)
Route::get('/projects/{slug}', [PublicController::class, 'projectBySlug']); // By slug
Route::get('/projects/{id}/properties', [PublicController::class, 'projectProperties'])->where('id', '[0-9]+');

// Properties
Route::get('/properties', [PublicController::class, 'properties']);
Route::get('/properties/{id}', [PublicController::class, 'propertyDetails'])->where('id', '[0-9]+');
Route::get('/properties/{id}/similar', [PublicController::class, 'similarProperties'])->where('id', '[0-9]+');

// Promoters
Route::get('/promoters', [PublicController::class, 'promoters']);
Route::get('/promoters/{id}', [PublicController::class, 'promoterDetails'])->where('id', '[0-9]+');
Route::get('/promoters/{id}/projects', [PublicController::class, 'promoterProjects'])->where('id', '[0-9]+');

// Search & Filters
Route::get('/search', [PublicController::class, 'search']);
Route::get('/cities', [PublicController::class, 'cities']);
Route::get('/property-types', [PublicController::class, 'propertyTypes']);
Route::get('/filters/options', [PublicController::class, 'filterOptions']);