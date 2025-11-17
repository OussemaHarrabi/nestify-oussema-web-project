<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Promoter;
use App\Models\Project;
use App\Models\Property;
use App\Models\Lead;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Middleware is handled in routes
    }

    /**
     * Check if user is admin
     */
    private function checkAdmin(Request $request)
    {
        if ($request->user()->user_type !== 'admin') {
            abort(403, 'Unauthorized. Admin access required.');
        }
    }

    /**
     * Admin dashboard statistics
     * 
     * GET /api/admin/dashboard
     */
    public function dashboard(Request $request)
    {
        $this->checkAdmin($request);

        // Promoter stats
        $totalPromoters = Promoter::count();
        $verifiedPromoters = Promoter::where('verified', true)->count();
        $pendingVerification = Promoter::where('verified', false)->count();

        // Project stats
        $totalProjects = Project::count();
        $publishedProjects = Project::where('is_published', true)->count();
        $pendingApproval = Project::where('is_published', false)->count();

        // Property stats
        $totalProperties = Property::count();
        $validatedProperties = Property::where('validated', true)->count();
        $pendingValidation = Property::where('validated', false)->count();

        // Lead stats
        $totalLeads = Lead::count();
        $newLeads = Lead::where('status', 'new')->count();
        $convertedLeads = Lead::where('status', 'converted')->count();

        // Recent activity
        $recentPromoters = Promoter::with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($promoter) {
                return [
                    'id' => $promoter->id,
                    'company_name' => $promoter->company_name,
                    'email' => $promoter->user->email,
                    'verified' => $promoter->verified,
                    'created_at' => $promoter->created_at,
                ];
            });

        $pendingProjects = Project::with('promoter:id,company_name')
            ->where('is_published', false)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($project) {
                return [
                    'id' => $project->id,
                    'name' => $project->name,
                    'promoter' => $project->promoter->company_name,
                    'city' => $project->city,
                    'created_at' => $project->created_at,
                ];
            });

        return response()->json([
            'stats' => [
                'promoters' => [
                    'total' => $totalPromoters,
                    'verified' => $verifiedPromoters,
                    'pending_verification' => $pendingVerification,
                ],
                'projects' => [
                    'total' => $totalProjects,
                    'published' => $publishedProjects,
                    'pending_approval' => $pendingApproval,
                ],
                'properties' => [
                    'total' => $totalProperties,
                    'validated' => $validatedProperties,
                    'pending_validation' => $pendingValidation,
                ],
                'leads' => [
                    'total' => $totalLeads,
                    'new' => $newLeads,
                    'converted' => $convertedLeads,
                ],
            ],
            'recent_promoters' => $recentPromoters,
            'pending_projects' => $pendingProjects,
        ]);
    }

    /**
     * List all promoters
     * 
     * GET /api/admin/promoters
     */
    public function promoters(Request $request)
    {
        $this->checkAdmin($request);

        $query = Promoter::with('user:id,name,email,created_at');

        // Filter by verification status
        if ($request->has('verified')) {
            $query->where('verified', $request->boolean('verified'));
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('email', 'like', "%{$search}%")
                                ->orWhere('name', 'like', "%{$search}%");
                  });
            });
        }

        $promoters = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($promoters);
    }

    /**
     * Get single promoter details
     * 
     * GET /api/admin/promoters/{id}
     */
    public function promoterDetails(Request $request, $id)
    {
        $this->checkAdmin($request);

        $promoter = Promoter::with([
            'user:id,name,email,phone,created_at',
            'projects' => function($q) {
                $q->select('id', 'promoter_id', 'name', 'city', 'status', 'is_published', 'created_at');
            }
        ])->findOrFail($id);

        // Additional stats
        $promoter->stats = [
            'total_leads' => $promoter->leads()->count(),
            'converted_leads' => $promoter->leads()->where('status', 'converted')->count(),
            'total_properties' => Property::where('user_id', $promoter->user_id)->count(),
        ];

        return response()->json($promoter);
    }

    /**
     * Verify/unverify promoter
     * 
     * PATCH /api/admin/promoters/{id}/verify
     */
    public function verifyPromoter(Request $request, $id)
    {
        $this->checkAdmin($request);

        $promoter = Promoter::findOrFail($id);

        $validated = $request->validate([
            'verified' => 'required|boolean'
        ]);

        $promoter->verified = $validated['verified'];
        $promoter->verified_at = $validated['verified'] ? now() : null;
        $promoter->save();

        return response()->json([
            'message' => $validated['verified'] 
                ? 'Promoter verified successfully' 
                : 'Promoter verification revoked',
            'promoter' => [
                'id' => $promoter->id,
                'verified' => $promoter->verified,
                'verified_at' => $promoter->verified_at,
            ]
        ]);
    }

    /**
     * List all projects
     * 
     * GET /api/admin/projects
     */
    public function projects(Request $request)
    {
        $this->checkAdmin($request);

        $query = Project::with('promoter:id,company_name');

        // Filter by publication status
        if ($request->has('is_published')) {
            $query->where('is_published', $request->boolean('is_published'));
        }

        // Filter by promoter
        if ($request->has('promoter_id')) {
            $query->where('promoter_id', $request->promoter_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $projects = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($projects);
    }

    /**
     * Approve/unpublish project
     * 
     * PATCH /api/admin/projects/{id}/publish
     */
    public function publishProject(Request $request, $id)
    {
        $this->checkAdmin($request);

        $project = Project::findOrFail($id);

        // Toggle publish status or use request value if provided
        if ($request->has('is_published')) {
            $isPublished = $request->boolean('is_published');
        } else {
            // Toggle: if currently published, unpublish; if unpublished, publish
            $isPublished = !$project->is_published;
        }

        $project->is_published = $isPublished;
        $project->published_at = $isPublished ? now() : null;
        $project->save();

        return response()->json([
            'message' => $isPublished 
                ? 'Project published successfully' 
                : 'Project unpublished',
            'project' => [
                'id' => $project->id,
                'is_published' => $project->is_published,
                'published_at' => $project->published_at,
            ]
        ]);
    }
    
    /**
     * Get specific project details (Admin)
     * 
     * GET /api/admin/projects/{id}
     */
    public function projectDetails(Request $request, $id)
    {
        $this->checkAdmin($request);

        $project = Project::with(['promoter.user', 'properties'])
            ->findOrFail($id);

        return response()->json([
            'project' => $project
        ]);
    }
    
    /**
     * Unpublish project (Admin)
     * 
     * PATCH /api/admin/projects/{id}/unpublish
     */
    public function unpublishProject(Request $request, $id)
    {
        $this->checkAdmin($request);

        $project = Project::findOrFail($id);
        $project->is_published = false;
        $project->published_at = null;
        $project->save();

        return response()->json([
            'message' => 'Project unpublished successfully',
            'project' => [
                'id' => $project->id,
                'is_published' => false,
            ]
        ]);
    }

    /**
     * List all properties
     * 
     * GET /api/admin/properties
     */
    public function properties(Request $request)
    {
        $this->checkAdmin($request);

        $query = Property::with([
            'project:id,name,promoter_id',
            'project.promoter:id,company_name'
        ]);

        // Filter by validation status
        if ($request->has('validated')) {
            $query->where('validated', $request->boolean('validated'));
        }

        // Filter by project
        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('reference', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        $properties = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($properties);
    }

    /**
     * Validate/invalidate property
     * 
     * PATCH /api/admin/properties/{id}/validate
     */
    public function validateProperty(Request $request, $id)
    {
        $this->checkAdmin($request);

        $property = Property::findOrFail($id);

        $validated = $request->validate([
            'validated' => 'required|boolean'
        ]);

        $property->update(['validated' => $validated['validated']]);

        return response()->json([
            'message' => $validated['validated'] 
                ? 'Property validated successfully' 
                : 'Property validation revoked',
            'property' => [
                'id' => $property->id,
                'validated' => $property->validated,
            ]
        ]);
    }
}