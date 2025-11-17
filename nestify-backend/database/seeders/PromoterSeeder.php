<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promoter;
use App\Models\User;

class PromoterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample promoter users
        $promoters = [
            [
                'user' => [
                    'name' => 'Ahmed Ben Ali',
                    'email' => 'ahmed@promoter1.tn',
                    'password' => bcrypt('password'),
                    'phone' => '+216 20 123 456',
                    'user_type' => 'promoter',
                ],
                'promoter' => [
                    'company_name' => 'Immobilier Premium Tunisie',
                    'description' => 'Spécialiste des projets résidentiels de luxe et VEFA en Tunisie. Plus de 15 ans d\'expérience dans le développement immobilier.',
                    'website' => 'https://premium-immobilier.tn',
                    'primary_phone' => '+216 20 123 456',
                    'primary_email' => 'ahmed@promoter1.tn',
                    'license_number' => 'LIC-2024-001',
                    'established_date' => '2010-03-15',
                    'employee_count' => 25,
                    'specializations' => ['Résidentiel', 'Luxe', 'VEFA'],
                    'headquarters_address' => 'Avenue Habib Bourguiba, Tunis 1000',
                    'headquarters_city' => 'Tunis',
                    'verified' => true,
                    'featured' => true,
                    'rating' => 4.8,
                    'review_count' => 127,
                ]
            ],
            [
                'user' => [
                    'name' => 'Fatma Trabelsi',
                    'email' => 'fatma@promoter2.tn',
                    'password' => bcrypt('password'),
                    'phone' => '+216 22 987 654',
                    'user_type' => 'promoter',
                ],
                'promoter' => [
                    'company_name' => 'Résidences du Cap',
                    'description' => 'Développeur immobilier spécialisé dans les projets côtiers et résidentiels haut de gamme à Sousse et Hammamet.',
                    'website' => 'https://residences-cap.tn',
                    'primary_phone' => '+216 22 987 654',
                    'primary_email' => 'fatma@promoter2.tn',
                    'license_number' => 'LIC-2024-002',
                    'established_date' => '2015-07-20',
                    'employee_count' => 18,
                    'specializations' => ['Résidentiel', 'Touristique', 'VEFA'],
                    'headquarters_address' => 'Zone touristique, Hammamet 8050',
                    'headquarters_city' => 'Hammamet',
                    'verified' => true,
                    'featured' => false,
                    'rating' => 4.6,
                    'review_count' => 89,
                ]
            ],
            [
                'user' => [
                    'name' => 'Mohamed Khelil',
                    'email' => 'mohamed@promoter3.tn',
                    'password' => bcrypt('password'),
                    'phone' => '+216 25 456 789',
                    'user_type' => 'promoter',
                ],
                'promoter' => [
                    'company_name' => 'Urban Development Group',
                    'description' => 'Groupe de développement urbain spécialisé dans les projets mixtes et les résidences modernes à Tunis et ses environs.',
                    'website' => 'https://urbandev.tn',
                    'primary_phone' => '+216 25 456 789',
                    'primary_email' => 'mohamed@promoter3.tn',
                    'license_number' => 'LIC-2024-003',
                    'established_date' => '2018-01-10',
                    'employee_count' => 32,
                    'specializations' => ['Résidentiel', 'Commercial', 'Mixte'],
                    'headquarters_address' => 'Les Berges du Lac, Tunis 1053',
                    'headquarters_city' => 'Tunis',
                    'verified' => true,
                    'featured' => true,
                    'rating' => 4.7,
                    'review_count' => 156,
                ]
            ]
        ];

        foreach ($promoters as $promoterData) {
            // Create user
            $user = User::firstOrCreate(
                ['email' => $promoterData['user']['email']],
                $promoterData['user']
            );

            // Create promoter profile
            $promoter = Promoter::firstOrCreate(
                ['user_id' => $user->id],
                array_merge($promoterData['promoter'], ['user_id' => $user->id])
            );

            $this->command->info("✅ Created promoter: {$promoter->company_name}");
        }

        $this->command->info('🎉 Sample promoters created successfully!');
    }
}

