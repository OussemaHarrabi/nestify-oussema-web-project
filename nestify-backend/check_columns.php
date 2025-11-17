<?php

try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=nestify_backend', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "📋 ACTUAL PROPERTIES TABLE STRUCTURE:\n";
    echo "=====================================\n";
    
    $stmt = $pdo->query("DESCRIBE properties");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $columnNames = [];
    foreach ($columns as $column) {
        $columnNames[] = $column['Field'];
        echo sprintf("%-20s %-25s %-10s %-15s\n", 
            $column['Field'], 
            $column['Type'], 
            $column['Null'], 
            $column['Default'] ?? 'NULL'
        );
    }
    
    echo "\n🔍 COLUMN NAMES ONLY:\n";
    echo implode(', ', $columnNames) . "\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
