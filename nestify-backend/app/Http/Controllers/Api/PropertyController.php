<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::with('user.agency')->where('validated', true);

        // Text search filters
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
                  
                // Only search governorate if column exists
                try {
                    $q->orWhere('governorate', 'like', "%{$search}%");
                } catch (\Exception $e) {
                    // Column doesn't exist, skip
                }
            });
        }

        // Price filters
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Surface filters
        if ($request->has('min_surface')) {
            $query->where('surface', '>=', $request->min_surface);
        }
        if ($request->has('max_surface')) {
            $query->where('surface', '<=', $request->max_surface);
        }

        // Property type filter
        if ($request->has('type')) {
            if (is_array($request->type)) {
                $query->whereIn('type', $request->type);
            } else {
                $query->where('type', $request->type);
            }
        }

        // Room filters (excluding living room as requested)
        if ($request->has('bedrooms')) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }
        if ($request->has('min_bedrooms')) {
            $query->where('bedrooms', '>=', $request->min_bedrooms);
        }
        if ($request->has('max_bedrooms')) {
            $query->where('bedrooms', '<=', $request->max_bedrooms);
        }

        // Bathroom filters
        if ($request->has('bathrooms')) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }
        if ($request->has('min_bathrooms')) {
            $query->where('bathrooms', '>=', $request->min_bathrooms);
        }

        // Total rooms filter (if needed)
        if ($request->has('total_rooms')) {
            $query->where('rooms', '>=', $request->total_rooms);
        }

        // Location filters
        if ($request->has('city')) {
            if (is_array($request->city)) {
                $query->whereIn('city', $request->city);
            } else {
                $query->where('city', 'like', "%{$request->city}%");
            }
        }
        if ($request->has('district')) {
            if (is_array($request->district)) {
                $query->whereIn('district', $request->district);
            } else {
                $query->where('district', 'like', "%{$request->district}%");
            }
        }

        // Floor filters
        if ($request->has('min_floor')) {
            $query->where('floor', '>=', $request->min_floor);
        }
        if ($request->has('max_floor')) {
            $query->where('floor', '<=', $request->max_floor);
        }

        // Building features
        if ($request->has('parking') && $request->parking == 1) {
            $query->where('parking', true);
        }
        if ($request->has('elevator') && $request->elevator == 1) {
            $query->where('elevator', true);
        }
        if ($request->has('terrace') && $request->terrace == 1) {
            $query->where('terrace', true);
        }
        if ($request->has('garden') && $request->garden == 1) {
            $query->where('garden', true);
        }

        // Property features (JSON search)
        if ($request->has('features')) {
            $features = is_array($request->features) ? $request->features : [$request->features];
            foreach ($features as $feature) {
                $query->whereJsonContains('features', $feature);
            }
        }

        // Specific feature filters
        if ($request->has('piscine') && $request->piscine == 1) {
            $query->whereJsonContains('features', 'Piscine');
        }
        if ($request->has('garage') && $request->garage == 1) {
            $query->whereJsonContains('features', 'Garage');
        }
        if ($request->has('climatisation') && $request->climatisation == 1) {
            $query->whereJsonContains('features', 'Climatisation');
        }
        if ($request->has('meuble') && $request->meuble == 1) {
            $query->whereJsonContains('features', 'Meublé');
        }
        if ($request->has('cuisine_equipee') && $request->cuisine_equipee == 1) {
            $query->whereJsonContains('features', 'Cuisine équipée');
        }
        if ($request->has('chauffage') && $request->chauffage == 1) {
            $query->whereJsonContains('features', 'Chauffage');
        }
        if ($request->has('securite') && $request->securite == 1) {
            $query->whereJsonContains('features', 'Sécurité');
        }

        // VEFA filter
        if ($request->has('is_vefa')) {
            $query->where('is_vefa', $request->is_vefa == 1);
        }

        // Published date filters
        if ($request->has('published_after')) {
            $query->where('published_date', '>=', $request->published_after);
        }
        if ($request->has('published_before')) {
            $query->where('published_date', '<=', $request->published_before);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['price', 'surface', 'created_at', 'published_date', 'views', 'bedrooms', 'bathrooms'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $perPage = min($perPage, 50); // Max 50 items per page

        $properties = $query->paginate($perPage);

        return response()->json([
            'data' => PropertyResource::collection($properties->items()),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ],
            'filters_applied' => $request->only([
                'search', 'min_price', 'max_price', 'min_surface', 'max_surface',
                'type', 'bedrooms', 'bathrooms', 'city', 'district', 'features',
                'parking', 'elevator', 'terrace', 'garden', 'is_vefa',
                'sort_by', 'sort_order'
            ])
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:Appartement,Villa,Maison,Studio,Duplex',
            'surface' => 'required|integer|min:1',
            'city' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'district' => 'nullable|string|max:255',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:0',
            'rooms' => 'nullable|integer|min:0',
            'floor' => 'nullable|integer|min:0',
            'total_floors' => 'nullable|integer|min:0',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'parking' => 'nullable|boolean',
            'elevator' => 'nullable|boolean',
            'terrace' => 'nullable|boolean',
            'garden' => 'nullable|boolean'
        ]);

        // Generate unique reference
        $reference = 'PROP-' . time() . '-' . rand(1000, 9999);

        $property = Property::create([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'type' => $request->type,
            'surface' => $request->surface,
            'reference' => $reference,
            'city' => $request->city,
            'district' => $request->district,
            'address' => $request->address,
            'bedrooms' => $request->bedrooms ?? 0,
            'bathrooms' => $request->bathrooms ?? 0,
            'rooms' => $request->rooms ?? 0,
            'floor' => $request->floor,
            'total_floors' => $request->total_floors,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'features' => $request->features ?? [],
            'parking' => $request->parking ?? false,
            'elevator' => $request->elevator ?? false,
            'terrace' => $request->terrace ?? false,
            'garden' => $request->garden ?? false,
            'user_id' => $request->user()->id,
            'validated' => $request->user()->user_type === 'admin',
        ]);

        return response()->json([
            'message' => 'Property created successfully',
            'property' => new PropertyResource($property->load('user.agency'))
        ], 201);
    }

    public function show($id)
    {
        $property = Property::with('user.agency')->findOrFail($id);
        $property->incrementViews();
        
        return response()->json(new PropertyResource($property));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        
        // Check ownership
        if ($property->user_id !== $request->user()->id && $request->user()->user_type !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->update($request->all());

        return response()->json([
            'message' => 'Property updated successfully',
            'property' => new PropertyResource($property->load('user.agency'))
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        
        // Check ownership
        if ($property->user_id !== $request->user()->id && $request->user()->user_type !== 'admin') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $property->delete();

        return response()->json(['message' => 'Property deleted successfully']);
    }

    public function myProperties(Request $request)
    {
        $properties = Property::where('user_id', $request->user()->id)
            ->with('user.agency')
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return response()->json([
            'data' => PropertyResource::collection($properties->items()),
            'pagination' => [
                'current_page' => $properties->currentPage(),
                'last_page' => $properties->lastPage(),
                'per_page' => $properties->perPage(),
                'total' => $properties->total(),
            ]
        ]);
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|file|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imageUrls = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('properties', 'public');
            $imageUrls[] = asset('storage/' . $path);
        }

        return response()->json([
            'message' => 'Images uploaded successfully',
            'images' => $imageUrls
        ]);
    }

    public function getFilterOptions()
    {
        $properties = Property::where('validated', true);

        return response()->json([
            'types' => Property::where('validated', true)->distinct()->pluck('type')->filter()->values(),
            'cities' => Property::where('validated', true)->distinct()->pluck('city')->filter()->values(),
            'districts' => Property::where('validated', true)->distinct()->pluck('district')->filter()->values(),
            'price_range' => [
                'min' => Property::where('validated', true)->min('price'),
                'max' => Property::where('validated', true)->max('price'),
            ],
            'surface_range' => [
                'min' => Property::where('validated', true)->min('surface'),
                'max' => Property::where('validated', true)->max('surface'),
            ],
            'bedroom_range' => [
                'min' => Property::where('validated', true)->where('bedrooms', '>', 0)->min('bedrooms'),
                'max' => Property::where('validated', true)->max('bedrooms'),
            ],
            'bathroom_range' => [
                'min' => Property::where('validated', true)->where('bathrooms', '>', 0)->min('bathrooms'),
                'max' => Property::where('validated', true)->max('bathrooms'),
            ],
            'available_features' => [
                'Garage',
                'Piscine',
                'Climatisation',
                'Ascenseur',
                'Meublé',
                'Cuisine équipée',
                'Chauffage',
                'Sécurité',
                'Terrasse',
                'Jardin',
                'Parking',
                'Cave',
                'Balcon'
            ],
            'sort_options' => [
                ['value' => 'price_asc', 'label' => 'Prix croissant'],
                ['value' => 'price_desc', 'label' => 'Prix décroissant'],
                ['value' => 'surface_asc', 'label' => 'Surface croissante'],
                ['value' => 'surface_desc', 'label' => 'Surface décroissante'],
                ['value' => 'created_at_desc', 'label' => 'Plus récent'],
                ['value' => 'created_at_asc', 'label' => 'Plus ancien'],
                ['value' => 'views_desc', 'label' => 'Plus consultés'],
            ]
        ]);
    }

    public function getStatistics()
    {
        $properties = Property::where('validated', true);

        return response()->json([
            'total_properties' => $properties->count(),
            'average_price' => round($properties->avg('price'), 2),
            'average_surface' => round($properties->avg('surface'), 2),
            'properties_by_type' => Property::where('validated', true)
                ->selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->pluck('count', 'type'),
            'properties_by_city' => Property::where('validated', true)
                ->selectRaw('city, COUNT(*) as count')
                ->groupBy('city')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->pluck('count', 'city'),
            'price_distribution' => [
                'under_100k' => Property::where('validated', true)->where('price', '<', 100000)->count(),
                '100k_300k' => Property::where('validated', true)->whereBetween('price', [100000, 300000])->count(),
                '300k_500k' => Property::where('validated', true)->whereBetween('price', [300000, 500000])->count(),
                '500k_1m' => Property::where('validated', true)->whereBetween('price', [500000, 1000000])->count(),
                'over_1m' => Property::where('validated', true)->where('price', '>', 1000000)->count(),
            ],
            'recent_properties' => Property::where('validated', true)
                ->where('created_at', '>=', now()->subDays(30))
                ->count(),
        ]);
    }

    public function searchSuggestions(Request $request)
    {
        $query = $request->get('query', '');
        
        if (strlen($query) < 2) {
            return response()->json(['suggestions' => []]);
        }

        $suggestions = collect();

        // Add city suggestions from real database
        $cities = Property::where('validated', true)
            ->where('city', 'like', "%{$query}%")
            ->distinct()
            ->pluck('city')
            ->take(5);
        
        foreach ($cities as $city) {
            $suggestions->push([
                'type' => 'city', 
                'value' => $city, 
                'label' => $city . ' (Ville)'
            ]);
        }

        // Add property title suggestions
        $properties = Property::where('validated', true)
            ->where('title', 'like', "%{$query}%")
            ->select('title', 'id', 'city')
            ->take(3)
            ->get();
        
        foreach ($properties as $property) {
            $suggestions->push([
                'type' => 'property',
                'value' => $property->id,
                'label' => $property->title . ' - ' . $property->city
            ]);
        }

        return response()->json(['suggestions' => $suggestions->take(10)]);
    }

    public function search(Request $request)
    {
        $query = Property::with('user.agency')->where('validated', true);
        
        $searchTerm = $request->get('query', '');
        
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                // Search in title
                $q->where('title', 'like', "%{$searchTerm}%")
                  // Search in description
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  // Search in city (exact and partial)
                  ->orWhere('city', 'like', "%{$searchTerm}%")
                  // Search in address
                  ->orWhere('address', 'like', "%{$searchTerm}%");
            });
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(15);

        return PropertyResource::collection($properties);
    }

    public function filter(Request $request)
    {
        $query = Property::with('user.agency')->where('validated', true);

        // Type filter (French property types)
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Price filters
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Location filters - SIMPLE AND BULLETPROOF
        if ($request->has('city') && $request->city) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->has('governorate') && $request->governorate) {
            // If governorate is provided, try to match by city first (most reliable)
            $query->where(function($q) use ($request) {
                $q->where('city', 'like', "%{$request->governorate}%")
                  ->orWhere('address', 'like', "%{$request->governorate}%");
            });
        }

        // Area/Surface filters
        if ($request->has('min_area') && $request->min_area) {
            $query->where('surface', '>=', $request->min_area);
        }
        if ($request->has('max_area') && $request->max_area) {
            $query->where('surface', '<=', $request->max_area);
        }

        // Surface filters (alternative naming)
        if ($request->has('min_surface') && $request->min_surface) {
            $query->where('surface', '>=', $request->min_surface);
        }
        if ($request->has('max_surface') && $request->max_surface) {
            $query->where('surface', '<=', $request->max_surface);
        }

        // Bedrooms filter
        if ($request->has('bedrooms') && $request->bedrooms) {
            $query->where('bedrooms', '>=', $request->bedrooms);
        }

        // Bathrooms filter
        if ($request->has('bathrooms') && $request->bathrooms) {
            $query->where('bathrooms', '>=', $request->bathrooms);
        }

        $properties = $query->orderBy('created_at', 'desc')->paginate(15);

        return PropertyResource::collection($properties);
    }

    public function getLocations()
    {
        // Get REAL cities from database
        $cities = Property::select('city')
            ->where('validated', true)
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->pluck('city')
            ->values();

        // Get REAL property types from database
        $propertyTypes = Property::select('type')
            ->where('validated', true)
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->values();

        // Tunisian governorates for location filtering
        $tunisianGovernorates = [
            'Tunis', 'Ariana', 'Ben Arous', 'Manouba', 'Nabeul', 'Zaghouan',
            'Bizerte', 'Béja', 'Jendouba', 'Kef', 'Siliana', 'Sousse',
            'Monastir', 'Mahdia', 'Sfax', 'Kairouan', 'Kasserine', 'Sidi Bouzid',
            'Gabès', 'Médenine', 'Tataouine', 'Gafsa', 'Tozeur', 'Kébili'
        ];

        return response()->json([
            'cities' => $cities,
            'governorates' => $tunisianGovernorates,
            'property_types' => $propertyTypes,
            'message' => 'Real locations extracted from database'
        ]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'is_primary' => 'nullable|boolean'
        ]);

        $property = Property::findOrFail($request->property_id);

        // Check if user owns this property (agency) or is admin
        if (auth()->user()->user_type === 'agency' && $property->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $imagePath = $image->storeAs('images/properties', $imageName, 'public');

            // Create property image record
            $propertyImage = $property->images()->create([
                'image_path' => $imagePath,
                'is_primary' => filter_var($request->input('is_primary', false), FILTER_VALIDATE_BOOLEAN)
            ]);

            // If this is set as primary, make sure no other images are primary
            if (filter_var($request->input('is_primary', false), FILTER_VALIDATE_BOOLEAN)) {
                $property->images()->where('id', '!=', $propertyImage->id)->update(['is_primary' => false]);
            }

            return response()->json([
                'message' => 'Image uploaded successfully',
                'image' => [
                    'id' => $propertyImage->id,
                    'url' => Storage::url($imagePath),
                    'is_primary' => $propertyImage->is_primary
                ]
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }
}
