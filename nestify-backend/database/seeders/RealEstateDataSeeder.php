<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RealEstateDataSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Property::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Load JSON data
        $jsonPath = database_path('../Real_estate_Nestify.nestify_properties.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error('JSON file not found at: ' . $jsonPath);
            return;
        }

        $jsonData = file_get_contents($jsonPath);
        $properties = json_decode($jsonData, true);

        if (!$properties) {
            $this->command->error('Failed to decode JSON data');
            return;
        }

        // Get first user as owner for all properties
        $user = User::first();
        if (!$user) {
            // Create a default user if none exists
            $user = User::create([
                'name' => 'Real Estate Admin',
                'email' => 'admin@nestify.com',
                'password' => bcrypt('password'),
                'phone' => '+216 12345678',
                'user_type' => 'agent'
            ]);
        }

        $this->command->info('Starting to import ' . count($properties) . ' properties...');
        
        $imported = 0;
        $skipped = 0;

        foreach ($properties as $index => $propertyData) {
            try {
                // Extract and clean data
                $title = $this->cleanString($propertyData['title'] ?? 'Property ' . ($index + 1));
                $description = $this->cleanString($propertyData['description'] ?? '');
                $price = $this->extractPrice($propertyData['price'] ?? 0);
                $type = $this->mapPropertyType($propertyData['type'] ?? 'apartment');
                $surface = (int) ($propertyData['surface'] ?? 0);
                
                // Location data
                $locationData = $propertyData['location_id'] ?? [];
                $city = $this->extractCity($locationData['city'] ?? 'Unknown');
                $district = $this->cleanString($locationData['district'] ?? '');
                $address = $this->cleanString($locationData['address'] ?? $district);
                
                // Apartment details
                $apartmentDetails = $propertyData['apartment_details_id'] ?? [];
                $rooms = (int) ($apartmentDetails['rooms'] ?? 3);
                $bedrooms = (int) ($apartmentDetails['bedrooms'] ?? 2);
                $bathrooms = (int) ($apartmentDetails['bathrooms'] ?? 1);
                $floor = (int) ($apartmentDetails['floor'] ?? 1);
                $totalFloors = (int) ($apartmentDetails['total_floors'] ?? 5);
                $parking = (bool) ($apartmentDetails['parking'] ?? false);
                $elevator = (bool) ($apartmentDetails['elevator'] ?? false);
                $terrace = (bool) ($apartmentDetails['terrace'] ?? false);
                $garden = (bool) ($apartmentDetails['garden'] ?? false);
                
                // Features
                $features = $apartmentDetails['features'] ?? [];
                $mappedFeatures = $this->mapFeatures($features);
                
                // Images
                $images = $propertyData['images'] ?? [];

                // Generate reference
                $reference = 'NESTIFY_' . strtoupper(substr(md5($propertyData['_id'] ?? uniqid()), 0, 8));

                // Skip if essential data is missing
                if (empty($title) || $surface <= 0) {
                    $this->command->warn("Skipping property: title='{$title}', surface={$surface}");
                    $skipped++;
                    continue;
                }

                // Set a default price if none or zero
                if ($price <= 0) {
                    // Generate a reasonable price based on surface and type
                    $basePrice = 150000; // Base price in TND
                    if ($type === 'Villa') $basePrice = 300000;
                    elseif ($type === 'Maison') $basePrice = 250000;
                    elseif ($type === 'Duplex') $basePrice = 200000;
                    
                    $price = $basePrice + ($surface * 1500); // 1500 TND per m²
                }

                if ($imported < 3) {
                    $this->command->info("Importing: {$title} - Price: {$price} - Surface: {$surface} - Type: {$type}");
                }
                
                // Create property
                Property::create([
                    'title' => $title,
                    'description' => $description,
                    'price' => $price,
                    'type' => $type,
                    'surface' => $surface,
                    'reference' => $reference,
                    'city' => $city,
                    'district' => $district,
                    'address' => $address,
                    'rooms' => $rooms,
                    'bedrooms' => $bedrooms,
                    'bathrooms' => $bathrooms,
                    'floor' => $floor,
                    'total_floors' => $totalFloors > 0 ? $totalFloors : 5,
                    'parking' => $parking,
                    'elevator' => $elevator,
                    'terrace' => $terrace,
                    'garden' => $garden,
                    'features' => $mappedFeatures,
                    'images' => $images,
                    'user_id' => $user->id,
                    'validated' => true,
                    'published_date' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $imported++;

                // Progress indicator
                if ($imported % 100 == 0) {
                    $this->command->info("Imported {$imported} properties...");
                }

            } catch (\Exception $e) {
                $skipped++;
                $this->command->error("Failed to import property '{$title}': " . $e->getMessage());
                if ($skipped < 5) {
                    $this->command->error("Full error: " . $e->getTraceAsString());
                }
                continue;
            }
        }

        $this->command->info("Import completed!");
        $this->command->info("✅ Imported: {$imported} properties");
        $this->command->info("⚠️ Skipped: {$skipped} properties");
    }

    private function cleanString($string)
    {
        if (!is_string($string)) {
            return '';
        }
        // Remove extra whitespace and line breaks
        return trim(preg_replace('/\s+/', ' ', $string));
    }

    private function extractPrice($price)
    {
        if (is_numeric($price) && $price > 0) {
            return (int) $price;
        }
        
        // Try to extract price from string
        if (is_string($price)) {
            preg_match('/(\d+(?:\s*\d+)*)/', $price, $matches);
            if (!empty($matches[1])) {
                $extractedPrice = (int) str_replace(' ', '', $matches[1]);
                if ($extractedPrice > 0) {
                    return $extractedPrice;
                }
            }
        }
        
        // Return 0 if no valid price found, will be handled later
        return 0;
    }

    private function mapPropertyType($type)
    {
        $type = strtolower($this->cleanString($type));
        
        $typeMap = [
            'appartement' => 'Appartement',
            'apartment' => 'Appartement',
            'maison' => 'Maison',
            'house' => 'Maison',
            'villa' => 'Villa',
            'terrain' => 'Maison', // Map to closest available
            'land' => 'Maison',
            'bureau' => 'Appartement', // Map to closest available
            'office' => 'Appartement',
            'commercial' => 'Appartement',
            'duplex' => 'Duplex',
            'studio' => 'Studio',
        ];

        return $typeMap[$type] ?? 'Appartement';
    }

    private function extractCity($cityString)
    {
        if (!is_string($cityString)) {
            return 'Unknown';
        }
        
        // Clean the city string and extract main city name
        $cleaned = $this->cleanString($cityString);
        
        // Common Tunisian cities
        $cities = [
            'Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'Nabeul', 'Zaghouan',
            'Bizerte', 'Béja', 'Jendouba', 'Kef', 'Siliana', 'Kairouan',
            'Kasserine', 'Sidi Bouzid', 'Sousse', 'Monastir', 'Mahdia',
            'Sfax', 'Gafsa', 'Tozeur', 'Kebili', 'Gabès', 'Médenine',
            'Tataouine', 'La Marsa', 'Carthage', 'Sidi Bou Said'
        ];
        
        foreach ($cities as $city) {
            if (stripos($cleaned, $city) !== false) {
                return $city;
            }
        }
        
        // Extract first part if multiple words
        $parts = explode(' ', $cleaned);
        return ucfirst(strtolower($parts[0]));
    }

    private function mapFeatures($features)
    {
        if (!is_array($features)) {
            return [];
        }
        
        $featureMap = [
            'garage' => 'garage',
            'parking' => 'parking',
            'ascenseur' => 'elevator',
            'elevator' => 'elevator',
            'piscine' => 'swimming_pool',
            'swimming_pool' => 'swimming_pool',
            'climatisation' => 'air_conditioning',
            'air_conditioning' => 'air_conditioning',
            'chauffage central' => 'central_heating',
            'central_heating' => 'central_heating',
            'sécurité' => 'security',
            'security' => 'security',
            'cuisine équipée' => 'fitted_kitchen',
            'fitted_kitchen' => 'fitted_kitchen',
            'balcon' => 'balcony',
            'balcony' => 'balcony',
            'terrasse' => 'terrace',
            'terrace' => 'terrace',
            'jardin' => 'garden',
            'garden' => 'garden',
            'double vitrage' => 'double_glazing',
            'double_glazing' => 'double_glazing',
        ];
        
        $mappedFeatures = [];
        foreach ($features as $feature) {
            $cleanFeature = strtolower($this->cleanString($feature));
            if (isset($featureMap[$cleanFeature])) {
                $mappedFeatures[] = $featureMap[$cleanFeature];
            }
        }
        
        return array_unique($mappedFeatures);
    }
}
