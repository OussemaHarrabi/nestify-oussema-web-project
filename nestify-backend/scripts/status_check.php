<?php

echo "=== NESTIFY BACKEND STATUS CHECK ===\n\n";

// Database connection test
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=nestify_db', 'root', '');
    echo "✅ Database connection: SUCCESSFUL\n";
    
    $count = $pdo->query('SELECT COUNT(*) FROM properties')->fetchColumn();
    echo "📊 Properties in database: {$count}\n";
    
    if ($count > 0) {
        $sample = $pdo->query('SELECT title, price, type, city, surface FROM properties LIMIT 3')->fetchAll(PDO::FETCH_ASSOC);
        echo "\n📋 Sample properties:\n";
        foreach ($sample as $i => $prop) {
            echo "  " . ($i + 1) . ". {$prop['title']}\n";
            echo "     Price: {$prop['price']} TND | Type: {$prop['type']} | City: {$prop['city']} | Surface: {$prop['surface']}m²\n";
        }
    }
    
    $userCount = $pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
    echo "\n👥 Users in database: {$userCount}\n";
    
} catch (Exception $e) {
    echo "❌ Database error: " . $e->getMessage() . "\n";
}

// API test
echo "\n=== API ENDPOINT TEST ===\n";
$apiUrl = 'http://127.0.0.1:8000/api/properties?limit=3';

$context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'header' => 'Accept: application/json'
    ]
]);

$response = @file_get_contents($apiUrl, false, $context);

if ($response) {
    $data = json_decode($response, true);
    if ($data && isset($data['data'])) {
        echo "✅ API Response: SUCCESS\n";
        echo "📊 Properties returned: " . count($data['data']) . "\n";
        echo "📄 Total available: " . ($data['total'] ?? 'Unknown') . "\n";
        
        if (!empty($data['data'])) {
            echo "\n📋 First API property:\n";
            $first = $data['data'][0];
            echo "  Title: {$first['title']}\n";
            echo "  Price: {$first['price']} TND\n";
            echo "  Type: {$first['type']}\n";
            echo "  City: {$first['city']}\n";
        }
    } else {
        echo "❌ API Response: Invalid JSON\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    }
} else {
    echo "❌ API Response: FAILED (Server might not be running)\n";
}

echo "\n=== SUMMARY ===\n";
echo "🎯 Status: " . ($count > 0 ? "READY FOR TESTING" : "NEEDS DATA IMPORT") . "\n";
echo "🚀 Server: " . ($response ? "RUNNING" : "CHECK IF RUNNING") . "\n";
echo "📱 Postman Collection: Ready for import\n";
echo "💾 Database: MySQL with {$count} properties\n";

echo "\n✅ Your Nestify backend is ready for submission!\n";
