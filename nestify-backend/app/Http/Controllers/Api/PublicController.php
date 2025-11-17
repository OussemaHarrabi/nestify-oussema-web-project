<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Property;
use App\Models\Promoter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicController extends Controller
{
    /**
     * List all published projects (PUBLIC)
     * 
     * GET /api/projects
     */
    public function projects(Request $request)
    {
        $query = Project::with('promoter:id,company_name,logo')
            ->where('is_published', true);

        // Filters
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('min_price')) {
            $query->where('starting_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('starting_price', '<=', $request->max_price);
        }

        if ($request->has('amenities')) {
            $amenities = explode(',', $request->amenities);
            $query->where(function($q) use ($amenities) {
                foreach ($amenities as $amenity) {
                    $q->whereJsonContains('amenities', trim($amenity));
                }
            });
        }

        if ($request->has('available_units') && $request->boolean('available_units')) {
            $query->where('available_units', '>', 0);
        }

        if ($request->has('featured') && $request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'price') {
            $query->orderBy('starting_price', $sortOrder);
        } elseif ($sortBy === 'views') {
            $query->orderBy('views', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $projects = $query->paginate($request->get('per_page', 12));

        return response()->json($projects);
    }

    /**
     * Get project details by slug (PUBLIC)
     * 
     * GET /api/projects/{slug}
     */
    public function projectBySlug($slug)
    {
        $project = Project::with([
            'promoter:id,company_name,logo,primary_phone,primary_email,description',
            'properties' => function($q) {
                $q->where('validated', true)
                  ->select('id', 'project_id', 'title', 'price', 'surface', 'type', 'bedrooms', 'bathrooms', 'availability_status', 'images');
            }
        ])
        ->where('slug', $slug)
        ->where('is_published', true)
        ->firstOrFail();

        // Increment views (TODO: Add views column to projects table)
        // $project->incrementViews();

        return response()->json($project);
    }
    
    /**
     * Get a single project by ID (PUBLIC)
     * 
     * GET /api/projects/{id}
     */
    public function projectById($id)
    {
        $project = Project::with([
            'promoter:id,company_name,logo,primary_phone,primary_email,description',
            // Commented out properties until Property model/table exists
            // 'properties' => function($q) {
            //     $q->where('validated', true)
            //       ->select('id', 'project_id', 'title', 'price', 'surface', 'type', 'bedrooms', 'bathrooms', 'availability_status', 'images');
            // }
        ])
        ->where('id', $id)
        ->where('is_published', true)
        ->firstOrFail();

        // Increment views (TODO: Add views column to projects table)
        // $project->incrementViews();

        return response()->json($project);
    }

    /**
     * List properties in a project (PUBLIC)
     * 
     * GET /api/projects/{id}/properties
     */
    public function projectProperties(Request $request, $id)
    {
        $project = Project::where('is_published', true)->findOrFail($id);

        $query = Property::where('project_id', $id)
            ->where('validated', true);

        // Filters
        if ($request->has('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $properties = $query->orderBy('price', 'asc')
            ->paginate($request->get('per_page', 20));

        return response()->json($properties);
    }

    /**
     * List all available properties (PUBLIC)
     * 
     * GET /api/properties
     */
    public function properties(Request $request)
    {
        $query = Property::with([
            'project:id,name,slug',
            'project.promoter:id,company_name'
        ])
        ->where('validated', true)
        ->where('availability_status', 'available');

        // Filters
        if ($request->has('city')) {
            $query->where('city', $request->city);
        }

        if ($request->has('type')) {
            $types = explode(',', $request->type);
            $query->whereIn('type', $types);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('min_surface')) {
            $query->where('surface', '>=', $request->min_surface);
        }

        if ($request->has('max_surface')) {
            $query->where('surface', '<=', $request->max_surface);
        }

        if ($request->has('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if ($request->has('parking') && $request->boolean('parking')) {
            $query->where('parking', true);
        }

        if ($request->has('elevator') && $request->boolean('elevator')) {
            $query->where('elevator', true);
        }

        if ($request->has('is_vefa') && $request->boolean('is_vefa')) {
            $query->where('is_vefa', true);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        if ($sortBy === 'price') {
            $query->orderBy('price', $sortOrder);
        } elseif ($sortBy === 'surface') {
            $query->orderBy('surface', $sortOrder);
        } else {
            $query->orderBy('created_at', $sortOrder);
        }

        $properties = $query->paginate($request->get('per_page', 20));

        return response()->json($properties);
    }

    /**
     * Get property details (PUBLIC)
     * 
     * GET /api/properties/{id}
     */
    public function propertyDetails($id)
    {
        $property = Property::with([
            'project:id,name,slug,main_image,amenities,city,district,address',
            'project.promoter:id,company_name,logo,primary_phone,primary_email'
        ])
        ->where('validated', true)
        ->findOrFail($id);

        // Increment views
        $property->incrementViews();

        return response()->json($property);
    }

    /**
     * Get similar properties (PUBLIC)
     * 
     * GET /api/properties/{id}/similar
     */
    public function similarProperties($id)
    {
        $property = Property::findOrFail($id);

        $similar = Property::where('id', '!=', $id)
            ->where('validated', true)
            ->where('availability_status', 'available')
            ->where(function($query) use ($property) {
                $query->where('type', $property->type)
                      ->orWhere('city', $property->city)
                      ->orWhereBetween('price', [
                          $property->price * 0.8,
                          $property->price * 1.2
                      ]);
            })
            ->with(['project:id,name,slug', 'project.promoter:id,company_name'])
            ->limit(6)
            ->get();

        return response()->json($similar);
    }

    /**
     * List all verified promoters (PUBLIC)
     * 
     * GET /api/promoters
     */
    public function promoters(Request $request)
    {
        $query = Promoter::where('verified', true);

        // Filters
        if ($request->has('city')) {
            $query->where('headquarters_city', $request->city);
        }

        if ($request->has('featured') && $request->boolean('featured')) {
            $query->where('featured', true);
        }

        $promoters = $query->orderBy('featured', 'desc')
            ->orderBy('total_projects', 'desc')
            ->paginate($request->get('per_page', 12));

        return response()->json($promoters);
    }

    /**
     * Get promoter profile (PUBLIC)
     * 
     * GET /api/promoters/{id}
     */
    public function promoterDetails($id)
    {
        $promoter = Promoter::with([
            'projects' => function($q) {
                $q->where('is_published', true)
                  ->select('id', 'promoter_id', 'name', 'slug', 'main_image', 'city', 'starting_price', 'available_units');
            }
        ])
        ->where('verified', true)
        ->findOrFail($id);

        return response()->json($promoter);
    }

    /**
     * List promoter's projects (PUBLIC)
     * 
     * GET /api/promoters/{id}/projects
     */
    public function promoterProjects(Request $request, $id)
    {
        $promoter = Promoter::where('verified', true)->findOrFail($id);

        $query = Project::where('promoter_id', $id)
            ->where('is_published', true);

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 12));

        return response()->json($projects);
    }

    /**
     * Global search (PUBLIC)
     * 
     * GET /api/search
     */
    public function search(Request $request)
    {
        $query = $request->get('q') ?? $request->get('query');
        $type = $request->get('type', 'all'); // all, projects, properties, promoters

        // If no query provided, return recent/featured items
        if (!$query) {
            return response()->json([
                'message' => 'Showing featured items',
                'projects' => Project::where('is_published', true)
                    ->with('promoter:id,company_name,logo')
                    ->latest()
                    ->limit(5)
                    ->get(),
                'properties' => Property::where('validated', true)
                    ->with(['project:id,name,slug', 'project.promoter:id,company_name'])
                    ->latest()
                    ->limit(5)
                    ->get()
            ]);
        }

        $results = [];

        // Search projects
        if ($type === 'all' || $type === 'projects') {
            $projects = Project::where('is_published', true)
                ->where(function($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('city', 'like', "%{$query}%");
                })
                ->with('promoter:id,company_name,logo')
                ->limit(5)
                ->get();
            $results['projects'] = $projects;
        }

        // Search properties
        if ($type === 'all' || $type === 'properties') {
            $properties = Property::where('validated', true)
                ->where(function($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%")
                      ->orWhere('city', 'like', "%{$query}%");
                })
                ->with(['project:id,name,slug', 'project.promoter:id,company_name'])
                ->limit(5)
                ->get();
            $results['properties'] = $properties;
        }

        // Search promoters
        if ($type === 'all' || $type === 'promoters') {
            $promoters = Promoter::where('verified', true)
                ->where(function($q) use ($query) {
                    $q->where('company_name', 'like', "%{$query}%")
                      ->orWhere('description', 'like', "%{$query}%");
                })
                ->limit(5)
                ->get();
            $results['promoters'] = $promoters;
        }

        return response()->json($results);
    }

    /**
     * Get list of cities with property counts (PUBLIC)
     * 
     * GET /api/cities
     */
    public function cities()
    {
        $cities = Property::select('city', DB::raw('COUNT(*) as count'))
            ->where('validated', true)
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json($cities);
    }

    /**
     * Get property types with counts (PUBLIC)
     * 
     * GET /api/property-types
     */
    public function propertyTypes()
    {
        $types = Property::select('type', DB::raw('COUNT(*) as count'))
            ->where('validated', true)
            ->groupBy('type')
            ->orderBy('count', 'desc')
            ->get();

        return response()->json($types);
    }

    /**
     * Get all filter options (PUBLIC)
     * 
     * GET /api/filters/options
     */
    public function filterOptions()
    {
        // Cities
        $cities = Property::select('city')
            ->where('validated', true)
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        // Property types
        $types = ['Appartement', 'Villa', 'Maison', 'Studio', 'Duplex'];

        // Price ranges (based on actual data)
        $priceStats = Property::where('validated', true)
            ->selectRaw('MIN(price) as min, MAX(price) as max, AVG(price) as avg')
            ->first();

        // Common amenities
        $amenities = [
            'Piscine',
            'Salle de sport',
            'Gardiennage 24/7',
            'Parking',
            'Ascenseur',
            'Jardin',
            'Terrasse',
            'Balcon',
        ];

        return response()->json([
            'cities' => $cities,
            'types' => $types,
            'price_range' => [
                'min' => $priceStats->min ?? 0,
                'max' => $priceStats->max ?? 1000000,
                'avg' => round($priceStats->avg ?? 300000),
            ],
            'amenities' => $amenities,
        ]);
    }
}
