<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promoter;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;

class PromoterController extends Controller
{
    private $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * Get current promoter profile
     * 
     * GET /api/promoter/profile
     */
    public function profile(Request $request)
    {
        $promoter = $request->user()->promoter;

        if (!$promoter) {
            return response()->json([
                'message' => 'Promoter profile not found'
            ], 404);
        }

        return response()->json([
            'id' => $promoter->id,
            'user_id' => $promoter->user_id,
            'company_name' => $promoter->company_name,
            'logo' => $promoter->logo,
            'description' => $promoter->description,
            'website' => $promoter->website,
            'primary_phone' => $promoter->primary_phone,
            'additional_phones' => $promoter->additional_phones,
            'primary_email' => $promoter->primary_email,
            'additional_emails' => $promoter->additional_emails,
            'license_number' => $promoter->license_number,
            'established_date' => $promoter->established_date,
            'employee_count' => $promoter->employee_count,
            'specializations' => $promoter->specializations,
            'headquarters_address' => $promoter->headquarters_address,
            'headquarters_city' => $promoter->headquarters_city,
            'branch_offices' => $promoter->branch_offices,
            'total_projects' => $promoter->total_projects,
            'completed_projects' => $promoter->completed_projects,
            'active_projects' => $promoter->active_projects,
            'rating' => $promoter->rating,
            'review_count' => $promoter->review_count,
            'verified' => $promoter->verified,
            'featured' => $promoter->featured,
            'verified_at' => $promoter->verified_at,
            'created_at' => $promoter->created_at,
        ]);
    }

