<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Promoter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'logo',
        'description',
        'website',
        'primary_phone',
        'additional_phones',
        'primary_email',
        'additional_emails',
        'license_number',
        'established_date',
        'employee_count',
        'specializations',
        'headquarters_address',
        'headquarters_city',
        'branch_offices',
        'total_projects',
        'completed_projects',
        'active_projects',
        'rating',
        'review_count',
        'verified',
        'featured',
        'verified_at'
    ];

    protected $casts = [
        'additional_phones' => 'array',
        'additional_emails' => 'array',
        'specializations' => 'array',
        'branch_offices' => 'array',
        'established_date' => 'date',
        'verified' => 'boolean',
        'featured' => 'boolean',
        'verified_at' => 'datetime',
        'rating' => 'float',
        'total_projects' => 'integer',
        'completed_projects' => 'integer',
        'active_projects' => 'integer',
        'employee_count' => 'integer',
        'review_count' => 'integer',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function activeProjects()
    {
        return $this->hasMany(Project::class)
            ->where('is_published', true)
            ->whereIn('status', ['under_construction', 'near_completion']);
    }

    public function completedProjects()
    {
        return $this->hasMany(Project::class)
            ->where('status', 'completed');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function newLeads()
    {
        return $this->hasMany(Lead::class)
            ->where('status', 'new')
            ->orderBy('created_at', 'desc');
    }

    // Helper Methods
    public function updateProjectCounts()
    {
        $this->update([
            'total_projects' => $this->projects()->count(),
            'active_projects' => $this->activeProjects()->count(),
            'completed_projects' => $this->completedProjects()->count(),
        ]);
    }

    public function calculateRating()
    {
        // Calculate rating based on completed projects, reviews, etc.
        // This is a placeholder - implement your rating logic
        return $this->rating;
    }

    public function getSlugAttribute()
    {
        return Str::slug($this->company_name);
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('verified', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeActive($query)
    {
        return $query->where('active_projects', '>', 0);
    }
}

