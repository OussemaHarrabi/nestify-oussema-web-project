<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPropertyView extends Model
{
    protected $fillable = [
        'user_id',
        'property_id',
        'session_id',
        'ip_address',
        'viewed_at'
    ];

    protected $casts = [
        'viewed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
