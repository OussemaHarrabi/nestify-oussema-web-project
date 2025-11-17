<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Agency;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@nestify.tn',
            'password' => Hash::make('password'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create agency user
        $agency = User::create([
            'name' => 'Société Immobiliere Belkacem',
            'email' => 'contact@belkacem-immo.tn',
            'password' => Hash::make('password'),
            'phone' => '+216 25925200',
            'user_type' => 'promoter',
            'email_verified_at' => now(),
        ]);

        Agency::create([
            'user_id' => $agency->id,
            'company_name' => 'Société immobiliere Belkacem',
            'website' => 'https://belkacem-immo.tn',
            'verified' => true,
            'additional_phones' => ['+216 20402650', '+216 25925200'],
        ]);

        // Create some client users
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Client User {$i}",
                'email' => "client{$i}@example.com",
                'password' => Hash::make('password'),
                'phone' => '+216 2' . sprintf('%07d', rand(1000000, 9999999)),
                'user_type' => 'client',
                'email_verified_at' => now(),
            ]);
        }

        // Import real estate data from JSON file
        $this->call(RealEstateDataSeeder::class);

        // Run migration seeders for new architecture
        $this->call([
            PromoterSeeder::class,
            ProjectSeeder::class,
        ]);

        $this->command->info('✅ Real estate database seeded successfully with your data!');
        $this->command->info('Created:');
        $this->command->info('- 1 Admin user (admin@nestify.tn / password)');
        $this->command->info('- 1 Agency user (contact@belkacem-immo.tn / password)');
        $this->command->info('- 5 Client users (client1@example.com to client5@example.com / password)');
        $this->command->info('- Real properties imported from your JSON data');
        $this->command->info('- Sample promoters created');
        $this->command->info('- Sample projects created');
    }
}
