<?php

require_once 'vendor/autoload.php';

// Set up Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== API Endpoint Testing ===\n\n";

// Test database connection
try {
    DB::connection()->getPdo();
    echo "✅ Database connection: SUCCESS\n";
} catch (Exception $e) {
    echo "❌ Database connection: FAILED - " . $e->getMessage() . "\n";
}

// Test basic routes
echo "\n=== Testing Controllers ===\n";

// Test if controllers exist and can be instantiated
$controllers = [
    'AuthController' => 'App\Http\Controllers\Api\AuthController',
    'PropertyController' => 'App\Http\Controllers\Api\PropertyController',
    'UserController' => 'App\Http\Controllers\Api\UserController',
    'FavoriteController' => 'App\Http\Controllers\Api\FavoriteController',
    'AgencyController' => 'App\Http\Controllers\Api\AgencyController',
    'AdminController' => 'App\Http\Controllers\Api\AdminController',
];

foreach ($controllers as $name => $class) {
    try {
        if (class_exists($class)) {
            $controller = new $class();
            echo "✅ $name: Class exists and can be instantiated\n";
        } else {
            echo "❌ $name: Class does not exist\n";
        }
    } catch (Exception $e) {
        echo "❌ $name: Error - " . $e->getMessage() . "\n";
    }
}

// Test models
echo "\n=== Testing Models ===\n";

$models = [
    'User' => 'App\Models\User',
    'Property' => 'App\Models\Property',
    'Agency' => 'App\Models\Agency',
    'Favorite' => 'App\Models\Favorite',
    'UserPropertyView' => 'App\Models\UserPropertyView',
    'UserSearch' => 'App\Models\UserSearch',
];

foreach ($models as $name => $class) {
    try {
        if (class_exists($class)) {
            echo "✅ $name: Model exists\n";
            
            // Test if we can create a query
            $model = new $class();
            $count = $model->count();
            echo "   Records count: $count\n";
        } else {
            echo "❌ $name: Model does not exist\n";
        }
    } catch (Exception $e) {
        echo "❌ $name: Error - " . $e->getMessage() . "\n";
    }
}

// Test middleware
echo "\n=== Testing Middleware ===\n";

$middlewares = [
    'CheckUserType' => 'App\Http\Middleware\CheckUserType',
    'CheckAdminPermission' => 'App\Http\Middleware\CheckAdminPermission',
];

foreach ($middlewares as $name => $class) {
    try {
        if (class_exists($class)) {
            echo "✅ $name: Middleware exists\n";
        } else {
            echo "❌ $name: Middleware does not exist\n";
        }
    } catch (Exception $e) {
        echo "❌ $name: Error - " . $e->getMessage() . "\n";
    }
}

echo "\n=== Testing completed ===\n";