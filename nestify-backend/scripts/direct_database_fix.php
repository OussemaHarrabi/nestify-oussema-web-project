<?php

// Direct database connection and column addition
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=nestify_backend', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔗 Connected to database successfully!\n";
    
    // Check current columns
    $stmt = $pdo->query("DESCRIBE properties");
    $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Current columns: " . implode(', ', $columns) . "\n";
    
    // Define columns to add
    $columnsToAdd = [
        'governorate' => "ADD COLUMN governorate VARCHAR(100) NULL AFTER city",
        'postal_code' => "ADD COLUMN postal_code VARCHAR(20) NULL AFTER governorate", 
        'status' => "ADD COLUMN status VARCHAR(50) DEFAULT 'A Vendre' AFTER type",
        'area' => "ADD COLUMN area DECIMAL(10,2) NULL AFTER status",
        'is_active' => "ADD COLUMN is_active TINYINT(1) DEFAULT 1 AFTER validated"
    ];
    
    // Add missing columns
    foreach ($columnsToAdd as $column => $sql) {
        if (!in_array($column, $columns)) {
            try {
                $pdo->exec("ALTER TABLE properties $sql");
                echo "✅ Added column: $column\n";
            } catch (Exception $e) {
                echo "❌ Error adding $column: " . $e->getMessage() . "\n";
            }
        } else {
            echo "✅ Column already exists: $column\n";
        }
    }
    
    // Update existing records
    echo "\n🔄 Updating existing records...\n";
    
    $updateQueries = [
        "UPDATE properties SET governorate = city WHERE governorate IS NULL OR governorate = ''",
        "UPDATE properties SET postal_code = '1000' WHERE postal_code IS NULL OR postal_code = ''",
        "UPDATE properties SET status = 'A Vendre' WHERE status IS NULL OR status = ''",
        "UPDATE properties SET area = surface WHERE area IS NULL AND surface IS NOT NULL",
        "UPDATE properties SET area = 100 WHERE area IS NULL OR area = 0",
        "UPDATE properties SET is_active = 1 WHERE is_active IS NULL"
    ];
    
    foreach ($updateQueries as $query) {
        try {
            $stmt = $pdo->exec($query);
            echo "✅ Updated $stmt records with: $query\n";
        } catch (Exception $e) {
            echo "❌ Error with query '$query': " . $e->getMessage() . "\n";
        }
    }
    
    // Final verification
    echo "\n📊 Final verification:\n";
    $stmt = $pdo->query("DESCRIBE properties");
    $finalColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($finalColumns as $col) {
        echo "  - {$col['Field']} ({$col['Type']}) {$col['Null']} {$col['Default']}\n";
    }
    
    echo "\n✅ DATABASE FIXED SUCCESSFULLY!\n";
    
} catch (Exception $e) {
    echo "❌ CRITICAL ERROR: " . $e->getMessage() . "\n";
}
