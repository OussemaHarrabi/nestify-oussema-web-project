<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function __construct()
    {
        // Middleware is handled in routes
    }

    /**
     * Submit lead inquiry (PUBLIC - no auth required)
     * 
     * POST /api/leads
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'promoter_id' => 'required|exists:promoters,id',
            'project_id' => 'nullable|exists:projects,id',
            'property_id' => 'nullable|exists:properties,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
            'type' => 'required|in:brochure_request,contact_request,visit_request,callback_request,info_request',
            'source' => 'nullable|string|max:100',
        ]);

        // Add tracking info
        $validated['user_ip'] = $request->ip();
        $validated['user_agent'] = $request->userAgent();
        $validated['referrer_url'] = $request->header('referer');
        $validated['fingerprint'] = Lead::generateFingerprint($validated['email'], $validated['phone']);

        // Create lead (score and priority auto-calculated by model)
        $lead = Lead::create($validated);

        return response()->json([
            'message' => 'Votre demande a été envoyée avec succès. Nous vous contacterons bientôt.',
            'lead' => [
                'id' => $lead->id,
                'status' => $lead->status,
                'priority' => $lead->priority,
            ]
        ], 201);
    }

    /**
     * List promoter's leads
     * 
     * GET /api/promoter/leads
     */
    public function index(Request $request)
    {
        $promoter = $request->user()->promoter;

        $query = Lead::where('promoter_id', $promoter->id)
            ->with(['project:id,name', 'property:id,title']);

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->has('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $leads = $query->paginate($request->get('per_page', 20));

        return response()->json($leads);
    }

    /**
     * Get lead details
     * 
     * GET /api/promoter/leads/{id}
     */
    public function show(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $lead = Lead::where('promoter_id', $promoter->id)
            ->with(['project', 'property'])
            ->findOrFail($id);

        return response()->json($lead);
    }

    /**
     * Update lead status
     * 
     * PATCH /api/promoter/leads/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $lead = Lead::where('promoter_id', $promoter->id)->findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:new,contacted,qualified,negotiation,converted,lost,spam',
            'notes' => 'nullable|string'
        ]);

        // Update status with timestamp
        $lead->status = $validated['status'];

        if ($validated['status'] === 'contacted' && !$lead->contacted_at) {
            $lead->contacted_at = now();
        } elseif ($validated['status'] === 'qualified' && !$lead->qualified_at) {
            $lead->qualified_at = now();
        } elseif ($validated['status'] === 'converted' && !$lead->converted_at) {
            $lead->converted_at = now();
        }

        if (isset($validated['notes'])) {
            $lead->notes = $validated['notes'];
        }

        $lead->save();

        return response()->json([
            'message' => 'Lead status updated successfully',
            'lead' => $lead->fresh()
        ]);
    }

    /**
     * Update lead priority
     * 
     * PATCH /api/promoter/leads/{id}/priority
     */
    public function updatePriority(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $lead = Lead::where('promoter_id', $promoter->id)->findOrFail($id);

        $validated = $request->validate([
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        $lead->update(['priority' => $validated['priority']]);

        return response()->json([
            'message' => 'Lead priority updated successfully',
            'lead' => [
                'id' => $lead->id,
                'priority' => $lead->priority
            ]
        ]);
    }

    /**
     * Update lead notes
     * 
     * PUT /api/promoter/leads/{id}/notes
     */
    public function updateNotes(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $lead = Lead::where('promoter_id', $promoter->id)->findOrFail($id);

        $validated = $request->validate([
            'notes' => 'required|string'
        ]);

        $lead->update(['notes' => $validated['notes']]);

        return response()->json([
            'message' => 'Notes updated successfully',
            'lead' => [
                'id' => $lead->id,
                'notes' => $lead->notes
            ]
        ]);
    }

    /**
     * Delete lead (mark as spam)
     * 
     * DELETE /api/promoter/leads/{id}
     */
    public function destroy(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $lead = Lead::where('promoter_id', $promoter->id)->findOrFail($id);

        // Mark as spam instead of deleting
        $lead->update(['status' => 'spam']);

        return response()->json([
            'message' => 'Lead marked as spam'
        ]);
    }

    /**
     * Get lead statistics
     * 
     * GET /api/promoter/leads/stats
     */
    public function stats(Request $request)
    {
        $promoter = $request->user()->promoter;

        $total = $promoter->leads()->count();
        $new = $promoter->leads()->where('status', 'new')->count();
        $contacted = $promoter->leads()->where('status', 'contacted')->count();
        $qualified = $promoter->leads()->where('status', 'qualified')->count();
        $converted = $promoter->leads()->where('status', 'converted')->count();
        $lost = $promoter->leads()->where('status', 'lost')->count();

        $conversionRate = $total > 0 ? round(($converted / $total) * 100, 2) : 0;

        // Leads by type
        $leadsByType = $promoter->leads()
            ->selectRaw('type, COUNT(*) as count')
            ->groupBy('type')
            ->get()
            ->pluck('count', 'type');

        // Leads by priority
        $leadsByPriority = $promoter->leads()
            ->selectRaw('priority, COUNT(*) as count')
            ->groupBy('priority')
            ->get()
            ->pluck('count', 'priority');

        // Recent trends (last 30 days)
        $recentLeads = $promoter->leads()
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total' => $total,
            'by_status' => [
                'new' => $new,
                'contacted' => $contacted,
                'qualified' => $qualified,
                'converted' => $converted,
                'lost' => $lost,
            ],
            'conversion_rate' => $conversionRate,
            'by_type' => $leadsByType,
            'by_priority' => $leadsByPriority,
            'recent_trends' => $recentLeads,
        ]);
    }
}
