<?php
/**
 * Seeder Verification Test Script
 * This script tests all seeders to verify they are functional
 */

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Database\Capsule\Manager as DB;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 SEEDER VERIFICATION TEST\n";
echo "==========================\n\n";

// Test 1: Check Database Connection
echo "1️⃣ Testing Database Connection...\n";
try {
    $userCount = DB::table('users')->count();
    $propertyCount = DB::table('properties')->count();
    $agencyCount = DB::table('agencies')->count();
    
    echo "✅ Database connected successfully!\n";
    echo "   - Users: {$userCount}\n";
    echo "   - Properties: {$propertyCount}\n";
    echo "   - Agencies: {$agencyCount}\n\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n\n";
    exit(1);
}

// Test 2: Check JSON Data File
echo "2️⃣ Testing JSON Data File...\n";
$jsonPath = base_path('Real_estate_Nestify.nestify_properties.json');
if (file_exists($jsonPath)) {
    $jsonData = json_decode(file_get_contents($jsonPath), true);
    if ($jsonData && is_array($jsonData)) {
        echo "✅ JSON file found and valid!\n";
        echo "   - Properties in JSON: " . count($jsonData) . "\n\n";
    } else {
        echo "❌ JSON file is invalid or empty\n\n";
    }
} else {
    echo "❌ JSON file not found at: {$jsonPath}\n\n";
}

// Test 3: Test AdminSeeder
echo "3️⃣ Testing AdminSeeder...\n";
try {
    $seeder = new \Database\Seeders\AdminSeeder();
    $seeder->run();
    echo "✅ AdminSeeder executed successfully!\n";
    
    // Check if admin users were created
    $adminCount = DB::table('users')->where('user_type', 'admin')->count();
    echo "   - Admin users: {$adminCount}\n\n";
} catch (Exception $e) {
    echo "❌ AdminSeeder failed: " . $e->getMessage() . "\n\n";
}

// Test 4: Test PropertySeeder (with fresh data)
echo "4️⃣ Testing PropertySeeder...\n";
try {
    // Clear properties table first
    DB::table('properties')->truncate();
    
    $seeder = new \Database\Seeders\PropertySeeder();
    $seeder->run();
    echo "✅ PropertySeeder executed successfully!\n";
    
    $propertyCount = DB::table('properties')->count();
    echo "   - Properties imported: {$propertyCount}\n\n";
} catch (Exception $e) {
    echo "❌ PropertySeeder failed: " . $e->getMessage() . "\n\n";
}

// Test 5: Test RealEstateDataSeeder
echo "5️⃣ Testing RealEstateDataSeeder...\n";
try {
    // Clear properties table first
    DB::table('properties')->truncate();
    
    $seeder = new \Database\Seeders\RealEstateDataSeeder();
    $seeder->run();
    echo "✅ RealEstateDataSeeder executed successfully!\n";
    
    $propertyCount = DB::table('properties')->count();
    echo "   - Properties imported: {$propertyCount}\n\n";
} catch (Exception $e) {
    echo "❌ RealEstateDataSeeder failed: " . $e->getMessage() . "\n\n";
}

// Test 6: Test DatabaseSeeder (main seeder)
echo "6️⃣ Testing DatabaseSeeder...\n";
try {
    // Clear all tables first
    DB::table('properties')->truncate();
    DB::table('agencies')->truncate();
    DB::table('users')->truncate();
    
    $seeder = new \Database\Seeders\DatabaseSeeder();
    $seeder->run();
    echo "✅ DatabaseSeeder executed successfully!\n";
    
    $userCount = DB::table('users')->count();
    $propertyCount = DB::table('properties')->count();
    $agencyCount = DB::table('agencies')->count();
    
    echo "   - Users created: {$userCount}\n";
    echo "   - Properties imported: {$propertyCount}\n";
    echo "   - Agencies created: {$agencyCount}\n\n";
} catch (Exception $e) {
    echo "❌ DatabaseSeeder failed: " . $e->getMessage() . "\n\n";
}

// Test 7: Verify Data Quality
echo "7️⃣ Testing Data Quality...\n";
try {
    $properties = DB::table('properties')->get();
    $validProperties = 0;
    $invalidProperties = 0;
    
    foreach ($properties as $property) {
        if (!empty($property->title) && $property->surface > 0 && $property->price > 0) {
            $validProperties++;
        } else {
            $invalidProperties++;
        }
    }
    
    echo "✅ Data quality check completed!\n";
    echo "   - Valid properties: {$validProperties}\n";
    echo "   - Invalid properties: {$invalidProperties}\n\n";
} catch (Exception $e) {
    echo "❌ Data quality check failed: " . $e->getMessage() . "\n\n";
}

echo "🎉 SEEDER VERIFICATION COMPLETE!\n";
echo "===============================\n";
echo "All seeders have been tested and verified.\n";
echo "Check the results above for any issues.\n";


