<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

try {
    // Delete existing test users
    DB::table('agencies')->where('company_name', 'like', 'Test%')->delete();
    DB::table('users')->whereIn('email', [
        'regular@test.com',
        'agency@test.com', 
        'admin@test.com'
    ])->delete();

    echo "Creating test users...\n";

    // Create regular user
    $regularUser = User::create([
        'name' => 'Regular User Test',
        'email' => 'regular@test.com',
        'password' => Hash::make('password123'),
        'phone' => '21622334455',
        'user_type' => 'regular_user',
        'city' => 'Tunis',
        'bio' => 'Test regular user account',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    echo "✓ Regular user created: {$regularUser->email}\n";

    // Create agency user
    $agencyUser = User::create([
        'name' => 'Agency User Test',
        'email' => 'agency@test.com',
        'password' => Hash::make('password123'),
        'phone' => '21633445566',
        'user_type' => 'agency',
        'city' => 'Tunis',
        'bio' => 'Test agency user account',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    
    // Create agency profile
    $agency = Agency::create([
        'user_id' => $agencyUser->id,
        'company_name' => 'Test Real Estate Agency',
        'website' => 'https://test-agency.com',
        'description' => 'Test agency for API testing',
        'addresses' => ['123 Test Street, Tunis'],
        'additional_phones' => ['21644556677'],
        'license_number' => 'LIC123456',
        'established_date' => '2020-01-01',
        'employee_count' => 10,
        'specializations' => ['residential', 'commercial'],
        'verified' => true,
    ]);
    echo "✓ Agency user created: {$agencyUser->email}\n";

    // Create admin user
    $adminUser = User::create([
        'name' => 'Admin User Test',
        'email' => 'admin@test.com',
        'password' => Hash::make('password123'),
        'phone' => '21655667788',
        'user_type' => 'admin',
        'city' => 'Tunis',
        'bio' => 'Test admin user account',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);
    echo "✓ Admin user created: {$adminUser->email}\n";

    echo "\n=== Test User Credentials ===\n";
    echo "Regular User: regular@test.com / password123\n";
    echo "Agency User:  agency@test.com / password123\n";
    echo "Admin User:   admin@test.com / password123\n";
    echo "\nAll users are ready for testing!\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}