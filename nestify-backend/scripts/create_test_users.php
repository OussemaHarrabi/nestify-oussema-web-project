<?php

require_once 'vendor/autoload.php';

// Set up Laravel environment
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Creating Test Users ===\n";

// Check if regular user exists
$regularUser = App\Models\User::where('email', 'user@nestify.tn')->first();
if (!$regularUser) {
    $regularUser = App\Models\User::create([
        'name' => 'Regular User',
        'email' => 'user@nestify.tn',
        'password' => bcrypt('user123'),
        'user_type' => 'regular_user',
        'phone' => '+21612345678',
        'city' => 'Tunis',
        'is_active' => true
    ]);
    echo "✅ Created regular user: {$regularUser->email}\n";
} else {
    echo "✅ Regular user already exists: {$regularUser->email}\n";
}

// Check if agency user exists
$agencyUser = App\Models\User::where('email', 'agency@nestify.tn')->first();
if (!$agencyUser) {
    $agencyUser = App\Models\User::create([
        'name' => 'Agency User',
        'email' => 'agency@nestify.tn',
        'password' => bcrypt('agency123'),
        'user_type' => 'agency',
        'phone' => '+21612345679',
        'city' => 'Tunis',
        'is_active' => true
    ]);
    echo "✅ Created agency user: {$agencyUser->email}\n";
    
    // Create agency profile
    App\Models\Agency::create([
        'user_id' => $agencyUser->id,
        'company_name' => 'Test Real Estate Agency',
        'website' => 'https://test-agency.com',
        'description' => 'A test real estate agency',
        'verified' => true,
        'addresses' => [
            [
                'type' => 'main',
                'address' => '123 Agency Street, Tunis',
                'city' => 'Tunis',
                'postal_code' => '1000'
            ]
        ],
        'additional_phones' => ['+21612345680']
    ]);
    echo "✅ Created agency profile for: {$agencyUser->email}\n";
} else {
    echo "✅ Agency user already exists: {$agencyUser->email}\n";
}

// Verify admin user exists
$adminUser = App\Models\User::where('email', 'admin@nestify.tn')->first();
if ($adminUser) {
    echo "✅ Admin user exists: {$adminUser->email}\n";
} else {
    $adminUser = App\Models\User::create([
        'name' => 'Admin User',
        'email' => 'admin@nestify.tn',
        'password' => bcrypt('admin123'),
        'user_type' => 'admin',
        'phone' => '+21612345681',
        'city' => 'Tunis',
        'is_active' => true,
        'admin_role' => 'super_admin',
        'permissions' => ['all']
    ]);
    echo "✅ Created admin user: {$adminUser->email}\n";
}

echo "\n=== Test Credentials ===\n";
echo "Regular User: user@nestify.tn / user123\n";
echo "Agency User: agency@nestify.tn / agency123\n";
echo "Admin User: admin@nestify.tn / admin123\n";

echo "\n=== User Count Summary ===\n";
echo "Total Users: " . App\Models\User::count() . "\n";
echo "Regular Users: " . App\Models\User::where('user_type', 'regular_user')->count() . "\n";
echo "Agency Users: " . App\Models\User::where('user_type', 'agency')->count() . "\n";
echo "Admin Users: " . App\Models\User::where('user_type', 'admin')->count() . "\n";

echo "\n=== Setup Complete ===\n";