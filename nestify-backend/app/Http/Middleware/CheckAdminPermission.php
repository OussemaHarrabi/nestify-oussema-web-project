<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $user = $request->user();

        // Check if user is admin
        if ($user->user_type !== 'admin') {
            return response()->json([
                'message' => 'Admin access required'
            ], 403);
        }

        // Check if user is active
        if (!$user->is_active) {
            return response()->json([
                'message' => 'Account is deactivated'
            ], 403);
        }

        // Check specific permissions if provided
        if (!empty($permissions)) {
            $userPermissions = $user->permissions ?? [];
            
            $hasPermission = false;
            foreach ($permissions as $permission) {
                if (in_array($permission, $userPermissions) || $user->admin_role === 'super_admin') {
                    $hasPermission = true;
                    break;
                }
            }

            if (!$hasPermission) {
                return response()->json([
                    'message' => 'Insufficient permissions. Required: ' . implode(', ', $permissions),
                    'your_permissions' => $userPermissions
                ], 403);
            }
        }

        return $next($request);
    }
}
