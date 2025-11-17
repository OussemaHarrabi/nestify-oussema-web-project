<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Property;
use App\Models\User;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing property creation...\n";

// Clear existing properties
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('properties')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');
echo "Cleared existing properties\n";

// Get or create a user
$user = User::first();
if (!$user) {
    echo "No user found!\n";
    exit;
}

echo "Using user: {$user->name}\n";

// Test creating one property
try {
    $property = Property::create([
        'title' => 'Test Apartment in Sahloul',
        'description' => 'A beautiful test apartment',
        'price' => 250000,
        'type' => 'Appartement',
        'surface' => 100,
        'city' => 'Sousse',
        'district' => 'Sahloul',
        'address' => 'Test Address',
        'reference' => 'TEST_001',
        'rooms' => 3,
        'bedrooms' => 2,
        'bathrooms' => 1,
        'floor' => 2,
        'total_floors' => 5,
        'parking' => true,
        'elevator' => true,
        'terrace' => false,
        'garden' => false,
        'features' => ['parking', 'elevator'],
        'images' => ['test.jpg'],
        'user_id' => $user->id,
        'validated' => true,
        'published_date' => now(),
    ]);
    
    echo "✅ Successfully created test property: {$property->title}\n";
    echo "Property ID: {$property->id}\n";
    
} catch (Exception $e) {
    echo "❌ Failed to create property: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
}

echo "Test completed!\n";
