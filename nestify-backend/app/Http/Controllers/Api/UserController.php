<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\UserPropertyView;
use App\Models\UserSearch;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getRecommendations(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }

        // Get user preferences
        $preferences = $user->preferences ?? [];
        
        // Build recommendations based on preferences and viewing history
        $query = Property::with('user.agency')->where('validated', true);
        
        // Apply preference filters
        if (isset($preferences['property_types']) && !empty($preferences['property_types'])) {
            $query->whereIn('type', $preferences['property_types']);
        }
        
        if (isset($preferences['cities']) && !empty($preferences['cities'])) {
            $query->whereIn('city', $preferences['cities']);
        }
        
        if (isset($preferences['price_range'])) {
            if (isset($preferences['price_range']['min'])) {
                $query->where('price', '>=', $preferences['price_range']['min']);
            }
            if (isset($preferences['price_range']['max'])) {
                $query->where('price', '<=', $preferences['price_range']['max']);
            }
        }
        
        if (isset($preferences['rooms_min'])) {
            $query->where('rooms', '>=', $preferences['rooms_min']);
        }
        
        // Get recently viewed property types to enhance recommendations
        $recentViews = UserPropertyView::where('user_id', $user->id)
            ->with('property')
            ->latest('viewed_at')
            ->take(10)
            ->get();
            
        if ($recentViews->isNotEmpty()) {
            $viewedTypes = $recentViews->pluck('property.type')->unique()->toArray();
            $viewedCities = $recentViews->pluck('property.city')->unique()->toArray();
            
            // Boost properties with similar characteristics
            $query->where(function($q) use ($viewedTypes, $viewedCities) {
                $q->whereIn('type', $viewedTypes)
                  ->orWhereIn('city', $viewedCities);
            });
        }
        
        $recommendations = $query->inRandomOrder()->take(12)->get();
        
        return response()->json([
            'recommendations' => $recommendations,
            'based_on' => [
                'preferences' => !empty($preferences),
                'viewing_history' => $recentViews->isNotEmpty(),
                'total_views' => $recentViews->count()
            ]
        ]);
    }
    
    public function getSearchHistory(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }
        
        $searches = UserSearch::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();
            
        return response()->json(['search_history' => $searches]);
    }
    
    public function getViewingHistory(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }
        
        $views = UserPropertyView::where('user_id', $user->id)
            ->with('property.user.agency')
            ->latest('viewed_at')
            ->paginate(15);
            
        return response()->json($views);
    }
    
    public function updatePreferences(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }
        
        $request->validate([
            'preferences' => 'required|array',
            'preferences.property_types' => 'nullable|array',
            'preferences.price_range' => 'nullable|array',
            'preferences.price_range.min' => 'nullable|numeric|min:0',
            'preferences.price_range.max' => 'nullable|numeric|min:0',
            'preferences.cities' => 'nullable|array',
            'preferences.rooms_min' => 'nullable|integer|min:1',
            'preferences.notifications' => 'nullable|boolean',
        ]);
        
        $user->update([
            'preferences' => $request->preferences
        ]);
        
        return response()->json([
            'message' => 'Preferences updated successfully',
            'preferences' => $user->preferences
        ]);
    }
    
    public function getProfile(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }
        
        // Get user stats
        $stats = [
            'total_favorites' => $user->favorites()->count(),
            'total_views' => UserPropertyView::where('user_id', $user->id)->count(),
            'total_searches' => UserSearch::where('user_id', $user->id)->count(),
            'recent_activity' => UserPropertyView::where('user_id', $user->id)
                ->latest('viewed_at')
                ->take(5)
                ->with('property:id,title,city,price')
                ->get()
        ];
        
        return response()->json([
            'profile' => $user->load('agency'),
            'stats' => $stats
        ]);
    }
    
    public function getDashboard(Request $request)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['message' => 'Authentication required'], 401);
        }
        
        // Get recent favorites
        $recentFavorites = $user->favorites()
            ->with('property.user.agency')
            ->latest()
            ->take(6)
            ->get();
            
        // Get recent searches
        $recentSearches = UserSearch::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        // Get viewing stats
        $viewStats = [
            'today' => UserPropertyView::where('user_id', $user->id)
                ->whereDate('viewed_at', today())
                ->count(),
            'this_week' => UserPropertyView::where('user_id', $user->id)
                ->where('viewed_at', '>=', now()->startOfWeek())
                ->count(),
            'this_month' => UserPropertyView::where('user_id', $user->id)
                ->where('viewed_at', '>=', now()->startOfMonth())
                ->count()
        ];
        
        return response()->json([
            'recent_favorites' => $recentFavorites,
            'recent_searches' => $recentSearches,
            'view_stats' => $viewStats,
            'preferences' => $user->preferences ?? []
        ]);
    }
}
