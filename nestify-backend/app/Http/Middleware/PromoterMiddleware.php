<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PromoterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        // Check if user is a promoter
        if (auth()->user()->user_type !== 'promoter') {
            return response()->json(['error' => 'Access denied. Promoter access required.'], 403);
        }

        // Check if user has a promoter profile
        if (!auth()->user()->promoter) {
            return response()->json(['error' => 'Promoter profile not found'], 403);
        }

        return $next($request);
    }
}

