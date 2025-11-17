<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "🧪 Testing Admin Authentication...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

// Step 1: Get admin user
$admin = User::where('email', 'admin@nestify.tn')->first();
if (!$admin) {
    echo "❌ Admin user not found!\n";
    exit(1);
}

echo "✅ Admin user found:\n";
echo "   ID: {$admin->id}\n";
echo "   Email: {$admin->email}\n";
echo "   User Type: {$admin->user_type}\n";
echo "   Active: " . ($admin->is_active ? 'Yes' : 'No') . "\n\n";

// Step 2: Create token
echo "🔑 Creating authentication token...\n";
$token = $admin->createToken('test_token')->plainTextToken;
echo "✅ Token created: " . substr($token, 0, 30) . "...\n\n";

// Step 3: Test API endpoint
echo "📡 Testing admin dashboard endpoint...\n";

$baseUrl = 'http://127.0.0.1:8000/api';
$url = $baseUrl . '/admin/dashboard';

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Authorization: Bearer ' . $token
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Response Code: {$httpCode}\n";

if ($httpCode === 200) {
    echo "✅ SUCCESS! Admin dashboard accessible\n\n";
    echo "Response:\n";
    $data = json_decode($response, true);
    echo json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ AUTHENTICATION TEST PASSED!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🎯 Use this token in Postman:\n";
    echo $token . "\n\n";
} else {
    echo "❌ FAILED! HTTP {$httpCode}\n";
    echo "Response: {$response}\n\n";
    
    if ($httpCode === 0) {
        echo "⚠️  Server not responding. Is Laravel running?\n";
        echo "Run: php artisan serve\n";
    }
}
