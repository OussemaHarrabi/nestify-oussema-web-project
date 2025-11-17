<?php

// Test the new simplified search and filter endpoints
echo "🧪 TESTING SIMPLIFIED SEARCH & FILTER ENDPOINTS\n\n";

$baseUrl = 'http://localhost:8000/api';

function testEndpoint($url, $description) {
    echo "🔍 Testing: $description\n";
    echo "URL: $url\n";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        if (isset($data['data'])) {
            echo "✅ SUCCESS: " . count($data['data']) . " results found\n";
        } else if (isset($data['suggestions'])) {
            echo "✅ SUCCESS: " . count($data['suggestions']) . " suggestions found\n";
        } else if (isset($data['cities'])) {
            echo "✅ SUCCESS: " . count($data['cities']) . " cities, " . count($data['property_types']) . " property types\n";
        } else {
            echo "✅ SUCCESS: Response received\n";
        }
    } else {
        echo "❌ FAILED: HTTP $httpCode\n";
        if ($response) {
            $error = json_decode($response, true);
            echo "Error: " . ($error['message'] ?? 'Unknown error') . "\n";
        }
    }
    echo "\n";
}

// Test endpoints
testEndpoint("$baseUrl/properties", "Get All Properties");
testEndpoint("$baseUrl/properties/search?query=Tunis", "Search for 'Tunis'");
testEndpoint("$baseUrl/properties/filter?type=Appartement", "Filter by Appartement");
testEndpoint("$baseUrl/properties/filter?city=Tunis", "Filter by City: Tunis");
testEndpoint("$baseUrl/properties/suggestions?query=Tu", "Suggestions for 'Tu'");
testEndpoint("$baseUrl/properties/locations", "Get Locations");

echo "🎯 TESTING COMPLETE!\n";
