<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            '_id' => 'nestify_' . $this->id,
            'url' => config('app.url') . '/properties/' . 'nestify_' . $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'published_date' => $this->published_date ? $this->published_date->format('d M Y') : null,
            'reference' => $this->reference,
            'price' => (float) $this->price,
            'type' => $this->type,
            'surface' => $this->surface,
            'rental_potential' => '', // Add this field to properties table if needed
            'images' => $this->images ?? [],
            'views' => $this->views ?? 0,
            'created_at' => $this->created_at->toISOString(),
            'validated' => $this->validated,
            
            'location_id' => [
                'address' => $this->address ?? '',
                'city' => $this->city,
                'district' => $this->district ?? '',
                'region' => '', // Add this field if needed
                'country' => 'Tunisie',
                'coordinates' => [
                    'lat' => (float) $this->latitude,
                    'lng' => (float) $this->longitude,
                ],
            ],
            
            'VEFA_details_id' => [
                'is_vefa' => $this->is_vefa,
                'delivery_date' => $this->delivery_date ? $this->delivery_date->format('F Y') : null,
                'construction_progress' => $this->construction_progress ?? '',
                'payment_schedule' => [], // Add this field if needed
                'guarantees' => [], // Add this field if needed
            ],
            
            'terrain_details_id' => [], // Add this for terrain properties
            
            'apartment_details_id' => $this->type === 'Appartement' ? [
                'rooms' => $this->rooms,
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
                'floor' => $this->floor,
                'total_floors' => $this->total_floors ?? 0,
                'parking' => $this->parking,
                'elevator' => $this->elevator,
                'terrace' => $this->terrace,
                'garden' => $this->garden,
                'features' => $this->features ?? [],
            ] : [],
            
            'villa_details_id' => $this->type === 'Villa' ? [
                'rooms' => $this->rooms,
                'bedrooms' => $this->bedrooms,
                'bathrooms' => $this->bathrooms,
                'parking' => $this->parking,
                'garden' => $this->garden,
                'terrace' => $this->terrace,
                'features' => $this->features ?? [],
            ] : [],
            
            'promoter_id' => $this->user && $this->user->agency ? [
                'name' => $this->user->agency->company_name,
                'contact' => [
                    'phone' => $this->user->phone ?? '',
                    'email' => $this->user->email ?? '',
                    'website' => $this->user->agency->website ?? '',
                    'addresses' => $this->user->agency->addresses ?? [],
                    'additional_phones' => $this->user->agency->additional_phones ?? [],
                ],
                'verified' => $this->user->agency->verified ?? false,
            ] : [
                'name' => $this->user->name ?? '',
                'contact' => [
                    'phone' => $this->user->phone ?? '',
                    'email' => $this->user->email ?? '',
                    'website' => '',
                    'addresses' => [],
                    'additional_phones' => [],
                ],
                'verified' => false,
            ],
        ];
    }
}
