<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔍 EXTRACTING REAL LOCATIONS FROM DATABASE...\n\n";

try {
    // Get all unique cities
    $cities = DB::table('properties')
        ->select('city')
        ->whereNotNull('city')
        ->where('city', '!=', '')
        ->distinct()
        ->orderBy('city')
        ->pluck('city')
        ->toArray();
    
    echo "📍 CITIES FOUND (" . count($cities) . "):\n";
    foreach ($cities as $city) {
        $count = DB::table('properties')->where('city', $city)->count();
        echo "  - $city ($count properties)\n";
    }
    
    // Get all unique addresses/districts
    $addresses = DB::table('properties')
        ->select('address')
        ->whereNotNull('address')
        ->where('address', '!=', '')
        ->distinct()
        ->orderBy('address')
        ->pluck('address')
        ->take(20) // First 20
        ->toArray();
    
    echo "\n📍 SAMPLE ADDRESSES (" . count($addresses) . "):\n";
    foreach ($addresses as $address) {
        echo "  - $address\n";
    }
    
    // Check if governorate column exists
    try {
        $governorates = DB::table('properties')
            ->select('governorate')
            ->whereNotNull('governorate')
            ->where('governorate', '!=', '')
            ->distinct()
            ->orderBy('governorate')
            ->pluck('governorate')
            ->toArray();
        
        echo "\n📍 GOVERNORATES FOUND (" . count($governorates) . "):\n";
        foreach ($governorates as $gov) {
            $count = DB::table('properties')->where('governorate', $gov)->count();
            echo "  - $gov ($count properties)\n";
        }
    } catch (Exception $e) {
        echo "\n⚠️  Governorate column doesn't exist yet\n";
    }
    
    // Get property types
    $types = DB::table('properties')
        ->select('type')
        ->whereNotNull('type')
        ->where('type', '!=', '')
        ->distinct()
        ->orderBy('type')
        ->pluck('type')
        ->toArray();
    
    echo "\n🏠 PROPERTY TYPES (" . count($types) . "):\n";
    foreach ($types as $type) {
        $count = DB::table('properties')->where('type', $type)->count();
        echo "  - $type ($count properties)\n";
    }
    
    echo "\n✅ LOCATIONS EXTRACTED SUCCESSFULLY!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
