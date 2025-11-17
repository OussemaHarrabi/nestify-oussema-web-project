<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$types): Response
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // Check if user type matches
        if (!in_array($request->user()->user_type, $types)) {
            return response()->json([
                'message' => 'Unauthorized. You do not have permission to access this resource.',
                'required_type' => $types,
                'your_type' => $request->user()->user_type
            ], 403);
        }

        return $next($request);
    }
}