<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return response()->json([
        'message' => 'Nestify API',
        'status' => 'active',
        'version' => '1.0.0',
        'docs' => url('/api/documentation')
    ]);
});

// Add a dummy login route to prevent "Route [login] not defined" error
// This is just a fallback - API authentication uses tokens
Route::get('/login', function () {
    return response()->json([
        'message' => 'This is an API-only application. Please use POST /api/login with credentials.',
        'endpoints' => [
            'login' => url('/api/login'),
            'register' => url('/api/register'),
        ]
    ], 401);
})->name('login');

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toIso8601String()
    ]);
});

// Test routes for debugging
Route::get('/test-api', function () {
    return response()->json(['message' => 'API test route working!']);
});

Route::post('/test-login', function () {
    return response()->json(['message' => 'POST request working!']);
});
Route::get('/debug-routes', function () {
    $routes = [];
    foreach (Route::getRoutes() as $route) {
        $routes[] = [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
        ];
    }
    return response()->json(['routes' => $routes]);
});

// Real Estate API Test Routes
Route::get('/api/test-real-estate', function () {
    return response()->json([
        'message' => 'Nestify Real Estate API is working!',
        'database' => 'SQLite with real estate data',
        'timestamp' => now(),
    ]);
});

Route::get('/api/real-estate-stats', function () {
    return response()->json([
        'users' => User::count(),
        'agencies' => Agency::count(),
        'properties' => Property::count(),
        'favorites' => Favorite::count(),
        'properties_by_type' => Property::selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get(),
    ]);
});

Route::get('/api/test-properties', function () {
    return response()->json([
        'data' => \App\Http\Resources\PropertyResource::collection(
            Property::with('user.agency')->take(5)->get()
        ),
    ]);
});

Route::get('/api/test-agencies', function () {
    return response()->json([
        'data' => Agency::with('user')->get(),
    ]);
});

Route::get('/api/test-users', function () {
    return response()->json([
        'data' => User::with('agency')->get(),
    ]);
});
