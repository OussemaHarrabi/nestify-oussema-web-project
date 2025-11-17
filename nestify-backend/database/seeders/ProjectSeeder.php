<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Promoter;
use App\Models\Project;
use App\Models\Property;
use Illuminate\Support\Str;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promoter = Promoter::first();
        
        if (!$promoter) {
            $this->command->error('No promoter found. Please create a promoter first.');
            return;
        }

        // Sample projects data
        $projects = [
            [
                'name' => 'Résidence Panamera III',
                'city' => 'Sousse',
                'district' => 'Sahloul',
                'description' => 'Une résidence moderne offrant confort et standing dans le quartier prisé de Sahloul. Architecture contemporaine avec finitions haut de gamme.',
                'amenities' => ['Piscine', 'Salle de sport', 'Gardiennage 24/7', 'Parking souterrain', 'Ascenseur', 'Espace vert'],
                'total_units' => 24,
                'buildings_count' => 1,
                'total_floors' => 6,
            ],
            [
                'name' => 'Résidence Les Jasmins',
                'city' => 'Tunis',
                'district' => 'Les Berges du Lac',
                'description' => 'Projet résidentiel de luxe au cœur des Berges du Lac. Vue panoramique sur le lac avec des espaces de vie généreux.',
                'amenities' => ['Piscine olympique', 'Spa & Wellness', 'Gardiennage 24/7', 'Parking privé', 'Terrasses communes', 'Playground'],
                'total_units' => 36,
                'buildings_count' => 2,
                'total_floors' => 8,
            ],
            [
                'name' => 'Complexe Al Andalus',
                'city' => 'Hammamet',
                'district' => 'Yasmine Hammamet',
                'description' => 'Complexe résidentiel touristique offrant un style de vie méditerranéen. À proximité immédiate de la plage et des commodités.',
                'amenities' => ['Piscine', 'Salle de sport', 'Gardiennage', 'Restaurant', 'Accès plage privée', 'Centre commercial'],
                'total_units' => 48,
                'buildings_count' => 3,
                'total_floors' => 5,
            ],
        ];

        foreach ($projects as $index => $projectData) {
            $project = Project::create([
                'promoter_id' => $promoter->id,
                'name' => $projectData['name'],
                'slug' => Str::slug($projectData['name']),
                'description' => $projectData['description'],
                'reference' => 'PRJ-2024-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'city' => $projectData['city'],
                'district' => $projectData['district'],
                'address' => "Avenue Principal, {$projectData['district']}, {$projectData['city']}",
                'status' => 'under_construction',
                'launch_date' => now()->subMonths(rand(6, 18)),
                'expected_delivery' => now()->addMonths(rand(6, 24)),
                'construction_progress' => rand(30, 85) . '% complété',
                'total_units' => $projectData['total_units'],
                'total_floors' => $projectData['total_floors'],
                'buildings_count' => $projectData['buildings_count'],
                'amenities' => $projectData['amenities'],
                'nearby_facilities' => ['Écoles', 'Centres commerciaux', 'Hôpitaux', 'Transport public'],
                'tags' => ['VEFA', 'Neuf', 'Standing'],
                'is_published' => true,
                'is_featured' => $index === 0,
                'published_at' => now()->subDays(rand(10, 60)),
            ]);

            $this->command->info("Created project: {$project->name}");

            // Create properties for this project
            $this->createPropertiesForProject($project, $projectData['total_units']);
        }

        // Update promoter counts
        $promoter->updateProjectCounts();
        
        $this->command->info('Projects seeded successfully!');
    }

    private function createPropertiesForProject(Project $project, int $count)
    {
        $types = ['Appartement', 'Appartement', 'Appartement', 'Duplex', 'Studio'];
        $features = [
            ['Balcon', 'Parking', 'Climatisation'],
            ['Terrasse', 'Cave', 'Parking'],
            ['Jardin privatif', 'Parking', 'Piscine privée'],
            ['Vue mer', 'Parking', 'Dressing'],
        ];

        $buildings = $project->buildings_count > 1 
            ? ['Building A', 'Building B', 'Building C']
            : ['Building A'];

        for ($i = 1; $i <= $count; $i++) {
            $type = $types[array_rand($types)];
            $surface = $this->getSurfaceByType($type);
            $bedrooms = $this->getBedroomsByType($type);
            $price = $surface * rand(2500, 4500);
            
            $building = $buildings[array_rand($buildings)];
            $floor = rand(1, $project->total_floors);
            $unitNumber = sprintf("%d%02d", $floor, $i % 10);

            Property::create([
                'user_id' => $project->promoter->user_id,
                'project_id' => $project->id,
                'title' => "{$type} {$surface}m² - {$project->name}",
                'description' => "Magnifique {$type} de {$surface}m² avec {$bedrooms} chambres dans le projet {$project->name}. Finitions de qualité, lumineux et spacieux.",
                'price' => $price,
                'type' => $type,
                'surface' => $surface,
                'reference' => strtoupper(Str::random(8)),
                'unit_number' => $unitNumber,
                'building_name' => $building,
                'validated' => true,
                'availability_status' => $this->getRandomAvailability(),
                'published_date' => now(),
                'city' => $project->city,
                'district' => $project->district,
                'address' => $project->address,
                'latitude' => $project->latitude,
                'longitude' => $project->longitude,
                'rooms' => $bedrooms + 1,
                'bedrooms' => $bedrooms,
                'bathrooms' => rand(1, 2),
                'floor' => $floor,
                'total_floors' => $project->total_floors,
                'parking' => rand(0, 1) === 1,
                'elevator' => true,
                'terrace' => rand(0, 1) === 1,
                'garden' => $floor === 0 && rand(0, 1) === 1,
                'features' => $features[array_rand($features)],
                'is_vefa' => true,
                'delivery_date' => $project->expected_delivery,
                'construction_progress' => $project->construction_progress,
                'construction_progress_percentage' => (int) filter_var($project->construction_progress, FILTER_SANITIZE_NUMBER_INT),
            ]);
        }

        // Update project unit counts and pricing
        $project->updateUnitCounts();
        $project->calculateStartingPrice();
        $project->calculateAveragePricePerSqm();

        $this->command->info("  └─ Created {$count} properties for {$project->name}");
    }

    private function getSurfaceByType(string $type): int
    {
        return match($type) {
            'Studio' => rand(35, 50),
            'Appartement' => rand(80, 150),
            'Duplex' => rand(140, 200),
            'Villa' => rand(180, 350),
            default => rand(80, 120),
        };
    }

    private function getBedroomsByType(string $type): int
    {
        return match($type) {
            'Studio' => 0,
            'Appartement' => rand(1, 3),
            'Duplex' => rand(2, 4),
            'Villa' => rand(3, 5),
            default => 2,
        };
    }

    private function getRandomAvailability(): string
    {
        $statuses = ['available', 'available', 'available', 'reserved', 'sold'];
        return $statuses[array_rand($statuses)];
    }
}

