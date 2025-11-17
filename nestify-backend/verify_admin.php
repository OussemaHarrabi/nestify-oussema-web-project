<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

echo "🔍 Checking Admin User...\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

$admin = User::where('email', 'admin@nestify.tn')->first();

if (!$admin) {
    echo "❌ Admin user does NOT exist!\n";
    echo "Creating admin now...\n\n";
    
    $admin = new User();
    $admin->name = 'Admin Nestify';
    $admin->email = 'admin@nestify.tn';
    $admin->password = bcrypt('admin123');
    $admin->phone = '+216 00 000 000';
    $admin->user_type = 'admin';
    $admin->is_active = true;
    $admin->save();
    
    echo "✅ Admin created successfully!\n";
} else {
    echo "✅ Admin user EXISTS\n\n";
}

echo "Admin Details:\n";
echo "  ID: {$admin->id}\n";
echo "  Name: {$admin->name}\n";
echo "  Email: {$admin->email}\n";
echo "  User Type: {$admin->user_type}\n";
echo "  Is Active: " . ($admin->is_active ? '✅ Yes' : '❌ No') . "\n";
echo "  Created: {$admin->created_at}\n\n";

// Fix user_type if wrong
if ($admin->user_type !== 'admin') {
    echo "⚠️  User type is '{$admin->user_type}' - Fixing to 'admin'...\n";
    $admin->user_type = 'admin';
    $admin->save();
    echo "✅ Fixed user_type!\n\n";
}

// Fix is_active if false
if (!$admin->is_active) {
    echo "⚠️  Admin is not active - Activating...\n";
    $admin->is_active = true;
    $admin->save();
    echo "✅ Admin activated!\n\n";
}

// Create a fresh token for testing
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo "🔑 Creating Fresh Admin Token...\n\n";

// Revoke old tokens
$tokenCount = $admin->tokens()->count();
if ($tokenCount > 0) {
    $admin->tokens()->delete();
    echo "🗑️  Revoked {$tokenCount} old token(s)\n";
}

$token = $admin->createToken('admin_token')->plainTextToken;

echo "\n✅ FRESH ADMIN TOKEN:\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
echo $token . "\n";
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";

echo "📋 Copy this token and use it in Postman:\n";
echo "  1. Go to Admin Collection → Variables\n";
echo "  2. Set 'admin_token' = (paste token above)\n";
echo "  3. Save and test admin endpoints\n\n";

echo "✅ Admin verification complete!\n";
