<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminFeeSetting extends Model
{
    protected $fillable = [
        'year',
        'month',
        'base_budget',
        'early_discount_enabled',
        'early_discount_days',
        'early_discount_type',
        'early_discount_value',
        'due_date',
        'rate_per_sqm',
        'honorarios_default',
    ];
}
