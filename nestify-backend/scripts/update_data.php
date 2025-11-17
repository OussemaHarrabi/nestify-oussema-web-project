<?php

// Update existing properties with governorate data
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    echo "🔄 Updating governorate data...\n";
    
    // Mapping of cities to governorates
    $cityToGovernorate = [
        'Tunis' => 'Tunis',
        'Ariana' => 'Ariana',
        'Ben Arous' => 'Ben Arous',
        'Manouba' => 'Manouba',
        'Sfax' => 'Sfax',
        'Sousse' => 'Sousse',
        'Kairouan' => 'Kairouan',
        'Monastir' => 'Monastir',
        'Mahdia' => 'Mahdia',
        'Bizerte' => 'Bizerte',
        'Beja' => 'Beja',
        'Jendouba' => 'Jendouba',
        'Le Kef' => 'Le Kef',
        'Siliana' => 'Siliana',
        'Gabes' => 'Gabes',
        'Medenine' => 'Medenine',
        'Tataouine' => 'Tataouine',
        'Gafsa' => 'Gafsa',
        'Tozeur' => 'Tozeur',
        'Kebili' => 'Kebili',
        'Kasserine' => 'Kasserine',
        'Sidi Bouzid' => 'Sidi Bouzid',
        'Zaghouan' => 'Zaghouan',
        'Nabeul' => 'Nabeul'
    ];
    
    foreach ($cityToGovernorate as $city => $governorate) {
        $count = DB::table('properties')
            ->where('city', 'like', "%{$city}%")
            ->whereNull('governorate')
            ->update(['governorate' => $governorate]);
        
        if ($count > 0) {
            echo "✅ Updated {$count} properties in {$city} -> {$governorate}\n";
        }
    }
    
    // Update area from surface where area is null
    $areaCount = DB::table('properties')
        ->whereNull('area')
        ->whereNotNull('surface')
        ->update(['area' => DB::raw('surface')]);
    
    if ($areaCount > 0) {
        echo "✅ Updated {$areaCount} properties with area data from surface\n";
    }
    
    // Set default status for properties without status
    $statusCount = DB::table('properties')
        ->whereNull('status')
        ->update(['status' => 'A Vendre']);
    
    if ($statusCount > 0) {
        echo "✅ Set default status for {$statusCount} properties\n";
    }
    
    echo "\n🎉 Database update completed successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
