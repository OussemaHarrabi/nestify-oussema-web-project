<?php

require_once 'vendor/autoload.php';

// Simple test API endpoints
$base_url = 'http://localhost:8000/api';

function testEndpoint($url, $method = 'GET', $data = null, $headers = []) {
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $headers[] = 'Content-Type: application/json';
    }
    
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    return [
        'http_code' => $httpCode,
        'response' => $response,
        'error' => $error
    ];
}

echo "=== API Testing ===\n\n";

// Test 1: Registration for regular user
echo "1. Testing regular user registration...\n";
$regData = [
    'name' => 'Test Regular User',
    'email' => 'test_regular@example.com',
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'user_type' => 'regular_user',
    'phone' => '21622334455',
    'city' => 'Tunis'
];

$result = testEndpoint($base_url . '/register', 'POST', $regData);
echo "Status: {$result['http_code']}\n";
if ($result['error']) {
    echo "Error: {$result['error']}\n";
} else {
    $response = json_decode($result['response'], true);
    if ($response) {
        echo "Response: " . json_encode($response, JSON_PRETTY_PRINT) . "\n";
    } else {
        echo "Raw response: {$result['response']}\n";
    }
}

echo "\n" . str_repeat('-', 50) . "\n\n";

// Test 2: Login with existing user
echo "2. Testing login with existing regular user...\n";
$loginData = [
    'email' => 'regular@test.com',
    'password' => 'password123'
];

$result = testEndpoint($base_url . '/login', 'POST', $loginData);
echo "Status: {$result['http_code']}\n";
if ($result['error']) {
    echo "Error: {$result['error']}\n";
} else {
    $response = json_decode($result['response'], true);
    if ($response) {
        echo "Response: " . json_encode($response, JSON_PRETTY_PRINT) . "\n";
        if (isset($response['access_token'])) {
            $token = $response['access_token'];
            echo "\n3. Testing authenticated endpoint...\n";
            $authResult = testEndpoint($base_url . '/user', 'GET', null, ["Authorization: Bearer $token"]);
            echo "Auth Status: {$authResult['http_code']}\n";
            if (!$authResult['error']) {
                $authResponse = json_decode($authResult['response'], true);
                if ($authResponse) {
                    echo "Auth Response: " . json_encode($authResponse, JSON_PRETTY_PRINT) . "\n";
                } else {
                    echo "Auth Raw response: {$authResult['response']}\n";
                }
            }
        }
    } else {
        echo "Raw response: {$result['response']}\n";
    }
}

echo "\n" . str_repeat('=', 50) . "\n";