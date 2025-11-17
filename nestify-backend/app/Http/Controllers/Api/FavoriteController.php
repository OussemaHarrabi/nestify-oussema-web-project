<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Property;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $favorites = $request->user()
            ->favorites()
            ->with('property.user.agency')
            ->latest()
            ->paginate(12);

        return response()->json($favorites);
    }

    public function toggle(Request $request, $propertyId)
    {
        $property = Property::findOrFail($propertyId);
        
        $favorite = Favorite::where([
            'user_id' => $request->user()->id,
            'property_id' => $propertyId
        ])->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['message' => 'Property removed from favorites', 'favorited' => false]);
        } else {
            Favorite::create([
                'user_id' => $request->user()->id,
                'property_id' => $propertyId
            ]);
            return response()->json(['message' => 'Property added to favorites', 'favorited' => true]);
        }
    }

    public function check(Request $request, $propertyId)
    {
        $favorited = Favorite::where([
            'user_id' => $request->user()->id,
            'property_id' => $propertyId
        ])->exists();

        return response()->json(['favorited' => $favorited]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id'
        ]);

        $property = Property::findOrFail($request->property_id);
        
        $favorite = Favorite::where([
            'user_id' => $request->user()->id,
            'property_id' => $request->property_id
        ])->first();

        if ($favorite) {
            return response()->json(['message' => 'Property already in favorites', 'favorited' => true]);
        }

        Favorite::create([
            'user_id' => $request->user()->id,
            'property_id' => $request->property_id
        ]);

        return response()->json(['message' => 'Property added to favorites', 'favorited' => true], 201);
    }

    public function destroy(Request $request, $propertyId)
    {
        $favorite = Favorite::where([
            'user_id' => $request->user()->id,
            'property_id' => $propertyId
        ])->first();

        if (!$favorite) {
            return response()->json(['message' => 'Property not in favorites'], 404);
        }

        $favorite->delete();
        return response()->json(['message' => 'Property removed from favorites', 'favorited' => false]);
    }
}
