<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Agency;
use App\Models\UserPropertyView;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        $agencies = Agency::with(['user:id,name,email,phone,city'])
            ->where('verified', true)
            ->paginate(15);

        return response()->json($agencies);
    }

    public function show($id)
    {
        $agency = Agency::with(['user:id,name,email,phone,city'])
            ->findOrFail($id);

        return response()->json($agency);
    }

    public function getProperties($id)
    {
        $agency = Agency::findOrFail($id);
        $properties = $agency->user->properties()
            ->where('validated', true)
            ->paginate(12);

        return response()->json($properties);
    }

    public function getDashboard(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;
        
        if (!$agency) {
            return response()->json(['message' => 'Agency profile not found'], 404);
        }

        // Property statistics
        $propertyStats = [
            'total' => $user->properties()->count(),
            'active' => $user->properties()->where('validated', true)->count(),
            'pending' => $user->properties()->where('validated', false)->count(),
            'total_views' => $user->properties()->sum('views'),
            'total_favorites' => Favorite::whereIn('property_id', $user->properties()->pluck('id'))->count()
        ];

        // Recent activity
        $recentViews = UserPropertyView::whereIn('property_id', $user->properties()->pluck('id'))
            ->with('property:id,title', 'user:id,name')
            ->latest('viewed_at')
            ->take(10)
            ->get();

        // Monthly stats
        $monthlyStats = $user->properties()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Property type distribution
        $typeDistribution = $user->properties()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get();

        // Recent favorites
        $recentFavorites = Favorite::whereIn('property_id', $user->properties()->pluck('id'))
            ->with('user:id,name', 'property:id,title,price')
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'agency' => $agency,
            'property_stats' => $propertyStats,
            'recent_views' => $recentViews,
            'monthly_stats' => $monthlyStats,
            'type_distribution' => $typeDistribution,
            'recent_favorites' => $recentFavorites
        ]);
    }

    public function getAnalytics(Request $request)
    {
        $user = $request->user();
        $timeframe = $request->get('timeframe', '30'); // days

        $startDate = now()->subDays($timeframe);

        // Views analytics
        $viewsData = UserPropertyView::whereIn('property_id', $user->properties()->pluck('id'))
            ->where('viewed_at', '>=', $startDate)
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Favorites analytics  
        $favoritesData = Favorite::whereIn('property_id', $user->properties()->pluck('id'))
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as favorites')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top performing properties
        $topProperties = $user->properties()
            ->withCount(['favorites' => function($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }])
            ->orderBy('views', 'desc')
            ->take(10)
            ->get(['id', 'title', 'city', 'price', 'views']);

        // User engagement
        $userEngagement = UserPropertyView::whereIn('property_id', $user->properties()->pluck('id'))
            ->where('viewed_at', '>=', $startDate)
            ->with('user:id,name,city')
            ->selectRaw('user_id, COUNT(*) as view_count')
            ->groupBy('user_id')
            ->orderBy('view_count', 'desc')
            ->take(20)
            ->get();

        return response()->json([
            'timeframe' => $timeframe,
            'views_data' => $viewsData,
            'favorites_data' => $favoritesData,
            'top_properties' => $topProperties,
            'user_engagement' => $userEngagement,
            'summary' => [
                'total_views' => $viewsData->sum('views'),
                'total_favorites' => $favoritesData->sum('favorites'),
                'avg_daily_views' => $viewsData->avg('views'),
                'unique_viewers' => $userEngagement->count()
            ]
        ]);
    }

    public function getProfile(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;
        
        if (!$agency) {
            return response()->json(['message' => 'Agency profile not found'], 404);
        }

        // Get agency statistics
        $stats = [
            'total_properties' => $user->properties()->count(),
            'verified_status' => $agency->verified,
            'rating' => $agency->rating,
            'review_count' => $agency->review_count,
            'member_since' => $user->created_at->format('Y-m-d'),
            'last_activity' => $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : null
        ];

        return response()->json([
            'user' => $user,
            'agency' => $agency,
            'stats' => $stats
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();
        $agency = $user->agency;
        
        if (!$agency) {
            return response()->json(['message' => 'Agency profile not found'], 404);
        }

        $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string|max:2000',
            'website' => 'sometimes|nullable|url',
            'addresses' => 'sometimes|array',
            'additional_phones' => 'sometimes|array',
            'business_hours' => 'sometimes|array',
            'specializations' => 'sometimes|array',
            'license_number' => 'sometimes|string|max:100',
            'established_date' => 'sometimes|date',
            'employee_count' => 'sometimes|integer|min:1|max:1000',
            'logo' => 'sometimes|string', // URL or base64
            
            // User fields
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'bio' => 'sometimes|string|max:500',
        ]);

        DB::beginTransaction();

        try {
            // Update user fields
            $user->update($request->only(['name', 'phone', 'bio']));

            // Update agency fields
            $agency->update($request->only([
                'company_name', 'description', 'website', 'addresses',
                'additional_phones', 'business_hours', 'specializations',
                'license_number', 'established_date', 'employee_count', 'logo'
            ]));

            DB::commit();

            return response()->json([
                'message' => 'Profile updated successfully',
                'user' => $user->fresh(),
                'agency' => $agency->fresh()
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Profile update failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadLogo(Request $request)
    {
        $request->validate([
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = $request->user();
        $agency = $user->agency;
        
        if (!$agency) {
            return response()->json(['message' => 'Agency profile not found'], 404);
        }

        try {
            // Delete old logo if exists
            if ($agency->logo && Storage::exists($agency->logo)) {
                Storage::delete($agency->logo);
            }

            // Store new logo
            $logoPath = $request->file('logo')->store('agency-logos', 'public');
            
            $agency->update(['logo' => $logoPath]);

            return response()->json([
                'message' => 'Logo uploaded successfully',
                'logo_url' => Storage::url($logoPath)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Logo upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getPropertyStats(Request $request, $propertyId)
    {
        $user = $request->user();
        
        // Verify ownership
        $property = $user->properties()->findOrFail($propertyId);

        $timeframe = $request->get('timeframe', '30'); // days
        $startDate = now()->subDays($timeframe);

        // Daily views
        $dailyViews = UserPropertyView::where('property_id', $propertyId)
            ->where('viewed_at', '>=', $startDate)
            ->selectRaw('DATE(viewed_at) as date, COUNT(*) as views')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Viewer demographics
        $viewerCities = UserPropertyView::where('property_id', $propertyId)
            ->whereNotNull('user_id')
            ->with('user:id,city')
            ->where('viewed_at', '>=', $startDate)
            ->get()
            ->groupBy('user.city')
            ->map->count()
            ->sortDesc()
            ->take(10);

        // Recent viewers
        $recentViewers = UserPropertyView::where('property_id', $propertyId)
            ->with('user:id,name,city')
            ->latest('viewed_at')
            ->take(20)
            ->get();

        // Favorites count
        $favoritesCount = Favorite::where('property_id', $propertyId)->count();

        return response()->json([
            'property' => $property,
            'timeframe' => $timeframe,
            'daily_views' => $dailyViews,
            'viewer_cities' => $viewerCities,
            'recent_viewers' => $recentViewers,
            'total_favorites' => $favoritesCount,
            'summary' => [
                'total_views' => $property->views,
                'views_in_period' => $dailyViews->sum('views'),
                'avg_daily_views' => $dailyViews->avg('views'),
                'unique_viewers' => $recentViewers->unique('user_id')->count()
            ]
        ]);
    }

    public function getLeads(Request $request)
    {
        $user = $request->user();
        
        // Get users who favorited agency properties
        $leads = Favorite::whereIn('property_id', $user->properties()->pluck('id'))
            ->with(['user:id,name,email,phone,city', 'property:id,title,price'])
            ->latest()
            ->paginate(15);

        return response()->json($leads);
    }
}
