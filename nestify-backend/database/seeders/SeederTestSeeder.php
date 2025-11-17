<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Property;
use App\Models\Agency;
use Illuminate\Support\Facades\DB;

class SeederTestSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('🧪 SEEDER VERIFICATION TEST');
        $this->command->info('==========================');
        $this->command->info('');

        // Test 1: Check Database Connection
        $this->command->info('1️⃣ Testing Database Connection...');
        try {
            $userCount = User::count();
            $propertyCount = Property::count();
            $agencyCount = Agency::count();
            
            $this->command->info('✅ Database connected successfully!');
            $this->command->info("   - Users: {$userCount}");
            $this->command->info("   - Properties: {$propertyCount}");
            $this->command->info("   - Agencies: {$agencyCount}");
            $this->command->info('');
        } catch (Exception $e) {
            $this->command->error('❌ Database connection failed: ' . $e->getMessage());
            $this->command->info('');
            return;
        }

        // Test 2: Check JSON Data File
        $this->command->info('2️⃣ Testing JSON Data File...');
        $jsonPath = base_path('Real_estate_Nestify.nestify_properties.json');
        if (file_exists($jsonPath)) {
            $jsonData = json_decode(file_get_contents($jsonPath), true);
            if ($jsonData && is_array($jsonData)) {
                $this->command->info('✅ JSON file found and valid!');
                $this->command->info("   - Properties in JSON: " . count($jsonData));
                $this->command->info('');
            } else {
                $this->command->error('❌ JSON file is invalid or empty');
                $this->command->info('');
            }
        } else {
            $this->command->error("❌ JSON file not found at: {$jsonPath}");
            $this->command->info('');
        }

        // Test 3: Test AdminSeeder
        $this->command->info('3️⃣ Testing AdminSeeder...');
        try {
            $seeder = new AdminSeeder();
            $seeder->run();
            $this->command->info('✅ AdminSeeder executed successfully!');
            
            $adminCount = User::where('user_type', 'admin')->count();
            $this->command->info("   - Admin users: {$adminCount}");
            $this->command->info('');
        } catch (Exception $e) {
            $this->command->error('❌ AdminSeeder failed: ' . $e->getMessage());
            $this->command->info('');
        }

        // Test 4: Test RealEstateDataSeeder
        $this->command->info('4️⃣ Testing RealEstateDataSeeder...');
        try {
            // Clear properties table first
            Property::truncate();
            
            $seeder = new RealEstateDataSeeder();
            $seeder->run();
            $this->command->info('✅ RealEstateDataSeeder executed successfully!');
            
            $propertyCount = Property::count();
            $this->command->info("   - Properties imported: {$propertyCount}");
            $this->command->info('');
        } catch (Exception $e) {
            $this->command->error('❌ RealEstateDataSeeder failed: ' . $e->getMessage());
            $this->command->info('');
        }

        // Test 5: Test DatabaseSeeder (main seeder)
        $this->command->info('5️⃣ Testing DatabaseSeeder...');
        try {
            // Clear all tables first
            Property::truncate();
            Agency::truncate();
            User::truncate();
            
            $seeder = new DatabaseSeeder();
            $seeder->run();
            $this->command->info('✅ DatabaseSeeder executed successfully!');
            
            $userCount = User::count();
            $propertyCount = Property::count();
            $agencyCount = Agency::count();
            
            $this->command->info("   - Users created: {$userCount}");
            $this->command->info("   - Properties imported: {$propertyCount}");
            $this->command->info("   - Agencies created: {$agencyCount}");
            $this->command->info('');
        } catch (Exception $e) {
            $this->command->error('❌ DatabaseSeeder failed: ' . $e->getMessage());
            $this->command->info('');
        }

        // Test 6: Verify Data Quality
        $this->command->info('6️⃣ Testing Data Quality...');
        try {
            $properties = Property::all();
            $validProperties = 0;
            $invalidProperties = 0;
            
            foreach ($properties as $property) {
                if (!empty($property->title) && $property->surface > 0 && $property->price > 0) {
                    $validProperties++;
                } else {
                    $invalidProperties++;
                }
            }
            
            $this->command->info('✅ Data quality check completed!');
            $this->command->info("   - Valid properties: {$validProperties}");
            $this->command->info("   - Invalid properties: {$invalidProperties}");
            $this->command->info('');
        } catch (Exception $e) {
            $this->command->error('❌ Data quality check failed: ' . $e->getMessage());
            $this->command->info('');
        }

        $this->command->info('🎉 SEEDER VERIFICATION COMPLETE!');
        $this->command->info('===============================');
        $this->command->info('All seeders have been tested and verified.');
        $this->command->info('Check the results above for any issues.');
    }
}


