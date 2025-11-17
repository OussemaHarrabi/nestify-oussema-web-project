<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'promoter_id',
        'project_id',
        'property_id',
        'name',
        'email',
        'phone',
        'message',
        'type',
        'status',
        'priority',
        'budget_range',
        'preferred_contact_method',
        'preferences',
        'notes',
        'source',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip_address',
        'user_agent',
        'last_contacted_at',
        'next_follow_up_at',
        'contact_attempts',
        'is_converted',
        'converted_at',
        'converted_property_id'
    ];

    protected $casts = [
        'preferences' => 'array',
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'converted_at' => 'datetime',
        'is_converted' => 'boolean',
        'contact_attempts' => 'integer'
    ];

    // Relationships
    public function promoter()
    {
        return $this->belongsTo(Promoter::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function convertedProperty()
    {
        return $this->belongsTo(Property::class, 'converted_property_id');
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    public function scopeConverted($query)
    {
        return $query->where('is_converted', true);
    }

    // Boot method - Set defaults
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($lead) {
            // Set default status if not provided
            if (empty($lead->status)) {
                $lead->status = 'new';
            }
            
            // Set default priority if not provided
            if (empty($lead->priority)) {
                $lead->priority = 'medium';
            }
            
            // Set default follow-up date
            if (empty($lead->next_follow_up_at)) {
                $lead->next_follow_up_at = now()->addDays(3);
            }

            // Set default contact attempts
            if (is_null($lead->contact_attempts)) {
                $lead->contact_attempts = 0;
            }
        });
    }
}
