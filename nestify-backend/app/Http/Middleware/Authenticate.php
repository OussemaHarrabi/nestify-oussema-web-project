<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // For API routes, return null to get JSON 401 response
        // This prevents "Route [login] not defined" error
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }
        
        // For web routes, return null (no login page in API-only app)
        return null;
    }
}

