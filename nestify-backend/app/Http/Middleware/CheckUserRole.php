<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!$request->user()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $userType = $request->user()->user_type;
        
        if (!in_array($userType, $roles)) {
            return response()->json([
                'message' => 'Forbidden. This action requires: ' . implode(' or ', $roles) . ' role.'
            ], 403);
        }

        return $next($request);
    }
}
