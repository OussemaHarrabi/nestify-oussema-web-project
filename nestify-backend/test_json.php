<?php

// Test file to debug JSON reading
echo "Testing JSON file reading...\n";

$jsonPath = __DIR__ . '/Real_estate_Nestify.nestify_properties.json';
echo "Looking for file at: {$jsonPath}\n";

if (!file_exists($jsonPath)) {
    echo "❌ File not found!\n";
    exit;
}

echo "✅ File exists\n";
echo "File size: " . number_format(filesize($jsonPath)) . " bytes\n";

$jsonData = file_get_contents($jsonPath);
if (!$jsonData) {
    echo "❌ Failed to read file content\n";
    exit;
}

echo "✅ File content loaded\n";

$properties = json_decode($jsonData, true);
if (!$properties) {
    echo "❌ JSON decode failed: " . json_last_error_msg() . "\n";
    exit;
}

echo "✅ JSON decoded successfully\n";
echo "Properties count: " . count($properties) . "\n";

if (count($properties) > 0) {
    $first = $properties[0];
    echo "\nFirst property:\n";
    echo "- Title: " . ($first['title'] ?? 'N/A') . "\n";
    echo "- Price: " . ($first['price'] ?? 'N/A') . "\n";
    echo "- Surface: " . ($first['surface'] ?? 'N/A') . "\n";
    echo "- Type: " . ($first['type'] ?? 'N/A') . "\n";
    
    $locationData = $first['location_id'] ?? [];
    echo "- City: " . ($locationData['city'] ?? 'N/A') . "\n";
    echo "- District: " . ($locationData['district'] ?? 'N/A') . "\n";
    
    $apartmentDetails = $first['apartment_details_id'] ?? [];
    echo "- Rooms: " . ($apartmentDetails['rooms'] ?? 'N/A') . "\n";
    echo "- Bedrooms: " . ($apartmentDetails['bedrooms'] ?? 'N/A') . "\n";
}

echo "\nTest completed!\n";
