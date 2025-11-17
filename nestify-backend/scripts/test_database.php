<?php

// Simple database test
$host = '127.0.0.1';
$dbname = 'nestify_backend';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "🔗 Connected to database: $dbname\n\n";
    
    // Check table structure
    echo "📋 Properties table structure:\n";
    $stmt = $pdo->query("DESCRIBE properties");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($columns as $column) {
        echo sprintf("  %-20s %-20s %-10s %-15s\n", 
            $column['Field'], 
            $column['Type'], 
            $column['Null'], 
            $column['Default']
        );
    }
    
    echo "\n🔢 Property count: ";
    $stmt = $pdo->query("SELECT COUNT(*) FROM properties");
    echo $stmt->fetchColumn() . "\n";
    
    // Test if we can run queries with all columns
    echo "\n🧪 Testing queries:\n";
    
    $testQueries = [
        "SELECT COUNT(*) FROM properties WHERE validated = 1" => "Basic validation filter",
        "SELECT COUNT(*) FROM properties WHERE city LIKE '%Tunis%'" => "City search",
    ];
    
    // Test optional columns
    $optionalQueries = [
        "SELECT COUNT(*) FROM properties WHERE governorate LIKE '%Tunis%'" => "Governorate search",
        "SELECT COUNT(*) FROM properties WHERE status = 'A Vendre'" => "Status filter",
        "SELECT COUNT(*) FROM properties WHERE is_active = 1" => "Active filter"
    ];
    
    foreach ($testQueries as $query => $description) {
        try {
            $stmt = $pdo->query($query);
            $count = $stmt->fetchColumn();
            echo "  ✅ $description: $count results\n";
        } catch (Exception $e) {
            echo "  ❌ $description: " . $e->getMessage() . "\n";
        }
    }
    
    foreach ($optionalQueries as $query => $description) {
        try {
            $stmt = $pdo->query($query);
            $count = $stmt->fetchColumn();
            echo "  ✅ $description: $count results\n";
        } catch (Exception $e) {
            echo "  ⚠️  $description: Column doesn't exist - " . $e->getMessage() . "\n";
        }
    }
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}
