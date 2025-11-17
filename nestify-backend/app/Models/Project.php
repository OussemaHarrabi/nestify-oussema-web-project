<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'promoter_id',
        'name',
        'slug',
        'description',
        'reference',
        'city',
        'district',
        'address',
        'latitude',
        'longitude',
        'status',
        'launch_date',
        'expected_delivery',
        'construction_progress',
        'construction_progress_percentage',
        'total_units',
        'available_units',
        'sold_units',
        'reserved_units',
        'total_floors',
        'buildings_count',
        'starting_price',
        'average_price_per_sqm',
        'price_range_min',
        'price_range_max',
        'amenities',
        'nearby_facilities',
        'tags',
        'images',
        'cover_image',
        'floor_plans',
        'virtual_tours',
        'is_published',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'seo_keywords'
    ];

    protected $casts = [
        'amenities' => 'array',
        'nearby_facilities' => 'array',
        'tags' => 'array',
        'images' => 'array',
        'floor_plans' => 'array',
        'virtual_tours' => 'array',
        'seo_keywords' => 'array',
        'launch_date' => 'date',
        'expected_delivery' => 'date',
        'published_at' => 'datetime',
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'starting_price' => 'decimal:2',
        'average_price_per_sqm' => 'decimal:2',
        'price_range_min' => 'decimal:2',
        'price_range_max' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'total_units' => 'integer',
        'available_units' => 'integer',
        'sold_units' => 'integer',
        'reserved_units' => 'integer',
        'total_floors' => 'integer',
        'buildings_count' => 'integer',
        'construction_progress_percentage' => 'integer'
    ];

    // Relationships
    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function availableProperties()
    {
        return $this->hasMany(Property::class)->where('availability_status', 'available');
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    // Helper Methods
    public function updateUnitCounts()
    {
        $this->update([
            'total_units' => $this->properties()->count(),
            'available_units' => $this->properties()->where('availability_status', 'available')->count(),
            'sold_units' => $this->properties()->where('availability_status', 'sold')->count(),
            'reserved_units' => $this->properties()->where('availability_status', 'reserved')->count(),
        ]);
    }

    public function calculateStartingPrice()
    {
        $minPrice = $this->properties()->where('availability_status', 'available')->min('price');
        $this->update(['starting_price' => $minPrice]);
    }

    public function calculateAveragePricePerSqm()
    {
        $avgPricePerSqm = $this->properties()
            ->where('availability_status', 'available')
            ->where('surface', '>', 0)
            ->selectRaw('AVG(price / surface) as avg_price_per_sqm')
            ->value('avg_price_per_sqm');
            
        $this->update(['average_price_per_sqm' => $avgPricePerSqm]);
    }

    public function calculatePriceRange()
    {
        $prices = $this->properties()
            ->where('availability_status', 'available')
            ->pluck('price');
            
        if ($prices->isNotEmpty()) {
            $this->update([
                'price_range_min' => $prices->min(),
                'price_range_max' => $prices->max(),
            ]);
        }
    }

    public function getCompletionPercentageAttribute()
    {
        return $this->construction_progress_percentage ?? 0;
    }

    public function getIsVefaAttribute()
    {
        return in_array('VEFA', $this->tags ?? []);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeVefa($query)
    {
        return $query->whereJsonContains('tags', 'VEFA');
    }

    public function scopeUnderConstruction($query)
    {
        return $query->whereIn('status', ['under_construction', 'near_completion']);
    }

    // Boot method to generate slug
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
        
        static::updating(function ($project) {
            if ($project->isDirty('name') && empty($project->slug)) {
                $project->slug = Str::slug($project->name);
            }
        });
    }
}

