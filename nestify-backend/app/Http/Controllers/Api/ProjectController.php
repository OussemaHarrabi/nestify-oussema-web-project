<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    private $imageService;

    public function __construct(ImageUploadService $imageService)
    {
        $this->imageService = $imageService;
    }

    /**
     * List promoter's projects
     * 
     * GET /api/promoter/projects
     */
    public function index(Request $request)
    {
        $promoter = $request->user()->promoter;

        $query = Project::where('promoter_id', $promoter->id);

        // Filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json($projects);
    }

    /**
     * Create new project
     * 
     * POST /api/promoter/projects
     */
    public function store(Request $request)
    {
        try {
            $promoter = $request->user()->promoter;
            
            if (!$promoter) {
                return response()->json([
                    'message' => 'Promoter profile not found'
                ], 404);
            }

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'city' => 'required|string|max:100',
                'district' => 'nullable|string|max:100',
                'address' => 'required|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'main_image' => 'nullable|image|max:5120', // CHANGED: Made nullable for JSON testing
                'images.*' => 'nullable|image|max:5120',
                'video_url' => 'nullable|url',
                'floor_plans.*' => 'nullable|image|max:5120',
                'brochure_pdf' => 'nullable|file|mimes:pdf|max:10240',
                'status' => 'nullable|in:planning,under_construction,near_completion,completed,on_hold',
                'launch_date' => 'nullable|date',
                'expected_delivery' => 'nullable|date',
                'construction_progress' => 'nullable|string',
                'total_units' => 'required|integer|min:1',
                'total_floors' => 'nullable|integer|min:1',
                'buildings_count' => 'nullable|integer|min:1',
                'amenities' => 'nullable|array',
                'amenities.*' => 'string',
                'nearby_facilities' => 'nullable|array',
                'nearby_facilities.*' => 'string',
                'tags' => 'nullable|array',
                'tags.*' => 'string',
                'is_published' => 'boolean',
            ]);

            // Upload main image ONLY if provided
            if ($request->hasFile('main_image')) {
                $mainImageResult = $this->imageService->upload(
                    $request->file('main_image'),
                    'projects/main-images'
                );
                // Use cover_image field name (based on your database)
                $validated['cover_image'] = $mainImageResult['url'];
            } else {
                // Set a placeholder or null
                $validated['cover_image'] = null;
            }

            // Upload gallery images
            if ($request->hasFile('images')) {
                $galleryImages = $this->imageService->uploadMultiple(
                    $request->file('images'),
                    'projects/galleries'
                );
                $validated['images'] = array_map(fn($img) => $img['url'], $galleryImages);
            }

            // Upload floor plans
            if ($request->hasFile('floor_plans')) {
                $floorPlans = $this->imageService->uploadMultiple(
                    $request->file('floor_plans'),
                    'projects/floor-plans'
                );
                $validated['floor_plans'] = array_map(fn($img) => $img['url'], $floorPlans);
            }

            // Upload brochure PDF
            if ($request->hasFile('brochure_pdf')) {
                $brochureResult = $this->imageService->uploadPDF(
                    $request->file('brochure_pdf'),
                    'projects/brochures'
                );
                $validated['brochure_pdf'] = $brochureResult['url'];
            }

            // Generate reference and slug
            $validated['promoter_id'] = $promoter->id;
            $validated['reference'] = 'PRJ-' . date('Y') . '-' . str_pad($promoter->projects()->count() + 1, 4, '0', STR_PAD_LEFT);
            $validated['slug'] = Str::slug($validated['name']);
            $validated['available_units'] = $validated['total_units'];

            // Set defaults
            $validated['status'] = $validated['status'] ?? 'under_construction';
            $validated['buildings_count'] = $validated['buildings_count'] ?? 1;
            $validated['is_published'] = $validated['is_published'] ?? false;

            if ($validated['is_published']) {
                $validated['published_at'] = now();
            }

            // Remove main_image from validated if it exists (we're using cover_image)
            unset($validated['main_image']);

            // Create project
            $project = Project::create($validated);

            // Update promoter counts
            $promoter->updateProjectCounts();

            return response()->json([
                'message' => 'Project created successfully',
                'project' => $project
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Create project error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to create project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single project
     * 
     * GET /api/promoter/projects/{id}
     */
    public function show(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)
            ->with(['properties' => function($q) {
                $q->select('id', 'project_id', 'title', 'price', 'surface', 'type', 'availability_status', 'bedrooms', 'bathrooms');
            }])
            ->findOrFail($id);

        return response()->json($project);
    }

    /**
     * Update project
     * 
     * PUT /api/promoter/projects/{id}
     */
    public function update(Request $request, $id)
    {
        try {
            $promoter = $request->user()->promoter;
            
            if (!$promoter) {
                return response()->json([
                    'message' => 'Promoter profile not found'
                ], 404);
            }

            $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'sometimes|string',
                'city' => 'sometimes|string|max:100',
                'district' => 'nullable|string|max:100',
                'address' => 'sometimes|string',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'main_image' => 'nullable|image|max:5120',
                'images.*' => 'nullable|image|max:5120',
                'video_url' => 'nullable|url',
                'floor_plans.*' => 'nullable|image|max:5120',
                'brochure_pdf' => 'nullable|file|mimes:pdf|max:10240',
                'status' => 'nullable|in:planning,under_construction,near_completion,completed,on_hold',
                'launch_date' => 'nullable|date',
                'expected_delivery' => 'nullable|date',
                'construction_progress' => 'nullable|string',
                'total_units' => 'sometimes|integer|min:1',
                'total_floors' => 'nullable|integer|min:1',
                'buildings_count' => 'nullable|integer|min:1',
                'amenities' => 'nullable|array',
                'nearby_facilities' => 'nullable|array',
                'tags' => 'nullable|array',
            ]);

            // Update main/cover image if provided
            if ($request->hasFile('main_image')) {
                $fieldName = 'cover_image'; // or check your database schema
                
                if ($project->{$fieldName}) {
                    $oldPath = $this->imageService->extractPathFromUrl($project->{$fieldName});
                    if ($oldPath) $this->imageService->delete($oldPath);
                }

                $mainImageResult = $this->imageService->upload(
                    $request->file('main_image'),
                    'projects/main-images'
                );
                $validated[$fieldName] = $mainImageResult['url'];
            }

            // Add new gallery images
            if ($request->hasFile('images')) {
                $newImages = $this->imageService->uploadMultiple(
                    $request->file('images'),
                    'projects/galleries'
                );
                $newUrls = array_map(fn($img) => $img['url'], $newImages);
                $existingImages = $project->images ?? [];
                $validated['images'] = array_merge($existingImages, $newUrls);
            }

            // Add new floor plans
            if ($request->hasFile('floor_plans')) {
                $newFloorPlans = $this->imageService->uploadMultiple(
                    $request->file('floor_plans'),
                    'projects/floor-plans'
                );
                $newUrls = array_map(fn($img) => $img['url'], $newFloorPlans);
                $existingFloorPlans = $project->floor_plans ?? [];
                $validated['floor_plans'] = array_merge($existingFloorPlans, $newUrls);
            }

            // Update brochure PDF
            if ($request->hasFile('brochure_pdf')) {
                if ($project->brochure_pdf) {
                    $oldPath = $this->imageService->extractPathFromUrl($project->brochure_pdf);
                    if ($oldPath) $this->imageService->delete($oldPath);
                }

                $brochureResult = $this->imageService->uploadPDF(
                    $request->file('brochure_pdf'),
                    'projects/brochures'
                );
                $validated['brochure_pdf'] = $brochureResult['url'];
            }

            // Update slug if name changed
            if (isset($validated['name']) && $validated['name'] !== $project->name) {
                $validated['slug'] = Str::slug($validated['name']);
            }

            // Remove main_image from validated
            unset($validated['main_image']);

            $project->update($validated);

            return response()->json([
                'message' => 'Project updated successfully',
                'project' => $project->fresh()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Update project error: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update project',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete project
     * 
     * DELETE /api/promoter/projects/{id}
     */
    public function destroy(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

        // Delete images from B2
        if ($project->main_image) {
            $path = $this->imageService->extractPathFromUrl($project->main_image);
            if ($path) $this->imageService->delete($path);
        }

        if ($project->images) {
            foreach ($project->images as $imageUrl) {
                $path = $this->imageService->extractPathFromUrl($imageUrl);
                if ($path) $this->imageService->delete($path);
            }
        }

        if ($project->floor_plans) {
            foreach ($project->floor_plans as $planUrl) {
                $path = $this->imageService->extractPathFromUrl($planUrl);
                if ($path) $this->imageService->delete($path);
            }
        }

        if ($project->brochure_pdf) {
            $path = $this->imageService->extractPathFromUrl($project->brochure_pdf);
            if ($path) $this->imageService->delete($path);
        }

        $project->delete();

        // Update promoter counts
        $promoter->updateProjectCounts();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }

    /**
     * Publish/unpublish project
     * 
     * PATCH /api/promoter/projects/{id}/publish
     */
    public function togglePublish(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

        $validated = $request->validate([
            'is_published' => 'required|boolean'
        ]);

        $project->is_published = $validated['is_published'];
        $project->published_at = $validated['is_published'] ? now() : null;
        $project->save();

        return response()->json([
            'message' => $validated['is_published'] ? 'Project published successfully' : 'Project unpublished successfully',
            'project' => [
                'id' => $project->id,
                'is_published' => $project->is_published,
                'published_at' => $project->published_at
            ]
        ]);
    }

    /**
     * Upload/replace brochure PDF
     * 
     * POST /api/promoter/projects/{id}/upload-brochure
     */
    public function uploadBrochure(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

        $request->validate([
            'brochure' => 'required|file|mimes:pdf|max:10240'
        ]);

        // Delete old brochure
        if ($project->brochure_pdf) {
            $oldPath = $this->imageService->extractPathFromUrl($project->brochure_pdf);
            if ($oldPath) $this->imageService->delete($oldPath);
        }

        // Upload new brochure
        $brochureResult = $this->imageService->uploadPDF(
            $request->file('brochure'),
            'projects/brochures'
        );

        $project->update(['brochure_pdf' => $brochureResult['url']]);

        return response()->json([
            'message' => 'Brochure uploaded successfully',
            'brochure_url' => $brochureResult['url']
        ]);
    }

    /**
     * Upload additional gallery images
     * 
     * POST /api/promoter/projects/{id}/upload-gallery
     */
    public function uploadGallery(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

        $request->validate([
            'images.*' => 'required|image|max:5120'
        ]);

        // Upload new gallery images
        $galleryImages = $this->imageService->uploadMultiple(
            $request->file('images'),
            'projects/galleries'
        );

        $newUrls = array_map(fn($img) => $img['url'], $galleryImages);
        $existingImages = $project->images ?? [];
        $project->update(['images' => array_merge($existingImages, $newUrls)]);

        return response()->json([
            'message' => 'Gallery images uploaded successfully',
            'uploaded_images' => $newUrls,
            'total_images' => count($existingImages) + count($newUrls)
        ]);
    }

    /**
     * Upload floor plans
     * 
     * POST /api/promoter/projects/{id}/upload-floor-plans
     */
    public function uploadFloorPlans(Request $request, $id)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

        $request->validate([
            'floor_plans.*' => 'required|image|max:5120'
        ]);

        // Upload new floor plans
        $floorPlans = $this->imageService->uploadMultiple(
            $request->file('floor_plans'),
            'projects/floor-plans'
        );

        $newUrls = array_map(fn($img) => $img['url'], $floorPlans);
        $existingFloorPlans = $project->floor_plans ?? [];
        $project->update(['floor_plans' => array_merge($existingFloorPlans, $newUrls)]);

        return response()->json([
            'message' => 'Floor plans uploaded successfully',
            'uploaded_plans' => $newUrls,
            'total_plans' => count($existingFloorPlans) + count($newUrls)
        ]);
    }

    /**
     * Delete specific image from project
     * 
     * DELETE /api/promoter/projects/{id}/images/{imageUrl}
     */
    public function deleteImage(Request $request, $id, $imageUrl)
    {
        $promoter = $request->user()->promoter;

        $project = Project::where('promoter_id', $promoter->id)->findOrFail($id);

        // Decode URL if needed
        $imageUrl = urldecode($imageUrl);

        // Extract path from URL
        $path = $this->imageService->extractPathFromUrl($imageUrl);
        
        if (!$path) {
            return response()->json([
                'message' => 'Invalid image URL'
            ], 400);
        }

        // Delete from B2
        $deleted = $this->imageService->delete($path);
        
        if (!$deleted) {
            return response()->json([
                'message' => 'Failed to delete image from storage'
            ], 500);
        }

        // Remove from project images array
        $images = $project->images ?? [];
        $images = array_filter($images, fn($img) => $img !== $imageUrl);
        $project->update(['images' => array_values($images)]);

        return response()->json([
            'message' => 'Image deleted successfully',
            'remaining_images' => count($images)
        ]);
    }
}
