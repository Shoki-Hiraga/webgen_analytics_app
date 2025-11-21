<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SerpOrganicResult extends Model
{
    protected $table = 'serp_organic_results';

    protected $fillable = [
        'original_keyword',
        'product',
        'priority',
        'fetched_date',
        'rank',
        'keyword',
        'url',
        'title',
    ];

    protected $casts = [
        'fetched_date' => 'date',
    ];
}
