<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    // ONLY use columns that ACTUALLY exist in the database
    protected $fillable = [
        'user_id', 'title', 'description', 'price', 'type', 'surface',
        'reference', 'images', 'validated', 'published_date',
        'city', 'district', 'address', 'latitude', 'longitude',
        'rooms', 'bedrooms', 'bathrooms', 'floor', 'total_floors',
        'parking', 'elevator', 'terrace', 'garden', 'features',
        'is_vefa', 'delivery_date', 'construction_progress'
    ];

    protected $casts = [
        'images' => 'array',
        'features' => 'array',
        'validated' => 'boolean',
        'parking' => 'boolean',
        'elevator' => 'boolean',
        'terrace' => 'boolean',
        'garden' => 'boolean',
        'is_vefa' => 'boolean',
        'published_date' => 'date',
        'delivery_date' => 'date',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function images()
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function incrementViews()
    {
        $this->increment('views');
    }
}
