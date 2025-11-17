<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Promoter;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Register a new promoter
     * 
     * POST /api/register
     */
    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'phone' => 'required|string|max:20',
                'company_name' => 'required|string|max:255',
                'license_number' => 'nullable|string|max:50|unique:promoters',
                'profile_picture' => 'nullable|image|max:5120',
            ]);

            DB::beginTransaction();

            // Handle profile picture upload
            $profilePictureUrl = null;
            if ($request->hasFile('profile_picture')) {
                $profileResult = $this->imageService->upload(
                    $request->file('profile_picture'),
                    'users/profile-pictures'
                );
                $profilePictureUrl = $profileResult['url'];
            }

            // Create user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'phone' => $validated['phone'],
                'user_type' => 'promoter',
                'profile_picture' => $profilePictureUrl,
                'is_active' => true,
            ]);

            // Create promoter profile
            $promoter = Promoter::create([
                'user_id' => $user->id,
                'company_name' => $validated['company_name'],
                'license_number' => $validated['license_number'] ?? null,
                'primary_phone' => $validated['phone'],
                'primary_email' => $validated['email'],
                'verified' => false,
            ]);

            DB::commit();

            // Create token
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Registration successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'profile_picture' => $user->profile_picture,
                ],
                'promoter' => [
                    'id' => $promoter->id,
                    'company_name' => $promoter->company_name,
                    'verified' => $promoter->verified,
                ],
                'token' => $token,
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Login user
     * 
     * POST /api/login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json([
                'message' => 'Your account has been deactivated. Please contact support.'
            ], 403);
        }

        // Update last login
        $user->update(['last_login_at' => now()]);

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        // Prepare response based on user type
        $response = [
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
                'profile_picture' => $user->profile_picture,
            ],
            'token' => $token,
        ];

        // Add promoter info if user is promoter
        if ($user->user_type === 'promoter') {
            $promoter = $user->promoter;
            if ($promoter) {
                $response['promoter'] = [
                    'id' => $promoter->id,
                    'company_name' => $promoter->company_name,
                    'verified' => $promoter->verified,
                    'logo' => $promoter->logo,
                ];
            }
        }

        return response()->json($response);
    }

    /**
     * Logout user
     * 
     * POST /api/logout
     */
    public function logout(Request $request)
    {
        try {
            // Revoke current token
            $request->user()->currentAccessToken()->delete();

            return response()->json([
                'message' => 'Logged out successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Logout failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current authenticated user
     * 
     * GET /api/user
     */
    public function user(Request $request)
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'message' => 'User not found'
                ], 404);
            }
            
            $response = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'user_type' => $user->user_type,
                'profile_picture' => $user->profile_picture,
            ];

            // Add promoter data if applicable
            if ($user->user_type === 'promoter') {
                $promoter = $user->promoter;
                if ($promoter) {
                    $response['promoter'] = [
                        'id' => $promoter->id,
                        'company_name' => $promoter->company_name,
                        'logo' => $promoter->logo,
                        'verified' => $promoter->verified,
                        'total_projects' => $promoter->total_projects,
                    ];
                } else {
                    Log::warning("User {$user->id} is promoter type but has no promoter record");
                }
            }

            return response()->json($response);
        } catch (\Exception $e) {
            Log::error('Get user error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to retrieve user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update user profile picture
     * 
     * POST /api/user/profile-picture
     */
    public function updateProfilePicture(Request $request)
    {
        try {
            $user = $request->user();

            $request->validate([
                'profile_picture' => 'required|image|max:5120',
            ]);

            // Delete old profile picture if exists
            if ($user->profile_picture) {
                $oldPath = $this->imageService->extractPathFromUrl($user->profile_picture);
                if ($oldPath) {
                    $this->imageService->delete($oldPath);
                }
            }

            // Upload new profile picture
            $profileResult = $this->imageService->upload(
                $request->file('profile_picture'),
                'users/profile-pictures'
            );

            $user->update(['profile_picture' => $profileResult['url']]);

            return response()->json([
                'message' => 'Profile picture updated successfully',
                'profile_picture' => $profileResult['url']
            ]);
        } catch (\Exception $e) {
            Log::error('Update profile picture error: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Failed to update profile picture',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}