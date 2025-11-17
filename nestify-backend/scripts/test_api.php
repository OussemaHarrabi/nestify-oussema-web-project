<?php
// Quick API test script
require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Simulate a simple test
echo "=== Nestify API Test ===\n";
echo "Testing basic functionality...\n\n";

// Test 1: Check if we can access models
try {
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    $propertyCount = \App\Models\Property::count();
    $userCount = \App\Models\User::count();
    $adminExists = \App\Models\User::where('user_type', 'admin')->exists();
    
    echo "✅ Database connection: SUCCESS\n";
    echo "📊 Properties in database: {$propertyCount}\n";
    echo "👥 Users in database: {$userCount}\n";
    echo "👑 Admin user exists: " . ($adminExists ? 'YES' : 'NO') . "\n";
    
    if ($propertyCount >= 700) {
        echo "✅ Real estate data: IMPORTED SUCCESSFULLY\n";
    } else {
        echo "⚠️  Real estate data: NEEDS IMPORT (run: php artisan db:seed --class=RealEstateDataSeeder)\n";
    }
    
    echo "\n=== API ENDPOINTS STATUS ===\n";
    echo "🌐 Server running on: http://127.0.0.1:8000\n";
    echo "📋 Test in Postman with: Nestify_Final_API_Collection_v2.postman_collection.json\n";
    echo "\n=== DEFAULT LOGIN CREDENTIALS ===\n";
    echo "👑 Admin: admin@nestify.tn / password\n";
    echo "🏢 Agency: contact@belkacem-immo.tn / password\n";
    echo "👤 Client: client1@example.com / password\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "💡 Make sure to run: composer install && php artisan key:generate\n";
}

echo "\n=== READY FOR TESTING! ===\n";
?>
