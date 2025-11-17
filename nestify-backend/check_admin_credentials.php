<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔑 ADMIN CREDENTIALS CHECK\n";
echo "═══════════════════════════════════════\n\n";

$admin = \App\Models\User::where('user_type', 'admin')->first();

if (!$admin) {
    echo "❌ NO ADMIN USER FOUND!\n\n";
    echo "Creating admin user...\n";
    
    $admin = \App\Models\User::create([
        'name' => 'Admin',
        'email' => 'admin@nestify.tn',
        'password' => bcrypt('password'),
        'user_type' => 'admin',
        'email_verified_at' => now()
    ]);
    
    echo "✅ Admin user created!\n";
}

echo "Admin User Details:\n";
echo "  ID: {$admin->id}\n";
echo "  Name: {$admin->name}\n";
echo "  Email: {$admin->email}\n";
echo "  Type: {$admin->user_type}\n";
echo "  Verified: " . ($admin->email_verified_at ? 'Yes' : 'No') . "\n\n";

echo "═══════════════════════════════════════\n";
echo "📋 USE THESE CREDENTIALS IN POSTMAN:\n";
echo "═══════════════════════════════════════\n\n";
echo "Email: {$admin->email}\n";
echo "Password: password\n\n";

echo "Testing login...\n";
if (\Hash::check('password', $admin->password)) {
    echo "✅ Password verification: CORRECT\n";
} else {
    echo "❌ Password verification: FAILED\n";
    echo "Resetting password...\n";
    $admin->update(['password' => bcrypt('password')]);
    echo "✅ Password reset to 'password'\n";
}

echo "\n═══════════════════════════════════════\n";
echo "🎯 POSTMAN SETUP:\n";
echo "═══════════════════════════════════════\n\n";
echo "1. URL: http://127.0.0.1:8000/api/login\n";
echo "2. Method: POST\n";
echo "3. Headers:\n";
echo "   Content-Type: application/json\n";
echo "   Accept: application/json\n";
echo "4. Body (raw JSON):\n";
echo "   {\n";
echo "       \"email\": \"{$admin->email}\",\n";
echo "       \"password\": \"password\"\n";
echo "   }\n\n";

// Generate a fresh token
$token = $admin->createToken('postman-test')->plainTextToken;
echo "5. Fresh Token Generated:\n";
echo "   {$token}\n\n";

echo "═══════════════════════════════════════\n";
