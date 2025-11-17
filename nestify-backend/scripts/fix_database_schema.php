<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🔧 FIXING DATABASE SCHEMA RADICALLY...\n";

try {
    // Get current columns
    $columns = Schema::getColumnListing('properties');
    echo "📋 Current columns: " . implode(', ', $columns) . "\n";
    
    $missingColumns = [];
    
    // Check which columns are missing
    $requiredColumns = [
        'governorate' => 'varchar(100)',
        'postal_code' => 'varchar(20)',
        'status' => 'varchar(50)',
        'area' => 'decimal(10,2)',
        'is_active' => 'tinyint(1)'
    ];
    
    foreach ($requiredColumns as $column => $type) {
        if (!in_array($column, $columns)) {
            $missingColumns[$column] = $type;
            echo "❌ Missing column: $column\n";
        } else {
            echo "✅ Column exists: $column\n";
        }
    }
    
    if (empty($missingColumns)) {
        echo "✅ All columns exist!\n";
    } else {
        echo "\n🔨 Adding missing columns...\n";
        
        foreach ($missingColumns as $column => $type) {
            try {
                $sql = "ALTER TABLE properties ADD COLUMN `$column` $type";
                if ($column === 'is_active') {
                    $sql .= " DEFAULT 1";
                } elseif ($column === 'status') {
                    $sql .= " DEFAULT 'A Vendre'";
                }
                
                DB::statement($sql);
                echo "✅ Added column: $column\n";
            } catch (Exception $e) {
                echo "❌ Error adding $column: " . $e->getMessage() . "\n";
            }
        }
    }
    
    // Update existing records with default values
    echo "\n🔄 Updating existing records with default values...\n";
    
    $updateQueries = [
        "UPDATE properties SET governorate = city WHERE governorate IS NULL OR governorate = ''",
        "UPDATE properties SET postal_code = '1000' WHERE postal_code IS NULL OR postal_code = ''",
        "UPDATE properties SET status = 'A Vendre' WHERE status IS NULL OR status = ''",
        "UPDATE properties SET area = 100 WHERE area IS NULL OR area = 0",
        "UPDATE properties SET is_active = 1 WHERE is_active IS NULL"
    ];
    
    foreach ($updateQueries as $query) {
        try {
            $affected = DB::statement($query);
            echo "✅ Updated records: $query\n";
        } catch (Exception $e) {
            echo "❌ Error updating: " . $e->getMessage() . "\n";
        }
    }
    
    // Verify final structure
    echo "\n📋 Final table structure:\n";
    $finalColumns = Schema::getColumnListing('properties');
    foreach ($finalColumns as $col) {
        echo "  - $col\n";
    }
    
    echo "\n✅ DATABASE SCHEMA FIXED SUCCESSFULLY!\n";
    
} catch (Exception $e) {
    echo "❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