    /**
     * Update promoter profile
     * 
     * PUT /api/promoter/profile
     */
    public function updateProfile(Request $request)
    {
        $promoter = $request->user()->promoter;

        if (!$promoter) {
            return response()->json([
                'message' => 'Promoter profile not found'
            ], 404);
        }

        $validated = $request->validate([
            'company_name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:5120', // 5MB
            'primary_phone' => 'sometimes|string|max:20',
            'additional_phones' => 'nullable|array',
            'additional_phones.*' => 'string|max:20',
            'primary_email' => 'sometimes|email|max:255',
            'additional_emails' => 'nullable|array',
            'additional_emails.*' => 'email|max:255',
            'headquarters_address' => 'nullable|string|max:255',
            'headquarters_city' => 'nullable|string|max:100',
            'branch_offices' => 'nullable|array',
            'branch_offices.*.city' => 'required|string',
            'branch_offices.*.address' => 'required|string',
            'branch_offices.*.phone' => 'nullable|string',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string',
            'employee_count' => 'nullable|integer|min:1',
            'established_date' => 'nullable|date',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($promoter->logo) {
                $oldPath = $this->imageService->extractPathFromUrl($promoter->logo);
                if ($oldPath) {
                    $this->imageService->delete($oldPath);
                }
            }

            // Upload new logo
            $logoResult = $this->imageService->upload(
                $request->file('logo'),
                'promoters/logos'
            );
            $validated['logo'] = $logoResult['url'];
        }

        // Update promoter
        $promoter->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'promoter' => $promoter->fresh()
        ]);
    }

    /**
     * Upload/replace promoter logo
     * 
     * POST /api/promoter/logo
     */
    public function uploadLogo(Request $request)
    {
        $promoter = $request->user()->promoter;

        if (!$promoter) {
            return response()->json([
                'message' => 'Promoter profile not found'
            ], 404);
        }

        $request->validate([
            'logo' => 'required|image|max:5120', // 5MB
        ]);

        // Delete old logo if exists
        if ($promoter->logo) {
            $oldPath = $this->imageService->extractPathFromUrl($promoter->logo);
            if ($oldPath) {
                $this->imageService->delete($oldPath);
            }
        }

        // Upload new logo
        $logoResult = $this->imageService->upload(
            $request->file('logo'),
            'promoters/logos'
        );

        $promoter->update(['logo' => $logoResult['url']]);

        return response()->json([
            'message' => 'Logo uploaded successfully',
            'logo_url' => $logoResult['url']
        ]);
    }

    /**
     * Get promoter dashboard statistics
     * 
     * GET /api/promoter/dashboard
     */
    public function dashboard(Request $request)
    {
        $promoter = $request->user()->promoter;

        if (!$promoter) {
            return response()->json([
                'message' => 'Promoter profile not found'
            ], 404);
        }

        // Get statistics
        $totalProperties = $promoter->projects()
            ->withCount('properties')
            ->get()
            ->sum('properties_count');

        $availableProperties = $promoter->projects()
            ->with(['properties' => function($q) {
                $q->where('availability_status', 'available');
            }])
            ->get()
            ->sum(fn($p) => $p->properties->count());

        $soldProperties = $promoter->projects()
            ->with(['properties' => function($q) {
                $q->where('availability_status', 'sold');
            }])
            ->get()
            ->sum(fn($p) => $p->properties->count());

        $reservedProperties = $promoter->projects()
            ->with(['properties' => function($q) {
                $q->where('availability_status', 'reserved');
            }])
            ->get()
            ->sum(fn($p) => $p->properties->count());

        $totalLeads = $promoter->leads()->count();
        $newLeads = $promoter->leads()->where('status', 'new')->count();
        $thisMonthLeads = $promoter->leads()
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();

        // Recent leads
        $recentLeads = $promoter->leads()
            ->with(['project:id,name', 'property:id,title'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($lead) {
                return [
                    'id' => $lead->id,
                    'name' => $lead->name,
                    'email' => $lead->email,
                    'phone' => $lead->phone,
                    'project' => $lead->project?->name,
                    'property' => $lead->property?->title,
                    'type' => $lead->type,
                    'status' => $lead->status,
                    'priority' => $lead->priority,
                    'created_at' => $lead->created_at,
                ];
            });

        // Recent projects
        $recentProjects = $promoter->projects()
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'slug' => $project->slug,
                    'city' => $project->city,
                    'status' => $project->status,
                    'total_units' => $project->total_units,
                    'available_units' => $project->available_units,
                    'main_image' => $project->cover_image ?? $project->main_image ?? null,
                    'is_published' => $project->is_published,
                ];
            });

        // Calculate conversion rate
        $convertedLeads = $promoter->leads()->where('is_converted', true)->count();
        $conversionRate = $totalLeads > 0 ? round(($convertedLeads / $totalLeads) * 100, 2) : 0;

        // FIXED: Calculate average response time using last_contacted_at instead of contacted_at
        $leadsWithContact = $promoter->leads()
            ->whereNotNull('last_contacted_at')
            ->get();
        
        $avgResponseTime = null;
        if ($leadsWithContact->count() > 0) {
            $totalHours = $leadsWithContact->map(function($lead) {
                return $lead->created_at->diffInHours($lead->last_contacted_at);
            })->sum();
            
            $avgResponseTime = $totalHours / $leadsWithContact->count();
        }

        $avgResponseTimeFormatted = $avgResponseTime 
            ? ($avgResponseTime < 24 
                ? round($avgResponseTime, 1) . ' hours' 
                : round($avgResponseTime / 24, 1) . ' days')
            : 'N/A';

        return response()->json([
            'stats' => [
                'total_projects' => $promoter->total_projects,
                'active_projects' => $promoter->active_projects,
                'completed_projects' => $promoter->completed_projects,
                'total_properties' => $totalProperties,
                'available_properties' => $availableProperties,
                'sold_properties' => $soldProperties,
                'reserved_properties' => $reservedProperties,
                'total_leads' => $totalLeads,
                'new_leads' => $newLeads,
                'this_month_leads' => $thisMonthLeads,
            ],
            'recent_leads' => $recentLeads,
            'recent_projects' => $recentProjects,
            'performance' => [
                'conversion_rate' => $conversionRate,
                'avg_response_time' => $avgResponseTimeFormatted,
            ]
        ]);
    }
}
