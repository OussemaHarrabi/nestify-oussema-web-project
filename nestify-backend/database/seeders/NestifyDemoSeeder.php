<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Promoter;
use App\Models\Project;
use App\Models\Property;
use App\Models\Lead;
use Illuminate\Support\Str;

class NestifyDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo "🚀 Starting Nestify Demo Data Seeding...\n\n";

        // 1. Create Admin User
        echo "👤 Creating Admin User...\n";
        $admin = User::firstOrCreate(
            ['email' => 'admin@nestify.tn'],
            [
                'name' => 'Admin Nestify',
                'password' => bcrypt('admin123'),
                'phone' => '+216 70 000 000',
                'user_type' => 'admin',
                'is_active' => true,
            ]
        );
        echo "✅ Admin created: {$admin->email}\n\n";

        // 2. Create Promoters
        echo "🏢 Creating Promoters...\n";
        
        $promoter1 = $this->createPromoter(
            'Société Promotion Immobilière Tunisia',
            'promoteur1@nestify.tn',
            'PromoterPass123!',
            '+216 71 123 456',
            'LIC-TN-2020-001',
            true // verified
        );
        echo "✅ Promoter 1: {$promoter1->company_name}\n";

        $promoter2 = $this->createPromoter(
            'Mediterranean Properties Development',
            'promoteur2@nestify.tn',
            'PromoterPass123!',
            '+216 71 234 567',
            'LIC-TN-2021-002',
            true // verified
        );
        echo "✅ Promoter 2: {$promoter2->company_name}\n";

        $promoter3 = $this->createPromoter(
            'Tunis Bay Real Estate',
            'promoteur3@nestify.tn',
            'PromoterPass123!',
            '+216 71 345 678',
            'LIC-TN-2022-003',
            false // not verified yet
        );
        echo "✅ Promoter 3: {$promoter3->company_name} (Pending Verification)\n\n";

        // 3. Create Projects for Promoter 1
        echo "🏗️ Creating Projects...\n";
        
        $project1 = $this->createProject($promoter1, [
            'name' => 'Résidence Les Jardins de Carthage',
            'description' => 'Projet résidentiel d\'exception situé dans le prestigieux quartier de Carthage. Architecture moderne, vue mer panoramique, 85 appartements de standing avec finitions premium.',
            'city' => 'Tunis',
            'district' => 'Carthage',
            'address' => 'Avenue Habib Bourguiba, Carthage Hannibal',
            'latitude' => 36.8525,
            'longitude' => 10.3233,
            'status' => 'under_construction',
            'launch_date' => '2025-01-15',
            'expected_delivery' => '2026-12-31',
            'construction_progress' => '55% des travaux achevés',
            'total_units' => 85,
            'total_floors' => 6,
            'buildings_count' => 2,
            'amenities' => ['Piscine olympique', 'Salle de sport', 'Gardiennage 24/7', 'Parking souterrain', 'Espaces verts', 'Terrains de jeux'],
            'nearby_facilities' => ['Plage à 300m', 'Carrefour 2km', 'Lycée Gustave Flaubert', 'Clinique Hannibal', 'Aéroport 10km'],
            'tags' => ['VEFA', 'Vue mer', 'Haut standing', 'Neuf'],
            'is_published' => true,
        ]);
        echo "✅ Project: {$project1->name}\n";

        $project2 = $this->createProject($promoter1, [
            'name' => 'Résidence Marina Bay',
            'description' => 'Complexe résidentiel moderne au cœur de La Marsa avec vue directe sur la marina. 60 appartements luxueux avec accès privé à la plage.',
            'city' => 'Tunis',
            'district' => 'La Marsa',
            'address' => 'Route de la Corniche, La Marsa',
            'latitude' => 36.8783,
            'longitude' => 10.3250,
            'status' => 'near_completion',
            'launch_date' => '2024-06-01',
            'expected_delivery' => '2025-12-31',
            'construction_progress' => '85% des travaux achevés',
            'total_units' => 60,
            'total_floors' => 5,
            'buildings_count' => 1,
            'amenities' => ['Piscine infinity', 'Spa & Wellness', 'Conciergerie', 'Parking privatif', 'Salle de réception'],
            'nearby_facilities' => ['Marina à 100m', 'Plage privée', 'Restaurants gastronomiques', 'Golf Carthage'],
            'tags' => ['VEFA', 'Vue marina', 'Luxe', 'Plage privée'],
            'is_published' => true,
        ]);
        echo "✅ Project: {$project2->name}\n";

        // 4. Create Projects for Promoter 2
        $project3 = $this->createProject($promoter2, [
            'name' => 'Résidence Mediterranea Sousse',
            'description' => 'Projet résidentiel haut standing à Sousse avec vue imprenable sur la mer. 120 unités modernes avec tous les équipements.',
            'city' => 'Sousse',
            'district' => 'Khezama',
            'address' => 'Zone touristique Khezama',
            'latitude' => 35.8256,
            'longitude' => 10.6369,
            'status' => 'under_construction',
            'launch_date' => '2025-03-01',
            'expected_delivery' => '2027-06-30',
            'construction_progress' => '35% des travaux achevés',
            'total_units' => 120,
            'total_floors' => 8,
            'buildings_count' => 3,
            'amenities' => ['3 Piscines', 'Club enfants', 'Salle de sport', 'Supérette', 'Pharmacie', 'Restaurant'],
            'nearby_facilities' => ['Plage 200m', 'Sousse City Center', 'Port El Kantaoui', 'Hôpitaux'],
            'tags' => ['VEFA', 'Vue mer', 'Résidence fermée', 'Famille'],
            'is_published' => true,
        ]);
        echo "✅ Project: {$project3->name}\n";

        $project4 = $this->createProject($promoter2, [
            'name' => 'Les Oliviers de Hammamet',
            'description' => 'Villas individuelles dans un cadre verdoyant à Hammamet. 40 villas de luxe avec jardins privés et piscines.',
            'city' => 'Hammamet',
            'district' => 'Hammamet Sud',
            'address' => 'Zone Touristique Hammamet Sud',
            'latitude' => 36.3667,
            'longitude' => 10.5833,
            'status' => 'planning',
            'launch_date' => '2025-09-01',
            'expected_delivery' => '2027-12-31',
            'construction_progress' => 'Études architecturales en cours',
            'total_units' => 40,
            'total_floors' => 2,
            'buildings_count' => 40,
            'amenities' => ['Club house', 'Piscines privées', 'Sécurité 24/7', 'Jardins paysagers', 'Terrain de tennis'],
            'nearby_facilities' => ['Golf Yasmine', 'Port de plaisance', 'Carthageland', 'Centre-ville 5km'],
            'tags' => ['Villas', 'Luxe', 'Jardin', 'Piscine privée'],
            'is_published' => false, // Not published yet
        ]);
        echo "✅ Project: {$project4->name} (Not Published)\n";

        // 5. Create Projects for Promoter 3 (Unverified)
        $project5 = $this->createProject($promoter3, [
            'name' => 'Tunis Bay Residence',
            'description' => 'Projet moderne dans la nouvelle zone Tunis Bay avec vue sur le lac.',
            'city' => 'Tunis',
            'district' => 'Tunis Bay',
            'address' => 'Avenue Tunis Bay',
            'status' => 'planning',
            'total_units' => 50,
            'is_published' => false,
        ]);
        echo "✅ Project: {$project5->name} (Unverified Promoter)\n\n";

        // 6. Create Properties for Project 1
        echo "🏠 Creating Properties...\n";
        
        $property1 = $this->createProperty($project1, $promoter1->user_id, [
            'title' => 'Appartement S+3 Vue Mer Panoramique',
            'description' => 'Magnifique appartement de 145m² au 4ème étage avec vue imprenable sur la mer et Carthage. Salon 40m², 3 chambres, 2 SDB, cuisine équipée, 2 balcons (15m²). Finitions marbre, climatisation centralisée.',
            'price' => 520000,
            'type' => 'Appartement',
            'surface' => 145,
            'unit_number' => 'A401',
            'building_name' => 'Building A - Vue Mer',
            'floor' => 4,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'features' => ['Vue mer panoramique', 'Double vitrage', 'Cuisine équipée', 'Dressing', 'Climatisation', 'Carrelage marbre'],
            'availability_status' => 'available',
        ]);

        $property2 = $this->createProperty($project1, $promoter1->user_id, [
            'title' => 'Appartement S+2 Standing',
            'description' => 'Appartement lumineux de 110m² avec balcon, 2 chambres spacieuses, salon moderne, cuisine équipée. Parking et cave inclus.',
            'price' => 380000,
            'type' => 'Appartement',
            'surface' => 110,
            'unit_number' => 'A302',
            'floor' => 3,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'features' => ['Balcon 10m²', 'Parquet', 'Cave', 'Parking'],
            'availability_status' => 'available',
        ]);

        $property3 = $this->createProperty($project1, $promoter1->user_id, [
            'title' => 'Penthouse S+4 Vue Mer Exceptionnelle',
            'description' => 'Penthouse d\'exception de 220m² avec terrasse 80m², vue mer à 360°. 4 chambres dont suite parentale 45m², 3 SDB, cuisine américaine haut de gamme, jacuzzi sur terrasse.',
            'price' => 980000,
            'type' => 'Appartement',
            'surface' => 220,
            'unit_number' => 'A601',
            'floor' => 6,
            'bedrooms' => 4,
            'bathrooms' => 3,
            'features' => ['Terrasse 80m²', 'Vue 360°', 'Jacuzzi', 'Domotique', 'Cuisine italienne', 'Suite parentale'],
            'availability_status' => 'reserved',
        ]);

        $property4 = $this->createProperty($project1, $promoter1->user_id, [
            'title' => 'Studio Moderne 55m²',
            'description' => 'Studio fonctionnel avec kitchenette équipée, salle de bain moderne, balcon. Idéal investissement locatif ou jeune couple.',
            'price' => 185000,
            'type' => 'Studio',
            'surface' => 55,
            'unit_number' => 'B105',
            'floor' => 1,
            'bedrooms' => 0,
            'bathrooms' => 1,
            'features' => ['Balcon', 'Kitchenette équipée', 'Parking'],
            'availability_status' => 'available',
        ]);

        $property5 = $this->createProperty($project1, $promoter1->user_id, [
            'title' => 'Duplex S+4 Dernier Étage',
            'description' => 'Superbe duplex de 180m² aux 5ème et 6ème étages. Espace de vie lumineux, 4 chambres, terrasse privative 40m². Vue mer dégagée.',
            'price' => 750000,
            'type' => 'Duplex',
            'surface' => 180,
            'unit_number' => 'A501-601',
            'floor' => 5,
            'bedrooms' => 4,
            'bathrooms' => 2,
            'features' => ['Duplex', 'Terrasse privée', 'Vue mer', 'Cheminée', 'Escalier intérieur'],
            'availability_status' => 'sold',
        ]);

        echo "✅ Created 5 properties for {$project1->name}\n";

        // 7. Create Properties for Project 2
        $property6 = $this->createProperty($project2, $promoter1->user_id, [
            'title' => 'Appartement S+3 Vue Marina',
            'description' => 'Appartement luxueux de 135m² face à la marina. 3 chambres, finitions haut de gamme, terrasse 20m² avec vue directe sur les bateaux.',
            'price' => 650000,
            'type' => 'Appartement',
            'surface' => 135,
            'unit_number' => 'M301',
            'floor' => 3,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'features' => ['Vue marina', 'Terrasse 20m²', 'Parquet massif', 'Cuisine allemande'],
            'availability_status' => 'available',
        ]);

        $property7 = $this->createProperty($project2, $promoter1->user_id, [
            'title' => 'Appartement S+2 Moderne',
            'description' => 'Appartement contemporain de 95m², 2 chambres, salon ouvert, cuisine équipée. Accès direct plage privée.',
            'price' => 420000,
            'type' => 'Appartement',
            'surface' => 95,
            'unit_number' => 'M204',
            'floor' => 2,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'features' => ['Accès plage', 'Design moderne', 'Smart home'],
            'availability_status' => 'available',
        ]);

        echo "✅ Created 2 properties for {$project2->name}\n";

        // 8. Create Properties for Project 3
        $property8 = $this->createProperty($project3, $promoter2->user_id, [
            'title' => 'Appartement S+3 Vue Mer Sousse',
            'description' => 'Appartement spacieux de 140m² à Sousse avec vue mer. 3 chambres, 2 SDB, grand balcon, finitions soignées.',
            'price' => 450000,
            'type' => 'Appartement',
            'surface' => 140,
            'unit_number' => 'S401',
            'floor' => 4,
            'bedrooms' => 3,
            'bathrooms' => 2,
            'features' => ['Vue mer', 'Grand balcon', 'Climatisation', 'Parking'],
            'availability_status' => 'available',
        ]);

        $property9 = $this->createProperty($project3, $promoter2->user_id, [
            'title' => 'Appartement S+2 Jardin',
            'description' => 'Appartement RDC avec jardin privatif 50m². 2 chambres, parfait pour famille. Accès direct piscine.',
            'price' => 380000,
            'type' => 'Appartement',
            'surface' => 105,
            'unit_number' => 'S101',
            'floor' => 0,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'features' => ['Jardin 50m²', 'RDC', 'Accès piscine', 'Idéal famille'],
            'availability_status' => 'available',
        ]);

        echo "✅ Created 2 properties for {$project3->name}\n\n";

        // 9. Create Leads
        echo "📊 Creating Leads...\n";
        
        $this->createLead($promoter1->id, $project1->id, $property1->id, [
            'name' => 'Ahmed Ben Salem',
            'email' => 'ahmed.salem@gmail.com',
            'phone' => '+216 98 123 456',
            'message' => 'Je suis très intéressé par l\'appartement S+3 vue mer. J\'aimerais organiser une visite ce weekend. Budget disponible 500K TND.',
            'type' => 'viewing_request',
            'status' => 'qualified',
            'priority' => 'high',
        ]);

        $this->createLead($promoter1->id, $project1->id, $property2->id, [
            'name' => 'Fatma Mansouri',
            'email' => 'fatma.m@yahoo.fr',
            'phone' => '+216 22 234 567',
            'message' => 'Bonjour, je cherche un appartement S+2 dans votre résidence. Quels sont les prix et les modalités de paiement?',
            'type' => 'price_inquiry',
            'status' => 'contacted',
            'priority' => 'medium',
        ]);

        $this->createLead($promoter1->id, $project1->id, null, [
            'name' => 'Mohamed Trabelsi',
            'email' => 'med.trabelsi@hotmail.com',
            'phone' => '+216 55 345 678',
            'message' => 'Intéressé par le projet Les Jardins de Carthage. Pouvez-vous m\'envoyer le dossier technique complet?',
            'type' => 'general_inquiry',
            'status' => 'new',
            'priority' => 'low',
        ]);

        $this->createLead($promoter1->id, $project2->id, $property6->id, [
            'name' => 'Karim Bouazizi',
            'email' => 'k.bouazizi@live.com',
            'phone' => '+216 29 456 789',
            'message' => 'Je souhaite acheter l\'appartement S+3 vue marina. Disponible pour signature rapide. Financement bancaire pré-approuvé.',
            'type' => 'contact_request',
            'status' => 'converted',
            'priority' => 'high',
        ]);

        $this->createLead($promoter1->id, $project2->id, null, [
            'name' => 'Salma Hajji',
            'email' => 'salma.h@gmail.com',
            'phone' => '+216 52 567 890',
            'message' => 'Bonjour, je voudrais avoir des informations sur les appartements disponibles à La Marsa.',
            'type' => 'general_inquiry',
            'status' => 'new',
            'priority' => 'medium',
        ]);

        $this->createLead($promoter2->id, $project3->id, $property9->id, [
            'name' => 'Youssef Gharbi',
            'email' => 'youssef.g@outlook.com',
            'phone' => '+216 24 678 901',
            'message' => 'Très intéressé par l\'appartement avec jardin à Sousse pour ma famille. Possibilité de visite cette semaine?',
            'type' => 'viewing_request',
            'status' => 'contacted',
            'priority' => 'high',
        ]);

        echo "✅ Created 6 leads across multiple projects\n\n";

        // 10. Update project and promoter statistics
        echo "📈 Updating Statistics...\n";
        
        foreach ([$project1, $project2, $project3, $project4, $project5] as $project) {
            $project->updateUnitCounts();
            $project->calculateStartingPrice();
            $project->calculateAveragePricePerSqm();
            $project->calculatePriceRange();
        }

        foreach ([$promoter1, $promoter2, $promoter3] as $promoter) {
            $promoter->updateProjectCounts();
        }

        echo "✅ Statistics updated\n\n";

        // Summary
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ SEEDING COMPLETE!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        echo "📊 Summary:\n";
        echo "  • 1 Admin user\n";
        echo "  • 3 Promoters (2 verified, 1 pending)\n";
        echo "  • 5 Projects (4 published, 1 pending)\n";
        echo "  • 9 Properties (various types and statuses)\n";
        echo "  • 6 Leads (various statuses)\n\n";
        
        echo "🔐 Login Credentials:\n";
        echo "  Admin:\n";
        echo "    Email: admin@nestify.tn\n";
        echo "    Password: admin123\n\n";
        echo "  Promoter 1:\n";
        echo "    Email: promoteur1@nestify.tn\n";
        echo "    Password: PromoterPass123!\n\n";
        echo "  Promoter 2:\n";
        echo "    Email: promoteur2@nestify.tn\n";
        echo "    Password: PromoterPass123!\n\n";
        echo "  Promoter 3 (Unverified):\n";
        echo "    Email: promoteur3@nestify.tn\n";
        echo "    Password: PromoterPass123!\n\n";
    }

    private function createPromoter($companyName, $email, $password, $phone, $license, $verified = false)
    {
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $companyName,
                'password' => bcrypt($password),
                'phone' => $phone,
                'user_type' => 'promoter',
                'is_active' => true,
            ]
        );

        return Promoter::firstOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $companyName,
                'primary_phone' => $phone,
                'primary_email' => $email,
                'license_number' => $license,
                'verified' => $verified,
                'verified_at' => $verified ? now() : null,
                'description' => $verified ? 'Promoteur immobilier de confiance avec plusieurs années d\'expérience dans le développement de projets résidentiels haut standing en Tunisie.' : null,
            ]
        );
    }

    private function createProject($promoter, $data)
    {
        $slug = Str::slug($data['name']);
        
        // Check if project already exists
        $existing = Project::where('slug', $slug)->first();
        if ($existing) {
            return $existing;
        }
        
        $defaults = [
            'promoter_id' => $promoter->id,
            'slug' => $slug,
            'reference' => 'PRJ-' . date('Y') . '-' . strtoupper(Str::random(6)),
            'available_units' => $data['total_units'] ?? 0,
            'city' => $data['city'] ?? 'Tunis',
            'address' => $data['address'] ?? 'Adresse non spécifiée',
            'description' => $data['description'] ?? 'Description du projet',
            'status' => $data['status'] ?? 'planning',
            'total_units' => $data['total_units'] ?? 0,
            'buildings_count' => $data['buildings_count'] ?? 1,
            'is_published' => $data['is_published'] ?? false,
            'published_at' => ($data['is_published'] ?? false) ? now() : null,
        ];

        return Project::create(array_merge($defaults, $data));
    }

    private function createProperty($project, $userId, $data)
    {
        $defaults = [
            'project_id' => $project->id,
            'user_id' => $userId,
            'reference' => strtoupper(Str::random(10)),
            'validated' => true,
            'published_date' => now(),
            'city' => $project->city,
            'district' => $project->district,
            'address' => $project->address,
            'latitude' => $project->latitude,
            'longitude' => $project->longitude,
            'is_vefa' => true,
            'delivery_date' => $project->expected_delivery,
            'construction_progress' => $project->construction_progress,
            'rooms' => ($data['bedrooms'] ?? 0) + 1,
            'parking' => true,
            'elevator' => true,
            'terrace' => in_array($data['type'] ?? '', ['Appartement', 'Duplex']),
            'garden' => false,
        ];

        return Property::create(array_merge($defaults, $data));
    }

    private function createLead($promoterId, $projectId, $propertyId, $data)
    {
        $defaults = [
            'promoter_id' => $promoterId,
            'project_id' => $projectId,
            'property_id' => $propertyId,
            'status' => $data['status'] ?? 'new',
            'priority' => $data['priority'] ?? 'medium',
            'source' => 'website',
            'ip_address' => '197.27.' . rand(1, 255) . '.' . rand(1, 255), // Tunisian IP range
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        ];

        $lead = Lead::create(array_merge($defaults, $data));

        // Add timestamps based on status
        if ($lead->status === 'contacted' || $lead->status === 'qualified' || $lead->status === 'converted') {
            $lead->last_contacted_at = now()->subDays(rand(1, 5));
        }

        if ($lead->status === 'converted') {
            $lead->is_converted = true;
            $lead->converted_at = now()->subDays(rand(1, 3));
            $lead->converted_property_id = $propertyId;
        }

        $lead->save();

        return $lead;
    }
}
