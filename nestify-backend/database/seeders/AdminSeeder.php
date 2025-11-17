<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Create super admin
        User::firstOrCreate([
            'email' => 'admin@nestify.tn'
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('admin123!@#'),
            'phone' => '+216 20 123 456',
            'user_type' => 'admin',
            'admin_role' => 'super_admin',
            'is_active' => true,
            'permissions' => [
                'manage_users',
                'manage_properties', 
                'manage_agencies',
                'view_analytics',
                'moderate_content',
                'system_settings'
            ],
            'bio' => 'Platform Super Administrator',
            'email_verified_at' => now(),
        ]);

        // Create content moderator
        User::firstOrCreate([
            'email' => 'moderator@nestify.tn'
        ], [
            'name' => 'Content Moderator',
            'password' => Hash::make('moderator123!'),
            'phone' => '+216 20 123 457',
            'user_type' => 'admin',
            'admin_role' => 'content_moderator',
            'is_active' => true,
            'permissions' => [
                'moderate_content',
                'manage_properties',
                'view_analytics'
            ],
            'bio' => 'Content Moderation Specialist',
            'email_verified_at' => now(),
        ]);

        // Create support admin
        User::firstOrCreate([
            'email' => 'support@nestify.tn'
        ], [
            'name' => 'Support Admin',
            'password' => Hash::make('support123!'),
            'phone' => '+216 20 123 458',
            'user_type' => 'admin',
            'admin_role' => 'support',
            'is_active' => true,
            'permissions' => [
                'view_analytics',
                'manage_users'
            ],
            'bio' => 'Customer Support Administrator',
            'email_verified_at' => now(),
        ]);

        // Create sample regular users
        User::firstOrCreate([
            'email' => 'buyer1@nestify.tn'
        ], [
            'name' => 'Ahmed Ben Ali',
            'password' => Hash::make('buyer123!'),
            'phone' => '+216 22 111 222',
            'user_type' => 'client',
            'is_active' => true,
            'preferences' => [
                'property_types' => ['Appartement', 'Villa'],
                'price_range' => ['min' => 100000, 'max' => 500000],
                'cities' => ['Tunis', 'Sousse', 'Sfax'],
                'rooms_min' => 2,
                'notifications' => true
            ],
            'city' => 'Tunis',
            'bio' => 'Looking for a family home',
            'email_verified_at' => now(),
        ]);

        User::firstOrCreate([
            'email' => 'buyer2@nestify.tn'
        ], [
            'name' => 'Fatma Trabelsi',
            'password' => Hash::make('buyer456!'),
            'phone' => '+216 22 333 444',
            'user_type' => 'client',
            'is_active' => true,
            'preferences' => [
                'property_types' => ['Appartement'],
                'price_range' => ['min' => 200000, 'max' => 800000],
                'cities' => ['La Marsa', 'Sidi Bou Said'],
                'rooms_min' => 3,
                'notifications' => true
            ],
            'city' => 'Tunis',
            'bio' => 'Investment property seeker',
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin and sample users created successfully!');
    }
}
