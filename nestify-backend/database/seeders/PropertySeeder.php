<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Agency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PropertySeeder extends Seeder
{
    private function parseDate($dateString)
    {
        if (!$dateString) {
            return null;
        }

        // Handle French months
        $frenchMonths = [
            'janvier' => 'January',
            'février' => 'February',
            'mars' => 'March',
            'avril' => 'April',
            'mai' => 'May',
            'juin' => 'June',
            'juillet' => 'July',
            'août' => 'August',
            'septembre' => 'September',
            'octobre' => 'October',
            'novembre' => 'November',
            'décembre' => 'December'
        ];

        $englishDate = str_replace(array_keys($frenchMonths), array_values($frenchMonths), strtolower($dateString));
        
        try {
            return \Carbon\Carbon::parse($englishDate);
        } catch (\Exception $e) {
            // If parsing fails, try to extract year and month
            if (preg_match('/(\d{4})/', $dateString, $matches)) {
                return \Carbon\Carbon::createFromFormat('Y-m-d', $matches[1] . '-01-01');
            }
            return null;
        }
    }

    public function run()
    {
        $jsonPath = base_path('Real_estate_Nestify.nestify_properties.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error("JSON file not found at: {$jsonPath}");
            return;
        }

        $this->command->info('Loading JSON data...');
        $jsonData = json_decode(file_get_contents($jsonPath), true);

        if (!$jsonData) {
            $this->command->error('Invalid JSON data');
            return;
        }

        $this->command->info('Found ' . count($jsonData) . ' properties to import');

        // Process in smaller batches to avoid memory issues
        $batchSize = 50;
        $totalBatches = ceil(count($jsonData) / $batchSize);
        $importedCount = 0;

        for ($batch = 0; $batch < $totalBatches; $batch++) {
            $offset = $batch * $batchSize;
            $batchData = array_slice($jsonData, $offset, $batchSize);
            
            $this->command->info("Processing batch " . ($batch + 1) . " of {$totalBatches}");

            DB::beginTransaction();

            try {
                foreach ($batchData as $index => $propertyData) {
                    $this->processProperty($propertyData, $offset + $index);
                    $importedCount++;
                }

                DB::commit();
                $this->command->info("Batch " . ($batch + 1) . " completed. Total imported: {$importedCount}");

            } catch (\Exception $e) {
                DB::rollback();
                $this->command->error("Batch {$batch} failed: " . $e->getMessage());
                $this->command->error("Error details: " . $e->getFile() . ":" . $e->getLine());
                break;
            }
        }

        $this->command->info("Import completed! Total properties imported: {$importedCount}");
    }

    private function processProperty($propertyData, $index)
    {
        // Create or find agency/promoter user
        $agencyUser = null;
        
        if (isset($propertyData['promoter_id']) && !empty($propertyData['promoter_id']['name'])) {
            $promoterData = $propertyData['promoter_id'];
            
            // Create agency user if not exists
            $email = $promoterData['contact']['email'] ?? 'agency_' . $index . '@nestify.tn';
            $agencyUser = User::firstOrCreate([
                'email' => $email
            ], [
                'name' => $promoterData['name'],
                'password' => Hash::make('agency123'),
                'phone' => $promoterData['contact']['phone'] ?? '',
                'user_type' => 'agency',
                'is_active' => true,
            ]);

            // Create agency profile
            Agency::firstOrCreate([
                'user_id' => $agencyUser->id
            ], [
                'company_name' => $promoterData['name'],
                'website' => $promoterData['contact']['website'] ?? '',
                'addresses' => $promoterData['contact']['addresses'] ?? [],
                'additional_phones' => $promoterData['contact']['additional_phones'] ?? [],
                'verified' => $promoterData['verified'] ?? false,
                'description' => 'Real estate agency from Nestify data'
            ]);
        }

        // Prepare property data
        $location = $propertyData['location_id'] ?? [];
        $apartmentDetails = $propertyData['apartment_details_id'] ?? [];
        $vefaDetails = $propertyData['VEFA_details_id'] ?? [];

        // Create property
        Property::create([
            'user_id' => $agencyUser ? $agencyUser->id : null,
            'title' => $propertyData['title'] ?? 'Untitled Property',
            'description' => $propertyData['description'] ?? '',
            'price' => floatval($propertyData['price'] ?? 0),
            'type' => $propertyData['type'] ?? 'Appartement',
            'surface' => intval($propertyData['surface'] ?? 0),
            'reference' => $propertyData['reference'] ?? $propertyData['_id'] ?? 'REF_' . $index,
            'images' => $propertyData['images'] ?? [],
            'validated' => $propertyData['validated'] ?? true,
            'published_date' => $this->parseDate($propertyData['published_date']) ?? now(),
            'city' => $location['city'] ?? '',
            'district' => $location['district'] ?? '',
            'address' => $location['address'] ?? '',
            'latitude' => isset($location['coordinates']['lat']) ? floatval($location['coordinates']['lat']) : null,
            'longitude' => isset($location['coordinates']['lng']) ? floatval($location['coordinates']['lng']) : null,
            'rooms' => intval($apartmentDetails['rooms'] ?? 0),
            'bedrooms' => intval($apartmentDetails['bedrooms'] ?? 0),
            'bathrooms' => intval($apartmentDetails['bathrooms'] ?? 0),
            'floor' => intval($apartmentDetails['floor'] ?? 0),
            'total_floors' => intval($apartmentDetails['total_floors'] ?? 0),
            'parking' => $apartmentDetails['parking'] ?? false,
            'elevator' => $apartmentDetails['elevator'] ?? false,
            'terrace' => $apartmentDetails['terrace'] ?? false,
            'garden' => $apartmentDetails['garden'] ?? false,
            'features' => $apartmentDetails['features'] ?? [],
            'is_vefa' => $vefaDetails['is_vefa'] ?? false,
            'delivery_date' => $this->parseDate($vefaDetails['delivery_date'] ?? null),
            'construction_progress' => $vefaDetails['construction_progress'] ?? null,
            'views' => intval($propertyData['views'] ?? 0),
        ]);
    }
}
