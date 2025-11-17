<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Agency;

echo "=== Database Status Check ===\n";

echo "\n--- Users in database ---\n";
$users = User::all(['id', 'name', 'email', 'user_type']);
foreach ($users as $user) {
    echo "{$user->id} - {$user->name} ({$user->email}) - {$user->user_type}\n";
}

echo "\n--- Agencies in database ---\n";
$agencies = Agency::with('user')->get();
foreach ($agencies as $agency) {
    echo "{$agency->id} - {$agency->company_name} (User: {$agency->user->email})\n";
}

echo "\n--- Test Authentication ---\n";
$testUser = User::where('email', 'regular@test.com')->first();
if ($testUser) {
    echo "✓ Regular test user exists\n";
    echo "  - Name: {$testUser->name}\n";
    echo "  - Email: {$testUser->email}\n";
    echo "  - User Type: {$testUser->user_type}\n";
    echo "  - Active: " . ($testUser->is_active ? 'Yes' : 'No') . "\n";
}

$agencyUser = User::where('email', 'agency@test.com')->first();
if ($agencyUser) {
    echo "✓ Agency test user exists\n";
    echo "  - Name: {$agencyUser->name}\n";
    echo "  - Email: {$agencyUser->email}\n";
    echo "  - User Type: {$agencyUser->user_type}\n";
    $agencyProfile = Agency::where('user_id', $agencyUser->id)->first();
    if ($agencyProfile) {
        echo "  - Agency Profile: {$agencyProfile->company_name}\n";
    }
}

$adminUser = User::where('email', 'admin@test.com')->first();
if ($adminUser) {
    echo "✓ Admin test user exists\n";
    echo "  - Name: {$adminUser->name}\n";
    echo "  - Email: {$adminUser->email}\n";
    echo "  - User Type: {$adminUser->user_type}\n";
}

echo "\nDatabase is ready for testing!\n";