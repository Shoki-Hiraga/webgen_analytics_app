<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdsKeywordPlannerResult extends Model
{
    protected $table = 'ads_keyword_planner_results';

    protected $fillable = [
        'original_keyword',
        'product',
        'priority',
        'keyword',
        'avg_monthly_search_volume',
        'competition_level',
        'competition_index',
        'low_cpc',
        'high_cpc',
    ];
}
