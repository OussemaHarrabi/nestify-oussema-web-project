<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSearch extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'search_criteria',
        'results_count',
        'ip_address'
    ];

    protected $casts = [
        'search_criteria' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
