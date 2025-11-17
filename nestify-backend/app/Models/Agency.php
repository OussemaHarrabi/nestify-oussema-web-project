<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'website',
        'addresses',
        'additional_phones',
        'verified',
        'description',
        'license_number',
        'established_date',
        'employee_count',
        'specializations',
        'rating',
        'review_count'
    ];

    protected $casts = [
        'addresses' => 'array',
        'additional_phones' => 'array',
        'verified' => 'boolean',
        'specializations' => 'array',
        'established_date' => 'date',
        'rating' => 'float',
        'employee_count' => 'integer',
        'review_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
