<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Agency;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    DB::beginTransaction();

    // Create test agency user
    $user = User::create([
        'name' => 'Test Agency Manager',
        'email' => 'test@agency.com',
        'password' => Hash::make('password123'),
        'phone' => '+216 71 123 456',
        'user_type' => 'agency',
        'city' => 'Tunis',
        'bio' => 'Gestionnaire de l\'agence de test',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    // Create agency profile
    $agency = Agency::create([
        'user_id' => $user->id,
        'company_name' => 'Test Agency Immobilier',
        'website' => 'https://test-agency.com',
        'description' => 'Agence immobilière de test pour développement et démonstration',
        'addresses' => ['123 Avenue Habib Bourguiba, Tunis'],
        'additional_phones' => ['+216 71 123 456', '+216 98 765 432'],
        'license_number' => 'TEST123456',
        'established_date' => '2020-01-01',
        'employee_count' => 5,
        'specializations' => ['Résidentiel', 'Commercial', 'Luxe'],
        'verified' => true,
        'rating' => 4.8,
        'review_count' => 25,
    ]);

    DB::commit();

    echo "✅ Test agency created successfully!\n";
    echo "📧 Email: test@agency.com\n";
    echo "🔑 Password: password123\n";
    echo "🏢 Agency: Test Agency Immobilier\n";
    echo "👤 User ID: {$user->id}\n";
    echo "🏢 Agency ID: {$agency->id}\n";

} catch (Exception $e) {
    DB::rollback();
    echo "❌ Error creating test agency: " . $e->getMessage() . "\n";
}

