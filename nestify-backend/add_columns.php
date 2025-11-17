<?php

// Add missing columns to properties table
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    // Add governorate column
    DB::statement("ALTER TABLE properties ADD COLUMN IF NOT EXISTS governorate VARCHAR(255) NULL AFTER city");
    
    // Add postal_code column
    DB::statement("ALTER TABLE properties ADD COLUMN IF NOT EXISTS postal_code VARCHAR(10) NULL AFTER governorate");
    
    // Add status column
    DB::statement("ALTER TABLE properties ADD COLUMN IF NOT EXISTS status VARCHAR(255) DEFAULT 'A Vendre' AFTER type");
    
    // Add area column
    DB::statement("ALTER TABLE properties ADD COLUMN IF NOT EXISTS area INT NULL AFTER status");
    
    // Add is_active column
    DB::statement("ALTER TABLE properties ADD COLUMN IF NOT EXISTS is_active BOOLEAN DEFAULT 1 AFTER validated");
    
    echo "✅ All missing columns added successfully!\n";
    
    // Update existing records to populate the new columns from existing data
    DB::statement("UPDATE properties SET area = surface WHERE area IS NULL AND surface IS NOT NULL");
    DB::statement("UPDATE properties SET governorate = 'Tunis' WHERE governorate IS NULL AND city LIKE '%Tunis%'");
    DB::statement("UPDATE properties SET governorate = 'Sfax' WHERE governorate IS NULL AND city LIKE '%Sfax%'");
    DB::statement("UPDATE properties SET governorate = 'Ariana' WHERE governorate IS NULL AND city LIKE '%Ariana%'");
    DB::statement("UPDATE properties SET governorate = 'Sousse' WHERE governorate IS NULL AND city LIKE '%Sousse%'");
    DB::statement("UPDATE properties SET governorate = 'Bizerte' WHERE governorate IS NULL AND city LIKE '%Bizerte%'");
    
    echo "✅ Existing data updated successfully!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
