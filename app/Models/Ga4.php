<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ga4 extends Model
{
    use HasFactory;

    protected $table = 'ga4_directly';

    protected $fillable = [
        'landing_url',
        'session_medium',
        'total_sessions',
        'cv_count',
        'cvr',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'cvr' => 'float',
    ];
}
